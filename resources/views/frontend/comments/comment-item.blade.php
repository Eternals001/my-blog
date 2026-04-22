{{-- resources/views/frontend/comments/comment-item.blade.php --}}
{{-- 评论项组件 --}}

@php
    $avatarColors = [
        'bg-red-500', 'bg-orange-500', 'bg-amber-500', 'bg-yellow-500',
        'bg-lime-500', 'bg-green-500', 'bg-emerald-500', 'bg-teal-500',
        'bg-cyan-500', 'bg-sky-500', 'bg-blue-500', 'bg-indigo-500',
        'bg-violet-500', 'bg-purple-500', 'bg-fuchsia-500', 'bg-pink-500',
    ];
    $colorIndex = crc32($comment->email ?? $comment->display_name) % count($avatarColors);
    $avatarColor = $avatarColors[$colorIndex];
    $initial = mb_substr($comment->display_name ?? '访客', 0, 1);
@endphp

<div class="comment-item" id="comment-{{ $comment->id }}">
    <div class="flex items-start gap-4">
        {{-- 头像 --}}
        @if($comment->avatar_url)
            <img src="{{ $comment->avatar_url }}" 
                 alt="{{ $comment->display_name }}"
                 class="w-10 h-10 rounded-full object-cover flex-shrink-0">
        @else
            <div class="w-10 h-10 rounded-full {{ $avatarColor }} flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                {{ strtoupper($initial) }}
            </div>
        @endif
        
        <div class="flex-1 min-w-0">
            {{-- 评论头部 --}}
            <div class="flex items-center flex-wrap gap-x-3 gap-y-1 mb-2">
                <span class="font-semibold text-gray-900 dark:text-white">
                    {{ $comment->display_name }}
                </span>
                @if($comment->is_author)
                    <span class="px-2 py-0.5 text-xs font-medium bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-full">
                        作者
                    </span>
                @endif
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $comment->created_at?->diffForHumans() }}
                </span>
            </div>
            
            {{-- 评论内容 --}}
            <div class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                {!! nl2br(e($comment->content)) !!}
            </div>
            
            {{-- 操作按钮 --}}
            <div class="flex items-center gap-4 mt-3">
                @auth
                    <button onclick="toggleReplyForm({{ $comment->id }})" 
                            class="text-xs text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        回复
                    </button>
                @endauth
                
                @if($comment->ip_address && auth()->check() && auth()->user()->is_admin)
                    <span class="text-xs text-gray-400 dark:text-gray-600">
                        IP: {{ $comment->ip_address }}
                    </span>
                @endif
            </div>
            
            {{-- 回复表单 --}}
            @auth
                <div id="reply-form-{{ $comment->id }}" class="hidden mt-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                    <form action="{{ route('comments.reply', [$comment->post, $comment]) }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="text" name="display_name" value="{{ auth()->user()->name }}" required
                                   placeholder="昵称"
                                   class="input text-sm">
                            <input type="email" name="email" value="{{ auth()->user()->email }}" required
                                   placeholder="邮箱"
                                   class="input text-sm">
                        </div>
                        <textarea name="content" rows="2" required
                                  placeholder="写下你的回复..."
                                  class="input text-sm resize-none"></textarea>
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500 dark:text-gray-400">@提醒 <strong>{{ $comment->display_name }}</strong></p>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="toggleReplyForm({{ $comment->id }})" 
                                        class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                    取消
                                </button>
                                <button type="submit" class="btn-primary text-sm py-2">
                                    发布回复
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endauth
            
            {{-- 子回复 --}}
            @if($comment->replies && $comment->replies->isNotEmpty())
                <div class="mt-6 pl-4 border-l-2 border-gray-100 dark:border-gray-800 space-y-6">
                    @foreach($comment->replies as $reply)
                        @include('frontend.comments.comment-item', ['comment' => $reply])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleReplyForm(commentId) {
        const form = document.getElementById(`reply-form-${commentId}`);
        if (form) {
            form.classList.toggle('hidden');
        }
    }
</script>
@endpush
