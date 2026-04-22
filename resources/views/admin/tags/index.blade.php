{{-- resources/views/admin/tags/index.blade.php --}}
{{-- 标签管理列表页面 --}}

<x-backend.layouts.app title="标签管理">

    {{-- 页面标题 --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    标签管理
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    管理博客文章的标签
                </p>
            </div>
            <a href="{{ route('admin.tags.create') }}" 
               class="btn-primary inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                新建标签
            </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- 标签列表 --}}
        <div class="lg:col-span-3">
            <div class="card overflow-hidden">
                {{-- 搜索和筛选 --}}
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <form method="GET" action="{{ route('admin.tags.index') }}" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="q"
                                       value="{{ request('q') }}"
                                       placeholder="搜索标签名称或别名..."
                                       class="input pl-10">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="btn-primary">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                搜索
                            </button>
                            @if(request('q'))
                                <a href="{{ route('admin.tags.index') }}" class="btn-secondary">
                                    清除
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- 标签列表 --}}
                <div class="p-4">
                    @if($tags->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">暂无标签</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">创建一个标签来标记您的文章</p>
                            <a href="{{ route('admin.tags.create') }}" class="btn-primary inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                创建第一个标签
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <th class="px-4 py-3">ID</th>
                                        <th class="px-4 py-3">名称</th>
                                        <th class="px-4 py-3">别名</th>
                                        <th class="px-4 py-3">文章数</th>
                                        <th class="px-4 py-3">创建时间</th>
                                        <th class="px-4 py-3 text-right">操作</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($tags as $tag)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $tag->id }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                        </svg>
                                                    </div>
                                                    <span class="font-medium text-gray-900 dark:text-white">
                                                        {{ $tag->name }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                <code class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-xs">
                                                    /{{ $tag->slug }}/
                                                </code>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400">
                                                    {{ $tag->published_posts_count ?? 0 }} 篇文章
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $tag->created_at->format('Y-m-d') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <a href="{{ route('admin.tags.edit', $tag) }}"
                                                       class="p-2 text-gray-400 hover:text-accent-600 dark:hover:text-accent-400 rounded-lg hover:bg-accent-50 dark:hover:bg-accent-900/20 transition-colors"
                                                       title="编辑">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('admin.tags.destroy', $tag) }}" 
                                                          method="POST" 
                                                          class="inline"
                                                          onsubmit="return confirm('确定要删除标签「{{ $tag->name }}」吗？');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                                                title="删除">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- 分页 --}}
                        <div class="mt-4">
                            {{ $tags->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 标签云侧边栏 --}}
        <div class="lg:col-span-1">
            <div class="card p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    标签云
                </h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($popularTags ?? [] as $tag)
                        <a href="{{ route('admin.tags.edit', $tag) }}" 
                           class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium transition-colors
                                  @if(($tag->published_posts_count ?? 0) > 10)
                                      bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400 hover:bg-primary-200 dark:hover:bg-primary-900/50
                                  @elseif(($tag->published_posts_count ?? 0) > 5)
                                      bg-accent-100 text-accent-800 dark:bg-accent-900/30 dark:text-accent-400 hover:bg-accent-200 dark:hover:bg-accent-900/50
                                  @else
                                      bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700
                                  @endif">
                            {{ $tag->name }}
                            <span class="text-xs opacity-70">({{ $tag->published_posts_count ?? 0 }})</span>
                        </a>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">暂无标签</p>
                    @endforelse
                </div>
                
                {{-- 统计信息 --}}
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <dl class="space-y-2">
                        <div class="flex justify-between items-center">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">标签总数</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $tags->total() }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">热门标签</dt>
                            <dd class="text-sm font-medium text-primary-600 dark:text-primary-400">
                                {{ $popularTags->first()?->name ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

</x-backend.layouts.app>
