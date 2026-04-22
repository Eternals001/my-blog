<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * 显示忘记密码页面
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * 发送密码重置链接
     */
    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $status = Password::sendResetLink($validated);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', '密码重置链接已发送到您的邮箱');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
