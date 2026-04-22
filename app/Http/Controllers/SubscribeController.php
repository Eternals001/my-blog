<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirm;
use App\Mail\SubscriptionWelcome;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscribeController extends Controller
{
    /**
     * 显示订阅表单
     */
    public function show()
    {
        return view('subscribe');
    }

    /**
     * 处理订阅
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => '请输入邮箱地址',
            'email.email' => '请输入有效的邮箱地址',
            'email.max' => '邮箱地址过长',
        ]);

        // 检查邮箱是否已被订阅
        $existingSubscription = Subscription::where('email', $request->email)->first();

        if ($existingSubscription && $existingSubscription->is_active) {
            return back()->with('info', '您已经订阅了博客');
        }

        // 如果存在非活跃订阅，重新激活
        if ($existingSubscription) {
            $existingSubscription->regenerateToken();
            $existingSubscription->is_active = false;
            $existingSubscription->unsubscribed_at = null;
            $existingSubscription->save();
        } else {
            $existingSubscription = Subscription::create([
                'email' => $request->email,
            ]);
        }

        // 发送确认邮件
        if (config('blog.subscription.confirmation_required', true)) {
            Mail::to($request->email)->queue(new SubscriptionConfirm($existingSubscription));
            return back()->with('success', '请查收确认邮件以完成订阅');
        }

        // 直接订阅成功
        $existingSubscription->confirm();
        Mail::to($request->email)->queue(new SubscriptionWelcome($existingSubscription));

        return back()->with('success', '订阅成功！');
    }

    /**
     * 确认订阅
     */
    public function confirm(string $token)
    {
        $subscription = Subscription::where('token', $token)->firstOrFail();

        if ($subscription->is_active) {
            return redirect()->route('home')->with('info', '已经订阅过了');
        }

        $subscription->confirm();

        // 发送欢迎邮件
        Mail::to($subscription->email)->queue(new SubscriptionWelcome($subscription));

        return redirect()->route('home')->with('success', '订阅成功！感谢您的支持');
    }

    /**
     * 显示取消订阅确认页
     */
    public function unsubscribeShow(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
        ], [
            'email.required' => '请输入邮箱地址',
            'email.email' => '请输入有效的邮箱地址',
            'token.required' => '缺少验证令牌',
        ]);

        $subscription = Subscription::where('email', $request->email)
            ->where('token', $request->token)
            ->firstOrFail();

        return view('emails.subscription.unsubscribe', compact('subscription'));
    }

    /**
     * 执行取消订阅
     */
    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
        ], [
            'email.required' => '请输入邮箱地址',
            'email.email' => '请输入有效的邮箱地址',
            'token.required' => '缺少验证令牌',
        ]);

        $subscription = Subscription::where('email', $request->email)
            ->where('token', $request->token)
            ->firstOrFail();

        $subscription->unsubscribe();

        return redirect()->route('home')->with('success', '已取消订阅，期待您再次归来');
    }
}
