{{-- resources/views/auth/reset-password.blade.php --}}
{{-- 重置密码页面 --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: $store.theme.dark }"
      x-bind:class="{ 'dark': darkMode }">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>重置密码 - {{ config('app.name') }}</title>
        
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
    
    <body class="min-h-screen bg-gradient-to-br from-gray-50 via-accent-50/30 to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
        
        {{-- 顶部背景装饰 --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-accent-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-primary-500/10 rounded-full blur-3xl"></div>
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
                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-accent-100 to-accent-200 dark:from-accent-900/50 dark:to-accent-800/50 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    
                    <div class="text-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            设置新密码
                        </h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            请输入您的新密码
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
                    
                    {{-- 表单 --}}
                    <form method="POST" action="{{ route('password.update') }}" 
                          class="space-y-5"
                          x-data="{
                              password: '',
                              passwordStrength: 0,
                              passwordStrengthLabel: '',
                              passwordStrengthColor: '',
                              checkPasswordStrength(password) {
                                  let strength = 0;
                                  if (password.length >= 8) strength++;
                                  if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                                  if (/\d/.test(password)) strength++;
                                  if (/[^a-zA-Z0-9]/.test(password)) strength++;
                                  
                                  this.passwordStrength = strength;
                                  
                                  if (strength === 0) {
                                      this.passwordStrengthLabel = '';
                                      this.passwordStrengthColor = 'bg-gray-200';
                                  } else if (strength === 1) {
                                      this.passwordStrengthLabel = '弱';
                                      this.passwordStrengthColor = 'bg-red-500';
                                  } else if (strength === 2) {
                                      this.passwordStrengthLabel = '中等';
                                      this.passwordStrengthColor = 'bg-yellow-500';
                                  } else if (strength === 3) {
                                      this.passwordStrengthLabel = '良好';
                                      this.passwordStrengthColor = 'bg-blue-500';
                                  } else {
                                      this.passwordStrengthLabel = '强';
                                      this.passwordStrengthColor = 'bg-green-500';
                                  }
                              }
                          }">
                        @csrf
                        
                        {{-- 隐藏 Token --}}
                        <input type="hidden" name="token" value="{{ $token ?? request()->route('token') }}">
                        
                        {{-- 邮箱（只读） --}}
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
                                       value="{{ old('email', request()->email) }}"
                                       required 
                                       readonly
                                       class="input pl-10 bg-gray-50 dark:bg-gray-900 cursor-not-allowed @error('email') border-red-500 @enderror">
                            </div>
                        </div>
                        
                        {{-- 新密码 --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                新密码
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
                                       autocomplete="new-password"
                                       placeholder="请输入新密码"
                                       x-model="password"
                                       @input="checkPasswordStrength(password)"
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
                            
                            {{-- 密码强度指示器 --}}
                            <div x-show="password.length > 0" class="mt-2">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full transition-all duration-300 rounded-full"
                                             :class="passwordStrengthColor"
                                             :style="'width: ' + (passwordStrength * 25) + '%'">
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium"
                                          :class="{
                                              'text-red-500': passwordStrength <= 1,
                                              'text-yellow-500': passwordStrength === 2,
                                              'text-blue-500': passwordStrength === 3,
                                              'text-green-500': passwordStrength >= 4
                                          }"
                                          x-text="passwordStrengthLabel">
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- 确认密码 --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                确认密码
                            </label>
                            <div class="relative" x-data="{ showPassword: false, confirmed: false }">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <input :type="showPassword ? 'text' : 'password'"
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="请再次输入新密码"
                                       x-model="$refs.confirmPassword.value"
                                       @input="confirmed = $event.target.value === password"
                                       class="input pl-10 pr-10 @error('password_confirmation') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg x-show="confirmed && $refs.confirmPassword.value.length > 0" 
                                         class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        {{-- 提交按钮 --}}
                        <button type="submit" 
                                class="btn-primary w-full py-2.5 text-base font-semibold relative overflow-hidden group">
                            重置密码
                        </button>
                    </form>
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
