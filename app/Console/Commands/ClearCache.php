<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class ClearCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-blog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除博客相关缓存';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('正在清除博客缓存...');

        $cacheService = app(CacheService::class);
        $cacheService->flushAll();

        // 清除视图缓存
        Artisan::call('view:clear');

        // 清除配置缓存
        if (File::exists(config_path('cache.php'))) {
            Artisan::call('config:clear');
        }

        $this->info('✓ 博客缓存已清除');

        // 显示缓存统计
        $stats = $cacheService->getStats();
        $this->newLine();
        $this->info('缓存状态:');
        
        foreach ($stats as $key => $isCached) {
            $status = $isCached ? '✓ 已缓存' : '○ 未缓存';
            $this->line("  {$key}: {$status}");
        }

        return Command::SUCCESS;
    }
}
