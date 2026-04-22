<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * 后台首页/仪表盘
     */
    public function index()
    {
        $stats = $this->getStats();

        $recentPosts = Post::with('author:id,name')
            ->latest()
            ->limit(5)
            ->get();

        $recentComments = Comment::with(['post:id,title,slug', 'user:id,name'])
            ->latest()
            ->limit(5)
            ->get();

        $popularPosts = Post::visible()
            ->orderByViewCount()
            ->limit(5)
            ->get(['id', 'title', 'slug', 'view_count']);

        // 获取访问趋势数据（最近7天）
        $visitStats = $this->getVisitStats();

        // 获取文章发布趋势（最近30天）
        $postTrend = $this->getPostTrend();

        return view('admin.dashboard', compact(
            'stats',
            'recentPosts',
            'recentComments',
            'popularPosts',
            'visitStats',
            'postTrend'
        ));
    }

    /**
     * 获取统计数据
     */
    protected function getStats(): array
    {
        return Cache::remember('dashboard_stats', now()->addMinutes(5), function () {
            return [
                'posts_count' => Post::count(),
                'published_posts' => Post::published()->count(),
                'draft_posts' => Post::draft()->count(),
                'scheduled_posts' => Post::scheduled()->count(),
                'comments_count' => Comment::count(),
                'pending_comments' => Comment::pending()->count(),
                'users_count' => User::count(),
                'subscriptions_count' => Subscription::active()->count(),
                'total_views' => Post::visible()->sum('view_count'),
            ];
        });
    }

    /**
     * 获取访问统计
     */
    protected function getVisitStats(): array
    {
        $stats = [];
        $dates = [];

        // 最近7天
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;
            $stats['pv'][$date] = 0;
            $stats['uv'][$date] = 0;
        }

        // 如果 Visitor 模型存在，获取数据
        if (class_exists(Visitor::class)) {
            $visitors = Visitor::whereDate('created_at', '>=', now()->subDays(6))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as pv, COUNT(DISTINCT ip_address) as uv')
                ->groupBy('date')
                ->get();

            foreach ($visitors as $visitor) {
                if (isset($stats['pv'][$visitor->date])) {
                    $stats['pv'][$visitor->date] = $visitor->pv;
                    $stats['uv'][$visitor->date] = $visitor->uv;
                }
            }
        }

        return [
            'dates' => array_map(fn($d) => now()->parse($d)->format('m/d'), $dates),
            'pv' => array_values($stats['pv']),
            'uv' => array_values($stats['uv']),
        ];
    }

    /**
     * 获取文章发布趋势
     */
    protected function getPostTrend(): array
    {
        $stats = [];
        $dates = [];

        // 最近30天
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;
            $stats[$date] = 0;
        }

        $posts = Post::whereDate('created_at', '>=', now()->subDays(29))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get();

        foreach ($posts as $post) {
            if (isset($stats[$post->date])) {
                $stats[$post->date] = $post->count;
            }
        }

        return [
            'dates' => array_map(fn($d) => now()->parse($d)->format('m/d'), $dates),
            'values' => array_values($stats),
        ];
    }

    /**
     * 获取快速统计 API（供前端图表使用）
     */
    public function stats()
    {
        return response()->json($this->getStats());
    }
}
