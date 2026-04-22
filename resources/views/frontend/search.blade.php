{{-- resources/views/frontend/search.blade.php --}}
{{-- 搜索结果页面 --}}

<x-layout.app 
    title="搜索结果: {{ $query }}"
    :metaDescription="'搜索「' . $query . '」的结果'">

    {{-- 页面头部 --}}
    <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="container-blog py-8 lg:py-12">
            <div class="max-w-2xl">
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    搜索结果
                </h1>
                
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <input type="text" 
                           name="q" 
                           value="{{ $query }}"
                           placeholder="搜索文章..."
                           class="input pr-12 text-lg">
                    <button type="submit" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-400 hover:text-primary-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>
                
                <p class="mt-4 text-gray-600 dark:text-gray-400">
                    找到 <strong class="text-primary-600 dark:text-primary-400">{{ $posts->total() }}</strong> 个与 
                    <strong class="text-gray-900 dark:text-white">"{{ $query }}"</strong> 相关的结果
                </p>
            </div>
        </div>
    </header>
    
    {{-- 搜索结果 --}}
    <section class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="container-blog">
            @if($posts->isNotEmpty())
                <div class="space-y-6">
                    @foreach($posts as $post)
                        <article class="card p-6">
                            <div class="flex gap-6">
                                @if($post->featured_image)
                                    <a href="{{ route('posts.show', $post->slug) }}" class="flex-shrink-0">
                                        <img src="{{ $post->featured_image }}" 
                                             alt="{{ $post->title }}"
                                             class="w-32 h-24 rounded-lg object-cover">
                                    </a>
                                @endif
                                
                                <div class="flex-1 min-w-0">
                                    @if($post->category)
                                        <a href="{{ route('categories.show', $post->category->slug) }}" 
                                           class="badge-primary mb-2 inline-block">
                                            {{ $post->category->name }}
                                        </a>
                                    @endif
                                    
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                        <a href="{{ route('posts.show', $post->slug) }}" 
                                           class="hover:text-primary-600 dark:hover:text-primary-400">
                                            {!! highlight($post->title, $query) !!}
                                        </a>
                                    </h2>
                                    
                                    <p class="text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">
                                        {!! highlight($post->excerpt ?? strip_tags($post->content), $query) !!}
                                    </p>
                                    
                                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ $post->author->name ?? '匿名' }}</span>
                                        <span>·</span>
                                        <span>{{ $post->published_at?->diffForHumans() ?? $post->created_at->diffForHumans() }}</span>
                                        <span>·</span>
                                        <span>{{ number_format($post->views_count) }} 阅读</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                
                @if($posts->hasPages())
                    <div class="mt-8">
                        <x-pagination :paginator="$posts" />
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-4 text-gray-500 dark:text-gray-400 mb-4">
                        未找到与 "<span class="text-gray-900 dark:text-white">{{ $query }}</span>" 相关的文章
                    </p>
                    <p class="text-sm text-gray-400 mb-6">
                        尝试使用更简短的关键词，或浏览以下热门标签
                    </p>
                    
                    @if(isset($popularTags))
                        <x-tag-cloud :tags="$popularTags" :maxItems="10" />
                    @endif
                </div>
            @endif
        </div>
    </section>
    
</x-layout.app
