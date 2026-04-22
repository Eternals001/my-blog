<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * 显示确认密码页面
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * 确认密码
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'password' => '密码不正确',
            ]);
        }

        $request->session()->passwordConfirmed();

        return redirect()->intended(route('home'));
    }
}
