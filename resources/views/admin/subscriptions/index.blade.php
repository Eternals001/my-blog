@extends('layouts.admin')

@section('title', '订阅管理')

@section('content')
<div class="page-header">
    <h1>订阅管理</h1>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>邮箱</th>
            <th>状态</th>
            <th>订阅时间</th>
            <th>取消时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @forelse($subscriptions as $subscription)
        <tr>
            <td>{{ $subscription->id }}</td>
            <td>{{ $subscription->email }}</td>
            <td>
                @if($subscription->is_active)
                    <span class="badge badge-green">活跃</span>
                @else
                    <span class="badge badge-gray">已取消</span>
                @endif
            </td>
            <td>{{ $subscription->subscribed_at?->format('Y-m-d H:i') ?? '-' }}</td>
            <td>{{ $subscription->unsubscribed_at?->format('Y-m-d H:i') ?? '-' }}</td>
            <td>
                <form method="POST" action="{{ route('admin.subscriptions.destroy', $subscription) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('确定删除？')">删除</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">暂无订阅</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $subscriptions->links() }}
@endsection
