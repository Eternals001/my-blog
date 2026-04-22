<?php

/**
 * Vercel PHP 入口文件
 */

// 加载 Composer 自动加载器
require __DIR__ . '/../vendor/autoload.php';

// 加载 Laravel 应用
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 创建内核实例
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// 处理请求
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// 发送响应
$response->send();

// 终止
$kernel->terminate($request, $response);