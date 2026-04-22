<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     * 确保用户邮箱已验证
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $redirectToRoute = null): Response
    {
        // 检查用户是否已登录
        if (!$request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login');
        }

        // 检查邮箱是否已验证
        if (!$request->user()->hasVerifiedEmail()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Email not verified',
                    'redirect' => route('verification.notice'),
                ], 403);
            }

            // 可选的重定向路由
            $redirectTo = $redirectToRoute ?? route('verification.notice');

            return $request->expectsJson()
                ? response()->json(['message' => 'Your email address is not verified.'], 403)
                : redirect()->intended($redirectTo);
        }

        return $next($request);
    }
}
