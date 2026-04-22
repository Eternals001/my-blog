{{-- resources/views/components/frontend/sidebar.blade.php --}}
{{-- 前台侧边栏组件 --}}

<aside class="w-full lg:w-80 space-y-8">

    {{-- 关于作者 --}}
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            关于作者
        </h3>
        <div class="flex items-center gap-3 mb-4">
            <img src="{{ $author->avatar ?? asset('images/default-avatar.png') }}" 
                 alt="{{ $author->name }}"
                 class="avatar-xl">
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white">
                    {{ $author->name ?? '博主' }}
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $author->title ?? '全栈开发者' }}
                </p>
            </div>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            {{ $author->bio ?? '热爱技术，分享代码。' }}
        </p>
        @if(Route::has('about'))
            <a href="{{ route('about') }}" class="btn-secondary w-full justify-center">
                阅读更多
            </a>
        @endif
    </div>

    {{-- 热门标签 --}}
    @if(isset($popularTags) && $popularTags->isNotEmpty())
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                热门标签
            </h3>
            <x-tag-cloud :tags="$popularTags" :showCount="true" />
        </div>
    @endif

    {{-- 热门文章 --}}
    @if(isset($popularPosts) && $popularPosts->isNotEmpty())
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                热门文章
            </h3>
            <div class="space-y-4">
                @foreach($popularPosts as $post)
                    <x-post-card :post="$post" variant="compact" :showImage="false" />
                @endforeach
            </div>
        </div>
    @endif

    {{-- 订阅订阅 --}}
    <div class="card p-6 bg-gradient-to-br from-primary-500 to-primary-600">
        <h3 class="text-lg font-semibold text-white mb-2">
            订阅更新
        </h3>
        <p class="text-sm text-primary-100 mb-4">
            订阅邮件，第一时间获取最新文章推送。
        </p>
        <form class="space-y-3" action="{{ route('subscribe') }}" method="POST">
            @csrf
            <input type="email" 
                   name="email"
                   placeholder="your@email.com"
                   class="input bg-white/20 border-white/30 text-white placeholder-primary-200 focus:bg-white/30"
                   required>
            <button type="submit" class="btn w-full bg-white text-primary-600 hover:bg-primary-50">
                立即订阅
            </button>
        </form>
    </div>

    {{-- 分类列表 --}}
    @if(isset($categories) && $categories->isNotEmpty())
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                文章分类
            </h3>
            <ul class="space-y-2">
                @foreach($categories as $category)
                    <li>
                        <a href="{{ route('categories.show', $category->slug) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <span>{{ $category->name }}</span>
                            <span class="text-sm text-gray-500">({{ $category->posts_count }})</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

</aside>
