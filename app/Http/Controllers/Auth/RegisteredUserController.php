<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * 显示注册页面
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * 处理注册请求
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // 使用 AuthService 注册用户（内部处理 token 哈希存储）
        $user = $this->authService->register($validated);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice')
            ->with('success', '注册成功！请验证您的邮箱。');
    }
}
