{{-- resources/views/components/post-card.blade.php --}}
{{-- 文章卡片组件 - 性能优化版 --}}

@props([
    'post',
    'variant' => 'default', // default | compact | featured
    'showImage' => true,
    'showExcerpt' => true,
    'showCategory' => true,
    'showAuthor' => true,
    'showStats' => true,
])

@php
    $postUrl = route('posts.show', $post->slug ?? $post->id);
    $categoryUrl = $post->category ? route('categories.show', $post->category->slug) : '#';
    $authorUrl = $post->author ? route('profile.show', $post->author->username ?? $post->author->id) : '#';
@endphp

@if($variant === 'featured')
    {{-- 精选文章卡片 --}}
    <article class="card group overflow-hidden">
        <a href="{{ $postUrl }}" class="block">
            @if($showImage && $post->featured_image)
                <div class="relative aspect-[16/9] overflow-hidden">
                    <img src="{{ $post->featured_image }}" 
                         alt="{{ $post->title }}"
                         loading="lazy"
                         decoding="async"
                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                         onerror="this.src='{{ asset('images/placeholder.png') }}'">
                    @if($post->is_pinned)
                        <span class="absolute top-4 left-4 px-3 py-1 bg-primary-600 text-white text-xs font-medium rounded-full">
                            置顶
                        </span>
                    @endif
                </div>
            @endif
        </a>
        
        <div class="p-6">
            {{-- 分类 --}}
            @if($showCategory && $post->category)
                <a href="{{ $categoryUrl }}" 
                   class="badge-primary mb-3 inline-block hover:bg-primary-200 dark:hover:bg-primary-800">
                    {{ $post->category->name }}
                </a>
            @endif
            
            {{-- 标题 --}}
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                <a href="{{ $postUrl }}">{{ $post->title }}</a>
            </h2>
            
            {{-- 摘要 --}}
            @if($showExcerpt)
                <p class="text-gray-600 dark:text-gray-400 line-clamp-3 mb-4">
                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 150) }}
                </p>
            @endif
            
            {{-- 元信息 --}}
            <div class="flex items-center justify-between">
                @if($showAuthor)
                    <div class="flex items-center gap-2">
                        <img src="{{ $post->author->avatar ?? asset('images/default-avatar.png') }}" 
                             alt="{{ $post->author->name ?? '作者' }}"
                             loading="lazy"
                             class="w-8 h-8 rounded-full object-cover">
                        <div>
                            <a href="{{ $authorUrl }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400">
                                {{ $post->author->name ?? '匿名' }}
                            </a>
                            <p class="text-xs text-gray-500">
                                {{ $post->published_at?->diffForHumans() ?? $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endif
                
                @if($showStats)
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ number_format($post->views_count ?? 0) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ $post->comments_count ?? 0 }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </article>
    
@elseif($variant === 'compact')
    {{-- 紧凑型卡片 --}}
    <article class="flex gap-4 group">
        @if($showImage && $post->featured_image)
            <a href="{{ $postUrl }}" class="flex-shrink-0">
                <img src="{{ $post->featured_image }}" 
                     alt="{{ $post->title }}"
                     loading="lazy"
                     decoding="async"
                     class="w-24 h-20 rounded-lg object-cover"
                     onerror="this.src='{{ asset('images/placeholder.png') }}'">
            </a>
        @endif
        
        <div class="flex-1 min-w-0">
            @if($showCategory && $post->category)
                <a href="{{ $categoryUrl }}" class="badge-primary text-xs">
                    {{ $post->category->name }}
                </a>
            @endif
            
            <h3 class="font-medium text-gray-900 dark:text-white mt-1 line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                <a href="{{ $postUrl }}">{{ $post->title }}</a>
            </h3>
            
            <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                <span>{{ $post->published_at?->diffForHumans() ?? $post->created_at->diffForHumans() }}</span>
                <span>·</span>
                <span>{{ number_format($post->views_count ?? 0) }} 阅读</span>
            </div>
        </div>
    </article>
    
@else
    {{-- 默认文章卡片 --}}
    <article class="card overflow-hidden group">
        @if($showImage && $post->featured_image)
            <a href="{{ $postUrl }}" class="block">
                <div class="relative aspect-[2/1] overflow-hidden">
                    <img src="{{ $post->featured_image }}" 
                         alt="{{ $post->title }}"
                         loading="lazy"
                         decoding="async"
                         class="post-card-image w-full h-full object-cover"
                         onerror="this.src='{{ asset('images/placeholder.png') }}'">
                    @if($post->is_pinned)
                        <span class="absolute top-3 left-3 px-2 py-0.5 bg-primary-600 text-white text-xs font-medium rounded-full">
                            置顶
                        </span>
                    @endif
                </div>
            </a>
        @endif
        
        <div class="p-5">
            {{-- 分类标签 --}}
            @if($showCategory && $post->category)
                <a href="{{ $categoryUrl }}" 
                   class="badge-primary mb-3 inline-block hover:bg-primary-200 dark:hover:bg-primary-800">
                    {{ $post->category->name }}
                </a>
            @endif
            
            {{-- 标题 --}}
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                <a href="{{ $postUrl }}">{{ $post->title }}</a>
            </h3>
            
            {{-- 摘要 --}}
            @if($showExcerpt)
                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-4">
                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 100) }}
                </p>
            @endif
            
            {{-- 底部信息 --}}
            <div class="flex items-center justify-between">
                @if($showAuthor)
                    <div class="flex items-center gap-2">
                        <img src="{{ $post->author->avatar ?? asset('images/default-avatar.png') }}" 
                             alt=""
                             loading="lazy"
                             class="avatar-sm">
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $post->author->name ?? '匿名' }}
                        </span>
                    </div>
                @endif
                
                @if($showStats)
                    <div class="flex items-center gap-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $post->published_at?->diffForHumans() ?? $post->created_at->diffForHumans() }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </article>
@endif
