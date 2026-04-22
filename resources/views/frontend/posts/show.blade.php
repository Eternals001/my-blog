{{-- resources/views/frontend/posts/show.blade.php --}}
{{-- 文章详情页 --}}

<x-layout.app :title="$post->title" :description="$post->excerpt ?? Str::limit(strip_tags($post->content), 160)">

    {{-- 文章头部 --}}
    <section class="relative bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
        @if($post->featured_image)
            <div class="absolute inset-0 h-64 sm:h-80">
                <img src="{{ $post->featured_image }}" 
                     alt="{{ $post->title }}"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-gray-900 via-white/80 dark:via-gray-900/80 to-transparent"></div>
            </div>
        @else
            <div class="absolute inset-0 h-48 sm:h-56 bg-gradient-to-br from-primary-600 via-primary-500 to-accent-500"></div>
        @endif
        
        <div class="relative container-blog py-16 sm:py-24">
            <div class="max-w-4xl mx-auto">
                {{-- 面包屑 --}}
                <nav class="flex items-center gap-2 text-sm mb-6">
                    <a href="{{ route('home') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        首页
                    </a>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('posts.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        文章
                    </a>
                    @if($post->category)
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <a href="{{ route('categories.show', $post->category->slug) }}" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            {{ $post->category->name }}
                        </a>
                    @endif
                </nav>
                
                {{-- 分类和标签 --}}
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    @if($post->category)
                        <a href="{{ route('categories.show', $post->category->slug) }}" 
                           class="badge-primary">
                            {{ $post->category->name }}
                        </a>
                    @endif
                    @if($post->is_pinned)
                        <span class="badge-secondary">
                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0116 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z"/>
                            </svg>
                            置顶
                        </span>
                    @endif
                </div>
                
                {{-- 标题 --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                    {{ $post->title }}
                </h1>
                
                {{-- 作者信息 --}}
                <div class="flex flex-wrap items-center gap-6 text-sm">
                    <div class="flex items-center gap-3">
                        <img src="{{ $post->author->avatar ?? asset('images/default-avatar.png') }}" 
                             alt="{{ $post->author->name }}"
                             class="w-12 h-12 rounded-full object-cover ring-2 ring-white dark:ring-gray-800 shadow-lg">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $post->author->name }}</p>
                            <p class="text-gray-500 dark:text-gray-400">{{ $post->author->bio ?? '博客作者' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $post->published_at?->format('Y年m月d日') ?? '未发布' }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ number_format($post->view_count) }} 阅读
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ $post->comments_count ?? 0 }} 评论
                        </span>
                    </div>
                </div>
                
                {{-- 标签 --}}
                @if($post->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mt-6 pt-6 border-t border-gray-100 dark:border-gray-800">
                        <svg class="w-5 h-5 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        @foreach($post->tags as $tag)
                            <a href="{{ route('tags.show', $tag->slug) }}" 
                               class="px-3 py-1.5 rounded-lg text-sm font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-primary-100 dark:hover:bg-primary-900/30 hover:text-primary-700 dark:hover:text-primary-400 transition-colors">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
    
    {{-- 文章内容 --}}
    <section class="py-12 lg:py-16 bg-white dark:bg-gray-900">
        <div class="container-blog">
            <div class="flex flex-col lg:flex-row gap-12">
                {{-- 文章主体 --}}
                <article class="lg:w-2/3">
                    <div class="prose prose-lg dark:prose-invert max-w-none
                                prose-headings:text-gray-900 dark:prose-headings:text-white
                                prose-p:text-gray-600 dark:prose-p:text-gray-300
                                prose-a:text-primary-600 dark:prose-a:text-primary-400 prose-a:no-underline hover:prose-a:underline
                                prose-code:text-primary-600 dark:prose-code:text-primary-400 prose-code:bg-gray-100 dark:prose-code:bg-gray-800 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:before:content-none prose-code:after:content-none
                                prose-pre:bg-gray-900 dark:prose-pre:bg-gray-800 prose-pre:text-gray-100
                                prose-blockquote:border-primary-500 prose-blockquote:bg-primary-50 dark:prose-blockquote:bg-primary-900/10 prose-blockquote:py-1 prose-blockquote:not-italic
                                prose-img:rounded-xl prose-img:shadow-lg
                                prose-li:text-gray-600 dark:prose-li:text-gray-300
                                prose-hr:border-gray-200 dark:prose-hr:border-gray-700">
                        {!! $post->content !!}
                    </div>
                    
                    {{-- 分享区域 --}}
                    <div class="mt-12 pt-8 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-500 dark:text-gray-400">分享到：</span>
                                <div class="flex items-center gap-2">
                                    <button onclick="shareTo('weibo')" class="w-10 h-10 rounded-xl bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white flex items-center justify-center transition-all">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M10.098 20.323c-3.977.391-7.414-1.406-7.672-4.02-.259-2.609 2.759-5.047 6.74-5.441 3.979-.394 7.413 1.404 7.671 4.018.259 2.6-2.759 5.049-6.739 5.443zM9.05 17.219c-.384.616-1.208.884-1.829.602-.612-.279-.793-.991-.406-1.593.379-.595 1.176-.861 1.793-.601.622.263.82.972.442 1.592zm1.27-1.627c-.141.237-.449.353-.689.253-.236-.09-.313-.361-.177-.586.138-.227.436-.346.672-.24.239.09.315.36.194.573zm.176-2.719c-1.893-.493-4.033.45-4.857 2.118-.836 1.704-.026 3.591 1.886 4.21 1.983.64 4.318-.341 5.132-2.179.8-1.793-.201-3.642-2.161-4.149zm7.563-1.224c-.346-.105-.578-.164-.399-.644.386-1.041.431-1.986.002-2.77-.822-1.5-3.048-2.01-5.262-1.209-1.455.531-2.655 1.426-3.38 2.485-1.542 2.247-1.373 4.82.479 6.388 1.926 1.632 4.994 1.838 7.09.5.431-.281.325-.449.104-.686-.388-.418-.388-.418-1.137-.566.219-.331.353-.708.384-1.116.083-1.084-.344-2.127-1.204-2.836.775.349 1.62.526 2.472.454.312-.026.463-.09.851-.001z"/>
                                        </svg>
                                    </button>
                                    <button onclick="shareTo('twitter')" class="w-10 h-10 rounded-xl bg-blue-400/10 hover:bg-blue-400 text-blue-400 hover:text-white flex items-center justify-center transition-all">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                    </button>
                                    <button onclick="copyLink()" class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 flex items-center justify-center transition-all" title="复制链接">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            {{-- 赞赏 --}}
                            <button onclick="toggleLike()" id="likeBtn" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-medium transition-all
                                {{ $post->liked ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-primary-100 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400' }}">
                                <svg class="w-5 h-5 {{ $post->liked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span id="likeText">{{ $post->liked ? '已赞赏' : '赞赏支持' }}</span>
                                <span id="likeCount" class="text-sm opacity-75">({{ $post->likes_count ?? 0 }})</span>
                            </button>
                        </div>
                    </div>
                    
                    {{-- 导航：上一篇/下一篇 --}}
                    @if($prevPost || $nextPost)
                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if($prevPost)
                                <a href="{{ route('posts.show', $prevPost->slug) }}" 
                                   class="group flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">上一篇</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white line-clamp-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                            {{ $prevPost->title }}
                                        </p>
                                    </div>
                                </a>
                            @else
                                <div></div>
                            @endif
                            
                            @if($nextPost)
                                <a href="{{ route('posts.show', $nextPost->slug) }}" 
                                   class="group flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors text-right">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">下一篇</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white line-clamp-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                            {{ $nextPost->title }}
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    @endif
                    
                    {{-- 评论区 --}}
                    <div class="mt-12 pt-8 border-t border-gray-100 dark:border-gray-800">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            评论
                            <span class="text-base font-normal text-gray-500 dark:text-gray-400">({{ $post->comments_count ?? 0 }})</span>
                        </h3>
                        
                        {{-- 评论区列表 --}}
                        <div class="space-y-6 mb-8">
                            @forelse($post->comments()->where('is_approved', true)->whereNull('parent_id')->with('replies')->get() as $comment)
                                @include('frontend.comments.comment-item', ['comment' => $comment])
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">暂无评论，来抢沙发吧~</p>
                                </div>
                            @endforelse
                        </div>
                        
                        {{-- 发表评论表单 --}}
                        @auth
                            <div class="card p-6 bg-gray-50 dark:bg-gray-800/50">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">发表评论</h4>
                                <form action="{{ route('comments.store', $post) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                昵称 <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="display_name" value="{{ auth()->user()->name }}" required
                                                   class="input">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                                邮箱 <span class="text-red-500">*</span>
                                            </label>
                                            <input type="email" name="email" value="{{ auth()->user()->email }}" required
                                                   class="input">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                            评论内容 <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="content" rows="4" required
                                                  placeholder="写下你的想法..."
                                                  class="input resize-none"></textarea>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            评论需要审核后显示，请文明发言
                                        </p>
                                        <button type="submit" class="btn-primary">
                                            提交评论
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="card p-6 bg-gray-50 dark:bg-gray-800/50 text-center">
                                <p class="text-gray-600 dark:text-gray-400 mb-4">
                                    登录后可发表评论
                                </p>
                                <a href="{{ route('login') }}" class="btn-primary">
                                    登录
                                </a>
                            </div>
                        @endauth
                    </div>
                </article>
                
                {{-- 侧边栏 --}}
                <aside class="lg:w-1/3">
                    <div class="sticky top-24 space-y-6">
                        {{-- 作者信息卡片 --}}
                        <div class="card p-6">
                            <div class="flex items-center gap-4 mb-4">
                                <img src="{{ $post->author->avatar ?? asset('images/default-avatar.png') }}" 
                                     alt="{{ $post->author->name }}"
                                     class="w-16 h-16 rounded-full object-cover ring-4 ring-primary-100 dark:ring-primary-900/30">
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $post->author->name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">博客作者</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                {{ $post->author->bio ?? '热爱技术，乐于分享。' }}
                            </p>
                            <a href="{{ route('about') }}" class="btn-outline w-full text-center">
                                关于作者
                            </a>
                        </div>
                        
                        {{-- 目录 --}}
                        @if(!empty($tableOfContents) && $tableOfContents->isNotEmpty())
                            <div class="card p-6">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                    文章目录
                                </h4>
                                <nav class="space-y-2">
                                    @foreach($tableOfContents as $item)
                                        <a href="#{{ $item['id'] }}" 
                                           class="block text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors py-1 border-l-2 border-transparent hover:border-primary-500"
                                           style="padding-left: {{ (($item['level'] ?? 2) - 1) * 12 + 12 }}px">
                                            {{ $item['text'] }}
                                        </a>
                                    @endforeach
                                </nav>
                            </div>
                        @endif
                        
                        {{-- 相关文章 --}}
                        @if($relatedPosts->isNotEmpty())
                            <div class="card p-6">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    相关文章
                                </h4>
                                <div class="space-y-4">
                                    @foreach($relatedPosts as $related)
                                        <a href="{{ route('posts.show', $related->slug) }}" class="group block">
                                            @if($related->featured_image)
                                                <img src="{{ $related->featured_image }}" 
                                                     alt="{{ $related->title }}"
                                                     class="w-full h-32 object-cover rounded-lg mb-2 group-hover:opacity-80 transition-opacity">
                                            @endif
                                            <h5 class="text-sm font-medium text-gray-900 dark:text-white line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                                {{ $related->title }}
                                            </h5>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // 分享功能
        function shareTo(platform) {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            
            const shareUrls = {
                weibo: `https://service.weibo.com/share/share.php?url=${url}&title=${title}`,
                twitter: `https://twitter.com/intent/tweet?url=${url}&text=${title}`,
            };
            
            if (shareUrls[platform]) {
                window.open(shareUrls[platform], '_blank', 'width=600,height=500');
            }
        }
        
        // 复制链接
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('链接已复制到剪贴板');
            });
        }
        
        // 点赞
        function toggleLike() {
            fetch('{{ route('posts.like', $post) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      const btn = document.getElementById('likeBtn');
                      const likeText = document.getElementById('likeText');
                      const likeCount = document.getElementById('likeCount');
                      const svg = btn.querySelector('svg');
                      
                      if (data.liked) {
                          btn.className = 'inline-flex items-center gap-2 px-6 py-3 rounded-xl font-medium transition-all bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400';
                          svg.classList.add('fill-current');
                          likeText.textContent = '已赞赏';
                      } else {
                          btn.className = 'inline-flex items-center gap-2 px-6 py-3 rounded-xl font-medium transition-all bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-primary-100 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400';
                          svg.classList.remove('fill-current');
                          likeText.textContent = '赞赏支持';
                      }
                      likeCount.textContent = `(${data.count})`;
                  }
              });
        }
        
        // 平滑滚动到锚点
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
    @endpush

</x-layout.app>
