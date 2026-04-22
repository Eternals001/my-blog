{{-- resources/views/subscription/subscribe.blade.php --}}
{{-- 订阅页面 --}}

<x-layout.app 
    title="订阅更新"
    metaDescription="订阅博客，第一时间获取最新文章和技术分享"
    robots="noindex, nofollow">

    {{-- Hero 区域 --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-accent-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%239C92AC%22%20fill-opacity%3D%220.05%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E')] opacity-50"></div>
        
        <div class="relative container-blog py-16 lg:py-24">
            <div class="max-w-2xl mx-auto">
                
                {{-- 页面标题 --}}
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-primary-500 to-accent-500 text-white mb-6 shadow-xl shadow-primary-500/25">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        订阅博客更新
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        第一时间获取最新文章、技术分享和博客动态
                    </p>
                </div>
                
                {{-- 订阅表单卡片 --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-200 dark:border-gray-700 p-8 lg:p-10">
                    
                    {{-- 成功提示 --}}
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-green-800 dark:text-green-300">订阅成功！</p>
                                    <p class="text-sm text-green-700 dark:text-green-400">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    {{-- 提示信息 --}}
                    @if(session('info'))
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-blue-800 dark:text-blue-300">{{ session('info') }}</p>
                            </div>
                        </div>
                    @endif
                    
                    {{-- 错误提示 --}}
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    @foreach($errors->all() as $error)
                                        <p class="text-red-800 dark:text-red-300">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    {{-- 订阅表单 --}}
                    <form action="{{ route('subscribe.subscribe') }}" method="POST" x-data="{ loading: false }" @submit="$refs.submitBtn.disabled = true; loading = true">
                        @csrf
                        
                        <div class="space-y-6">
                            {{-- 邮箱输入 --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    邮箱地址 <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                        </svg>
                                    </div>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           required
                                           placeholder="your@email.com"
                                           x-ref="submitBtn"
                                           class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    我们不会向第三方透露您的邮箱地址
                                </p>
                            </div>
                            
                            {{-- 订阅说明 --}}
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-5 space-y-3">
                                <h4 class="font-medium text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    订阅权益
                                </h4>
                                <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        新文章发布时及时通知
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        独家技术分享和教程
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        完全免费，随时退订
                                    </li>
                                </ul>
                            </div>
                            
                            {{-- 隐私政策 --}}
                            <div class="flex items-start gap-3">
                                <input type="checkbox" 
                                       id="privacy" 
                                       name="privacy" 
                                       required
                                       class="mt-1 w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <label for="privacy" class="text-sm text-gray-600 dark:text-gray-400">
                                    我已阅读并同意 <a href="#" class="text-primary-600 hover:text-primary-500 underline">隐私政策</a>，了解如何使用和保护我的个人信息
                                </label>
                            </div>
                            
                            {{-- 提交按钮 --}}
                            <button type="submit" 
                                    :disabled="loading"
                                    class="w-full btn-primary py-4 text-base font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <template x-if="loading">
                                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <span x-show="!loading">立即订阅</span>
                                <span x-show="loading">订阅中...</span>
                            </button>
                        </div>
                    </form>
                </div>
                
                {{-- 其他操作 --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        返回首页
                    </a>
                </div>
            </div>
        </div>
    </section>

</x-layout.app>
