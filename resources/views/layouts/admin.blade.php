<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '管理后台') - {{ config('blog.name') }}</title>
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2>管理后台</h2>
            <nav>
                <a href="{{ route('admin.dashboard') }}">仪表盘</a>
                <a href="{{ route('admin.posts.index') }}">文章</a>
                <a href="{{ route('admin.categories.index') }}">分类</a>
                <a href="{{ route('admin.tags.index') }}">标签</a>
                <a href="{{ route('admin.comments.index') }}">评论</a>
                <a href="{{ route('admin.users.index') }}">用户</a>
                <a href="{{ route('admin.subscriptions.index') }}">订阅</a>
                <a href="{{ route('admin.settings') }}">设置</a>
            </nav>
            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}">返回博客</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">退出登录</button>
                </form>
            </div>
        </aside>
        <main class="admin-main">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
