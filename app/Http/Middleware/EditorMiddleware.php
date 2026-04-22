<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EditorMiddleware
{
    /**
     * Handle an incoming request.
     * 允许博主和管理员访问
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

        // 检查用户是否是博主或编辑
        if (!$request->user()->role->canManage()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden - Editor access required'], 403);
            }
            abort(403, '您需要编辑或管理员权限才能访问此页面');
        }

        return $next($request);
    }
}
