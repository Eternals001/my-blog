{{-- resources/views/frontend/post.blade.php --}}
{{-- 文章详情页 --}}

<x-layout.app 
    :title="$post->title . ' | ' . config('app.name')"
    :metaTitle="$post->seo_title ?? $post->title . ' | ' . config('app.name')"
    :metaDescription="$post->meta_description ?? Str::limit(strip_tags($post->content), 160)"
    :metaKeywords="($post->meta_keywords ?? '') ?: $post->tags->pluck('name')->implode(',')"
    :ogImage="$post->featured_image ?? asset('images/og-default.png')"
    ogType="article"
    :author="$post->author->name ?? config('app.author')"
    :publishedTime="$post->published_at?->toIso8601String()"
    :modifiedTime="$post->updated_at?->toIso8601String()">

    {{-- 文章头部 --}}
    <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="container-blog py-8 lg:py-12">
            
            {{-- 面包屑 --}}
            <nav class="mb-6 text-sm" aria-label="面包屑">
                <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                            首页
                        </a>
                    </li>
                    <li>/</li>
                    <li>
                        <a href="{{ route('posts.index') }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                            文章
                        </a>
                    </li>
                    @if($post->category)
                        <li>/</li>
                        <li>
                            <a href="{{ route('categories.show', $post->category->slug) }}" 
                               class="hover:text-primary-600 dark:hover:text-primary-400">
                                {{ $post->category->name }}
                            </a>
                        </li>
                    @endif
                    <li>/</li>
                    <li class="text-gray-900 dark:text-white truncate max-w-[200px]">
                        {{ $post->title }}
                    </li>
                </ol>
            </nav>
            
            {{-- 分类标签 --}}
            @if($post->category)
                <a href="{{ route('categories.show', $post->category->slug) }}" 
                   class="badge-primary mb-4 inline-block hover:bg-primary-200 dark:hover:bg-primary-800">
                    {{ $post->category->name }}
                </a>
            @endif
            
            {{-- 标题 --}}
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ $post->title }}
            </h1>
            
            {{-- 元信息 --}}
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                {{-- 作者 --}}
                <div class="flex items-center gap-2">
                    <img src="{{ $post->author->avatar ?? asset('images/default-avatar.png') }}" 
                         alt=""
                         class="w-10 h-10 rounded-full object-cover">
                    <a href="{{ route('profile.show', $post->author->username ?? $post->author->id) }}" 
                       class="font-medium text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400">
                        {{ $post->author->name }}
                    </a>
                </div>
                
                <span>·</span>
                
                {{-- 发布时间 --}}
                <time datetime="{{ $post->published_at->toIso8601String() }}">
                    {{ $post->published_at->format('Y年m月d日') }}
                </time>
                
                <span>·</span>
                
                {{-- 阅读时间 --}}
                <span>{{ $post->reading_time ?? ceil(str_word_count(strip_tags($post->content)) / 400) }} 分钟阅读</span>
                
                <span>·</span>
                
                {{-- 阅读数 --}}
                <span>{{ number_format($post->views_count) }} 阅读</span>
            </div>
        </div>
    </header>
    
    {{-- 文章主体 --}}
    <div class="bg-white dark:bg-gray-900">
        <div class="container-blog py-8 lg:py-12">
            <div class="flex flex-col lg:flex-row gap-8">
                
                {{-- 侧边目录（PC端） --}}
                <aside class="hidden lg:block w-64 flex-shrink-0">
                    <div class="toc-container" x-data="{ activeId: '' }">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 uppercase tracking-wider">
                            文章目录
                        </h4>
                        <nav id="toc" class="space-y-1">
                            {{-- TOC将由JS动态生成 --}}
                            <div class="text-sm text-gray-400 dark:text-gray-500">
                                加载中...
                            </div>
                        </nav>
                    </div>
                </aside>
                
                {{-- 文章内容 --}}
                <article class="flex-1 min-w-0">
                    
                    {{-- 特色图片 --}}
                    @if($post->featured_image)
                        <figure class="mb-8 rounded-xl overflow-hidden">
                            <img src="{{ $post->featured_image }}" 
                                 alt="{{ $post->title }}"
                                 class="w-full h-auto max-h-[500px] object-cover">
                        </figure>
                    @endif
                    
                    {{-- 文章正文 --}}
                    <div class="prose-blog dark:prose-invert max-w-none">
                        {!! $post->content !!}
                    </div>
                    
                    {{-- 标签 --}}
                    @if($post->tags->isNotEmpty())
                        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">标签：</span>
                                @foreach($post->tags as $tag)
                                    <a href="{{ route('tags.show', $tag->slug) }}" 
                                       class="badge-accent hover:bg-accent-200 dark:hover:bg-accent-800">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    {{-- 分享按钮 --}}
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">分享到</span>
                            <div class="flex items-center gap-3">
                                <button onclick="BlogUtils.sharePost('{{ $post->title }}')"
                                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                    </svg>
                                </button>
                                <button onclick="BlogUtils.copyToClipboard(window.location.href)"
                                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- 点赞区域 --}}
                    <div class="mt-8 text-center">
                        <button type="button" 
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 dark:bg-gray-800 rounded-full text-gray-700 dark:text-gray-300 hover:bg-primary-100 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                                wire:click="toggleLike">
                            <svg class="w-5 h-5" fill="{{ $post->is_liked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span>{{ $post->likes_count ?? 0 }} 个赞</span>
                        </button>
                    </div>
                </article>
                
                {{-- 右侧留白（平衡布局） --}}
                <aside class="hidden xl:block w-64 flex-shrink-0">
                </aside>
            </div>
        </div>
    </div>
    
    {{-- 相关文章 --}}
    @if($relatedPosts->isNotEmpty())
        <section class="py-12 bg-gray-50 dark:bg-gray-800/50">
            <div class="container-blog">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">
                    相关文章
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $relatedPost)
                        <x-post-card :post="$relatedPost" :showExcerpt="false" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    
    {{-- 评论区域 --}}
    <section class="py-12 bg-white dark:bg-gray-900">
        <div class="container-blog max-w-4xl">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">
                评论 ({{ $post->comments_count ?? 0 }})
            </h2>
            
            {{-- 评论表单 --}}
            @auth
                <div class="card p-6 mb-8" x-data="{ content: '', replyTo: null }">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        发表评论
                    </h3>
                    
                    {{-- 回复提示 --}}
                    <div x-show="replyTo" class="mb-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            回复 @<span x-text="replyTo"></span>
                        </span>
                        <button @click="replyTo = null" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <form wire:submit="storeComment">
                        <textarea wire:model="commentContent"
                                  x-model="content"
                                  rows="4"
                                  class="input mb-4"
                                  placeholder="写下你的评论..."
                                  required></textarea>
                        
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                登录后即可发表评论
                            </p>
                            <button type="submit" class="btn-primary" :disabled="!content.trim()">
                                发布评论
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="card p-6 mb-8 text-center">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        请登录后发表评论
                    </p>
                    <a href="{{ route('login') }}" class="btn-primary">
                        登录
                    </a>
                </div>
            @endauth
            
            {{-- 评论列表 --}}
            @if($comments->isNotEmpty())
                <div class="space-y-6">
                    @foreach($comments as $comment)
                        <x-comment-item :comment="$comment" :post="$post" />
                    @endforeach
                </div>
                
                @if($comments->hasPages())
                    <div class="mt-8">
                        <x-pagination :paginator="$comments" />
                    </div>
                @endif
            @else
                <p class="text-center text-gray-500 dark:text-gray-400 py-8">
                    暂无评论，来发表第一评论吧！
                </p>
            @endif
        </div>
    </section>
    
    @push('scripts')
    <script>
        // 生成文章目录
        document.addEventListener('DOMContentLoaded', function() {
            const article = document.querySelector('.prose-blog');
            const toc = document.getElementById('toc');
            
            if (!article || !toc) return;
            
            const headings = article.querySelectorAll('h2, h3');
            
            if (headings.length === 0) {
                toc.innerHTML = '<p class="text-sm text-gray-400">暂无目录</p>';
                return;
            }
            
            let html = '';
            headings.forEach((heading, index) => {
                // 添加ID
                if (!heading.id) {
                    heading.id = 'heading-' + index;
                }
                
                const level = heading.tagName.toLowerCase();
                const indent = level === 'h3' ? 'ml-3' : '';
                
                html += `
                    <a href="#${heading.id}" 
                       class="toc-link ${indent}"
                       data-target="${heading.id}">
                        ${heading.textContent}
                    </a>
                `;
            });
            
            toc.innerHTML = html;
            
            // 滚动监听
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    const id = entry.target.getAttribute('id');
                    const link = toc.querySelector(`[data-target="${id}"]`);
                    
                    if (link) {
                        if (entry.isIntersecting) {
                            toc.querySelectorAll('.toc-link').forEach(l => l.classList.remove('toc-link-active'));
                            link.classList.add('toc-link-active');
                        }
                    }
                });
            }, { rootMargin: '-80px 0px -80% 0px' });
            
            headings.forEach(heading => observer.observe(heading));
        });
    </script>
    @endpush
    
</x-layout.app>
