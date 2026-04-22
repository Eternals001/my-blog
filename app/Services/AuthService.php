<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * 用户注册
     *
     * @param array $data 注册数据
     * @return User
     */
    public function register(array $data): User
    {
        // 生成邮箱验证 token - 使用哈希存储
        $rawToken = Str::random(64);
        $hashedToken = hash('sha256', $rawToken);

        // 创建用户
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'], ['rounds' => 12]),
            'role' => UserRole::SUBSCRIBER,
            'email_token' => $hashedToken,
        ]);

        // 发送验证邮件（携带原始 token）
        $this->sendVerificationEmail($user, $rawToken);

        return $user;
    }

    /**
     * 用户登录
     *
     * @param Request $request
     * @param array $credentials
     * @param bool $remember
     * @return User|null
     */
    public function login(Request $request, array $credentials, bool $remember = false): ?User
    {
        if (!auth()->attempt($credentials, $remember)) {
            return null;
        }

        $request->session()->regenerate();

        return auth()->user();
    }

    /**
     * 用户登出
     *
     * @param Request $request
     * @return bool
     */
    public function logout(Request $request): bool
    {
        auth()->guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return true;
    }

    /**
     * 发送密码重置链接
     *
     * @param string $email
     * @return string
     */
    public function sendResetLink(string $email): string
    {
        $status = Password::sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new \Exception(__($status));
        }

        return $status;
    }

    /**
     * 重置密码
     *
     * @param string $email
     * @param string $token
     * @param string $password
     * @return bool
     */
    public function resetPassword(string $email, string $token, string $password): bool
    {
        $status = Password::reset(
            [
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
                'token' => $token,
            ],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password, ['rounds' => 12]),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET;
    }

    /**
     * 验证密码重置 Token
     *
     * @param string $email
     * @param string $token
     * @return bool
     */
    public function verifyResetToken(string $email, string $token): bool
    {
        $record = \DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record) {
            return false;
        }

        // 检查 token 是否过期（默认 60 分钟过期）
        $expiryMinutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60);
        if (now()->gt($record->created_at->addMinutes($expiryMinutes))) {
            // 清理过期 token
            \DB::table('password_reset_tokens')->where('email', $email)->delete();
            return false;
        }

        return Hash::check($token, $record->token);
    }

    /**
     * 发送验证邮件
     *
     * @param User $user
     * @param string|null $rawToken 原始 token（注册时生成）
     * @return void
     */
    public function sendVerificationEmail(User $user, ?string $rawToken = null): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        // 如果没有传入原始 token，则重新生成
        if (!$rawToken) {
            $rawToken = $user->generateEmailToken();
        }

        $user->notify(new VerifyEmailNotification($rawToken));
    }

    /**
     * 验证邮箱
     *
     * @param User $user
     * @return bool
     */
    public function verifyEmail(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'email_token' => null,
        ])->save();

        return true;
    }

    /**
     * 更新用户密码
     *
     * @param User $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->forceFill([
            'password' => Hash::make($newPassword, ['rounds' => 12]),
            'remember_token' => Str::random(60),
        ])->save();

        return true;
    }

    /**
     * 检查邮箱是否已被注册
     *
     * @param string $email
     * @return bool
     */
    public function isEmailTaken(string $email): bool
    {
        return User::where('email', $email)->exists();
    }

    /**
     * 获取用户最后登录时间
     *
     * @param User $user
     * @return \Carbon\Carbon|null
     */
    public function getLastLoginTime(User $user): ?\Carbon\Carbon
    {
        return $user->last_login_at;
    }

    /**
     * 更新用户最后登录时间
     *
     * @param User $user
     * @return void
     */
    public function updateLastLoginTime(User $user): void
    {
        $user->forceFill([
            'last_login_at' => now(),
        ])->save();
    }
}
