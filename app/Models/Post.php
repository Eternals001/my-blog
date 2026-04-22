<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Markdown\Markdown;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
        'html_content',
        'excerpt',
        'cover_image',
        'status',
        'is_sticky',
        'view_count',
        'published_at',
        'seo_title',
        'seo_description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PostStatus::class,
            'is_sticky' => 'boolean',
            'view_count' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // 自动渲染 HTML 内容
        static::saving(function (Post $post) {
            if ($post->isDirty('content')) {
                $post->html_content = (new Markdown())->toHtml($post->content);
            }
        });

        // 清除缓存
        static::saved(function () {
            Cache::forget('recent_posts');
            Cache::forget('popular_posts');
        });
    }

    // ==================== Relationships ====================

    /**
     * 获取文章作者
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 获取文章分类
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 获取文章标签
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    /**
     * 获取文章评论
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 获取已批准的评论
     */
    public function approvedComments(): HasMany
    {
        return $this->comments()->where('is_approved', true);
    }

    // ==================== Scopes ====================

    /**
     * 范围：已发布文章
     */
    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::PUBLISHED);
    }

    /**
     * 范围：草稿文章
     */
    public function scopeDraft($query)
    {
        return $query->where('status', PostStatus::DRAFT);
    }

    /**
     * 范围：定时发布文章
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', PostStatus::SCHEDULED);
    }

    /**
     * 范围：私有文章
     */
    public function scopePrivate($query)
    {
        return $query->where('status', PostStatus::PRIVATE);
    }

    /**
     * 范围：置顶文章
     */
    public function scopeSticky($query)
    {
        return $query->where('is_sticky', true);
    }

    /**
     * 范围：已发布的或定时发布的（可见文章）
     */
    public function scopeVisible($query)
    {
        return $query->where(function ($q) {
            $q->where('status', PostStatus::PUBLISHED)
              ->orWhere(function ($q2) {
                  $q2->where('status', PostStatus::SCHEDULED)
                     ->where('published_at', '<=', now());
              });
        });
    }

    /**
     * 范围：按发布时间排序
     */
    public function scopeOrderByPublished($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * 范围：按浏览量排序
     */
    public function scopeOrderByViews($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    /**
     * 范围：按置顶状态排序（置顶优先）
     */
    public function scopeOrderBySticky($query)
    {
        return $query->orderByDesc('is_sticky');
    }

    /**
     * 范围：按浏览量排序
     */
    public function scopeOrderByViewCount($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    /**
     * 范围：按热度排序
     */
    public function scopeOrderByPopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    /**
     * 范围：搜索 - 使用参数绑定防止 SQL 注入
     */
    public function scopeSearch($query, ?string $keyword)
    {
        if (!$keyword) {
            return $query;
        }

        // 使用参数绑定防止 SQL 注入
        $wildcard = '%' . $keyword . '%';

        return $query->where(function ($q) use ($wildcard) {
            $q->where('title', 'like', $wildcard)
              ->orWhere('content', 'like', $wildcard)
              ->orWhere('excerpt', 'like', $wildcard);
        });
    }

    /**
     * 范围：指定分类
     */
    public function scopeInCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * 范围：指定标签
     */
    public function scopeWithTag($query, int $tagId)
    {
        return $query->whereHas('tags', function ($q) use ($tagId) {
            $q->where('tags.id', $tagId);
        });
    }

    /**
     * 范围：指定作者
     */
    public function scopeByAuthor($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ==================== Accessors ====================

    /**
     * 获取文章状态标签
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    /**
     * 获取封面图片 URL
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }

        if (str_starts_with($this->cover_image, 'http')) {
            return $this->cover_image;
        }

        return asset('storage/' . $this->cover_image);
    }

    /**
     * 获取格式化后的浏览量
     */
    public function getFormattedViewCountAttribute(): string
    {
        $count = $this->view_count;

        if ($count >= 1000000) {
            return round($count / 1000000, 1) . 'M';
        }

        if ($count >= 1000) {
            return round($count / 1000, 1) . 'K';
        }

        return (string) $count;
    }

    // ==================== Mutators ====================

    /**
     * 设置 slug 时自动生成唯一标识
     */
    public function setSlugAttribute(string $value): void
    {
        if (!$this->exists) {
            $this->attributes['slug'] = $value;
            return;
        }

        $slug = $value;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $value . '-' . $count;
            $count++;
        }

        $this->attributes['slug'] = $slug;
    }

    // ==================== Methods ====================

    /**
     * 递增浏览量
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * 获取评论数
     */
    public function getCommentCountAttribute(): int
    {
        return $this->approvedComments()->count();
    }

    /**
     * 检查是否可以评论
     */
    public function canComment(): bool
    {
        return $this->status === PostStatus::PUBLISHED;
    }

    /**
     * 发布文章
     */
    public function publish(): bool
    {
        $this->status = PostStatus::PUBLISHED;
        $this->published_at = now();
        return $this->save();
    }

    /**
     * 保存为草稿
     */
    public function draft(): bool
    {
        $this->status = PostStatus::DRAFT;
        return $this->save();
    }

    /**
     * 定时发布
     */
    public function schedule(\DateTimeInterface $publishedAt): bool
    {
        $this->status = PostStatus::SCHEDULED;
        $this->published_at = $publishedAt;
        return $this->save();
    }

    /**
     * 获取 URL
     */
    public function getUrl(): string
    {
        return route('blog.post', ['slug' => $this->slug]);
    }
}
