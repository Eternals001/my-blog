<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    /**
     * 表名
     */
    protected $table = 'visitors';

    /**
     * 是否自动维护时间戳
     */
    public $timestamps = false;

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'ip_address',
        'user_id',
        'post_id',
        'user_agent',
        'referer',
        'country',
        'city',
    ];

    /**
     * 类型转换
     */
    protected $casts = [
        'visited_at' => 'datetime',
    ];

    /**
     * 获取关联的文章
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * 获取访问者用户
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 记录一次访问
     */
    public static function record(array $data): self
    {
        return static::create([
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'user_id' => $data['user_id'] ?? auth()->id(),
            'post_id' => $data['post_id'] ?? null,
            'user_agent' => $data['user_agent'] ?? request()->userAgent(),
            'referer' => $data['referer'] ?? request()->header('referer'),
            'visited_at' => now(),
        ]);
    }

    /**
     * 按日期统计访问量
     */
    public static function statsByDate(string $startDate, string $endDate)
    {
        return static::whereBetween('visited_at', [$startDate, $endDate])
            ->selectRaw('DATE(visited_at) as date, COUNT(*) as pv, COUNT(DISTINCT ip_address) as uv')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * 按文章统计访问量
     */
    public static function statsByPost(int $limit = 10)
    {
        return static::whereNotNull('post_id')
            ->selectRaw('post_id, COUNT(*) as pv, COUNT(DISTINCT ip_address) as uv')
            ->groupBy('post_id')
            ->orderByDesc('pv')
            ->limit($limit)
            ->with('post:id,title,slug')
            ->get();
    }
}
