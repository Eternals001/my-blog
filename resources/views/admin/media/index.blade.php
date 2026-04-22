{{-- resources/views/admin/media/index.blade.php --}}
{{-- 媒体库管理页面 --}}

<x-backend.layout.app title="媒体库">
    
    <div x-data="mediaLibrary()" x-init="init()">
        
        {{-- 页面头部 --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">媒体库</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        管理您的图片、视频和文档文件
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    {{-- 上传按钮 --}}
                    <button @click="showUploader = true" 
                            class="btn-primary inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        上传文件
                    </button>
                </div>
            </div>
        </div>
        
        {{-- 工具栏 --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                
                {{-- 筛选按钮 --}}
                <div class="flex items-center gap-2">
                    <button @click="filter = 'all'" 
                            :class="filter === 'all' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        全部
                    </button>
                    <button @click="filter = 'image'" 
                            :class="filter === 'image' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        图片
                    </button>
                    <button @click="filter = 'video'" 
                            :class="filter === 'video' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        视频
                    </button>
                    <button @click="filter = 'document'" 
                            :class="filter === 'document' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        文档
                    </button>
                </div>
                
                {{-- 搜索框 --}}
                <div class="relative">
                    <input type="text" 
                           x-model="search"
                           placeholder="搜索文件..."
                           class="w-full lg:w-64 pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                {{-- 视图切换 --}}
                <div class="flex items-center gap-2 border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-gray-700 pt-4 lg:pt-0 lg:pl-4">
                    <button @click="view = 'grid'" 
                            :class="view === 'grid' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                            class="p-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button @click="view = 'list'" 
                            :class="view === 'list' ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                            class="p-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        {{-- 拖拽上传区域 --}}
        <div x-show="showUploader" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-4"
             class="mb-6">
            <x-backend.media-uploader 
                :upload-url="route('backend.media.upload')"
                @upload-complete="handleUploadComplete"
                @upload-error="handleUploadError"
                @close="showUploader = false" />
        </div>
        
        {{-- 文件列表 --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            
            {{-- 网格视图 --}}
            <div x-show="view === 'grid'" class="p-6">
                @if($media->isNotEmpty())
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        @foreach($media as $item)
                            <div class="group relative bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:border-primary-300 dark:hover:border-primary-700 transition-all duration-200">
                                {{-- 预览图 --}}
                                <div class="aspect-square relative overflow-hidden">
                                    @if($item->isImage())
                                        <img src="{{ $item->url }}" 
                                             alt="{{ $item->name }}"
                                             loading="lazy"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @elseif($item->isVideo())
                                        <div class="w-full h-full flex items-center justify-center bg-gray-900">
                                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    {{-- 悬停操作 --}}
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        <button @click="copyUrl('{{ $item->url }}')" 
                                                class="p-2 bg-white/20 hover:bg-white/30 rounded-lg text-white transition-colors"
                                                title="复制链接">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                        <a href="{{ $item->url }}" 
                                           target="_blank"
                                           class="p-2 bg-white/20 hover:bg-white/30 rounded-lg text-white transition-colors"
                                           title="查看大图">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                        <button @click="confirmDelete({{ $item->id }})" 
                                                class="p-2 bg-red-500/80 hover:bg-red-500 rounded-lg text-white transition-colors"
                                                title="删除">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                {{-- 文件信息 --}}
                                <div class="p-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate" title="{{ $item->name }}">
                                        {{ $item->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $item->formatted_size }} • {{ $item->created_at->format('Y-m-d') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">暂无媒体文件</p>
                        <button @click="showUploader = true" class="mt-4 text-primary-600 dark:text-primary-400 hover:underline">
                            上传第一个文件
                        </button>
                    </div>
                @endif
            </div>
            
            {{-- 列表视图 --}}
            <div x-show="view === 'list'" class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">预览</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">文件名</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">类型</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">大小</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">上传时间</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">操作</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($media as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800">
                                        @if($item->isImage())
                                            <img src="{{ $item->url }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->mime_type }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($item->isImage())
                                            bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                                        @elseif($item->isVideo())
                                            bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300
                                        @else
                                            bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                        @endif">
                                        {{ $item->type_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $item->formatted_size }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $item->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="copyUrl('{{ $item->url }}')" 
                                                class="p-1.5 text-gray-500 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                                                title="复制链接">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                        <a href="{{ $item->url }}" 
                                           target="_blank"
                                           class="p-1.5 text-gray-500 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                                           title="查看">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <button @click="confirmDelete({{ $item->id }})" 
                                                class="p-1.5 text-gray-500 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                                                title="删除">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="mt-4 text-gray-500 dark:text-gray-400">暂无媒体文件</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- 分页 --}}
            @if($media->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $media->links() }}
                </div>
            @endif
        </div>
        
        {{-- 删除确认弹窗 --}}
        <div x-show="deleteId" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto"
             @keydown.escape.window="deleteId = null">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/50" @click="deleteId = null"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">确认删除</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                            确定要删除这个文件吗？此操作无法撤销。
                        </p>
                        <div class="flex gap-3">
                            <button @click="deleteId = null" class="flex-1 btn-outline">
                                取消
                            </button>
                            <button @click="deleteFile()" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-medium transition-colors">
                                删除
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Toast 通知 --}}
        <div x-show="toast.show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             :class="{
                 'bg-green-500': toast.type === 'success',
                 'bg-red-500': toast.type === 'error',
                 'bg-blue-500': toast.type === 'info'
             }"
             class="fixed bottom-4 right-4 px-6 py-3 rounded-xl text-white shadow-lg z-50">
            <p x-text="toast.message"></p>
        </div>
    </div>
    
    @push('scripts')
    <script>
        function mediaLibrary() {
            return {
                view: 'grid',
                filter: 'all',
                search: '',
                showUploader: false,
                deleteId: null,
                toast: {
                    show: false,
                    type: 'info',
                    message: ''
                },
                
                init() {
                    // 从 URL 参数读取视图
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.get('view')) {
                        this.view = urlParams.get('view');
                    }
                },
                
                copyUrl(url) {
                    navigator.clipboard.writeText(url).then(() => {
                        this.showToast('success', '链接已复制到剪贴板');
                    }).catch(() => {
                        this.showToast('error', '复制失败，请手动复制');
                    });
                },
                
                confirmDelete(id) {
                    this.deleteId = id;
                },
                
                deleteFile() {
                    if (!this.deleteId) return;
                    
                    // 发送删除请求
                    fetch(`/admin/media/${this.deleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              this.showToast('success', '文件已删除');
                              setTimeout(() => {
                                  window.location.reload();
                              }, 1000);
                          } else {
                              this.showToast('error', data.message || '删除失败');
                          }
                      })
                      .catch(error => {
                          this.showToast('error', '删除失败');
                      });
                    
                    this.deleteId = null;
                },
                
                handleUploadComplete(data) {
                    this.showToast('success', '文件上传成功');
                    this.showUploader = false;
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                },
                
                handleUploadError(message) {
                    this.showToast('error', message || '上传失败');
                },
                
                showToast(type, message) {
                    this.toast = { show: true, type, message };
                    setTimeout(() => {
                        this.toast.show = false;
                    }, 3000);
                }
            }
        }
    </script>
    @endpush
</x-backend.layout.app>
