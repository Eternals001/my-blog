<?php

namespace App\Http\Middleware;

use App\Models\Post;
use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 只跟踪 GET 请求
        if ($request->method() !== 'GET') {
            return $response;
        }

        // 获取当前路由参数
        $routeName = $request->route()?->getName();

        // 如果是文章详情页，增加浏览量并记录访问
        if ($routeName === 'blog.post' && $slug = $request->route('slug')) {
            $this->trackPostView($slug, $request);
        }

        return $response;
    }

    /**
     * 跟踪文章浏览
     */
    protected function trackPostView(string $slug, Request $request): void
    {
        // 使用 Cache 防止同一用户短时间内重复计数
        $cacheKey = "post_view_{$slug}_{$request->ip()}";
        $cacheMinutes = config('blog.view_count_decay', 30); // 同一 IP 30 分钟内只计算一次

        if (!Cache::has($cacheKey)) {
            // 更新数据库中的浏览量
            $post = Post::where('slug', $slug)->first();

            if ($post) {
                $post->incrementViewCount();

                // 记录访问日志（异步）
                $this->recordVisitor($post->id, $request);

                // 设置防刷缓存
                Cache::put($cacheKey, true, now()->addMinutes($cacheMinutes));
            }
        }
    }

    /**
     * 记录访问日志
     */
    protected function recordVisitor(int $postId, Request $request): void
    {
        try {
            // 尝试使用 Visitor 模型记录
            if (class_exists(Visitor::class)) {
                Visitor::create([
                    'ip_address' => $request->ip(),
                    'user_id' => auth()->id(),
                    'post_id' => $postId,
                    'user_agent' => $request->userAgent(),
                    'referer' => $request->header('referer'),
                    'visited_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // 静默处理，避免影响正常请求
            report($e);
        }
    }
}
