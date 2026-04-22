{{-- resources/views/blog/posts/index.blade.php --}}
{{-- 文章列表页面 --}}

<x-layout.app title="文章列表">

    {{-- 页面标题区域 --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-accent-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900"></div>
        <div class="relative container-blog py-12 lg:py-16">
            <div class="max-w-2xl">
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    文章列表
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    共 {{ $posts->total() }} 篇文章
                </p>
            </div>
        </div>
    </section>

    {{-- 筛选器 --}}
    <section class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 sticky top-16 z-10">
        <div class="container-blog">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 py-4">
                {{-- 分类筛选 --}}
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('posts.index') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ !request('category') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        全部
                    </a>
                    @foreach($categories ?? [] as $category)
                        <a href="{{ route('posts.index', ['category' => $category->slug]) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap
                                  {{ request('category') === $category->slug ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                {{-- 排序选项 --}}
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">排序：</span>
                    <select onchange="location.href = this.value" 
                            class="form-select text-sm py-2 pl-3 pr-8 rounded-lg border-gray-300 dark:border-gray-600 focus:ring-primary-500">
                        <option value="{{ route('posts.index', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                            最新发布
                        </option>
                        <option value="{{ route('posts.index', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>
                            最多浏览
                        </option>
                        <option value="{{ route('posts.index', array_merge(request()->except('sort'), ['sort' => 'commented'])) }}" {{ request('sort') === 'commented' ? 'selected' : '' }}>
                            最多评论
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    {{-- 文章列表 --}}
    <section class="py-12 lg:py-16 bg-gray-50 dark:bg-gray-800/50">
        <div class="container-blog">
            @if($posts->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($posts as $post)
                        <article class="card group overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col">
                            {{-- 封面图 --}}
                            @if($post->featured_image)
                                <a href="{{ route('posts.show', $post->slug) }}" class="block">
                                    <div class="relative aspect-[16/9] overflow-hidden">
                                        <img src="{{ $post->featured_image }}" 
                                             alt="{{ $post->title }}"
                                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                        @if($post->is_pinned)
                                            <span class="absolute top-3 left-3 px-2.5 py-1 bg-primary-600 text-white text-xs font-medium rounded-full shadow-lg flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5v6h2v-6h5v-2l-2-2z"/>
                                                </svg>
                                                置顶
                                            </span>
                                        @endif
                                        @if($post->featured)
                                            <span class="absolute top-3 right-3 px-2.5 py-1 bg-accent-500 text-white text-xs font-medium rounded-full shadow-lg">
                                                精选
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            @endif
                            
                            <div class="flex-1 p-5 flex flex-col">
                                {{-- 分类标签 --}}
                                @if($post->category)
                                    <a href="{{ route('categories.show', $post->category->slug) }}" 
                                       class="badge-primary mb-3 self-start hover:bg-primary-200 dark:hover:bg-primary-800">
                                        {{ $post->category->name }}
                                    </a>
                                @endif
                                
                                {{-- 标题 --}}
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                    <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                                </h2>
                                
                                {{-- 摘要 --}}
                                <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3 mb-4 flex-1">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                                </p>
                                
                                {{-- 元信息 --}}
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $post->author->avatar ?? asset('images/default-avatar.png') }}" 
                                             alt="{{ $post->author->name }}"
                                             class="w-8 h-8 rounded-full object-cover">
                                        <div class="text-sm">
                                            <span class="font-medium text-gray-900 dark:text-white">
                                                {{ $post->author->name }}
                                            </span>
                                            <span class="text-gray-500 dark:text-gray-400 block">
                                                {{ $post->published_at?->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1" title="浏览量">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ number_format($post->view_count) }}
                                        </span>
                                        <span class="flex items-center gap-1" title="评论数">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                            {{ $post->comments_count ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                                
                                {{-- 标签 --}}
                                @if($post->tags->isNotEmpty())
                                    <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
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
                        </article>
                    @endforeach
                </div>
                
                {{-- 分页 --}}
                @if($posts->hasPages())
                    <div class="mt-12 flex justify-center">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                            {{ $posts->withQueryString()->links() }}
                        </div>
                    </div>
                @endif
            @else
                {{-- 空状态 --}}
                <div class="text-center py-16">
                    <svg class="w-20 h-20 mx-auto text-gray-300 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">暂无文章</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">这里还没有发布任何文章</p>
                    <a href="{{ route('posts.create') ?? '#' }}" class="btn-primary inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        写一篇文章
                    </a>
                </div>
            @endif
        </div>
    </section>

</x-layout.app>