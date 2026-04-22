<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * 备份目录
     */
    protected string $backupPath;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        
        // 确保备份目录存在
        if (!File::isDirectory($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
    }

    /**
     * 备份列表页面
     */
    public function index()
    {
        $backups = $this->getBackupList();

        return view('backend.backups.index', compact('backups'));
    }

    /**
     * 创建新备份
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'full'); // full, database, files

        try {
            $filename = $this->generateBackupFilename($type);
            
            if ($type === 'full' || $type === 'database') {
                $this->backupDatabase($filename);
            }

            if ($type === 'full' || $type === 'files') {
                $this->backupFiles($filename);
            }

            // 创建备份信息文件
            $this->createBackupInfo($filename, $type);

            return redirect()
                ->route('admin.backups.index')
                ->with('success', '备份创建成功: ' . $filename);
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.backups.index')
                ->with('error', '备份失败: ' . $e->getMessage());
        }
    }

    /**
     * 下载备份
     */
    public function download(string $filename)
    {
        $filePath = $this->backupPath . '/' . $filename;

        if (!File::exists($filePath)) {
            abort(404, '备份文件不存在');
        }

        return Response::download($filePath, $filename, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }

    /**
     * 删除备份
     */
    public function destroy(string $filename)
    {
        $filePath = $this->backupPath . '/' . $filename;

        if (!File::exists($filePath)) {
            return redirect()
                ->route('admin.backups.index')
                ->with('error', '备份文件不存在');
        }

        try {
            File::delete($filePath);

            return redirect()
                ->route('admin.backups.index')
                ->with('success', '备份已删除');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.backups.index')
                ->with('error', '删除失败: ' . $e->getMessage());
        }
    }

    /**
     * 获取备份列表
     */
    protected function getBackupList(): array
    {
        $files = File::files($this->backupPath);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'zip') {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'size_formatted' => $this->formatBytes($file->getSize()),
                    'created_at' => \Carbon\Carbon::createFromTimestamp($file->getMTime()),
                    'path' => $file->getPathname(),
                ];
            }
        }

        // 按时间倒序
        usort($backups, function ($a, $b) {
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });

        return $backups;
    }

    /**
     * 生成备份文件名
     */
    protected function generateBackupFilename(string $type): string
    {
        $prefix = match($type) {
            'database' => 'db',
            'files' => 'files',
            default => 'backup',
        };

        return sprintf(
            '%s_%s_%s.zip',
            $prefix,
            config('app.url'),
            now()->format('Y-m-d_His')
        );
    }

    /**
     * 备份数据库
     */
    protected function backupDatabase(string $filename): void
    {
        $zip = new ZipArchive();
        $zipPath = $this->backupPath . '/' . $filename;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('无法创建 ZIP 文件');
        }

        // 获取所有表
        $tables = DB::select('SHOW TABLES');
        $tablePrefix = config('database.connections.mysql.prefix', '');

        $sqlContent = "-- Blog Database Backup\n";
        $sqlContent .= "-- Generated: " . now()->format('Y-m-d H:i:s') . "\n";
        $sqlContent .= "-- Database: " . config('database.connections.mysql.database') . "\n\n";

        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            $tableNameWithoutPrefix = str_replace($tablePrefix, '', $tableName);

            // 跳过会话表
            if (in_array($tableNameWithoutPrefix, ['sessions', 'cache', 'jobs', 'failed_jobs'])) {
                continue;
            }

            $sqlContent .= "\n-- Table: {$tableNameWithoutPrefix}\n";

            // DROP TABLE
            $sqlContent .= "DROP TABLE IF EXISTS `{$tableNameWithoutPrefix}`;\n";

            // CREATE TABLE
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            if (!empty($createTable)) {
                $createSQL = array_values((array)$createTable[0])[1];
                $sqlContent .= $createSQL . ";\n";
            }

            // INSERT DATA
            $rows = DB::table($tableName)->get();
            if ($rows->isNotEmpty()) {
                $columns = DB::getSchemaBuilder()->getColumnListing($tableNameWithoutPrefix);
                $columnNames = '`' . implode('`, `', $columns) . '`';

                foreach ($rows as $row) {
                    $values = [];
                    foreach ((array)$row as $value) {
                        if (is_null($value)) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = "'" . DB::getPdo()->quote($value) . "'";
                        }
                    }
                    $sqlContent .= "INSERT INTO `{$tableNameWithoutPrefix}` ({$columnNames}) VALUES (" . implode(', ', $values) . ");\n";
                }
            }
        }

        $zip->addFromString('database.sql', $sqlContent);
        $zip->close();
    }

    /**
     * 备份文件
     */
    protected function backupFiles(string $filename): void
    {
        $zipPath = $this->backupPath . '/' . $filename;
        $tempDir = storage_path('app/temp_backup_' . time());

        File::makeDirectory($tempDir, 0755, true);

        // 复制现有 ZIP
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE);
        
        // 要备份的目录
        $dirsToBackup = [
            public_path('uploads') => 'uploads',
            public_path('images') => 'images',
        ];

        foreach ($dirsToBackup as $dir => $zipPathInArchive) {
            if (File::isDirectory($dir)) {
                $this->addDirectoryToZip($zip, $dir, $zipPathInArchive);
            }
        }

        $zip->close();

        // 清理临时目录
        File::deleteDirectory($tempDir);
    }

    /**
     * 递归添加目录到 ZIP
     */
    protected function addDirectoryToZip(ZipArchive $zip, string $sourceDir, string $basePath): void
    {
        $files = File::allFiles($sourceDir);

        foreach ($files as $file) {
            $relativePath = $basePath . '/' . $file->getRelativePathname();
            $zip->addFile($file->getPathname(), $relativePath);
        }
    }

    /**
     * 创建备份信息文件
     */
    protected function createBackupInfo(string $filename, string $type): void
    {
        $info = [
            'filename' => $filename,
            'type' => $type,
            'created_at' => now()->toIso8601String(),
            'app_version' => config('app.version', '1.0.0'),
            'database' => config('database.connections.mysql.database'),
        ];

        $zip = new ZipArchive();
        $zipPath = $this->backupPath . '/' . $filename;

        $zip->open($zipPath);
        $zip->addFromString('backup_info.json', json_encode($info, JSON_PRETTY_PRINT));
        $zip->close();
    }

    /**
     * 格式化字节大小
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }

        return round($bytes, 2) . ' ' . $units[$unitIndex];
    }
}
