<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * 显示用户资料页（公开）
     */
    public function profile(User $user): View
    {
        $posts = $user->publishedPosts()
            ->orderBy('published_at', 'desc')
            ->paginate(config('blog.per_page', 10));

        return view('user.profile', compact('user', 'posts'));
    }

    /**
     * 显示用户设置页（需登录）
     */
    public function settings(): View
    {
        return view('user.settings');
    }

    /**
     * 显示编辑资料页
     */
    public function edit(): View
    {
        return view('user.edit', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * 更新个人资料
     */
    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // 处理头像上传
        if ($request->hasFile('avatar')) {
            // 删除旧头像
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // 保存新头像
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user->update($validated);

        return redirect()->route('user.settings')
            ->with('success', '资料已更新');
    }

    /**
     * 更新密码
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->forceFill([
            'password' => Hash::make($validated['password'], ['rounds' => 12]),
            'remember_token' => null,
        ])->save();

        return redirect()->route('user.settings')
            ->with('success', '密码已更新');
    }

    /**
     * 发送邮箱验证邮件
     */
    public function sendVerificationEmail(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('user.settings')
                ->with('info', '邮箱已验证');
        }

        $this->authService->sendVerificationEmail($user);

        return redirect()->route('user.settings')
            ->with('status', '验证邮件已发送');
    }

    /**
     * 删除账户（本人）
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password:web'],
            'confirm' => ['required', 'string', 'equals:DELETE'],
        ]);

        $user = $request->user();

        // 删除头像
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // 删除用户
        auth()->logout();
        $user->delete();

        return redirect()->route('home')
            ->with('status', '账户已删除');
    }
}
