<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 注册中间件别名
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'editor' => \App\Http\Middleware\EditorMiddleware::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'track.visitor' => \App\Http\Middleware\TrackVisitorMiddleware::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);

        // Web 中间件组
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitorMiddleware::class,
        ]);

        // 配置 API 限流
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // 配置限流器
        $middleware->throttleApi('60,1');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
