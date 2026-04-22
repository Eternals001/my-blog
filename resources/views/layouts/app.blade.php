{{-- resources/views/layouts/app.blade.php --}}
{{-- 主布局文件 --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: $store.theme.dark }"
      x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $metaDescription ?? config('app.description', '一个现代优雅的博客') }}">
    <meta name="keywords" content="{{ $metaKeywords ?? '' }}">
    
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $metaTitle ?? $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $metaDescription ?? '' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/og-image.png') }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ request()->url() }}">
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle ?? $title ?? config('app.name') }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? '' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/og-image.png') }}">
    
    @stack('meta')
    <title>{{ $title ?? config('app.name') }}</title>
    
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="canonical" href="{{ request()->url() }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- 暗黑模式初始化脚本 --}}
    <script>
        (function() {
            const stored = localStorage.getItem('theme');
            let shouldBeDark = stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (shouldBeDark) {
                document.documentElement.classList.add('dark');
                if (typeof window.$store !== 'undefined') {
                    window.$store.theme.dark = true;
                }
            }
        })();
    </script>
    
    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col antialiased">
    {{-- 顶部导航 --}}
    @if($showHeader ?? true)
        <x-layout.header />
    @endif
    
    {{-- 主内容区域 --}}
    <main class="flex-1">
        @yield('content')
    </main>
    
    {{-- 底部 --}}
    @if($showFooter ?? true)
        <x-layout.footer />
    @endif
    
    {{-- Toast 通知 --}}
    <x-toast.container />
    
    {{-- 搜索弹窗 --}}
    <x-layout.search-modal />
    
    @stack('scripts')
</body>
</html>