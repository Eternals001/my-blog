<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'name' => 'string',
            'slug' => 'string',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        // 自动生成 slug
        static::creating(function (Tag $tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        // 确保 slug 唯一
        static::saving(function (Tag $tag) {
            if ($tag->isDirty('slug') || !$tag->exists) {
                $tag->slug = static::generateUniqueSlug($tag->slug ?? Str::slug($tag->name), $tag->id);
            }
        });
    }

    /**
     * 生成唯一的 slug
     */
    public static function generateUniqueSlug(string $slug, ?int $excludeId = null): string
    {
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    // ==================== Relationships ====================

    /**
     * 获取使用该标签的文章
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tag');
    }

    /**
     * 获取使用该标签的已发布文章
     */
    public function publishedPosts(): BelongsToMany
    {
        return $this->posts()->published();
    }

    // ==================== Scopes ====================

    /**
     * 范围：搜索
     */
    public function scopeSearch($query, ?string $keyword)
    {
        if (!$keyword) {
            return $query;
        }

        return $query->where('name', 'like', "%{$keyword}%");
    }

    /**
     * 范围：有文章关联的标签
     */
    public function scopeHasPosts($query)
    {
        return $query->whereHas('posts', function ($q) {
            $q->where('status', 'published');
        });
    }

    /**
     * 范围：按文章数量排序
     */
    public function scopeOrderByPostCount($query)
    {
        return $query->withCount('publishedPosts')
            ->orderBy('published_posts_count', 'desc');
    }

    // ==================== Accessors ====================

    /**
     * 获取文章数量
     */
    public function getPostCountAttribute(): int
    {
        return $this->publishedPosts()->count();
    }

    // ==================== Methods ====================

    /**
     * 通过名称查找或创建标签
     */
    public static function findOrCreateByName(string $name): self
    {
        $slug = Str::slug($name);

        $tag = static::where('slug', $slug)->first();

        if (!$tag) {
            $tag = static::create([
                'name' => $name,
                'slug' => $slug,
            ]);
        }

        return $tag;
    }

    /**
     * 通过名称数组查找或创建标签
     */
    public static function findOrCreateByNames(array $names): \Illuminate\Support\Collection
    {
        $tags = collect();

        foreach ($names as $name) {
            $tags->push(static::findOrCreateByName(trim($name)));
        }

        return $tags;
    }
}
