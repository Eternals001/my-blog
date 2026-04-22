{{-- resources/views/frontend/categories/show.blade.php --}}
{{-- 分类页面 --}}

<x-layout.app :title="$category->name" :description="'浏览 ' . $category->name . ' 分类下的所有文章'">

    {{-- 分类头部 --}}
    <section class="relative bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-accent-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900"></div>
        
        <div class="relative container-blog py-16">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('home') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    首页
                </a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('posts.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    分类
                </a>
            </div>
            
            <div class="flex items-start gap-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center shadow-lg shadow-primary-500/25 flex-shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-3">
                        {{ $category->name }}
                    </h1>
                    @if($category->description)
                        <p class="text-gray-600 dark:text-gray-400 mb-4 max-w-2xl">
                            {{ $category->description }}
                        </p>
                    @endif
                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            {{ $category->posts_count ?? 0 }} 篇文章
                        </span>
                        @if($category->parent)
                            <span>
                                隶属于 
                                <a href="{{ route('categories.show', $category->parent->slug) }}" 
                                   class="text-primary-600 dark:text-primary-400 hover:underline">
                                    {{ $category->parent->name }}
                                </a>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- 子分类 --}}
            @if($category->children && $category->children->isNotEmpty())
                <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">子分类</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach($category->children as $child)
                            <a href="{{ route('categories.show', $child->slug) }}" 
                               class="px-4 py-2 rounded-xl bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-primary-300 dark:hover:border-primary-600 hover:text-primary-600 dark:hover:text-primary-400 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ $child->name }}
                                <span class="text-xs text-gray-400">({{ $child->posts_count ?? 0 }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
    
    {{-- 文章列表 --}}
    <section class="py-12 lg:py-16 bg-gray-50 dark:bg-gray-800/50">
        <div class="container-blog">
            @if($posts->isNotEmpty())
                <div class="space-y-6">
                    @foreach($posts as $post)
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
                                    
                                    @if($post->tags->isNotEmpty())
                                        <div class="flex flex-wrap gap-2 mt-4">
                                            @foreach($post->tags->take(3) as $tag)
                                                <a href="{{ route('tags.show', $tag->slug) }}" 
                                                   class="px-2.5 py-1 text-xs rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-primary-100 dark:hover:bg-primary-900/30 hover:text-primary-700 dark:hover:text-primary-400 transition-colors">
                                                    {{ $tag->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">暂无文章</h3>
                    <p class="text-gray-500 dark:text-gray-400">该分类下还没有文章</p>
                </div>
            @endif
        </div>
    </section>

</x-layout.app>
