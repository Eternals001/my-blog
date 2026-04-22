{{-- resources/views/components/layout/header.blade.php --}}
{{-- 顶部导航组件 --}}

<header x-data="mobileMenu()" 
        class="sticky top-0 z-40 w-full backdrop-blur-md bg-white/80 dark:bg-gray-900/80 border-b border-gray-200 dark:border-gray-800">
    
    {{-- 顶部工具栏（移动端） --}}
    <div class="lg:hidden flex items-center justify-between px-4 h-14">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <x-logo class="h-8 w-8" />
            <span class="font-bold text-lg text-gray-900 dark:text-white">
                {{ config('app.name', '博客') }}
            </span>
        </a>
        
        {{-- 右侧按钮 --}}
        <div class="flex items-center gap-1">
            {{-- 搜索按钮 --}}
            <button @click="$dispatch('open-search')" 
                    class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
            
            {{-- 主题切换 --}}
            <button @click="$store.theme.toggle()" 
                    class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800"
                    aria-label="切换主题">
                <svg x-show="$store.theme.dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg x-show="!$store.theme.dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>
            
            {{-- 用户菜单（移动端） --}}
            @auth
                <div x-data="dropdown()" class="relative">
                    <button @click="toggle()" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                        <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" 
                             alt=""
                             class="w-7 h-7 rounded-full object-cover bg-gray-200">
                    </button>
                    
                    <div x-show="open" 
                         @click.away="close()"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-2 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                        
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            管理后台
                        </a>
                        <a href="{{ route('profile.show', auth()->user()) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            个人资料
                        </a>
                        <a href="{{ route('user.posts', auth()->user()) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            我的文章
                        </a>
                        <a href="{{ route('user.favorites', auth()->user()) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            我的收藏
                        </a>
                        <a href="{{ route('user.settings') }}" 
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            账号设置
                        </a>
                        
                        <hr class="my-2 border-gray-100 dark:border-gray-700">
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                退出登录
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-ghost text-sm">
                    登录
                </a>
                <a href="{{ route('register') }}" class="btn-primary text-sm py-1.5">
                    注册
                </a>
            @endauth
            
            {{-- 移动端菜单按钮 --}}
            <button @click="toggle()" 
                    class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800"
                    :aria-expanded="open"
                    aria-label="打开菜单">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    
    {{-- 主导航栏（桌面端） --}}
    <div class="hidden lg:block">
        <div class="container-blog flex items-center justify-between h-16">
            {{-- Logo 区域 --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <x-logo class="h-10 w-10" />
                <span class="font-bold text-xl text-gray-900 dark:text-white">
                    {{ config('app.name', '博客') }}
                </span>
            </a>
            
            {{-- 主导航菜单 --}}
            <nav class="flex items-center gap-1" role="navigation" aria-label="主导航">
                @foreach($navItems = [
                    ['route' => 'home', 'label' => '首页', 'icon' => 'home'],
                    ['route' => 'posts.index', 'label' => '文章', 'icon' => 'article'],
                    ['route' => 'categories.index', 'label' => '分类', 'icon' => 'folder'],
                    ['route' => 'tags.index', 'label' => '标签', 'icon' => 'tag'],
                    ['route' => 'about', 'label' => '关于', 'icon' => 'user'],
                ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link {{ request()->routeIs($item['route']) ? 'nav-link-active' : '' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
                
                {{-- 订阅按钮 --}}
                @if(config('blog.subscription.enabled', true))
                <a href="{{ route('subscribe.show') }}" 
                   class="ml-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    订阅
                </a>
                @endif
                
                {{-- RSS 订阅 --}}
                <a href="{{ route('feed.rss') }}" 
                   class="p-2 text-gray-500 hover:text-orange-600 dark:text-gray-400 dark:hover:text-orange-400 transition-colors"
                   title="RSS 订阅"
                   aria-label="RSS 订阅">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6.18 15.64a2.18 2.18 0 0 1 2.18 2.18C8.36 19.01 7.38 20 6.18 20C4.98 20 4 19.01 4 17.82a2.18 2.18 0 0 1 2.18-2.18M4 4.44A15.56 15.56 0 0 1 19.56 20h-2.83A12.73 12.73 0 0 0 4 7.27V4.44m0 5.66a9.9 9.9 0 0 1 9.9 9.9h-2.83A7.07 7.07 0 0 0 4 12.93V10.1z"/>
                    </svg>
                </a>
            </nav>
            
            {{-- 右侧功能区 --}}
            <div class="flex items-center gap-2">
                {{-- 搜索按钮 --}}
                <button @click="$dispatch('open-search')" 
                        class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                        aria-label="搜索文章">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
                
                {{-- 主题切换 --}}
                <button @click="$store.theme.toggle()" 
                        class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                        aria-label="切换主题">
                    <svg x-show="$store.theme.dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="!$store.theme.dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
                
                {{-- 用户菜单（桌面端） --}}
                @auth
                    <div x-data="dropdown()" class="relative">
                        <button @click="toggle()" 
                                class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                :aria-expanded="open">
                            <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" 
                                 alt="{{ auth()->user()->name }}"
                                 class="w-9 h-9 rounded-full object-cover bg-gray-200 ring-2 ring-white dark:ring-gray-700">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden xl:block">
                                {{ auth()->user()->name }}
                            </span>
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        {{-- 下拉菜单 --}}
                        <div x-show="open" 
                             @click.away="close()"
                             @keydown.escape.window="close()"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                             class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 py-2 overflow-hidden">
                            
                            {{-- 用户信息头部 --}}
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-primary-50 to-transparent dark:from-primary-900/20">
                                <div class="flex items-center gap-3">
                                    <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" 
                                         alt=""
                                         class="w-12 h-12 rounded-full object-cover bg-gray-200">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ auth()->user()->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ auth()->user()->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- 菜单项 --}}
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                        </svg>
                                    </div>
                                    <span>管理后台</span>
                                </a>
                                
                                <a href="{{ route('profile.show', auth()->user()) }}" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <span>个人资料</span>
                                </a>
                                
                                <a href="{{ route('user.posts', auth()->user()) }}" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <span>我的文章</span>
                                </a>
                                
                                <a href="{{ route('user.favorites', auth()->user()) }}" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </div>
                                    <span>我的收藏</span>
                                </a>
                                
                                <a href="{{ route('user.settings') }}" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <span>账号设置</span>
                                </a>
                            </div>
                            
                            {{-- 退出登录 --}}
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                        </div>
                                        <span>退出登录</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost text-sm font-medium">
                        登录
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm font-medium">
                        注册
                    </a>
                @endauth
            </div>
        </div>
    </div>
    
    {{-- 移动端侧边菜单 --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 lg:hidden">
    </div>
    
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         @click.stop
         class="fixed top-0 left-0 w-80 h-full bg-white dark:bg-gray-900 shadow-xl z-50 lg:hidden">
        
        {{-- 菜单头部 --}}
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-800">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <x-logo class="h-8 w-8" />
                <span class="font-bold text-lg text-gray-900 dark:text-white">
                    {{ config('app.name', '博客') }}
                </span>
            </a>
            <button @click="close()" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        {{-- 菜单内容 --}}
        <nav class="p-4 space-y-1">
            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors {{ request()->routeIs($item['route']) ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
        
        {{-- 用户信息（已登录） --}}
        @auth
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" 
                         alt=""
                         class="w-10 h-10 rounded-full object-cover bg-gray-200">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <a href="{{ route('dashboard') }}" class="text-xs text-primary-600 dark:text-primary-400">
                            进入后台 →
                        </a>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</header>

{{-- 搜索模态框 --}}
@include('components.partials.search-modal')

{{-- 下拉菜单组件脚本 --}}
@once
@push('scripts')
<script>
function dropdown() {
    return {
        open: false,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        }
    }
}

function mobileMenu() {
    return {
        open: false,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        }
    }
}
</script>
@endpush
@endonce
