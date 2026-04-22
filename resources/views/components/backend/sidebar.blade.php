{{-- resources/views/components/backend/sidebar.blade.php --}}
{{-- 后台侧边栏组件 - 响应式版本 --}}

<aside x-data="{ 
    collapsed: false,
    mobileOpen: false,
    init() {
        // 响应式检测
        if (window.innerWidth < 1024) {
            this.collapsed = true;
        }
        // 监听窗口大小变化
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                this.collapsed = false;
                this.mobileOpen = false;
            }
        });
    }
}" 
       class="relative">
    
    {{-- 移动端遮罩层 --}}
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 bg-black/50 z-30 lg:hidden"
         x-cloak></div>
    
    {{-- 侧边栏 --}}
    <div x-show="!collapsed || mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="-translate-x-full lg:translate-x-0 lg:w-0"
         x-transition:enter-end="translate-x-0 lg:w-64"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="translate-x-0 lg:w-64"
         x-transition:leave-end="-translate-x-full lg:w-0"
         class="fixed lg:relative inset-y-0 left-0 z-40 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 min-h-screen flex flex-col
                lg:block"
         :class="{ 'hidden lg:block': collapsed && !mobileOpen }"
         x-cloak>
        
        {{-- Logo --}}
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <x-logo class="h-8 w-8" />
                <span class="font-bold text-lg text-gray-900 dark:text-white">
                    {{ config('app.name') }}
                </span>
            </a>
            
            {{-- 关闭按钮（移动端） --}}
            <button @click="mobileOpen = false" 
                    class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        {{-- 导航菜单 --}}
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            
            @php
                $currentRoute = request()->route()->getName();
            @endphp
            
            {{-- 主导航 --}}
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-3">
                概览
            </div>
            
            <a href="{{ route('dashboard') }}"
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'dashboard') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="truncate">仪表盘</span>
            </a>
            
            {{-- 内容管理 --}}
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-3">
                内容管理
            </div>
            
            <a href="{{ route('backend.posts.index') }}"
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'backend.posts') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <span class="truncate">文章</span>
            </a>
            
            <a href="{{ route('backend.categories.index') }}"
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'backend.categories') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                <span class="truncate">分类</span>
            </a>
            
            <a href="{{ route('backend.tags.index') }}"
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'backend.tags') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span class="truncate">标签</span>
            </a>
            
            <a href="{{ route('backend.pages.index') }}"
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'backend.pages') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="truncate">页面</span>
            </a>
            
            {{-- 用户管理 --}}
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-3">
                用户管理
            </div>
            
            <a href="{{ route('backend.comments.index') }}"
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'backend.comments') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span class="truncate flex-1">评论</span>
                @if(($pendingCommentsCount ?? 0) > 0)
                    <span class="px-2 py-0.5 text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full">
                        {{ $pendingCommentsCount }}
                    </span>
                @endif
            </a>
            
            <a href="{{ route('backend.users.index') }}"
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'backend.users') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="truncate">用户</span>
            </a>
            
            {{-- 系统 --}}
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-3">
                系统
            </div>
            
            <a href="{{ route('backend.media.index') }}"
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'backend.media') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="truncate">媒体库</span>
            </a>
            
            <a href="{{ route('backend.settings.index') }}"
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'backend.settings') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="truncate">设置</span>
            </a>
            
            <a href="{{ route('admin.backups.index') }}"
               @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ str_starts_with($currentRoute, 'admin.backups') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                <span class="truncate">备份</span>
            </a>
            
        </nav>
        
        {{-- 底部 --}}
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('home') }}" 
               target="_blank"
               class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span class="truncate">查看站点</span>
            </a>
        </div>
    </div>
    
    {{-- 移动端菜单切换按钮 --}}
    <button @click="mobileOpen = !mobileOpen" 
            class="lg:hidden fixed bottom-4 left-4 z-50 w-12 h-12 rounded-full bg-primary-600 hover:bg-primary-700 text-white shadow-lg shadow-primary-500/30 flex items-center justify-center transition-colors"
            :class="{ 'bg-gray-600 hover:bg-gray-700': mobileOpen }"
            aria-label="切换菜单">
        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    
    {{-- 桌面端收起/展开按钮 --}}
    <button @click="collapsed = !collapsed" 
            class="hidden lg:flex absolute top-20 -right-3 w-6 h-6 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors z-10">
        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': collapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    
</aside>
