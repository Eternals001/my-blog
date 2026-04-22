<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'ip',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'target_id' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /**
     * 获取操作用户
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 范围：按操作类型筛选
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * 范围：按目标类型筛选
     */
    public function scopeTargetType($query, string $targetType)
    {
        return $query->where('target_type', $targetType);
    }

    /**
     * 范围：日期范围筛选
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}