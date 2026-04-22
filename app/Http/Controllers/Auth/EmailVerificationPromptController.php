<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * 显示邮箱验证提示
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        // 如果已经验证，直接跳转
        if ($request->user()->hasVerifiedEmail()) {
            $redirectPath = $request->user()->role->canManage()
                ? route('admin.dashboard')
                : route('home');
                
            return redirect()->intended($redirectPath);
        }

        return view('auth.verify-email');
    }
}
