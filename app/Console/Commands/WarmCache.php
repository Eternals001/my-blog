<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;

class WarmCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm-up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '预热博客缓存';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('正在预热博客缓存...');
        $this->newLine();

        $startTime = microtime(true);

        $cacheService = app(CacheService::class);
        $cacheService->warmUp();

        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2);

        $this->info("✓ 缓存预热完成 (耗时: {$duration}ms)");

        // 显示缓存统计
        $stats = $cacheService->getStats();
        $this->newLine();
        $this->info('缓存状态:');
        
        foreach ($stats as $key => $isCached) {
            $status = $isCached ? '✓ 已缓存' : '✗ 未缓存';
            $icon = $isCached ? '✓' : '✗';
            $this->line("  {$icon} {$key}");
        }

        return Command::SUCCESS;
    }
}
