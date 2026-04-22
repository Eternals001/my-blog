<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('blog.name') }}</title>
    <meta name="description" content="{{ config('blog.description') }}">
</head>
<body>
    <div id="app">
        <!-- 博客内容将在此渲染 -->
        <header>
            <h1>{{ config('blog.name') }}</h1>
            <nav>
                <a href="{{ route('home') }}">首页</a>
                <a href="{{ route('blog.posts.index') }}">文章</a>
                <a href="{{ route('subscribe.show') }}">订阅</a>
            </nav>
        </header>

        <main>
            <h2>欢迎来到博客</h2>
            <p>这是一个使用 Laravel + Livewire + FluxUI 构建的现代化博客系统。</p>
        </main>

        <footer>
            <p>&copy; {{ date('Y') }} {{ config('blog.name') }}. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
