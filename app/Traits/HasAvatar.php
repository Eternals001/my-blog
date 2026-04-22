<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HasAvatar
{
    /**
     * 获取用户头像 URL
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            // 如果是外部 URL，直接返回
            if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
                return $this->avatar;
            }
            
            // 本地存储的头像
            if (Storage::disk('public')->exists($this->avatar)) {
                return Storage::url($this->avatar);
            }
        }

        // 生成 Gravatar 头像
        return $this->getGravatarUrl();
    }

    /**
     * 生成 Gravatar URL
     */
    protected function getGravatarUrl(): string
    {
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=120";
    }

    /**
     * 检查是否有自定义头像
     */
    public function hasCustomAvatar(): bool
    {
        if (!$this->avatar) {
            return false;
        }

        return !str_starts_with($this->avatar, 'http://') && !str_starts_with($this->avatar, 'https://');
    }
}
