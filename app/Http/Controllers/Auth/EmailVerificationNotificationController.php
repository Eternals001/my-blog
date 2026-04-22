<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * 发送验证邮件
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        $this->authService->sendVerificationEmail($request->user());

        return back()->with('status', '验证链接已发送');
    }
}
