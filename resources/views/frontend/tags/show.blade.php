{{-- resources/views/frontend/tags/show.blade.php --}}
{{-- 标签页面 --}}

<x-layout.app :title="'#' . $tag->name" :description="'浏览标签 #' . $tag->name . ' 下的所有文章'">

    {{-- 标签头部 --}}
    <section class="relative bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-green-50 via-white to-primary-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900"></div>
        
        <div class="relative container-blog py-16">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('home') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    首页
                </a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-sm text-gray-500 dark:text-gray-400">标签</span>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-green-500/25 flex-shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                        <span class="text-gray-400">#</span>{{ $tag->name }}
                    </h1>
                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            {{ $tag->posts_count ?? 0 }} 篇文章
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {{-- 文章列表 --}}
    <section class="py-12 lg:py-16 bg-gray-50 dark:bg-gray-800/50">
        <div class="container-blog">
            @if($posts->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <article class="card overflow-hidden hover:shadow-lg transition-all duration-300 group">
                            @if($post->featured_image)
                                <a href="{{ route('posts.show', $post->slug) }}" class="block">
                                    <div class="aspect-[16/9] overflow-hidden">
                                        <img src="{{ $post->featured_image }}" 
                                             alt="{{ $post->title }}"
                                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                    </div>
                                </a>
                            @endif
                            
                            <div class="p-5">
                                @if($post->category)
                                    <a href="{{ route('categories.show', $post->category->slug) }}" 
                                       class="badge-primary mb-3 inline-block text-xs">
                                        {{ $post->category->name }}
                                    </a>
                                @endif
                                
                                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                    <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-4">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 80) }}
                                </p>
                                
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $post->author->avatar ?? asset('images/default-avatar.png') }}" 
                                             alt="{{ $post->author->name }}"
                                             class="w-6 h-6 rounded-full object-cover">
                                        <span class="text-gray-700 dark:text-gray-300">
                                            {{ $post->author->name }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ number_format($post->view_count) }}
                                        </span>
                                        <span>{{ $post->published_at?->format('m/d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                
                @if($posts->hasPages())
                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                @endif
            @else
                <div class="card p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">暂无文章</h3>
                    <p class="text-gray-500 dark:text-gray-400">该标签下还没有文章</p>
                </div>
            @endif
        </div>
    </section>

</x-layout.app>
