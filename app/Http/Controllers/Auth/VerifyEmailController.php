<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Auth\Events\EmailVerified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * 验证邮箱
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('home') . '?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new EmailVerified($user));
        }

        return redirect()->route('home')->with('status', '邮箱验证成功！');
    }
}
