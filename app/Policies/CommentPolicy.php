<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // 所有人都可以查看评论列表
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        // 已批准的评论所有人都可以查看
        if ($comment->is_approved) {
            return true;
        }

        // 评论作者可以查看自己的评论
        if ($user->id === $comment->user_id) {
            return true;
        }

        // 管理员和编辑可以查看所有评论
        return $user->role->canManage();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // 所有登录用户都可以发表评论
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        // 评论作者可以更新自己的评论
        if ($user->id === $comment->user_id) {
            return true;
        }

        // 管理员和编辑可以更新任何评论
        return $user->role->canManage();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // 评论作者可以删除自己的评论
        if ($user->id === $comment->user_id) {
            return true;
        }

        // 管理员和编辑可以删除任何评论
        return $user->role->canManage();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $user->role->canManage();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->role->isAdmin();
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Comment $comment): bool
    {
        return $user->role->canManage();
    }

    /**
     * Determine whether the user can reply to the model.
     */
    public function reply(User $user, Comment $comment): bool
    {
        return $user->role->canManage() || $user->id === $comment->user_id;
    }
}
