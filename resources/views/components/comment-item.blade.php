{{-- resources/views/components/comment-item.blade.php --}}
{{-- 评论项组件 --}}

@props([
    'comment',
    'post' => null,
    'depth' => 0,
    'maxDepth' => 3,
])

@php
    $isAdmin = $comment->user && $comment->user->is_admin;
    $canReply = $depth < $maxDepth && auth()->check();
@endphp

<article class="comment-item" 
         id="comment-{{ $comment->id }}"
         data-comment-id="{{ $comment->id }}">
    
    {{-- 评论主体 --}}
    <div class="flex gap-4">
        {{-- 头像 --}}
        <div class="flex-shrink-0">
            <img src="{{ $comment->user->avatar ?? asset('images/default-avatar.png') }}" 
                 alt="{{ $comment->user->name ?? '访客' }}"
                 class="avatar-md">
        </div>
        
        {{-- 评论内容 --}}
        <div class="flex-1 min-w-0">
            {{-- 用户信息行 --}}
            <div class="flex items-center flex-wrap gap-x-3 gap-y-1">
                <span class="font-medium text-gray-900 dark:text-white">
                    {{ $comment->user->name ?? '访客' }}
                </span>
                
                @if($isAdmin)
                    <span class="badge-primary text-xs">博主</span>
                @endif
                
                <time class="text-sm text-gray-500 dark:text-gray-400" 
                      datetime="{{ $comment->created_at->toIso8601String() }}">
                    {{ $comment->created_at->diffForHumans() }}
                </time>
                
                @if($comment->created_at->diffForHumans() !== $comment->updated_at->diffForHumans())
                    <span class="text-xs text-gray-400">(已编辑)</span>
                @endif
            </div>
            
            {{-- 评论内容 --}}
            <div class="mt-2 text-gray-700 dark:text-gray-300 prose-sm dark:prose-invert max-w-none">
                {!! $comment->content !!}
            </div>
            
            {{-- 操作按钮 --}}
            <div class="comment-actions mt-3">
                {{-- 点赞 --}}
                <button type="button" 
                        class="flex items-center gap-1 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                        wire:click="likeComment({{ $comment->id }})">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span>{{ $comment->likes_count ?? 0 }}</span>
                </button>
                
                {{-- 回复 --}}
                @if($canReply)
                    <button type="button" 
                            class="flex items-center gap-1 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                            @click="$dispatch('reply-to', { commentId: {{ $comment->id }}, userName: '{{ $comment->user->name }}' })">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        回复
                    </button>
                @endif
                
                {{-- 编辑（仅作者） --}}
                @can('update', $comment)
                    <button type="button" 
                            class="flex items-center gap-1 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                            @click="$dispatch('edit-comment', { commentId: {{ $comment->id }} })">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        编辑
                    </button>
                @endcan
                
                {{-- 删除（仅作者或管理员） --}}
                @can('delete', $comment)
                    <button type="button" 
                            class="flex items-center gap-1 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                            wire:click="deleteComment({{ $comment->id }})"
                            wire:confirm="确定要删除这条评论吗？">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        删除
                    </button>
                @endcan
            </div>
        </div>
    </div>
    
    {{-- 回复列表 --}}
    @if($comment->replies && $comment->replies->count() > 0)
        <div class="comment-thread mt-6">
            @foreach($comment->replies as $reply)
                <x-comment-item :comment="$reply" 
                               :post="$post" 
                               :depth="$depth + 1" 
                               :maxDepth="$maxDepth" />
            @endforeach
        </div>
    @endif
</article>
