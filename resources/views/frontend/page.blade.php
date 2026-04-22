{{-- resources/views/frontend/page.blade.php --}}
{{-- 独立页面 --}}

<x-layout.app 
    :title="$page->title"
    :metaTitle="$page->seo_title ?? $page->title"
    :metaDescription="$page->meta_description ?? $page->excerpt"
    :ogImage="$page->featured_image">

    {{-- 页面内容 --}}
    <article class="py-12 lg:py-16 bg-white dark:bg-gray-900">
        <div class="container-blog max-w-4xl">
            
            {{-- 面包屑 --}}
            <nav class="mb-8 text-sm" aria-label="面包屑">
                <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                            首页
                        </a>
                    </li>
                    <li>/</li>
                    <li class="text-gray-900 dark:text-white">
                        {{ $page->title }}
                    </li>
                </ol>
            </nav>
            
            {{-- 标题 --}}
            <header class="mb-12 text-center">
                @if($page->featured_image)
                    <figure class="mb-8 rounded-xl overflow-hidden">
                        <img src="{{ $page->featured_image }}" 
                             alt="{{ $page->title }}"
                             class="w-full h-auto max-h-[400px] object-cover">
                    </figure>
                @endif
                
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                    {{ $page->title }}
                </h1>
                
                @if($page->excerpt)
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                        {{ $page->excerpt }}
                    </p>
                @endif
            </header>
            
            {{-- 页面正文 --}}
            <div class="prose-blog dark:prose-invert max-w-none">
                {!! $page->content !!}
            </div>
            
            {{-- 底部导航 --}}
            @if($page->updated_at)
                <footer class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700 text-center text-sm text-gray-500 dark:text-gray-400">
                    最后更新于 {{ $page->updated_at->format('Y年m月d日') }}
                </footer>
            @endif
        </div>
    </article>
    
</x-layout.app>
