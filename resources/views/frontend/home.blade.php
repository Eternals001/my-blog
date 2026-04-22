{{-- resources/views/frontend/home.blade.php --}}
{{-- 前台首页 --}}

<x-layout.app 
    title="首页 - {{ config('app.name') }}"
    :metaTitle="config('app.name') . ' - 探索技术的无限可能'"
    :metaDescription="'欢迎访问' . config('app.name') . '，分享编程心得、前沿技术和生活感悟。包含' . ($stats['posts'] ?? 0) . '篇文章，涵盖多个技术领域的深度内容。'"
    :metaKeywords="config('blog.seo.keywords', '博客, Laravel, PHP, 技术分享, 编程, Web开发')"
    :ogImage="asset('images/og-home.png')"
    ogType="website"
    :author="config('app.author', '博主')">

    {{-- Hero 区域 --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-accent-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%239C92AC%22%20fill-opacity%3D%220.05%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E')] opacity-50"></div>
        
        <div class="relative container-blog py-16 lg:py-24">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl lg:text-5xl xl:text-6xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                    探索<span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-accent-600">技术</span>的无限可能
                </h1>
                <p class="text-lg lg:text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto">
                    在这里分享编程心得、前沿技术和生活感悟，与志同道合的朋友一起成长。
                </p>
                <div class="flex items-center justify-center gap-4 flex-wrap">
                    <a href="{{ route('posts.index') }}" class="btn-primary px-8 py-3 text-base shadow-lg shadow-primary-500/25">
                        浏览文章
                    </a>
                    <a href="{{ route('about') }}" class="btn-outline px-8 py-3 text-base">
                        关于我
                    </a>
                </div>
            </div>
            
            {{-- 统计数据 --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-8 mt-16 max-w-4xl mx-auto">
                @foreach([
                    ['value' => $stats['posts'] ?? 0, 'label' => '篇文章', 'icon' => 'article'],
                    ['value' => $stats['categories'] ?? 0, 'label' => '个分类', 'icon' => 'folder'],
                    ['value' => $stats['tags'] ?? 0, 'label' => '个标签', 'icon' => 'tag'],
                    ['value' => $stats['visits'] ?? 0, 'label' => '次访问', 'icon' => 'eye'],
                ] as $stat)
                    <div class="text-center group">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white dark:bg-gray-800 shadow-lg shadow-gray-200/50 dark:shadow-none mb-3 group-hover:scale-110 transition-transform duration-300">
                            @switch($stat['icon'])
                                @case('article')
                                    <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                    @break
                                @case('folder')
                                    <svg class="w-7 h-7 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                    @break
                                @case('tag')
                                    <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    @break
                                @case('eye')
                                    <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    @break
                            @endswitch
                        </div>
                        <div class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stat['value']) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $stat['label'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    {{-- 精选文章 --}}
    @if($featuredPosts->isNotEmpty())
        <section class="py-12 lg:py-16 bg-white dark:bg-gray-900">
            <div class="container-blog">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 bg-gradient-to-b from-primary-500 to-accent-500 rounded-full"></div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                            精选文章
                        </h2>
                    </div>
                    <a href="{{ route('posts.index') }}" 
                       class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors group">
                        查看全部
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($featuredPosts->take(3) as $post)
                        <article class="group card overflow-hidden hover:shadow-xl transition-all duration-300">
                            @if($post->featured_image)
                                <a href="{{ route('posts.show', $post->slug) }}" class="block">
                                    <div class="relative aspect-[16/9] overflow-hidden">
                                        <img src="{{ $post->featured_image }}" 
                                             alt="{{ $post->title }}"
                                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                        @if($post->is_pinned)
                                            <span class="absolute top-4 left-4 px-3 py-1 bg-primary-600 text-white text-xs font-medium rounded-full shadow-lg">
                                                置顶
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            @endif
                            
                            <div class="p-6">
                                @if($post->category)
                                    <a href="{{ route('categories.show', $post->category->slug) }}" 
                                       class="badge-primary mb-3 inline-block hover:bg-primary-200 dark:hover:bg-primary-800">
                                        {{ $post->category->name }}
                                    </a>
                                @endif
                                
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                    <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                
                                <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2 mb-4">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 100) }}
                                </p>
                                
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $post->author->avatar ?? asset('images/default-avatar.png') }}" 
                                             alt="{{ $post->author->name }}"
                                             class="w-8 h-8 rounded-full object-cover">
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">
                                            {{ $post->author->name }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ number_format($post->view_count) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    
    {{-- 文章列表 + 侧边栏 --}}
    <section class="py-12 lg:py-16 bg-gray-50 dark:bg-gray-800/50">
        <div class="container-blog">
            <div class="flex flex-col lg:flex-row gap-8">
                {{-- 主内容区 --}}
                <div class="lg:w-2/3">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-8 bg-gradient-to-b from-primary-500 to-accent-500 rounded-full"></div>
                            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                                最新文章
                            </h2>
                        </div>
                        <a href="{{ route('posts.index') }}" 
                           class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors">
                            全部文章 →
                        </a>
                    </div>
                    
                    <div class="space-y-6">
                        @forelse($posts as $post)
                            <article class="card overflow-hidden hover:shadow-lg transition-all duration-300 group">
                                <div class="flex flex-col sm:flex-row">
                                    @if($post->featured_image)
                                        <a href="{{ route('posts.show', $post->slug) }}" class="sm:w-48 h-48 sm:h-auto flex-shrink-0">
                                            <div class="w-full h-full aspect-video sm:aspect-auto sm:h-full overflow-hidden">
                                                <img src="{{ $post->featured_image }}" 
                                                     alt="{{ $post->title }}"
                                                     class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                            </div>
                                        </a>
                                    @endif
                                    
                                    <div class="flex-1 p-6">
                                        <div class="flex items-center gap-2 mb-3">
                                            @if($post->category)
                                                <a href="{{ route('categories.show', $post->category->slug) }}" 
                                                   class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                                                    {{ $post->category->name }}
                                                </a>
                                                <span class="text-gray-300 dark:text-gray-600">·</span>
                                            @endif
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $post->published_at?->diffForHumans() }}
                                            </span>
                                        </div>
                                        
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                            <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                                        </h3>
                                        
                                        <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2 mb-4">
                                            {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                                        </p>
                                        
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <img src="{{ $post->author->avatar ?? asset('images/default-avatar.png') }}" 
                                                     alt="{{ $post->author->name }}"
                                                     class="w-7 h-7 rounded-full object-cover">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $post->author->name }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    {{ number_format($post->view_count) }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                    </svg>
                                                    {{ $post->comments_count ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        {{-- 标签 --}}
                                        @if($post->tags->isNotEmpty())
                                            <div class="flex flex-wrap gap-2 mt-4">
                                                @foreach($post->tags->take(3) as $tag)
                                                    <a href="{{ route('tags.show', $tag->slug) }}" 
                                                       class="px-2.5 py-1 text-xs rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-primary-100 dark:hover:bg-primary-900/30 hover:text-primary-700 dark:hover:text-primary-400 transition-colors">
                                                        {{ $tag->name }}
                                                    </a>
                                                @endforeach
                                                @if($post->tags->count() > 3)
                                                    <span class="px-2.5 py-1 text-xs text-gray-500 dark:text-gray-400">
                                                        +{{ $post->tags->count() - 3 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="card p-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">暂无文章</h3>
                                <p class="text-gray-500 dark:text-gray-400">敬请期待更多精彩内容</p>
                            </div>
                        @endforelse
                    </div>
                    
                    {{-- 分页 --}}
                    @if($posts->hasPages())
                        <div class="mt-8">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>
                
                {{-- 侧边栏 --}}
                <aside class="lg:w-1/3 space-y-6">
                    {{-- 标签云 --}}
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            标签云
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse($popularTags ?? [] as $tag)
                                <a href="{{ route('tags.show', $tag->slug) }}" 
                                   class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200
                                          @if(($tag->posts_count ?? 0) > 10)
                                              bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 hover:bg-primary-200 dark:hover:bg-primary-900/50
                                          @elseif(($tag->posts_count ?? 0) > 5)
                                              bg-accent-100 text-accent-700 dark:bg-accent-900/30 dark:text-accent-400 hover:bg-accent-200 dark:hover:bg-accent-900/50
                                          @else
                                              bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600
                                          @endif">
                                    {{ $tag->name }}
                                    <span class="opacity-60">({{ $tag->posts_count ?? 0 }})</span>
                                </a>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">暂无标签</p>
                            @endforelse
                        </div>
                    </div>
                    
                    {{-- 热门文章 --}}
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                            </svg>
                            热门文章
                        </h3>
                        <div class="space-y-4">
                            @forelse($popularPosts ?? [] as $index => $post)
                                <a href="{{ route('posts.show', $post->slug) }}" class="flex items-start gap-3 group">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-gradient-to-br 
                                                 @if($index === 0) from-red-500 to-orange-500
                                                 @elseif($index === 1) from-yellow-500 to-amber-500
                                                 @else from-gray-400 to-gray-500
                                                 @endif
                                                 flex items-center justify-center text-white font-bold text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                            {{ $post->title }}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-2">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ number_format($post->view_count) }}
                                            </span>
                                        </p>
                                    </div>
                                </a>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">暂无热门文章</p>
                            @endforelse
                        </div>
                    </div>
                    
                    {{-- 最新评论 --}}
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            最新评论
                        </h3>
                        <div class="space-y-4">
                            @forelse($recentComments ?? [] as $comment)
                                <div class="flex items-start gap-3">
                                    <img src="{{ $comment->avatar_url ?? asset('images/default-avatar.png') }}" 
                                         alt="{{ $comment->display_name }}"
                                         class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $comment->display_name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mt-0.5">
                                            {{ Str::limit($comment->content, 60) }}
                                        </p>
                                        @if($comment->post)
                                            <a href="{{ route('posts.show', $comment->post->slug) }}" 
                                               class="text-xs text-primary-600 dark:text-primary-400 hover:underline mt-1 inline-block">
                                                {{ Str::limit($comment->post->title, 30) }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">暂无评论</p>
                            @endforelse
                        </div>
                    </div>
                    
                    {{-- 订阅区域 --}}
                    <div class="card p-6 bg-gradient-to-br from-primary-600 to-accent-600 text-white">
                        <h3 class="text-lg font-semibold mb-2">订阅更新</h3>
                        <p class="text-sm text-white/80 mb-4">
                            订阅我的博客，第一时间获取最新文章推送
                        </p>
                        <form action="{{ route('subscribe') }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="email" 
                                   name="email" 
                                   required
                                   placeholder="输入您的邮箱"
                                   class="w-full px-4 py-2.5 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 text-white placeholder-white/60 focus:outline-none focus:bg-white/30 transition-colors">
                            <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-white text-primary-600 font-medium hover:bg-white/90 transition-colors">
                                立即订阅
                            </button>
                        </form>
                    </div>
                </aside>
            </div>
        </div>
    </section>

</x-layout.app>
