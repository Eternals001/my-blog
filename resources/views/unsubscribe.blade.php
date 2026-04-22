@extends('layouts.app')

@section('title', '取消订阅')

@section('content')
<section class="unsubscribe-section">
    <h1>取消订阅</h1>
    <p>确定要取消订阅吗？</p>
    <p>邮箱: {{ $subscription->email }}</p>

    <form method="POST" action="{{ route('subscribe.unsubscribe') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $subscription->email }}">
        <input type="hidden" name="token" value="{{ $subscription->token }}">
        <button type="submit" class="btn btn-danger">确认取消订阅</button>
        <a href="{{ route('home') }}" class="btn btn-secondary">返回首页</a>
    </form>
</section>
@endsection
