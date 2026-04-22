{{-- resources/views/auth/forgot-password.blade.php --}}
{{-- 忘记密码页面 --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: $store.theme.dark }"
      x-bind:class="{ 'dark': darkMode }">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>忘记密码 - {{ config('app.name') }}</title>
        
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- 暗黑模式初始化脚本 --}}
        <script>
            (function() {
                const stored = localStorage.getItem('theme');
                let shouldBeDark = stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches);
                if (shouldBeDark) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>
    </head>
    
    <body class="min-h-screen bg-gradient-to-br from-gray-50 via-primary-50/30 to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
        
        {{-- 顶部背景装饰 --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-accent-500/10 rounded-full blur-3xl"></div>
        </div>
        
        {{-- 主内容 --}}
        <main class="flex-1 flex items-center justify-center p-4 relative">
            <div class="w-full max-w-md">
                
                {{-- Logo --}}
                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                        <x-logo class="h-14 w-14 transition-transform duration-300 group-hover:scale-110" />
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ config('app.name') }}
                        </span>
                    </a>
                </div>
                
                {{-- 卡片 --}}
                <div class="card p-8 backdrop-blur-sm bg-white/80 dark:bg-gray-800/80">
                    {{-- 图标 --}}
                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/50 dark:to-primary-800/50 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    
                    <div class="text-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            找回密码
                        </h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            输入您的注册邮箱，我们将发送密码重置链接
                        </p>
                    </div>
                    
                    {{-- 成功提示 --}}
                    @if (session('status'))
                        <div x-data="{ show: true }" 
                             x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-green-700 dark:text-green-300 font-medium">
                                        邮件发送成功！
                                    </p>
                                    <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                        {{ session('status') }}
                                    </p>
                                </div>
                                <button @click="show = false" class="text-green-400 hover:text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    {{-- 错误提示 --}}
                    @if ($errors->any())
                        <div x-data="{ show: true }" 
                             x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex-1 text-sm text-red-700 dark:text-red-300">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                                <button @click="show = false" class="text-red-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    {{-- 表单 --}}
                    @if (!session('status'))
                        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                            @csrf
                            
                            {{-- 邮箱 --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    邮箱地址
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           required 
                                           autofocus
                                           autocomplete="email"
                                           placeholder="请输入注册时的邮箱地址"
                                           class="input pl-10 @error('email') border-red-500 @enderror">
                                </div>
                            </div>
                            
                            {{-- 提示信息 --}}
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-sm text-blue-700 dark:text-blue-300">
                                        <p>如果没有收到邮件，请检查：</p>
                                        <ul class="mt-1 list-disc list-inside space-y-1">
                                            <li>垃圾邮件文件夹</li>
                                            <li>邮箱地址是否正确</li>
                                            <li>稍后再试或联系管理员</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- 提交按钮 --}}
                            <button type="submit" 
                                    class="btn-primary w-full py-2.5 text-base font-semibold relative overflow-hidden group">
                                发送重置链接
                            </button>
                        </form>
                    @endif
                </div>
                
                {{-- 返回登录 --}}
                <p class="mt-6 text-center">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        返回登录
                    </a>
                </p>
            </div>
        </main>
        
        {{-- 页脚 --}}
        <footer class="py-4 text-center text-sm text-gray-500 dark:text-gray-400">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </footer>
        
        {{-- Toast 通知 --}}
        <x-toast.container />
    </body>
</html>
