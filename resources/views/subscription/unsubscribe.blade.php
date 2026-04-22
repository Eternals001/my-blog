{{-- resources/views/subscription/unsubscribe.blade.php --}}
{{-- 退订确认页面 --}}

<x-layout.app 
    title="取消订阅"
    robots="noindex, nofollow">

    <section class="min-h-[70vh] flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            
            {{-- 退订确认卡片 --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 text-center">
                
                {{-- 成功状态 --}}
                @if(session('success'))
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                            <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                            已成功退订
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            您已成功取消订阅，我们将不再向您发送邮件通知。
                        </p>
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 mb-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                如果您改变主意，随时可以重新订阅。
                            </p>
                        </div>
                    </div>
                    
                    <a href="{{ route('home') }}" class="btn-primary inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        返回首页
                    </a>
                {{-- 确认退订 --}}
                @elseif(isset($subscription))
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                            <svg class="w-10 h-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                            确认取消订阅
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">
                            您确定要取消订阅以下邮箱吗？
                        </p>
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 mb-6">
                            <p class="font-mono text-primary-600 dark:text-primary-400 font-medium">
                                {{ $subscription->email }}
                            </p>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            退订后，您将不再收到我们的更新通知。
                        </p>
                    </div>
                    
                    <form action="{{ route('subscribe.unsubscribe') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="email" value="{{ $subscription->email }}">
                        <input type="hidden" name="token" value="{{ $subscription->token }}">
                        
                        <div class="flex gap-3">
                            <a href="{{ route('home') }}" class="flex-1 btn-outline">
                                取消
                            </a>
                            <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                                确认退订
                            </button>
                        </div>
                    </form>
                {{-- 无效链接 --}}
                @else
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-yellow-100 dark:bg-yellow-900/30 mb-4">
                            <svg class="w-10 h-10 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                            链接已失效
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            此退订链接已过期或无效，请检查邮件中的链接是否正确。
                        </p>
                    </div>
                    
                    <a href="{{ route('home') }}" class="btn-primary inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        返回首页
                    </a>
                @endif
            </div>
        </div>
    </section>

</x-layout.app>
