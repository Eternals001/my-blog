<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // 所有人都可以查看分类列表
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $category): bool
    {
        return true; // 所有人都可以查看分类
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->role->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        // 防止删除有文章的分类
        if ($category->posts()->exists()) {
            return false;
        }

        return $user->role->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        return $user->role->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        // 防止删除有文章的分类
        if ($category->posts()->withTrashed()->exists()) {
            return false;
        }

        return $user->role->isAdmin();
    }

    /**
     * Determine whether the user can reorder categories.
     */
    public function reorder(User $user): bool
    {
        return $user->role->isAdmin();
    }
}
