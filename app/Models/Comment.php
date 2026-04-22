<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'author_name',
        'author_email',
        'author_url',
        'content',
        'is_approved',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'author_url' => 'string',
        ];
    }

    // ==================== Relationships ====================

    /**
     * 获取评论所属文章
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * 获取评论作者（用户）
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 获取父评论
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * 获取子评论
     */
    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // ==================== Scopes ====================

    /**
     * 范围：已批准的评论
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * 范围：待审核的评论
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * 范围：顶级评论（无父评论）
     */
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * 范围：回复评论
     */
    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * 范围：按创建时间排序
     */
    public function scopeOrderByNewest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * 范围：按创建时间倒序（最早的在前）
     */
    public function scopeOrderByOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    // ==================== Accessors ====================

    /**
     * 获取评论者显示名称
     */
    public function getDisplayNameAttribute(): string
    {
        // 如果是登录用户，使用用户名
        if ($this->user) {
            return $this->user->name;
        }

        // 否则使用评论时填写的名称
        return $this->author_name;
    }

    /**
     * 获取评论者头像
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->user?->avatar_url) {
            return $this->user->avatar_url;
        }

        // 使用 Gravatar
        $email = md5(strtolower(trim($this->author_email)));
        return "https://www.gravatar.com/avatar/{$email}?d=identicon&s=80";
    }

    /**
     * 获取评论者网站链接
     */
    public function getWebsiteAttribute(): ?string
    {
        if ($this->user?->id) {
            return route('user.profile', ['user' => $this->user->id]);
        }

        return $this->author_url;
    }

    /**
     * 获取纯文本内容
     */
    public function getPlainContentAttribute(): string
    {
        return strip_tags($this->content);
    }

    // ==================== Methods ====================

    /**
     * 批准评论
     */
    public function approve(): bool
    {
        $this->is_approved = true;
        return $this->save();
    }

    /**
     * 驳回评论
     */
    public function reject(): bool
    {
        $this->is_approved = false;
        return $this->save();
    }

    /**
     * 判断是否为回复
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * 判断评论是否属于指定用户
     */
    public function belongsToUser(User $user): bool
    {
        if ($this->user_id) {
            return $this->user_id === $user->id;
        }

        return false;
    }

    /**
     * 获取嵌套回复
     */
    public function getNestedReplies(int $maxDepth = 3): \Illuminate\Support\Collection
    {
        if ($maxDepth <= 0) {
            return collect();
        }

        $replies = $this->children()->approved()->get();

        return $replies->map(function ($reply) use ($maxDepth) {
            $reply->nested_replies = $reply->getNestedReplies($maxDepth - 1);
            return $reply;
        });
    }

    /**
     * 获取深度
     */
    public function getDepth(): int
    {
        $depth = 0;
        $comment = $this->parent;

        while ($comment) {
            $depth++;
            $comment = $comment->parent;
        }

        return $depth;
    }
}
