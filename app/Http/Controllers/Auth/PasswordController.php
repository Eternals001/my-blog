<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * 显示更新密码页面
     */
    public function edit(): View
    {
        return view('auth.change-password');
    }

    /**
     * 更新密码
     */
    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->forceFill([
            'password' => Hash::make($validated['password'], ['rounds' => 12]),
        ])->save();

        return redirect()->back()->with('status', '密码已更新');
    }
}
