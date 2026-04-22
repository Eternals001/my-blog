<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * 如果用户已登录，重定向到首页或仪表盘
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // 根据用户角色重定向到不同页面
                $user = Auth::guard($guard)->user();
                
                if ($user->role->canManage()) {
                    return redirect()->route('admin.dashboard');
                }
                
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
