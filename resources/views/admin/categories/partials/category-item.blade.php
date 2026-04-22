{{-- resources/views/admin/categories/partials/category-item.blade.php --}}
{{-- 分类树形项组件 --}}

@props([
    'category',
    'depth' => 0
])

@php
    $hasChildren = $category->children && $category->children->count() > 0;
    $itemPaddingLeft = 24 + ($depth * 24);
@endphp

<div class="category-item group" 
     data-id="{{ $category->id }}"
     data-parent-id="{{ $category->parent_id ?? '0' }}"
     draggable="true"
     x-data="{ expanded: true }"
     @dragstart="$dispatch('dragstart', { category: {{ $category->id }} })"
     @dragover="$dispatch('dragover', { category: {{ $category->id }} })"
     @drop="$dispatch('drop', { category: {{ $category->id }} })">

    <div class="flex items-center py-3 px-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150 group"
         style="padding-left: {{ $itemPaddingLeft }}px;"
         :class="{ 'bg-gray-50 dark:bg-gray-800/50': '{{ $category->id }}' === '{{ $dragOverItem ?? '' }}' }">
        
        {{-- 拖拽手柄 --}}
        <div class="cursor-move mr-3 text-gray-300 dark:text-gray-600 hover:text-gray-500 dark:hover:text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
            </svg>
        </div>

        {{-- 展开/收起按钮 --}}
        @if($hasChildren)
            <button @click="expanded = !expanded" 
                    class="mr-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="w-5 h-5 transform transition-transform duration-200" 
                     :class="{ 'rotate-90': expanded }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        @else
            <div class="w-5 mr-2"></div>
        @endif

        {{-- 分类图标 --}}
        <div class="w-10 h-10 rounded-lg bg-{{ $category->color ?? 'primary' }}-100 dark:bg-{{ $category->color ?? 'primary' }}-900/30 flex items-center justify-center mr-4">
            <span class="text-{{ $category->color ?? 'primary' }}-600 dark:text-{{ $category->color ?? 'primary' }}-400 font-semibold text-sm">
                {{ Str::substr($category->name, 0, 1) }}
            </span>
        </div>

        {{-- 分类信息 --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <h4 class="font-medium text-gray-900 dark:text-white truncate">
                    {{ $category->name }}
                </h4>
                @if($category->is_active === false)
                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                        隐藏
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-3 mt-1 text-xs text-gray-500 dark:text-gray-400">
                <span>/{{ $category->slug }}/</span>
                @if($category->description)
                    <span class="truncate max-w-xs">{{ Str::limit($category->description, 30) }}</span>
                @endif
            </div>
        </div>

        {{-- 文章数 --}}
        <div class="hidden sm:flex items-center gap-1 mr-6 text-sm text-gray-500 dark:text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            <span>{{ $category->posts_count ?? 0 }}</span>
        </div>

        {{-- 排序 --}}
        <div class="hidden md:flex items-center gap-1 mr-4 text-sm text-gray-500 dark:text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
            <span>{{ $category->order ?? 0 }}</span>
        </div>

        {{-- 操作按钮 --}}
        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
            {{-- 添加子分类 --}}
            <a href="{{ route('admin.categories.create', ['parent_id' => $category->id]) }}"
               class="p-2 text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors"
               title="添加子分类">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </a>
            
            {{-- 编辑 --}}
            <a href="{{ route('admin.categories.edit', $category) }}"
               class="p-2 text-gray-400 hover:text-accent-600 dark:hover:text-accent-400 rounded-lg hover:bg-accent-50 dark:hover:bg-accent-900/20 transition-colors"
               title="编辑分类">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>
            
            {{-- 删除 --}}
            @if(!$hasChildren)
                <form action="{{ route('admin.categories.destroy', $category) }}" 
                      method="POST" 
                      class="inline"
                      onsubmit="return confirm('确定要删除分类「{{ $category->name }}」吗？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                            title="删除分类">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- 子分类 --}}
    @if($hasChildren)
        <div x-show="expanded" x-collapse>
            @each('admin.categories.partials.category-item', $category->children, 'category', '')
        </div>
    @endif
</div>
