<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case SUBSCRIBER = 'subscriber';

    /**
     * 获取角色显示名称
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => '管理员',
            self::EDITOR => '编辑',
            self::SUBSCRIBER => '订阅者',
        };
    }

    /**
     * 获取角色颜色
     */
    public function color(): string
    {
        return match ($this) {
            self::ADMIN => 'red',
            self::EDITOR => 'blue',
            self::SUBSCRIBER => 'gray',
        };
    }

    /**
     * 判断是否有管理权限
     */
    public function canManage(): bool
    {
        return $this === self::ADMIN || $this === self::EDITOR;
    }

    /**
     * 判断是否为管理员
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * 获取所有枚举值
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
