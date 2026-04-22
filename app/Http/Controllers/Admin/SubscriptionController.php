<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * 订阅列表
     */
    public function index(Request $request)
    {
        $query = Subscription::query();

        if ($status = $request->get('status')) {
            if ($status === 'active') {
                $query->active();
            } elseif ($status === 'inactive') {
                $query->inactive();
            }
        }

        if ($search = $request->get('q')) {
            $query->where('email', 'like', "%{$search}%");
        }

        $subscriptions = $query->latest()->paginate(20);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * 删除订阅
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return back()->with('success', '订阅已删除');
    }
}
