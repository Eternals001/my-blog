{{-- resources/views/components/toast/container.blade.php --}}
{{-- Toast 通知容器 --}}

<div x-data="{ shown: false, message: '', type: 'success' }"
     @toast.window="
         shown = true;
         message = $event.detail.message;
         type = $event.detail.type || 'success';
         setTimeout(() => { shown = false }, 5000);
     "
     x-show="shown"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"
     class="fixed bottom-4 right-4 z-50 max-w-sm">
    
    <div class="flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg"
         :class="{
             'bg-green-50 dark:bg-green-900/50 text-green-800 dark:text-green-200': type === 'success',
             'bg-red-50 dark:bg-red-900/50 text-red-800 dark:text-red-200': type === 'error',
             'bg-yellow-50 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200': type === 'warning',
             'bg-blue-50 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200': type === 'info',
         }">
        
        {{-- 图标 --}}
        <template x-if="type === 'success'">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </template>
        
        <template x-if="type === 'error'">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </template>
        
        <template x-if="type === 'warning'">
            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </template>
        
        <template x-if="type === 'info'">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </template>
        
        {{-- 消息 --}}
        <p class="text-sm font-medium" x-text="message"></p>
        
        {{-- 关闭按钮 --}}
        <button @click="shown = false" class="ml-auto -mr-1 p-1 rounded hover:bg-black/10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
