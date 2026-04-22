<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 认证路由
|--------------------------------------------------------------------------
|
| 包含用户注册、登录、密码重置等认证相关路由
|
*/

// ==================== Guest 路由（未登录用户） ====================
Route::middleware('guest')->group(function () {
    // 注册 - 限流：1小时最多3次
    Route::middleware('throttle:3,60')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])
            ->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);
    });

    // 登录 - 限流：5分钟最多5次
    Route::middleware('throttle:5,5')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    // 忘记密码 - 限流：1小时最多3次
    Route::middleware('throttle:3,60')->group(function () {
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');
    });

    // 重置密码
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// ==================== Auth 路由（已登录用户） ====================
Route::middleware('auth')->group(function () {
    // 邮箱验证提示
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    // 邮箱验证
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // 发送验证邮件
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:3,60')
        ->name('verification.send');

    // 确认密码
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // 更新密码
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // 登出
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
