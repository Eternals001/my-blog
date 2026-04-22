<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ListBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '列出所有备份文件';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $backupPath = storage_path('app/backups');

        if (!File::isDirectory($backupPath)) {
            $this->info('备份目录不存在，请先运行 backup:run 创建备份。');
            return Command::SUCCESS;
        }

        $files = File::files($backupPath);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'zip') {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'created' => \Carbon\Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d H:i:s'),
                    'age' => \Carbon\Carbon::createFromTimestamp($file->getMTime())->diffForHumans(),
                ];
            }
        }

        if (empty($backups)) {
            $this->info('暂无备份记录。');
            return Command::SUCCESS;
        }

        // 按时间倒序
        usort($backups, function ($a, $b) {
            return strcmp($b['created'], $a['created']);
        });

        $this->info("找到 " . count($backups) . " 个备份文件:");
        $this->newLine();

        $this->table(
            ['文件名', '大小', '创建时间', '创建于'],
            array_map(function ($backup) {
                return [
                    $backup['filename'],
                    $backup['size'],
                    $backup['created'],
                    $backup['age'],
                ];
            }, $backups)
        );

        return Command::SUCCESS;
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
