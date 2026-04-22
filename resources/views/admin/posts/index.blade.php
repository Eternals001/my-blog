{{-- resources/views/admin/posts/index.blade.php --}}
{{-- 后台文章列表页面 --}}

@extends('backend.layouts.app')

@section('title', '文章管理')

@push('styles')
<style>
    /* 自定义样式 */
    .status-dot {
        @apply inline-block w-2 h-2 rounded-full;
    }
    .status-dot-draft { @apply bg-gray-500; }
    .status-dot-published { @apply bg-green-500; }
    .status-dot-scheduled { @apply bg-yellow-500; }
    .status-dot-private { @apply bg-red-500; }
</style>
@endpush

@section('content')
<div class="animate-fade-in">
    {{-- 页面标题 --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">文章管理</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                共 {{ $posts->total() }} 篇文章
            </p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            新建文章
        </a>
    </div>

    {{-- 筛选表单 --}}
    <div class="card p-4 mb-6">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
                           placeholder="搜索文章标题..."
                           class="input pl-10">
                </div>
            </div>

            {{-- 状态筛选 --}}
            <div>
                <label for="status" class="sr-only">状态</label>
                <select id="status" name="status" class="input">
                    <option value="">全部状态</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>草稿</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>已发布</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>定时发布</option>
                    <option value="private" {{ request('status') == 'private' ? 'selected' : '' }}>私有</option>
                </select>
            </div>

            {{-- 分类筛选 --}}
            <div>
                <label for="category" class="sr-only">分类</label>
                <select id="category" name="category" class="input">
                    <option value="">全部分类</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 提交按钮 --}}
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1">
                    筛选
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn-secondary">
                    重置
                </a>
            </div>
        </form>
    </div>

    {{-- 批量操作工具栏 --}}
    <div x-data="{ selected: [], selectAll: false }" x-show="selected.length > 0" class="mb-4">
        <div class="bg-primary-500 text-white rounded-lg p-4 flex items-center justify-between">
            <span class="font-medium">已选择 <span x-text="selected.length"></span> 篇文章</span>
            <div class="flex items-center gap-3">
                <button @click="
                    fetch('{{ route('admin.posts.batch-update') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ ids: selected, action: 'publish' })
                    }).then(() => location.reload())
                " class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-colors">
                    批量发布
                </button>
                <button @click="
                    if(confirm('确定要删除选中的文章吗？')) {
                        fetch('{{ route('admin.posts.batch-delete') }}', {
                            method: 'DELETE',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ ids: selected })
                        }).then(() => location.reload())
                    }
                " class="px-3 py-1.5 bg-red-500 hover:bg-red-600 rounded-lg text-sm transition-colors">
                    批量删除
                </button>
            </div>
        </div>
    </div>

    {{-- 文章列表 --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left w-10">
                            <input type="checkbox" 
                                   x-model="selectAll"
                                   @change="
                                       if(selectAll) {
                                           selected = {{ $posts->pluck('id')->toJson() }};
                                       } else {
                                           selected = [];
                                       }
                                   "
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            标题
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            分类
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            标签
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            状态
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            浏览量
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            发布时间
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <input type="checkbox" 
                                       x-model="selected"
                                       value="{{ $post->id }}"
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="block group">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                        {{ $post->title }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs mt-1">
                                        {{ $post->excerpt }}
                                    </p>
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                @if($post->category)
                                    <a href="{{ route('categories.show', $post->category->slug) }}" 
                                       class="badge-secondary text-xs">
                                        {{ $post->category->name }}
                                    </a>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($post->tags->take(3) as $tag)
                                        <span class="badge-primary text-xs">{{ $tag->name }}</span>
                                    @empty
                                        <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                    @endforelse
                                    @if($post->tags->count() > 3)
                                        <span class="text-xs text-gray-500">+{{ $post->tags->count() - 3 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="status-dot status-dot-{{ $post->status->value }}"></span>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $post->status->label() }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1 text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ number_format($post->view_count) }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $post->published_at?->format('Y-m-d H:i') ?: '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- 预览按钮 --}}
                                    <a href="{{ route('posts.show', $post) }}" 
                                       target="_blank"
                                       class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                       title="预览">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    {{-- 编辑按钮 --}}
                                    <a href="{{ route('admin.posts.edit', $post) }}" 
                                       class="p-2 text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                                       title="编辑">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    
                                    {{-- 删除按钮 --}}
                                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('确定要删除这篇文章吗？')"
                                                class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                                                title="删除">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">还没有文章</p>
                                    <a href="{{ route('admin.posts.create') }}" class="btn-primary">
                                        写第一篇文章
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- 分页 --}}
        @if($posts->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                {{ $posts->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('postList', () => ({
        selected: [],
        selectAll: false,
    }));
});
</script>
@endpush
