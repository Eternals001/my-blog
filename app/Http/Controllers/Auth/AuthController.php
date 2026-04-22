<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * 显示登录页面
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * 处理登录请求
     */
    public function login(LoginRequest $request): RedirectResponse
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
     * 显示注册页面
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * 处理注册请求
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = $this->authService->register($validated);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice')
            ->with('success', '注册成功！请验证您的邮箱。');
    }

    /**
     * 显示忘记密码页面
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * 发送密码重置链接
     */
    public function sendResetLink(ForgotPasswordRequest $request): RedirectResponse
    {
        try {
            $this->authService->sendResetLink($request->validated('email'));

            return back()->with('status', '密码重置链接已发送到您的邮箱');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => '发送失败，请稍后重试']);
        }
    }

    /**
     * 显示重置密码页面
     */
    public function showResetPassword(string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request('email'),
        ]);
    }

    /**
     * 处理密码重置
     */
    public function resetPassword(ResetPasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $status = Password::reset(
            [
                'email' => $validated['email'],
                'password' => $validated['password'],
                'password_confirmation' => $validated['password_confirmation'],
                'token' => $validated['token'],
            ],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password, ['rounds' => 12]),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', '密码已重置，请使用新密码登录');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }

    /**
     * 处理登出请求
     */
    public function logout(Request $request): RedirectResponse
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
