@extends('layouts.app')

@section('title', '验证邮箱')

@section('content')
<div class="auth-container">
    <h1>验证邮箱</h1>

    <p>我们已向您的邮箱发送了验证链接，请查收。</p>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <p>没有收到验证邮件?</p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">重新发送</button>
    </form>
</div>
@endsection
