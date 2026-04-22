<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    // ==================== Relationships ====================

    /**
     * 获取父分类
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * 获取子分类
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    /**
     * 获取直接子分类（快捷方法）
     */
    public function subcategories(): HasMany
    {
        return $this->children();
    }

    /**
     * 获取该分类下的所有文章
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * 获取该分类下已发布的文章
     */
    public function publishedPosts(): HasMany
    {
        return $this->posts()->published();
    }

    // ==================== Scopes ====================

    /**
     * 范围：顶级分类
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * 范围：按排序字段排序
     */
    public function scopeOrderByOrder($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * 范围：有文章的分类
     */
    public function scopeHasPosts($query)
    {
        return $query->whereHas('posts', function ($q) {
            $q->where('status', 'published');
        });
    }

    // ==================== Accessors ====================

    /**
     * 获取完整的层级路径名称
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->name];

        if ($this->parent) {
            array_unshift($path, $this->parent->full_path);
        }

        return implode(' / ', $path);
    }

    /**
     * 获取深度
     */
    public function getDepthAttribute(): int
    {
        $depth = 0;
        $category = $this->parent;

        while ($category) {
            $depth++;
            $category = $category->parent;
        }

        return $depth;
    }

    /**
     * 获取文章数量
     */
    public function getPostCountAttribute(): int
    {
        return $this->publishedPosts()->count();
    }

    // ==================== Methods ====================

    /**
     * 判断是否为顶级分类
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * 判断是否为指定分类的祖先
     */
    public function isAncestorOf(Category $category): bool
    {
        $parent = $category->parent;

        while ($parent) {
            if ($parent->id === $this->id) {
                return true;
            }
            $parent = $parent->parent;
        }

        return false;
    }

    /**
     * 判断是否为指定分类的后代
     */
    public function isDescendantOf(Category $category): bool
    {
        return $category->isAncestorOf($this);
    }

    /**
     * 获取所有后代分类 ID
     */
    public function getAllDescendantIds(): array
    {
        $ids = [];

        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllDescendantIds());
        }

        return $ids;
    }

    /**
     * 获取所有后代分类（包括自身）
     */
    public function getAllDescendantsAndSelf(): \Illuminate\Support\Collection
    {
        $descendants = collect([$this]);

        foreach ($this->children as $child) {
            $descendants = $descendants->merge($child->getAllDescendantsAndSelf());
        }

        return $descendants;
    }
}
