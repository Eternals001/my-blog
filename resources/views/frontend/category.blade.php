{{-- resources/views/frontend/category.blade.php --}}
{{-- 分类页面 --}}

<x-layout.app 
    :title="$category->name . ' | 分类 | ' . config('app.name')"
    :metaTitle="$category->name . ' - ' . config('app.name')"
    :metaDescription="'浏览「' . $category->name . '」分类下的所有文章，包含' . ($category->posts_count ?? $posts->total()) . '篇文章。' . Str::limit($category->description ?? '', 120)"
    :metaKeywords="$category->name . ', ' . Str::limit($category->description ?? '', 60)"
    :ogImage="$category->image ?? asset('images/og-default.png')"
    :section="$category->name">

    {{-- 页面头部 --}}
    <header class="bg-gradient-to-r from-primary-600 to-primary-500 text-white">
        <div class="container-blog py-12 lg:py-16">
            <div class="max-w-2xl">
                <div class="flex items-center gap-2 text-primary-100 mb-4">
                    <a href="{{ route('home') }}" class="hover:text-white">首页</a>
                    <span>/</span>
                    <a href="{{ route('categories.index') }}" class="hover:text-white">分类</a>
                    <span>/</span>
                    <span>{{ $category->name }}</span>
                </div>
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-bold">
                            {{ $category->name }}
                        </h1>
                        <p class="text-primary-100 mt-1">
                            {{ $category->posts_count ?? $posts->total() }} 篇文章
                        </p>
                    </div>
                </div>
                
                @if($category->description)
                    <p class="text-primary-100">
                        {{ $category->description }}
                    </p>
                @endif
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">
                        该分类下暂无文章
                    </p>
                    <a href="{{ route('posts.index') }}" class="btn-primary mt-4">
                        浏览全部文章
                    </a>
                </div>
            @endif
        </div>
    </section>
    
    {{-- 其他分类 --}}
    @if($otherCategories->isNotEmpty())
        <section class="py-12 bg-white dark:bg-gray-800">
            <div class="container-blog">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">
                    其他分类
                </h2>
                <div class="flex flex-wrap gap-3">
                    @foreach($otherCategories as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}" 
                           class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                            {{ $cat->name }}
                            <span class="ml-1 text-sm text-gray-500">({{ $cat->posts_count }})</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    
</x-layout.app>
