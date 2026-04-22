<?php

namespace App\Enums;

enum PostStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case SCHEDULED = 'scheduled';
    case PRIVATE = 'private';

    /**
     * 获取状态显示名称
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => '草稿',
            self::PUBLISHED => '已发布',
            self::SCHEDULED => '定时发布',
            self::PRIVATE => '私有',
        };
    }

    /**
     * 获取状态颜色
     */
    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PUBLISHED => 'green',
            self::SCHEDULED => 'yellow',
            self::PRIVATE => 'red',
        };
    }

    /**
     * 判断是否可见
     */
    public function isVisible(): bool
    {
        return $this === self::PUBLISHED;
    }

    /**
     * 判断是否可以被访问
     */
    public function isAccessible(): bool
    {
        return $this === self::PUBLISHED || $this === self::PRIVATE;
    }

    /**
     * 获取所有枚举值
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
