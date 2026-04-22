{{-- resources/views/backend/dashboard.blade.php --}}
{{-- 后台仪表盘 --}}

<x-backend.layouts.app title="仪表盘">

    {{-- 页面标题 --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    仪表盘
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    欢迎回来，{{ auth()->user()->name }}！以下是您博客的概况。
                </p>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <span id="current-time"></span>
            </div>
        </div>
    </div>

    {{-- 统计卡片 --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- 今日文章数 --}}
        <div class="card p-6 group hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        今日文章
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $stats['today_posts'] ?? 0 }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        总计 {{ $stats['posts'] ?? 0 }} 篇
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-lg shadow-primary-500/20 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                <span class="inline-flex items-center text-green-600 dark:text-green-400">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    +{{ $stats['posts_this_week'] ?? 0 }}
                </span>
                <span class="text-gray-400 dark:text-gray-500 ml-2">本周</span>
            </div>
        </div>

        {{-- 总评论数 --}}
        <div class="card p-6 group hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        总评论数
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $stats['comments'] ?? 0 }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        待审 {{ $stats['pending_comments'] ?? 0 }}
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                <span class="inline-flex items-center text-accent-600 dark:text-accent-400">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01"/>
                    </svg>
                    {{ $stats['comments_this_week'] ?? 0 }} 条新评论
                </span>
            </div>
        </div>

        {{-- 今日访问量 --}}
        <div class="card p-6 group hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        今日访问
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($stats['today_visits'] ?? 0) }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        总访问 {{ number_format($stats['views'] ?? 0) }}
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/20 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                <span class="inline-flex items-center text-green-600 dark:text-green-400">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    +{{ $stats['visits_growth'] ?? 0 }}%
                </span>
                <span class="text-gray-400 dark:text-gray-500 ml-2">较上周</span>
            </div>
        </div>

        {{-- 订阅用户数 --}}
        <div class="card p-6 group hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        订阅用户
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $stats['subscribers'] ?? 0 }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        活跃订阅 {{ $stats['active_subscribers'] ?? 0 }}
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                <span class="inline-flex items-center text-purple-600 dark:text-purple-400">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    +{{ $stats['new_subscribers'] ?? 0 }}
                </span>
                <span class="text-gray-400 dark:text-gray-500 ml-2">本月新增</span>
            </div>
        </div>
    </div>

    {{-- 趋势图表区域 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- 7天文章发布趋势 --}}
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    7天文章发布趋势
                </h3>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    近7天
                </span>
            </div>
            <div class="relative h-48">
                <svg class="w-full h-full" viewBox="0 0 400 150" preserveAspectRatio="none">
                    {{-- 网格线 --}}
                    <line x1="0" y1="0" x2="400" y2="0" stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="1" stroke-dasharray="4"/>
                    <line x1="0" y1="37.5" x2="400" y2="37.5" stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="1" stroke-dasharray="4"/>
                    <line x1="0" y1="75" x2="400" y2="75" stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="1" stroke-dasharray="4"/>
                    <line x1="0" y1="112.5" x2="400" y2="112.5" stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="1" stroke-dasharray="4"/>
                    <line x1="0" y1="150" x2="400" y2="150" stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="1"/>
                    
                    {{-- 图表数据 --}}
                    @php
                        $postTrend = $stats['post_trend'] ?? [0,0,0,0,0,0,0];
                        $maxPost = max($postTrend) ?: 1;
                        $points = [];
                        $width = 400;
                        $height = 150;
                        $padding = 10;
                        
                        for ($i = 0; $i < count($postTrend); $i++) {
                            $x = $padding + ($i / (count($postTrend) - 1)) * ($width - $padding * 2);
                            $y = $height - $padding - ($postTrend[$i] / $maxPost) * ($height - $padding * 2);
                            $points[] = "{$x},{$y}";
                        }
                        $pathD = 'M ' . implode(' L ', $points);
                        $areaD = $pathD . " L {$width},{$height} L 0,{$height} Z";
                    @endphp
                    
                    {{-- 面积填充 --}}
                    <defs>
                        <linearGradient id="postGradient" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="rgb(99, 102, 241)" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="rgb(99, 102, 241)" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="{{ $areaD }}" fill="url(#postGradient)"/>
                    
                    {{-- 折线 --}}
                    <path d="{{ $pathD }}" fill="none" stroke="rgb(99, 102, 241)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    
                    {{-- 数据点 --}}
                    @foreach($points as $index => $point)
                        @php [$x, $y] = explode(',', $point); @endphp
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="5" fill="white" stroke="rgb(99, 102, 241)" stroke-width="2"/>
                        <text x="{{ $x }}" y="{{ $height - 2 }}" text-anchor="middle" class="text-xs fill-gray-500 dark:fill-gray-400" font-size="10">
                            {{ ['周一', '周二', '周三', '周四', '周五', '周六', '周日'][$index] }}
                        </text>
                    @endforeach
                </svg>
            </div>
        </div>

        {{-- 7天访问量趋势 --}}
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    7天访问量趋势
                </h3>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    近7天
                </span>
            </div>
            <div class="relative h-48">
                <svg class="w-full h-full" viewBox="0 0 400 150" preserveAspectRatio="none">
                    {{-- 网格线 --}}
                    <line x1="0" y1="0" x2="400" y2="0" stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="1" stroke-dasharray="4"/>
                    <line x1="0" y1="37.5" x2="400" y2="37.5" stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="1" stroke-dasharray="4"/>
                    <line x1="0" y1="75" x2="400" y2="75" stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="1" stroke-dasharray="4"/>
                    <line x1="0" y1="112.5" x2="400" y2="112.5" stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="1" stroke-dasharray="4"/>
                    <line x1="0" y1="150" x2="400" y2="150" stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="1"/>
                    
                    {{-- 图表数据 --}}
                    @php
                        $visitTrend = $stats['visit_trend'] ?? [0,0,0,0,0,0,0];
                        $maxVisit = max($visitTrend) ?: 1;
                        $visitPoints = [];
                        $width = 400;
                        $height = 150;
                        $padding = 10;
                        
                        for ($i = 0; $i < count($visitTrend); $i++) {
                            $x = $padding + ($i / (count($visitTrend) - 1)) * ($width - $padding * 2);
                            $y = $height - $padding - ($visitTrend[$i] / $maxVisit) * ($height - $padding * 2);
                            $visitPoints[] = "{$x},{$y}";
                        }
                        $visitPathD = 'M ' . implode(' L ', $visitPoints);
                        $visitAreaD = $visitPathD . " L {$width},{$height} L 0,{$height} Z";
                    @endphp
                    
                    {{-- 面积填充 --}}
                    <defs>
                        <linearGradient id="visitGradient" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="rgb(16, 185, 129)" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="rgb(16, 185, 129)" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="{{ $visitAreaD }}" fill="url(#visitGradient)"/>
                    
                    {{-- 折线 --}}
                    <path d="{{ $visitPathD }}" fill="none" stroke="rgb(16, 185, 129)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    
                    {{-- 数据点 --}}
                    @foreach($visitPoints as $index => $point)
                        @php [$x, $y] = explode(',', $point); @endphp
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="5" fill="white" stroke="rgb(16, 185, 129)" stroke-width="2"/>
                        <text x="{{ $x }}" y="{{ $height - 2 }}" text-anchor="middle" class="text-xs fill-gray-500 dark:fill-gray-400" font-size="10">
                            {{ ['周一', '周二', '周三', '周四', '周五', '周六', '周日'][$index] }}
                        </text>
                    @endforeach
                </svg>
            </div>
        </div>
    </div>

    {{-- 快捷操作和最近动态 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- 快捷操作 --}}
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                快捷操作
            </h3>
            <div class="space-y-3">
                <a href="{{ route('admin.posts.create') }}" 
                   class="flex items-center p-3 rounded-xl bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">写新文章</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">创建一篇博客文章</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" 
                   class="flex items-center p-3 rounded-xl bg-accent-50 dark:bg-accent-900/20 hover:bg-accent-100 dark:hover:bg-accent-900/30 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-accent-500 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">待审评论</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            @if(($stats['pending_comments'] ?? 0) > 0)
                                <span class="text-red-500 font-medium">{{ $stats['pending_comments'] }}</span> 条待审核
                            @else
                                暂无待审评论
                            @endif
                        </p>
                    </div>
                </a>
                
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center p-3 rounded-xl bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">网站预览</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">在前台查看您的博客</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- 最近发布的文章 --}}
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    最近文章
                </h3>
                <a href="{{ route('admin.posts.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                    查看全部
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentPosts ?? [] as $post)
                    <a href="{{ route('admin.posts.edit', $post) }}" class="flex items-start group">
                        @if($post->featured_image)
                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" 
                                 class="w-12 h-12 rounded-lg object-cover mr-3">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                {{ $post->title }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full 
                            @if($post->status === 'published') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                            @elseif($post->status === 'draft') bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                            @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                            @endif">
                            @if($post->status === 'published') 已发布
                            @elseif($post->status === 'draft') 草稿
                            @else 定时发布 @endif
                        </span>
                    </a>
                @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        <p class="text-sm">暂无文章</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 最近的评论和订阅 --}}
        <div class="space-y-6">
            {{-- 最近的评论 --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        最新评论
                    </h3>
                    <a href="{{ route('admin.comments.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                        查看全部
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($recentComments ?? [] as $comment)
                        <div class="flex items-start group">
                            <img src="{{ $comment->avatar_url ?? asset('images/default-avatar.png') }}" 
                                 alt="{{ $comment->display_name }}"
                                 class="w-8 h-8 rounded-full mr-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $comment->display_name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                    {{ Str::limit($comment->content, 50) }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    {{ $comment->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if(!$comment->is_approved)
                                <span class="px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    待审
                                </span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                            <p class="text-sm">暂无评论</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 最近的订阅 --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        最新订阅
                    </h3>
                    <a href="{{ route('admin.subscriptions.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                        查看全部
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($recentSubscriptions ?? [] as $subscription)
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900 dark:text-white truncate">
                                    {{ $subscription->email }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $subscription->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if($subscription->is_active)
                                <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    活跃
                                </span>
                            @else
                                <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                    未激活
                                </span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                            <p class="text-sm">暂无订阅</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</x-backend.layouts.app>

@push('scripts')
<script>
    // 更新时间显示
    function updateTime() {
        const now = new Date();
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            weekday: 'long',
            hour: '2-digit',
            minute: '2-digit'
        };
        document.getElementById('current-time').textContent = now.toLocaleDateString('zh-CN', options);
    }
    updateTime();
    setInterval(updateTime, 60000);
</script>
@endpush
