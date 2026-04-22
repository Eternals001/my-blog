<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 首页
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

// 博客文章路由组
Route::prefix('blog')->name('blog.')->group(function () {
    // 搜索
    Route::get('search', [\App\Http\Controllers\Blog\SearchController::class, 'index'])
        ->name('search');

    // 评论（放在文章详情路由之前，避免被覆盖）- 限流：5分钟最多3次
    Route::post('posts/{post}/comments', [\App\Http\Controllers\Blog\CommentController::class, 'store'])
        ->middleware('throttle:3,5')
        ->name('comments.store');

    // 文章列表
    Route::get('posts', [\App\Http\Controllers\Blog\PostController::class, 'index'])
        ->name('posts.index');

    // 文章详情
    Route::get('posts/{slug}', [\App\Http\Controllers\Blog\PostController::class, 'show'])
        ->name('post');

    // 分类文章列表
    Route::get('category/{slug}', [\App\Http\Controllers\Blog\CategoryController::class, 'show'])
        ->name('category');

    // 标签文章列表
    Route::get('tag/{slug}', [\App\Http\Controllers\Blog\TagController::class, 'show'])
        ->name('tag');

    // 作者文章列表
    Route::get('author/{user}', [\App\Http\Controllers\Blog\AuthorController::class, 'show'])
        ->name('author');
});

// 订阅路由 - 带速率限制防止滥用
Route::prefix('subscribe')->name('subscribe.')->group(function () {
    Route::get('/', [\App\Http\Controllers\SubscribeController::class, 'show'])->name('show');
    // 订阅操作限流：5分钟内最多3次
    Route::post('/', [\App\Http\Controllers\SubscribeController::class, 'subscribe'])
        ->middleware('throttle:3,5')
        ->name('subscribe');
    Route::get('confirm/{token}', [\App\Http\Controllers\SubscribeController::class, 'confirm'])->name('confirm');
    Route::get('unsubscribe', [\App\Http\Controllers\SubscribeController::class, 'unsubscribeShow'])->name('unsubscribe.show');
    // 退订操作限流：5分钟内最多3次
    Route::post('unsubscribe', [\App\Http\Controllers\SubscribeController::class, 'unsubscribe'])
        ->middleware('throttle:3,5')
        ->name('unsubscribe');
});

// 用户资料（公开）
Route::get('user/{user}', [\App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');

// 用户设置（需登录）
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('settings', [\App\Http\Controllers\UserController::class, 'settings'])->name('settings');
    Route::put('profile', [\App\Http\Controllers\UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('password', [\App\Http\Controllers\UserController::class, 'updatePassword'])->name('password.update');
    Route::post('verification/send', [\App\Http\Controllers\UserController::class, 'sendVerificationEmail'])->name('verification.send');
    Route::delete('account', [\App\Http\Controllers\UserController::class, 'destroy'])->name('account.delete');
});

// 认证路由
require __DIR__ . '/auth.php';

// 管理员路由
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'stats'])->name('dashboard.stats');

    // 文章管理
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    Route::patch('posts/{post}/publish', [\App\Http\Controllers\Admin\PostController::class, 'publish'])->name('posts.publish');
    Route::patch('posts/{post}/unpublish', [\App\Http\Controllers\Admin\PostController::class, 'unpublish'])->name('posts.unpublish');
    Route::patch('posts/{post}/sticky', [\App\Http\Controllers\Admin\PostController::class, 'sticky'])->name('posts.sticky');
    Route::post('posts/bulk-action', [\App\Http\Controllers\Admin\PostController::class, 'bulkAction'])->name('posts.bulk-action');

    // 分类管理
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    // 标签管理
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class);

    // 评论管理
    Route::get('comments', [\App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
    Route::get('comments/pending', [\App\Http\Controllers\Admin\CommentController::class, 'pending'])->name('comments.pending');
    Route::patch('comments/{comment}/approve', [\App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
    Route::patch('comments/{comment}/spam', [\App\Http\Controllers\Admin\CommentController::class, 'spam'])->name('comments.spam');
    Route::delete('comments/{comment}', [\App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('comments/bulk-approve', [\App\Http\Controllers\Admin\CommentController::class, 'bulkApprove'])->name('comments.bulk-approve');
    Route::post('comments/bulk-delete', [\App\Http\Controllers\Admin\CommentController::class, 'bulkDelete'])->name('comments.bulk-delete');

    // 用户管理
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // 订阅管理
    Route::get('subscriptions', [\App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::delete('subscriptions/{subscription}', [\App\Http\Controllers\Admin\SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    // 系统设置
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::match(['put', 'patch'], 'settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/reset', [\App\Http\Controllers\Admin\SettingsController::class, 'reset'])->name('settings.reset');

    // 数据备份
    Route::prefix('backups')->name('backups.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Backend\BackupController::class, 'index'])->name('index');
        Route::post('create', [\App\Http\Controllers\Backend\BackupController::class, 'create'])->name('create');
        Route::get('download/{filename}', [\App\Http\Controllers\Backend\BackupController::class, 'download'])->name('download');
        Route::delete('destroy/{filename}', [\App\Http\Controllers\Backend\BackupController::class, 'destroy'])->name('destroy');
    });
});

// 站点地图
Route::get('sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// RSS/Atom 订阅
Route::get('feed/rss', [\App\Http\Controllers\FeedController::class, 'rss'])->name('feed.rss');
Route::get('feed/atom', [\App\Http\Controllers\FeedController::class, 'atom'])->name('feed.atom');

// robots.txt
Route::get('robots.txt', function () {
    $sitemapUrl = url('/sitemap.xml');
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Disallow: /admin/\n";
    $content .= "Disallow: /user/settings\n";
    $content .= "Sitemap: {$sitemapUrl}\n";
    
    return response($content, 200, [
        'Content-Type' => 'text/plain; charset=utf-8',
    ]);
})->name('robots');
