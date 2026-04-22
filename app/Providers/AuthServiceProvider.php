<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        Comment::class => CommentPolicy::class,
        Category::class => CategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // 强制注册所有策略
        $this->registerPolicies();

        // 定义用户角色权限 Gate
        $this->defineGates();
    }

    /**
     * 定义权限 Gate
     */
    protected function defineGates(): void
    {
        // ==================== 用户管理 Gate ====================

        // 博主/管理员权限
        Gate::define('admin', function (User $user) {
            return $user->role->isAdmin();
        });

        // 编辑及以上权限
        Gate::define('editor', function (User $user) {
            return $user->role->canManage();
        });

        // 订阅者及以上权限
        Gate::define('user', function (User $user) {
            return true; // 所有登录用户
        });

        // ==================== 文章管理 Gate ====================

        // 创建文章
        Gate::define('create-post', function (User $user) {
            return $user->role->canManage();
        });

        // 编辑自己的文章
        Gate::define('update-own-post', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });

        // 编辑任何文章
        Gate::define('update-any-post', function (User $user) {
            return $user->role->canManage();
        });

        // 删除自己的文章
        Gate::define('delete-own-post', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });

        // 删除任何文章
        Gate::define('delete-any-post', function (User $user) {
            return $user->role->isAdmin();
        });

        // ==================== 评论管理 Gate ====================

        // 发表评论
        Gate::define('create-comment', function (User $user) {
            return true; // 所有登录用户都可以评论
        });

        // 删除自己的评论
        Gate::define('delete-own-comment', function (User $user, Comment $comment) {
            return $user->id === $comment->user_id;
        });

        // 删除任何评论
        Gate::define('delete-any-comment', function (User $user) {
            return $user->role->canManage();
        });

        // 审核评论
        Gate::define('approve-comment', function (User $user) {
            return $user->role->canManage();
        });

        // ==================== 分类管理 Gate ====================

        // 创建分类
        Gate::define('create-category', function (User $user) {
            return $user->role->isAdmin();
        });

        // 编辑分类
        Gate::define('update-category', function (User $user) {
            return $user->role->isAdmin();
        });

        // 删除分类
        Gate::define('delete-category', function (User $user) {
            return $user->role->isAdmin();
        });

        // ==================== 标签管理 Gate ====================

        // 创建标签
        Gate::define('create-tag', function (User $user) {
            return $user->role->canManage();
        });

        // 编辑标签
        Gate::define('update-tag', function (User $user) {
            return $user->role->canManage();
        });

        // 删除标签
        Gate::define('delete-tag', function (User $user) {
            return $user->role->canManage();
        });

        // ==================== 用户管理 Gate ====================

        // 管理用户
        Gate::define('manage-users', function (User $user) {
            return $user->role->isAdmin();
        });

        // 编辑用户
        Gate::define('update-user', function (User $user, User $target) {
            // 用户可以编辑自己，或者管理员可以编辑其他用户
            return $user->id === $target->id || $user->role->isAdmin();
        });

        // 删除用户
        Gate::define('delete-user', function (User $user, User $target) {
            // 防止删除自己
            if ($user->id === $target->id) {
                return false;
            }
            return $user->role->isAdmin();
        });

        // ==================== 系统设置 Gate ====================

        // 访问后台仪表盘
        Gate::define('view-admin-dashboard', function (User $user) {
            return $user->role->canManage();
        });

        // 系统设置
        Gate::define('system-settings', function (User $user) {
            return $user->role->isAdmin();
        });
    }
}
