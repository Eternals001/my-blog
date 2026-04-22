@extends('layouts.app')

@php
    $seoTitle = $post->seo_title ?: $post->title;
    $seoDescription = $post->seo_description ?: $post->excerpt ?: Str::limit(strip_tags($post->html_content), 160);
    $ogImage = $post->cover_image_url ?: config('blog.seo.default_og_image', asset('images/og-image.png'));
@endphp

@section('title', $seoTitle . ' - ' . config('blog.name'))

@push('meta')
<!-- Primary Meta Tags -->
<meta name="title" content="{{ $seoTitle }}">
<meta name="description" content="{{ $seoDescription }}">
<meta name="keywords" content="{{ $post->tags->pluck('name')->implode(', ') }}">
<meta name="author" content="{{ $post->author->name ?? '' }}">
<meta name="robots" content="index, follow">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="article">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:site_name" content="{{ config('blog.name') }}">
<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

<!-- Article Specific -->
<meta property="article:published_time" content="{{ $post->published_at?->toIso8601String() }}">
<meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
<meta property="article:author" content="{{ $post->author->name ?? '' }}">
@if($post->category)
<meta property="article:section" content="{{ $post->category->name }}">
@endif
@foreach($post->tags as $tag)
<meta property="article:tag" content="{{ $tag->name }}">
@endforeach

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<!-- Canonical -->
<link rel="canonical" href="{{ url()->current() }}">
@endpush

@push('ld-json')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "{{ $post->title }}",
    "description": "{{ $seoDescription }}",
    "image": "{{ $ogImage }}",
    "datePublished": "{{ $post->published_at?->toIso8601String() }}",
    "dateModified": "{{ $post->updated_at->toIso8601String() }}",
    "author": {
        "@type": "Person",
        "name": "{{ $post->author->name ?? 'Unknown' }}",
        "url": "{{ route('blog.author', $post->author, false) }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "{{ config('blog.name') }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ config('blog.logo') ?: asset('images/logo.png') }}"
        }
    },
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ url()->current() }}"
    },
    "keywords": "{{ $post->tags->pluck('name')->implode(', ') }}"
}
</script>

<!-- Breadcrumb Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "首页",
            "item": "{{ url('/') }}"
        },
        @if($post->category)
        {
            "@type": "ListItem",
            "position": 2,
            "name": "{{ $post->category->name }}",
            "item": "{{ route('blog.category', $post->category->slug, false) }}"
        },
        @endif
        {
            "@type": "ListItem",
            "position": {{ $post->category ? 3 : 2 }},
            "name": "{{ $post->title }}",
            "item": "{{ url()->current() }}"
        }
    ]
}
</script>
@endpush

@section('content')
<article class="post max-w-4xl mx-auto px-4 py-8">
    {{-- 面包屑导航 --}}
    <nav class="breadcrumb mb-6" aria-label="面包屑导航">
        <ol class="flex items-center text-sm text-gray-500 dark:text-gray-400 space-x-2">
            <li>
                <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400">首页</a>
            </li>
            @if($post->category)
            <li class="flex items-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </li>
            <li>
                <a href="{{ route('blog.category', $post->category->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{ $post->category->name }}
                </a>
            </li>
            @endif
        </ol>
    </nav>

    {{-- 文章头部 --}}
    <header class="post-header mb-8">
        @if($post->cover_image_url)
        <div class="post-cover mb-6 rounded-xl overflow-hidden">
            <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" 
                 class="w-full h-auto max-h-[500px] object-cover">
        </div>
        @endif
        
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            {{ $post->title }}
        </h1>
        
        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-4">
            <span class="post-author flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <a href="{{ route('blog.author', $post->author) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{ $post->author->name }}
                </a>
            </span>
            
            <span class="post-date flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $post->published_at?->format('Y-m-d') }}
            </span>
            
            <span class="post-views flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                {{ $post->view_count }} 次浏览
            </span>
        </div>
        
        {{-- 标签 --}}
        @if($post->tags->isNotEmpty())
        <div class="post-tags flex flex-wrap gap-2">
            @foreach($post->tags as $tag)
            <a href="{{ route('blog.tag', $tag->slug) }}" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                {{ $tag->name }}
            </a>
            @endforeach
        </div>
        @endif
    </header>

    {{-- 文章正文 --}}
    <div class="post-body prose prose-lg dark:prose-invert max-w-none mb-12">
        {!! $post->html_content !!}
    </div>

    {{-- 文章底部 --}}
    <footer class="post-footer border-t border-gray-200 dark:border-gray-700 pt-8">
        {{-- 分类信息 --}}
        @if($post->category)
        <div class="post-category-info mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <span class="text-gray-600 dark:text-gray-400">分类：</span>
            <a href="{{ route('blog.category', $post->category->slug) }}" 
               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                {{ $post->category->name }}
            </a>
        </div>
        @endif
        
        {{-- 分享按钮 --}}
        <div class="share-buttons flex items-center gap-4 mb-8">
            <span class="text-gray-600 dark:text-gray-400">分享到：</span>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" 
               target="_blank" rel="noopener"
               class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
               target="_blank" rel="noopener"
               class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
        </div>
    </footer>
</article>

{{-- 评论区 --}}
<section class="comments-section max-w-4xl mx-auto px-4 pb-12">
    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            评论 ({{ $post->approvedComments->count() }})
        </h2>

        {{-- 发表评论表单 --}}
        @auth
        <form method="POST" action="{{ route('blog.comments.store', $post) }}" class="comment-form mb-8">
            @csrf
            <div class="mb-4">
                <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    发表你的看法
                </label>
                <textarea 
                    id="comment"
                    name="content" 
                    rows="4" 
                    placeholder="写下你的评论..."
                    required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg 
                           focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 
                           focus:border-transparent dark:bg-gray-700 dark:text-white
                           transition-colors"
                >{{ old('content') }}</textarea>
                @error('content')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                发表评论
            </button>
        </form>
        @else
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-8 text-center">
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">登录</a>
                后发表评论
            </p>
        </div>
        @endauth

        {{-- 评论列表 --}}
        <div class="comments-list space-y-6">
            @forelse($post->approvedComments->whereNull('parent_id') as $comment)
            <div class="comment bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                <div class="flex items-start space-x-4">
                    <div class="comment-avatar flex-shrink-0">
                        <img src="{{ $comment->avatar_url }}" alt="{{ $comment->display_name }}" 
                             class="w-10 h-10 rounded-full">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-2">
                            <span class="comment-author font-medium text-gray-900 dark:text-white">
                                {{ $comment->display_name }}
                            </span>
                            <span class="comment-date text-sm text-gray-500 dark:text-gray-400">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                        
                        @if(config('blog.comments.allow_anonymous', true))
                        @auth
                        <button type="button" 
                                class="reply-btn mt-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400"
                                onclick="document.getElementById('reply-to-{{ $comment->id }}').classList.toggle('hidden')">
                            回复
                        </button>
                        @endauth
                        
                        {{-- 回复表单 --}}
                        @auth
                        <form id="reply-to-{{ $comment->id }}" method="POST" 
                              action="{{ route('blog.comments.store', $post) }}" 
                              class="mt-3 hidden">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <textarea name="content" rows="2" placeholder="写下你的回复..."
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                             dark:bg-gray-700 dark:text-white mb-2"></textarea>
                            <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">发送回复</button>
                        </form>
                        @endauth
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 dark:text-gray-400 py-8">暂无评论，成为第一个评论的人吧！</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
