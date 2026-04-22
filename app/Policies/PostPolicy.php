<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // 所有人都可以查看文章列表
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        // 已发布的文章所有人都可以查看
        if ($post->isPublished()) {
            return true;
        }

        // 草稿和待审核文章只有作者和管理员/编辑可以查看
        if ($user->role->canManage()) {
            return true;
        }

        // 作者可以查看自己的草稿
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role->canManage();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        // 管理员和编辑可以更新任何文章
        if ($user->role->canManage()) {
            return true;
        }

        // 作者可以更新自己的文章
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // 管理员可以删除任何文章
        if ($user->role->isAdmin()) {
            return true;
        }

        // 作者可以删除自己的文章
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->role->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->role->isAdmin();
    }

    /**
     * Determine whether the user can publish/unpublish the model.
     */
    public function publish(User $user, Post $post): bool
    {
        return $user->role->canManage();
    }

    /**
     * Determine whether the user can feature the model.
     */
    public function feature(User $user, Post $post): bool
    {
        return $user->role->canManage();
    }

    /**
     * Determine whether the user can manage comments on the model.
     */
    public function manageComments(User $user, Post $post): bool
    {
        return $user->role->canManage() || $user->id === $post->user_id;
    }
}
