@extends('layouts.app')

@section('title', '搜索 - ' . config('blog.name'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">
            @if($query)
                搜索结果: "{{ $query }}"
                <span class="text-gray-500 text-lg font-normal ml-2">({{ $total }} 个结果)</span>
            @else
                搜索
            @endif
        </h1>

        @if($query)
            <form action="{{ route('blog.search') }}" method="GET" class="mb-6">
                <div class="relative">
                    <input type="text"
                           name="q"
                           value="{{ $query }}"
                           placeholder="搜索文章..."
                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                        搜索
                    </button>
                </div>
            </form>

            @if($posts->count() > 0)
                <div class="space-y-6">
                    @foreach($posts as $post)
                        <article class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                            <h2 class="text-xl font-semibold mb-2">
                                <a href="{{ route('blog.post', $post->slug) }}" class="text-gray-900 hover:text-primary-600 transition">
                                    {{ $post->title }}
                                </a>
                            </h2>

                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <span>{{ $post->author->name }}</span>
                                <span class="mx-2">·</span>
                                <span>{{ $post->published_at->diffForHumans() }}</span>
                                @if($post->category)
                                    <span class="mx-2">·</span>
                                    <a href="{{ route('blog.category', $post->category->slug) }}" class="hover:text-primary-600">
                                        {{ $post->category->name }}
                                    </a>
                                @endif
                            </div>

                            <p class="text-gray-600 line-clamp-2">
                                {{ $post->excerpt ?? Str::limit(strip_tags($post->html_content), 200) }}
                            </p>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $posts->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 mb-4">没有找到相关文章</p>
                    <a href="{{ route('blog.posts.index') }}" class="text-primary-600 hover:text-primary-700">
                        浏览所有文章 →
                    </a>
                </div>
            @endif
        @else
            <form action="{{ route('blog.search') }}" method="GET" class="mb-6">
                <div class="relative">
                    <input type="text"
                           name="q"
                           placeholder="输入关键词搜索文章..."
                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                        搜索
                    </button>
                </div>
            </form>

            <div class="text-center py-12">
                <p class="text-gray-500">输入关键词开始搜索</p>
            </div>
        @endif
    </div>
</div>
@endsection
