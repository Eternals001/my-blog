{{-- resources/views/frontend/tag.blade.php --}}
{{-- 标签页面 --}}

<x-layout.app 
    :title="'#' . $tag->name . ' | 标签 | ' . config('app.name')"
    :metaTitle="'#' . $tag->name . ' - ' . config('app.name')"
    :metaDescription="'浏览标签「' . $tag->name . '」下的所有文章，包含' . ($tag->posts_count ?? $posts->total()) . '篇相关技术文章和教程。'"
    :metaKeywords="$tag->name . ', 标签, 技术文章, 教程'"
    :ogImage="asset('images/og-default.png')"
    :tags="[$tag->name]">

    {{-- 页面头部 --}}
    <header class="bg-gradient-to-r from-accent-600 to-accent-500 text-white">
        <div class="container-blog py-12 lg:py-16">
            <div class="max-w-2xl">
                <div class="flex items-center gap-2 text-accent-100 mb-4">
                    <a href="{{ route('home') }}" class="hover:text-white">首页</a>
                    <span>/</span>
                    <a href="{{ route('tags.index') }}" class="hover:text-white">标签</a>
                    <span>/</span>
                    <span>{{ $tag->name }}</span>
                </div>
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-bold">
                            # {{ $tag->name }}
                        </h1>
                        <p class="text-accent-100 mt-1">
                            {{ $tag->posts_count ?? $posts->total() }} 篇文章
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    {{-- 文章列表 --}}
    <section class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="container-blog">
            @if($posts->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <x-post-card :post="$post" />
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">
                        该标签下暂无文章
                    </p>
                    <a href="{{ route('posts.index') }}" class="btn-primary mt-4">
                        浏览全部文章
                    </a>
                </div>
            @endif
        </div>
    </section>
    
</x-layout.app>
