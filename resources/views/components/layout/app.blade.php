{{-- resources/views/components/layout/app.blade.php --}}
{{-- 主布局组件 --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: $store.theme.dark }"
      x-bind:class="{ 'dark': darkMode }"
      x-cloak>
    <head>
        {{-- 字符编码 --}}
        <meta charset="UTF-8">
        
        {{-- 视口配置 --}}
        <meta name="viewport" 
              content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
        
        {{-- 页面标题 --}}
        <title>{{ $title ?? config('app.name', '博客') }}</title>
        <meta name="title" content="{{ $metaTitle ?? config('app.name', '博客') }}">
        
        {{-- 描述 --}}
        <meta name="description" 
              content="{{ Str::limit(strip_tags($metaDescription ?? '一个使用 Laravel 构建的个人博客系统'), 160) }}">
        
        {{-- 关键词 --}}
        <meta name="keywords" content="{{ $metaKeywords ?? '博客, Laravel, PHP' }}">
        
        {{-- 作者 --}}
        <meta name="author" content="{{ $author ?? config('app.author', '博主') }}">
        
        {{-- 机器人配置 --}}
        <meta name="robots" content="{{ $robots ?? 'index, follow' }}">
        
        {{-- Open Graph / Facebook --}}
        <meta property="og:type" content="{{ $ogType ?? 'website' }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:title" content="{{ $metaTitle ?? config('app.name') }}">
        <meta property="og:description" content="{{ Str::limit(strip_tags($metaDescription ?? ''), 160) }}">
        <meta property="og:image" content="{{ $ogImage ?? asset('images/og-default.png') }}">
        <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
        
        {{-- Open Graph 增强标签 --}}
        @if(isset($publishedTime))
            <meta property="article:published_time" content="{{ $publishedTime }}">
        @endif
        @if(isset($modifiedTime))
            <meta property="article:modified_time" content="{{ $modifiedTime }}">
        @endif
        @if(isset($author))
            <meta property="article:author" content="{{ $author }}">
        @endif
        @if(isset($section))
            <meta property="article:section" content="{{ $section }}">
        @endif
        @if(isset($tags) && is_array($tags))
            @foreach($tags as $tag)
                <meta property="article:tag" content="{{ $tag }}">
            @endforeach
        @endif
        
        {{-- Twitter Card --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="{{ config('blog.social.twitter') ?? '@username' }}">
        <meta name="twitter:creator" content="{{ config('blog.social.twitter') ?? '@username' }}">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="{{ $metaTitle ?? config('app.name') }}">
        <meta name="twitter:description" content="{{ Str::limit(strip_tags($metaDescription ?? ''), 160) }}">
        <meta name="twitter:image" content="{{ $ogImage ?? asset('images/og-default.png') }}">
        
        {{-- Canonical URL --}}
        <link rel="canonical" href="{{ url()->current() }}">
        
        {{-- RSS 订阅 --}}
        <link rel="alternate" type="application/rss+xml" 
              title="{{ config('app.name') }} RSS Feed" 
              href="{{ route('feed') }}">
        
        {{-- 主题颜色 --}}
        <meta name="theme-color" content="#4f46e5" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#1e1b4b" media="(prefers-color-scheme: dark)">
        
        {{-- Favicon --}}
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
        
        {{-- 预连接外部资源 --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        {{-- JSON-LD Schema.org --}}
        @if(isset($ogType) && $ogType === 'article' && isset($post))
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Article",
                "headline": "{{ $post->title }}",
                "description": "{{ Str::limit(strip_tags($post->meta_description ?? $post->excerpt ?? ''), 160) }}",
                "image": "{{ $post->featured_image ?? asset('images/og-default.png') }}",
                "author": {
                    "@type": "Person",
                    "name": "{{ $post->author->name ?? config('app.author') }}",
                    "url": "{{ route('profile.show', $post->author->username ?? $post->author->id ?? '') }}"
                },
                "publisher": {
                    "@type": "Organization",
                    "name": "{{ config('app.name') }}",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "{{ asset('images/logo.png') }}"
                    }
                },
                "datePublished": "{{ $post->published_at?->toIso8601String() ?? now()->toIso8601String() }}",
                "dateModified": "{{ $post->updated_at?->toIso8601String() ?? now()->toIso8601String() }}",
                "mainEntityOfPage": {
                    "@type": "WebPage",
                    "@id": "{{ url()->current() }}"
                }
            }
            </script>
        @else
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "WebSite",
                "name": "{{ config('app.name') }}",
                "url": "{{ url('/') }}",
                "description": "{{ Str::limit(strip_tags($metaDescription ?? '一个使用 Laravel 构建的个人博客系统'), 160) }}",
                "publisher": {
                    "@type": "Organization",
                    "name": "{{ config('app.name') }}",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "{{ asset('images/logo.png') }}"
                    }
                }
            }
            </script>
        @endif
        
        {{-- Vite 资源引用 --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- 额外头部资源 --}}
        @stack('head')
        
        {{-- 暗黑模式初始化脚本（防止闪烁） --}}
        <script>
            (function() {
                // 检查 localStorage
                const stored = localStorage.getItem('theme');
                let shouldBeDark = false;
                
                if (stored === 'dark') {
                    shouldBeDark = true;
                } else if (stored === 'light') {
                    shouldBeDark = false;
                } else {
                    // 跟随系统
                    shouldBeDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                }
                
                if (shouldBeDark) {
                    document.documentElement.classList.add('dark');
                    document.body.classList.add('dark');
                }
            })();
        </script>
    </head>
    
    <body class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased">
        {{-- Skip to content（无障碍） --}}
        <a href="#main-content" 
           class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 
                  focus:z-50 focus:px-4 focus:py-2 focus:bg-primary-600 focus:text-white 
                  focus:rounded-lg focus:shadow-lg">
            跳转到主要内容
        </a>
        
        {{-- 页面头部 --}}
        <x-layout.header />
        
        {{-- 主内容区域 --}}
        <main id="main-content" role="main" class="min-h-screen">
            {{ $slot }}
        </main>
        
        {{-- 页面底部 --}}
        <x-layout.footer />
        
        {{-- Toast 通知 --}}
        @auth
            <x-toast.container />
        @endauth
        
        {{-- 额外脚本 --}}
        @stack('scripts')
    </body>
</html>
