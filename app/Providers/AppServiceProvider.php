<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Markdown\Markdown;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 注册 Markdown 服务
        $this->app->singleton(Markdown::class, function () {
            return new Markdown([
                'markdown' => [
                    'allow_inline_markdown' => true,
                ],
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 加载博客配置
        config([
            'blog' => require config_path('blog.php'),
        ]);
    }
}
