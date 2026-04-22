{{-- resources/views/admin/comments/index.blade.php --}}
{{-- 评论审核列表页面 --}}

<x-backend.layouts.app title="评论管理">

    {{-- 页面标题 --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    评论管理
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    审核和管理用户评论
                </p>
            </div>
            <div class="flex items-center gap-3">
                @if($pendingCount > 0)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        {{ $pendingCount }} 条待审核
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- 操作提示 --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- 筛选表单 --}}
    <div class="card p-4 mb-6">
        <form method="GET" action="{{ route('admin.comments.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            {{-- 搜索框 --}}
            <div class="md:col-span-2">
                <label for="search" class="sr-only">搜索</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           id="search" 
                           name="q" 
                           value="{{ request('q') }}"
                           placeholder="搜索评论内容..."
                           class="input pl-10">
                </div>
            </div>

            {{-- 状态筛选 --}}
            <div>
                <label for="status" class="sr-only">状态</label>
                <select id="status" name="status" class="input">
                    <option value="">全部状态</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>待审核</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>已通过</option>
                    <option value="spam" {{ request('status') === 'spam' ? 'selected' : '' }}>垃圾评论</option>
                </select>
            </div>

            {{-- 文章筛选 --}}
            <div>
                <label for="post" class="sr-only">文章</label>
                <select id="post" name="post_id" class="input">
                    <option value="">全部文章</option>
                    @foreach($posts ?? [] as $post)
                        <option value="{{ $post->id }}" {{ request('post_id') == $post->id ? 'selected' : '' }}>
                            {{ Str::limit($post->title, 30) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 按钮 --}}
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    筛选
                </button>
                @if(request()->anyFilled(['q', 'status', 'post_id']))
                    <a href="{{ route('admin.comments.index') }}" class="btn-secondary">
                        重置
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- 评论列表 --}}
    <div class="card overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    共 {{ $comments->total() }} 条评论
                </span>
                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                        待审核
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        已通过
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        垃圾评论
                    </span>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($comments as $comment)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <div class="flex gap-4">
                        {{-- 头像 --}}
                        <div class="flex-shrink-0">
                            <img src="{{ $comment->avatar_url ?? asset('images/default-avatar.png') }}" 
                                 alt="{{ $comment->display_name }}"
                                 class="w-10 h-10 rounded-full object-cover">
                        </div>

                        {{-- 评论内容 --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            {{ $comment->display_name }}
                                        </span>
                                        @if($comment->user)
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                                                认证用户
                                            </span>
                                        @endif
                                        <span class="px-2 py-0.5 text-xs rounded-full 
                                            @if($comment->status === 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                            @elseif($comment->status === 'approved') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                            @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                            @endif">
                                            @if($comment->status === 'pending') 待审核
                                            @elseif($comment->status === 'approved') 已通过
                                            @else 垃圾评论 @endif
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $comment->created_at->format('Y-m-d H:i') }}
                                        @if($comment->ip)
                                            · IP: {{ $comment->ip }}
                                        @endif
                                    </p>
                                </div>

                                {{-- 操作按钮 --}}
                                <div class="flex items-center gap-1">
                                    @if($comment->status !== 'approved')
                                        <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2 text-gray-400 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors"
                                                    title="通过">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                        </button>
                                    @endif
                                    
                                    @if($comment->status !== 'spam')
                                        <form action="{{ route('admin.comments.spam', $comment) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                                    title="标记为垃圾">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                </svg>
                                        </button>
                                    @endif
                                    
                                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('确定要删除这条评论吗？')"
                                                class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                                title="删除">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- 评论正文 --}}
                            <div class="mt-3">
                                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                                    {{ $comment->content }}
                                </p>
                            </div>

                            {{-- 所属文章 --}}
                            @if($comment->post)
                                <div class="mt-3 flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <a href="{{ route('posts.show', $comment->post->slug) }}" 
                                       target="_blank"
                                       class="text-primary-600 dark:text-primary-400 hover:underline truncate max-w-xs">
                                        {{ Str::limit($comment->post->title, 50) }}
                                    </a>
                                </div>
                            @endif

                            {{-- 回复内容 --}}
                            @if($comment->parent)
                                <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                        </svg>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            回复 {{ $comment->parent->display_name }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ Str::limit($comment->parent->content, 100) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">暂无评论</h3>
                    <p class="text-gray-500 dark:text-gray-400">没有找到符合条件的评论</p>
                </div>
            @endforelse
        </div>

        {{-- 分页 --}}
        @if($comments->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $comments->withQueryString()->links() }}
            </div>
        @endif
    </div>

</x-backend.layouts.app
