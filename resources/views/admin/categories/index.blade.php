{{-- resources/views/admin/categories/index.blade.php --}}
{{-- 分类管理列表页面 --}}

<x-backend.layouts.app title="分类管理">

    {{-- 页面标题 --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    分类管理
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    管理博客文章的分类，支持树形结构展示和拖拽排序
                </p>
            </div>
            <a href="{{ route('admin.categories.create') }}" 
               class="btn-primary inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                新建分类
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

    {{-- 分类列表 --}}
    <div class="card overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        共 {{ $categories->count() }} 个分类
                    </span>
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    拖拽分类可调整排序，拖入其他分类可设置父子关系
                </div>
            </div>
        </div>
        
        <div class="p-4" x-data="categoryTree()">
            @if($categories->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">暂无分类</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">创建一个分类来组织您的文章</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn-primary inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        创建第一个分类
                    </a>
                </div>
            @else
                <div class="space-y-1">
                    @each('admin.categories.partials.category-item', $categories, 'category')
                </div>
            @endif
        </div>
    </div>

</x-backend.layouts.app>

@push('scripts')
<script>
    function categoryTree() {
        return {
            // 拖拽排序功能
            draggedItem: null,
            dragOverItem: null,
            
            dragStart(event, category) {
                this.draggedItem = category;
                event.dataTransfer.effectAllowed = 'move';
                event.target.classList.add('opacity-50');
            },
            
            dragEnd(event) {
                event.target.classList.remove('opacity-50');
                this.draggedItem = null;
                this.dragOverItem = null;
            },
            
            dragOver(event, category) {
                event.preventDefault();
                event.dataTransfer.dropEffect = 'move';
                this.dragOverItem = category;
            },
            
            drop(event, targetCategory) {
                event.preventDefault();
                if (this.draggedItem && this.draggedItem.id !== targetCategory.id) {
                    // 发送请求更新分类
                    fetch(`/admin/categories/${this.draggedItem.id}/move`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            parent_id: targetCategory.parent_id,
                            order: targetCategory.order
                        })
                    }).then(() => {
                        window.location.reload();
                    });
                }
            },
            
            // 展开/收起子分类
            toggleChildren(categoryId) {
                const children = document.querySelectorAll(`[data-parent-id="${categoryId}"]`);
                children.forEach(child => {
                    child.classList.toggle('hidden');
                });
            }
        }
    }
</script>
@endpush
