{{-- resources/views/backend/layouts/app.blade.php --}}
{{-- 后台主布局 --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: $store.theme.dark, sidebarOpen: false }"
      x-bind:class="{ 'dark': darkMode }"
      x-cloak>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? '管理后台' }} - {{ config('app.name') }}</title>
        
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
    
    <body class="min-h-screen bg-gray-100 dark:bg-gray-900">
        
        {{-- 移动端侧边栏遮罩 --}}
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden">
        </div>
        
        {{-- 侧边栏 --}}
        <aside x-show="sidebarOpen"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed top-0 left-0 z-50 w-64 h-full bg-white dark:bg-gray-800 shadow-xl lg:translate-x-0 lg:static lg:z-auto">
            
            {{-- Logo --}}
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <x-logo class="h-8 w-8" />
                    <span class="font-bold text-lg text-gray-900 dark:text-white">
                        {{ config('app.name') }}
                    </span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            {{-- 导航菜单 --}}
            <nav class="p-4 space-y-1">
                @php
                    $adminNav = [
                        ['route' => 'dashboard', 'label' => '仪表盘', 'icon' => 'home'],
                        ['divider' => true, 'label' => '内容管理'],
                        ['route' => 'backend.posts.index', 'label' => '文章', 'icon' => 'article'],
                        ['route' => 'backend.categories.index', 'label' => '分类', 'icon' => 'folder'],
                        ['route' => 'backend.tags.index', 'label' => '标签', 'icon' => 'tag'],
                        ['route' => 'backend.pages.index', 'label' => '页面', 'icon' => 'document'],
                        ['divider' => true, 'label' => '用户管理'],
                        ['route' => 'backend.comments.index', 'label' => '评论', 'icon' => 'chat'],
                        ['route' => 'backend.users.index', 'label' => '用户', 'icon' => 'users'],
                        ['divider' => true, 'label' => '系统'],
                        ['route' => 'backend.settings.index', 'label' => '设置', 'icon' => 'cog'],
                    ];
                @endphp
                
                @foreach($adminNav as $item)
                    @if(isset($item['divider']))
                        <div class="pt-4 pb-2">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                {{ $item['label'] }}
                            </span>
                        </div>
                    @else
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs($item['route']) ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                            @switch($item['icon'])
                                @case('home')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    @break
                                @case('article')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                    @break
                                @case('folder')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                    @break
                                @case('tag')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    @break
                                @case('document')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    @break
                                @case('chat')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    @break
                                @case('users')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    @break
                                @case('cog')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    @break
                            @endswitch
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>
            
            {{-- 底部链接 --}}
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('home') }}" 
                   target="_blank"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    查看站点
                </a>
            </div>
        </aside>
        
        {{-- 主内容区 --}}
        <div class="flex-1 flex flex-col min-h-screen lg:ml-64">
            
            {{-- 顶部工具栏 --}}
            <header class="sticky top-0 z-30 h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between h-full px-4 lg:px-6">
                    
                    {{-- 左侧 --}}
                    <div class="flex items-center gap-4">
                        {{-- 移动端菜单按钮 --}}
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="lg:hidden p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        
                        {{-- 面包屑 --}}
                        <nav class="hidden sm:flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <a href="{{ route('dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-300">后台</a>
                            @yield('breadcrumb')
                        </nav>
                    </div>
                    
                    {{-- 右侧 --}}
                    <div class="flex items-center gap-2">
                        {{-- 主题切换 --}}
                        <button @click="$store.theme.toggle()" 
                                class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <svg x-show="$store.theme.dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <svg x-show="!$store.theme.dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </button>
                        
                        {{-- 通知 --}}
                        <button class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($pendingComments ?? 0 > 0)
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                        </button>
                        
                        {{-- 用户菜单 --}}
                        <div x-data="dropdown()" class="relative">
                            <button @click="toggle()" class="flex items-center gap-2 p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" 
                                     alt=""
                                     class="w-8 h-8 rounded-full object-cover">
                                <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ auth()->user()->name }}
                                </span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="close()"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    个人资料
                                </a>
                                <hr class="my-1 border-gray-100 dark:border-gray-700">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        退出登录
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            {{-- 页面内容 --}}
            <main class="flex-1 p-4 lg:p-6">
                {{ $slot }}
            </main>
            
            {{-- 页脚 --}}
            <footer class="py-4 px-6 border-t border-gray-200 dark:border-gray-700 text-center text-sm text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </footer>
        </div>
        
        {{-- Toast 通知 --}}
        <x-toast.container />
        
        {{-- 额外脚本 --}}
        @stack('scripts')
    </body>
</html>
