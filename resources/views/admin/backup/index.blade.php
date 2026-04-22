{{-- resources/views/admin/backup/index.blade.php --}}
{{-- 备份管理页面 --}}

<x-backend.layout.app title="备份管理">
    
    <div x-data="backupManager()">
        
        {{-- 页面头部 --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">备份管理</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        管理数据库和文件的备份
                    </p>
                </div>
                <button @click="createBackup()" 
                        :disabled="creating"
                        class="btn-primary inline-flex items-center gap-2 disabled:opacity-50">
                    <template x-if="creating">
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <template x-if="!creating">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </template>
                    创建新备份
                </button>
            </div>
        </div>
        
        {{-- 统计信息 --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $backups->count() }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">备份文件</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSize }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">总大小</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $latestBackup ?? '无' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">最新备份</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- 备份列表 --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                文件名
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                类型
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                大小
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                创建时间
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                操作
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($backups as $backup)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            @if(Str::contains($backup['filename'], '.zip'))
                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $backup['filename'] }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $backup['path'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $type = match(true) {
                                            Str::contains($backup['filename'], '_database') => ['label' => '数据库', 'color' => 'blue'],
                                            Str::contains($backup['filename'], '_files') => ['label' => '文件', 'color' => 'green'],
                                            default => ['label' => '完整', 'color' => 'purple']
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @switch($type['color'])
                                            @case('blue')
                                                bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                                                @break
                                            @case('green')
                                                bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                                @break
                                            @default
                                                bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300
                                        @endswitch">
                                        {{ $type['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $backup['size'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $backup['created_at'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('backend.backups.download', ['filename' => $backup['filename']]) }}" 
                                           class="p-1.5 text-gray-500 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="下载">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                        <button @click="confirmDelete('{{ $backup['filename'] }}')" 
                                                class="p-1.5 text-gray-500 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
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
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                    </svg>
                                    <p class="mt-4 text-gray-500 dark:text-gray-400">暂无备份文件</p>
                                    <button @click="createBackup()" class="mt-4 text-primary-600 dark:text-primary-400 hover:underline">
                                        创建第一个备份
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- 删除确认弹窗 --}}
        <div x-show="deleteFilename" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto"
             @keydown.escape.window="deleteFilename = null">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/50" @click="deleteFilename = null"></div>
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
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">确认删除备份</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                            确定要删除备份文件 <span class="font-mono font-medium" x-text="deleteFilename"></span> 吗？此操作无法撤销。
                        </p>
                        <div class="flex gap-3">
                            <button @click="deleteFilename = null" class="flex-1 btn-outline">
                                取消
                            </button>
                            <button @click="deleteBackup()" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-medium transition-colors">
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
        function backupManager() {
            return {
                creating: false,
                deleteFilename: null,
                toast: {
                    show: false,
                    type: 'info',
                    message: ''
                },
                
                async createBackup() {
                    this.creating = true;
                    this.showToast('info', '正在创建备份，请稍候...');
                    
                    try {
                        const response = await fetch('{{ route('backend.backups.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.showToast('success', '备份创建成功');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            this.showToast('error', data.message || '备份创建失败');
                        }
                    } catch (error) {
                        this.showToast('error', '备份创建失败，请重试');
                    }
                    
                    this.creating = false;
                },
                
                confirmDelete(filename) {
                    this.deleteFilename = filename;
                },
                
                async deleteBackup() {
                    if (!this.deleteFilename) return;
                    
                    try {
                        const response = await fetch(`/admin/backup/${this.deleteFilename}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.showToast('success', '备份已删除');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            this.showToast('error', data.message || '删除失败');
                        }
                    } catch (error) {
                        this.showToast('error', '删除失败，请重试');
                    }
                    
                    this.deleteFilename = null;
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
