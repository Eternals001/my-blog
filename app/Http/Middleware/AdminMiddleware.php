<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 检查用户是否已登录
        if (!$request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login');
        }

        // 检查用户是否有管理权限
        if (!$request->user()->canManage()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            abort(403, '您没有权限访问此页面');
        }

        return $next($request);
    }
}
