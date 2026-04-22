<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'token',
        'is_active',
        'subscribed_at',
        'unsubscribed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        // 创建时自动生成订阅 token
        static::creating(function (Subscription $subscription) {
            if (empty($subscription->token)) {
                $subscription->token = bin2hex(random_bytes(32));
            }
            if (empty($subscription->subscribed_at)) {
                $subscription->subscribed_at = now();
            }
        });
    }

    // ==================== Scopes ====================

    /**
     * 范围：活跃订阅
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 范围：非活跃订阅
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // ==================== Methods ====================

    /**
     * 确认订阅
     */
    public function confirm(): bool
    {
        $this->is_active = true;
        $this->subscribed_at = now();
        $this->unsubscribed_at = null;
        return $this->save();
    }

    /**
     * 取消订阅
     */
    public function unsubscribe(): bool
    {
        $this->is_active = false;
        $this->unsubscribed_at = now();
        return $this->save();
    }

    /**
     * 重新订阅
     */
    public function resubscribe(): bool
    {
        $this->is_active = true;
        $this->subscribed_at = now();
        $this->unsubscribed_at = null;
        return $this->save();
    }

    /**
     * 验证取消订阅 token
     */
    public function verifyUnsubscribeToken(string $token): bool
    {
        return $this->token === $token;
    }

    /**
     * 生成新的订阅 token
     */
    public function regenerateToken(): string
    {
        $this->token = bin2hex(random_bytes(32));
        $this->save();
        return $this->token;
    }

    /**
     * 通过邮箱查找订阅，如果不存在则创建
     */
    public static function findOrCreateByEmail(string $email): self
    {
        $subscription = static::where('email', $email)->first();

        if (!$subscription) {
            $subscription = static::create([
                'email' => $email,
            ]);
        }

        return $subscription;
    }

    /**
     * 获取取消订阅 URL
     */
    public function getUnsubscribeUrl(): string
    {
        return route('subscribe.unsubscribe', [
            'token' => $this->token,
            'email' => $this->email,
        ]);
    }
}
