<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Traits\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasAvatar;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'email_token',
        'email_verified_at',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    // ==================== Relationships ====================

    /**
     * 获取用户的所有文章
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * 获取用户的已发布文章
     */
    public function publishedPosts(): HasMany
    {
        return $this->posts()->where('status', 'published');
    }

    /**
     * 获取用户的评论
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 获取用户已批准的评论
     */
    public function approvedComments(): HasMany
    {
        return $this->comments()->where('is_approved', true);
    }

    // ==================== Scopes ====================

    /**
     * 范围：仅管理员
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', UserRole::ADMIN);
    }

    /**
     * 范围：仅编辑
     */
    public function scopeEditors($query)
    {
        return $query->where('role', UserRole::EDITOR);
    }

    /**
     * 范围：仅订阅者
     */
    public function scopeSubscribers($query)
    {
        return $query->where('role', UserRole::SUBSCRIBER);
    }

    /**
     * 范围：可管理的用户（管理员和编辑）
     */
    public function scopeManageable($query)
    {
        return $query->whereIn('role', [UserRole::ADMIN, UserRole::EDITOR]);
    }

    /**
     * 范围：已验证邮箱
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    // ==================== Accessors ====================

    /**
     * 获取用户角色显示名称
     */
    public function getRoleLabelAttribute(): string
    {
        return $this->role->label();
    }

    /**
     * 获取头像 URL
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }

        return asset('storage/' . $this->avatar);
    }

    // ==================== Methods ====================

    /**
     * 判断是否有管理权限
     */
    public function canManage(): bool
    {
        return $this->role->canManage();
    }

    /**
     * 判断是否为管理员
     */
    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }

    /**
     * 判断是否为编辑
     */
    public function isEditor(): bool
    {
        return $this->role === UserRole::EDITOR;
    }

    /**
     * 判断是否可以管理指定用户
     */
    public function canManageUser(User $user): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isEditor() && !$user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * 生成邮箱验证 token - 使用哈希存储提高安全性
     * 返回原始 token 用于验证链接，用户通过邮件点击链接时携带
     */
    public function generateEmailToken(): string
    {
        // 生成原始 token
        $rawToken = bin2hex(random_bytes(32));
        // 存储哈希值到数据库
        $this->email_token = hash('sha256', $rawToken);
        $this->save();
        // 返回原始 token，用于验证链接
        return $rawToken;
    }

    /**
     * 验证邮箱 token - 使用时间安全比较防止时序攻击
     */
    public function verifyEmailToken(string $token): bool
    {
        if (!$this->email_token) {
            return false;
        }
        // 使用 hash_equals 进行时间安全的比较，防止时序攻击
        return hash_equals($this->email_token, hash('sha256', $token));
    }

    /**
     * 确认邮箱验证
     */
    public function confirmEmailVerification(): void
    {
        $this->email_verified_at = now();
        $this->email_token = null;
        $this->save();
    }
}
