{{-- resources/views/auth/login.blade.php --}}
{{-- 登录页面 --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: $store.theme.dark }"
      x-bind:class="{ 'dark': darkMode }">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>登录 - {{ config('app.name') }}</title>
        
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
                    <p class="mt-3 text-gray-600 dark:text-gray-400">
                        欢迎回来，开始探索精彩内容
                    </p>
                </div>
                
                {{-- 登录卡片 --}}
                <div class="card p-8 backdrop-blur-sm bg-white/80 dark:bg-gray-800/80">
                    <div class="text-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            登录账户
                        </h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            请输入您的账户信息
                        </p>
                    </div>
                    
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
                    
                    {{-- 登录表单 --}}
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        
                        {{-- 邮箱/用户名 --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                邮箱地址
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                </div>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       required 
                                       autofocus
                                       autocomplete="email"
                                       placeholder="请输入邮箱地址"
                                       class="input pl-10 @error('email') border-red-500 @enderror">
                            </div>
                        </div>
                        
                        {{-- 密码 --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                密码
                            </label>
                            <div class="relative" x-data="{ showPassword: false }">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input :type="showPassword ? 'text' : 'password'"
                                       id="password" 
                                       name="password" 
                                       required 
                                       autocomplete="current-password"
                                       placeholder="请输入密码"
                                       class="input pl-10 pr-10 @error('password') border-red-500 @enderror">
                                <button type="button" 
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        {{-- 记住我和忘记密码 --}}
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" 
                                       name="remember" 
                                       id="remember"
                                       {{ old('remember') ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200">
                                    记住我
                                </span>
                            </label>
                            
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" 
                                   class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                    忘记密码？
                                </a>
                            @endif
                        </div>
                        
                        {{-- 登录按钮 --}}
                        <button type="submit" 
                                class="btn-primary w-full py-2.5 text-base font-semibold relative overflow-hidden group"
                                x-data="{ loading: false }"
                                @submit="loading = true">
                            <span x-show="!loading">登录</span>
                            <span x-show="loading" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                登录中...
                            </span>
                        </button>
                    </form>
                    
                    {{-- 第三方登录 --}}
                    <div class="mt-8">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                    或使用以下方式登录
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-6 grid grid-cols-3 gap-3">
                            {{-- 微信登录 --}}
                            <button type="button" 
                                    class="btn-ghost border border-gray-200 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-500 hover:text-green-600 dark:hover:text-green-400"
                                    title="微信登录">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8.691 2.188C3.891 2.188 0 5.476 0 9.53c0 2.212 1.17 4.203 3.002 5.55a.59.59 0 01.213.665l-.39 1.48c-.019.07-.048.141-.048.213 0 .163.13.295.29.295a.326.326 0 00.167-.054l1.903-1.114a.864.864 0 01.717-.098 10.16 10.16 0 002.837.403c.276 0 .543-.027.811-.05-.857-2.578.157-4.972 1.932-6.446 1.703-1.415 3.882-1.98 5.853-1.838-.576-3.583-4.196-6.348-8.596-6.348zM5.785 5.991c.642 0 1.162.529 1.162 1.18a1.17 1.17 0 01-1.162 1.178A1.17 1.17 0 014.623 7.17c0-.651.52-1.18 1.162-1.18zm5.813 0c.642 0 1.162.529 1.162 1.18a1.17 1.17 0 01-1.162 1.178 1.17 1.17 0 01-1.162-1.178c0-.651.52-1.18 1.162-1.18zm2.891 3.928a.624.624 0 100 1.247.624.624 0 000-1.247zm-5.096 1.38c.463.323.73.868.73 1.47 0 .98-.792 1.746-1.746 1.746-.953 0-1.746-.766-1.746-1.746 0-.601.267-1.146.73-1.469a.624.624 0 10-1.016.744c-.644.448-1.048 1.164-1.048 1.965 0 1.294 1.038 2.331 2.33 2.331 1.293 0 2.33-1.037 2.33-2.33 0-.8-.404-1.516-1.048-1.965a.624.624 0 00-1.016-.744.622.622 0 00.75-.75zm9.333.38a.624.624 0 100 1.247.624.624 0 000-1.247zm-1.77 2.12c.463.323.73.868.73 1.47 0 .98-.792 1.746-1.746 1.746-.953 0-1.746-.766-1.746-1.746 0-.601.267-1.146.73-1.469a.624.624 0 00-1.016.744c-.644.448-1.048 1.164-1.048 1.965 0 1.294 1.038 2.331 2.33 2.331 1.293 0 2.33-1.037 2.33-2.33 0-.8-.404-1.516-1.048-1.965a.624.624 0 00-1.016-.744.622.622 0 00.75-.75z"/>
                                </svg>
                            </button>
                            
                            {{-- GitHub 登录 --}}
                            <a href="{{ route('socialite.redirect', 'github') }}" 
                               class="btn-ghost border border-gray-200 dark:border-gray-700 hover:border-gray-900 dark:hover:border-gray-100 hover:text-gray-900 dark:hover:text-gray-100"
                               title="GitHub 登录">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                            
                            {{-- Google 登录 --}}
                            <a href="{{ route('socialite.redirect', 'google') }}" 
                               class="btn-ghost border border-gray-200 dark:border-gray-700 hover:border-red-500 hover:text-red-500"
                               title="Google 登录">
                                <svg class="w-5 h-5" viewBox="0 0 24 24">
                                    <path fill="#EA4335" d="M5.26620003,9.76452941 C6.19878754,6.93863203 8.85444915,4.90909091 12,4.90909091 C13.6909091,4.90909091 15.2181818,5.50909091 16.4181818,6.49090909 L19.9090909,3 C17.7818182,1.14545455 15.0545455,0 12,0 C7.27006974,0 3.1977497,2.69829785 1.23999023,6.65002441 L5.26620003,9.76452941 Z"/>
                                    <path fill="#34A853" d="M16.0407269,18.0125889 C14.9509167,18.7163016 13.5660892,19.0909091 12,19.0909091 C8.86648613,19.0909091 6.21911939,17.076871 5.27698177,14.2678769 L1.23746264,17.3349879 C3.19279051,21.2936293 7.26500293,24 12,24 C14.9328362,24 17.7353462,22.9573905 19.834192,20.9995801 L16.0407269,18.0125889 Z"/>
                                    <path fill="#4A90E2" d="M19.834192,20.9995801 C22.0291676,18.9520994 23.4545455,15.903663 23.4545455,12 C23.4545455,11.2909091 23.3454545,10.5272727 23.1818182,9.81818182 L12,9.81818182 L12,14.4545455 L18.4363636,14.4545455 C18.1187732,16.013626 17.2662994,17.2212117 16.0407269,18.0125889 L19.834192,20.9995801 Z"/>
                                    <path fill="#FBBC05" d="M5.27698177,14.2678769 C5.03832634,13.556323 4.90909091,12.7937589 4.90909091,12 C4.90909091,11.2182781 5.03443647,10.4668121 5.26620003,9.76452941 L1.23999023,6.65002441 C0.43658717,8.26043162 0,10.0753848 0,12 C0,13.9195484 0.444780743,15.7.924003087,17.3313788 L5.27698177,14.2678769 Z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                {{-- 注册链接 --}}
                <p class="mt-6 text-center text-gray-600 dark:text-gray-400">
                    还没有账户？
                    <a href="{{ route('register') }}" 
                       class="font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                        立即注册
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
