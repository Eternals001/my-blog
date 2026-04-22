{{-- resources/views/components/partials/search-modal.blade.php --}}
{{-- 搜索模态框组件 --}}

<div x-data="search()"
     @open-search.window="toggle()"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     role="dialog"
     aria-modal="true"
     aria-label="搜索">
    
    {{-- 背景遮罩 --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm">
    </div>
    
    {{-- 模态框内容 --}}
    <div class="relative min-h-screen flex items-start justify-center pt-[10vh] px-4">
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.stop
             class="relative w-full max-w-2xl bg-white dark:bg-gray-900 rounded-2xl shadow-2xl overflow-hidden">
            
            {{-- 搜索输入框 --}}
            <div class="flex items-center gap-3 px-4 border-b border-gray-200 dark:border-gray-700">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       x-model="query"
                       x-ref="searchInput"
                       @keydown.escape.window="close()"
                       @keydown.down.prevent="$dispatch('search-focus-next')"
                       @keydown.up.prevent="$dispatch('search-focus-prev')"
                       placeholder="搜索文章..."
                       class="flex-1 py-4 bg-transparent border-0 focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400 text-lg"
                       autocomplete="off">
                
                <kbd class="hidden sm:inline-flex items-center gap-1 px-2 py-1 text-xs text-gray-400 bg-gray-100 dark:bg-gray-800 rounded">
                    ESC
                </kbd>
                
                <button @click="close()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            {{-- 搜索结果 --}}
            <div class="max-h-96 overflow-y-auto">
                
                {{-- 加载状态 --}}
                <div x-show="$wire.isLoading" class="p-8 text-center">
                    <div class="inline-flex items-center gap-2 text-gray-500">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>搜索中...</span>
                    </div>
                </div>
                
                {{-- 搜索结果列表 --}}
                <div x-show="!$wire.isLoading && results.length > 0" class="py-2">
                    <template x-for="(result, index) in results" :key="result.id">
                        <a :href="result.url"
                           @click="close()"
                           class="flex items-start gap-4 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                           :class="{ 'bg-gray-50 dark:bg-gray-800': index === focusedIndex }">
                            {{-- 缩略图 --}}
                            <template x-if="result.thumbnail">
                                <img :src="result.thumbnail" 
                                     :alt="result.title"
                                     class="w-16 h-12 rounded-lg object-cover bg-gray-200">
                            </template>
                            <template x-if="!result.thumbnail">
                                <div class="w-16 h-12 rounded-lg bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/50 dark:to-primary-800/50 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </template>
                            
                            {{-- 内容 --}}
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="result.title"></h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2" x-text="result.excerpt"></p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="badge-gray" x-text="result.category"></span>
                                    <span class="text-xs text-gray-400" x-text="result.date"></span>
                                </div>
                            </div>
                        </a>
                    </template>
                </div>
                
                {{-- 无结果 --}}
                <div x-show="!$wire.isLoading && query.length > 0 && results.length === 0" class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">未找到与 "<span x-text="query"></span>" 相关的文章</p>
                </div>
                
                {{-- 搜索提示 --}}
                <div x-show="!$wire.isLoading && query.length === 0" class="p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">快捷键</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <kbd class="font-mono">/</kbd> 快速搜索
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <kbd class="font-mono">↑↓</kbd> 选择
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <kbd class="font-mono">Enter</kbd> 打开
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <kbd class="font-mono">Esc</kbd> 关闭
                        </span>
                    </div>
                </div>
            </div>
            
            {{-- 底部统计 --}}
            <div x-show="results.length > 0" class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>找到 <span x-text="results.length"></span> 个结果</span>
                    <a href="#" @click.prevent="goToSearch()" class="hover:text-primary-600 dark:hover:text-primary-400">
                        查看全部 →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
