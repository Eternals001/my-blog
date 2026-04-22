<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * 显示登录页面
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * 处理登录请求
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => '邮箱或密码错误',
                ]);
        }

        $request->session()->regenerate();

        // 获取重定向路径
        $redirectPath = $this->getRedirectPath(Auth::user());

        return redirect()->intended($redirectPath);
    }

    /**
     * 处理登出请求
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * 根据用户角色获取重定向路径
     */
    protected function getRedirectPath(User $user): string
    {
        if ($user->role->canManage()) {
            return route('admin.dashboard');
        }

        return route('home');
    }
}
