{{-- resources/views/frontend/posts/index.blade.php --}}
{{-- 文章列表页 --}}

<x-layout.app title="文章列表" description="浏览所有文章">

    {{-- 页面头部 --}}
    <section class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
        <div class="container-blog py-12">
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                文章列表
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                共 {{ $posts->total() ?? 0 }} 篇文章
            </p>
        </div>
    </section>
    
    {{-- 筛选和搜索 --}}
    <section class="py-6 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800 sticky top-0 z-10 backdrop-blur-sm bg-white/80 dark:bg-gray-900/80">
        <div class="container-blog">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                {{-- 分类筛选 --}}
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('posts.index') }}" 
                       class="px-4 py-2 rounded-xl text-sm font-medium transition-colors
                              {{ !request('category') ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        全部
                    </a>
                    @foreach($categories ?? [] as $cat)
                        <a href="{{ route('posts.index', ['category' => $cat->slug]) }}" 
                           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors whitespace-nowrap
                                  {{ request('category') === $cat->slug ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
                
                {{-- 搜索 --}}
                <form action="{{ route('posts.index') }}" method="GET" class="flex items-center gap-3 w-full lg:w-auto">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="relative flex-1 lg:w-64">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="搜索文章..."
                               class="input pl-10 w-full">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="btn-primary">
                        搜索
                    </button>
                </form>
            </div>
        </div>
    </section>
    
    {{-- 文章列表 --}}
    <section class="py-12 lg:py-16 bg-white dark:bg-gray-900">
        <div class="container-blog">
            @if(request('search'))
                <div class="mb-6 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-xl border border-primary-100 dark:border-primary-800">
                    <p class="text-sm text-primary-700 dark:text-primary-300">
                        搜索结果：<strong>"{{ request('search') }}"</strong>，找到 {{ $posts->total() }} 篇文章
                    </p>
                </div>
            @endif
            
            @if($posts->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($posts as $post)
                        <article class="card overflow-hidden hover:shadow-xl transition-all duration-300 group flex flex-col">
                            @if($post->featured_image)
                                <a href="{{ route('posts.show', $post->slug) }}" class="block">
                                    <div class="relative aspect-[16/9] overflow-hidden">
                                        <img src="{{ $post->featured_image }}" 
                                             alt="{{ $post->title }}"
                                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                        @if($post->is_pinned)
                                            <span class="absolute top-3 left-3 px-2.5 py-1 bg-primary-600 text-white text-xs font-medium rounded-full shadow-lg flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0116 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z"/>
                                                </svg>
                                                置顶
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            @endif
                            
                            <div class="flex-1 p-5 flex flex-col">
                                @if($post->category)
                                    <a href="{{ route('categories.show', $post->category->slug) }}" 
                                       class="badge-primary mb-3 inline-block text-xs self-start">
                                        {{ $post->category->name }}
                                    </a>
                                @endif
                                
                                <h3 class="text-base lg:text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2 flex-shrink-0">
                                    <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-4 flex-1">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 100) }}
                                </p>
                                
                                <div class="flex items-center justify-between text-xs mt-auto pt-4 border-t border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $post->author->avatar ?? asset('images/default-avatar.png') }}" 
                                             alt="{{ $post->author->name }}"
                                             class="w-6 h-6 rounded-full object-cover">
                                        <span class="text-gray-700 dark:text-gray-300">
                                            {{ $post->author->name }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ number_format($post->view_count) }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                            {{ $post->comments_count ?? 0 }}
                                        </span>
                                        <span>{{ $post->published_at?->format('m/d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                
                @if($posts->hasPages())
                    <div class="mt-12">
                        {{ $posts->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="card p-16 text-center">
                    <svg class="w-20 h-20 mx-auto text-gray-300 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-3">暂无文章</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        @if(request('search'))
                            未找到与 "{{ request('search') }}" 相关的文章
                        @else
                            这里暂时没有文章，敬请期待
                        @endif
                    </p>
                    @if(request('search'))
                        <a href="{{ route('posts.index') }}" class="btn-primary">
                            返回全部文章
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>

</x-layout.app>
