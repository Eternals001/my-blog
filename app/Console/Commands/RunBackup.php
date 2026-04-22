<?php

namespace App\Console\Commands;

use App\Models\Backup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class RunBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:run 
                            {--type=full : 备份类型 (full, database, files)}
                            {--filename= : 自定义文件名}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '运行数据库和文件备份';

    /**
     * 备份目录
     */
    protected string $backupPath;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');
        $customFilename = $this->option('filename');

        $this->backupPath = storage_path('app/backups');

        // 确保备份目录存在
        if (!File::isDirectory($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }

        $filename = $customFilename ?: $this->generateFilename($type);

        $this->info("开始创建备份: {$filename}");

        try {
            if ($type === 'full' || $type === 'database') {
                $this->info('备份数据库...');
                $this->backupDatabase($filename);
            }

            if ($type === 'full' || $type === 'files') {
                $this->info('备份文件...');
                $this->backupFiles($filename);
            }

            $this->createInfoFile($filename, $type);

            $filePath = $this->backupPath . '/' . $filename;
            $fileSize = File::size($filePath);

            $this->newLine();
            $this->info("✓ 备份创建成功!");
            $this->table(
                ['项目', '值'],
                [
                    ['文件名', $filename],
                    ['类型', $type],
                    ['大小', $this->formatBytes($fileSize)],
                    ['路径', $filePath],
                ]
            );

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("备份失败: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * 生成备份文件名
     */
    protected function generateFilename(string $type): string
    {
        $prefix = match($type) {
            'database' => 'db',
            'files' => 'files',
            default => 'backup',
        };

        $appName = preg_replace('/[^a-zA-Z0-9]/', '_', config('app.url'));
        
        return sprintf(
            '%s_%s_%s.zip',
            $prefix,
            $appName,
            now()->format('Y-m-d_His')
        );
    }

    /**
     * 备份数据库
     */
    protected function backupDatabase(string $filename): void
    {
        $zipPath = $this->backupPath . '/' . $filename;
        
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('无法创建 ZIP 文件');
        }

        $tables = DB::select('SHOW TABLES');
        $tablePrefix = config('database.connections.mysql.prefix', '');
        $sqlContent = $this->generateSqlHeader();

        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            $tableNameWithoutPrefix = str_replace($tablePrefix, '', $tableName);

            // 跳过系统表
            if (in_array($tableNameWithoutPrefix, ['sessions', 'cache', 'jobs', 'failed_jobs', 'personal_access_tokens'])) {
                $this->line("  跳过系统表: {$tableNameWithoutPrefix}");
                continue;
            }

            $this->line("  处理表: {$tableNameWithoutPrefix}");
            
            $sqlContent .= "\n-- Table: {$tableNameWithoutPrefix}\n";
            $sqlContent .= "DROP TABLE IF EXISTS `{$tableNameWithoutPrefix}`;\n";

            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            if (!empty($createTable)) {
                $createSQL = array_values((array)$createTable[0])[1];
                $sqlContent .= $createSQL . ";\n";
            }

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

        $this->info("  数据库备份完成");
    }

    /**
     * 生成 SQL 文件头
     */
    protected function generateSqlHeader(): string
    {
        return <<<SQL
-- =====================================================
-- Blog Database Backup
-- Generated by Laravel Backup Command
-- =====================================================
-- Date: {$this->getSqlTimestamp()}
-- Database: {config('database.connections.mysql.database')}
-- App: {config('app.name')}
-- Version: {config('app.version', '1.0.0')}
-- =====================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

SQL;
    }

    /**
     * 获取 SQL 时间戳
     */
    protected function getSqlTimestamp(): string
    {
        return now()->format('Y-m-d H:i:s');
    }

    /**
     * 备份文件
     */
    protected function backupFiles(string $filename): void
    {
        $zipPath = $this->backupPath . '/' . $filename;

        // 如果文件已存在，先打开
        if (File::exists($zipPath)) {
            $zip = new ZipArchive();
            $zip->open($zipPath);
        } else {
            $zip = new ZipArchive();
            $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        }

        $dirsToBackup = [
            public_path('uploads') => 'uploads',
            public_path('images') => 'images',
        ];

        $fileCount = 0;
        foreach ($dirsToBackup as $dir => $archivePath) {
            if (File::isDirectory($dir)) {
                $this->line("  压缩目录: {$archivePath}");
                $fileCount += $this->addDirectoryToZip($zip, $dir, $archivePath);
            }
        }

        $zip->close();
        $this->info("  文件备份完成 ({$fileCount} 个文件)");
    }

    /**
     * 递归添加目录到 ZIP
     */
    protected function addDirectoryToZip(ZipArchive $zip, string $sourceDir, string $basePath): int
    {
        $count = 0;
        $files = File::allFiles($sourceDir);

        foreach ($files as $file) {
            $relativePath = $basePath . '/' . $file->getRelativePathname();
            $zip->addFile($file->getPathname(), $relativePath);
            $count++;
        }

        return $count;
    }

    /**
     * 创建备份信息文件
     */
    protected function createInfoFile(string $filename, string $type): void
    {
        $zip = new ZipArchive();
        $zipPath = $this->backupPath . '/' . $filename;
        $zip->open($zipPath);

        $info = [
            'filename' => $filename,
            'type' => $type,
            'created_at' => now()->toIso8601String(),
            'app' => [
                'name' => config('app.name'),
                'version' => config('app.version', '1.0.0'),
                'url' => config('app.url'),
            ],
            'database' => config('database.connections.mysql.database'),
            'backup_command' => 'backup:run',
        ];

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
