{{-- resources/views/components/layout/footer.blade.php --}}
{{-- 页脚组件 --}}

<footer class="bg-gray-900 dark:bg-gray-950 text-gray-300">
    {{-- 主内容 --}}
    <div class="container-blog py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            
            {{-- 关于我们 --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <x-logo class="h-10 w-10" />
                    <span class="font-bold text-xl text-white">
                        {{ config('app.name', '博客') }}
                    </span>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed mb-4">
                    {{ $aboutText ?? '一个分享技术见解与生活感悟的个人博客，记录成长的每一步。' }}
                </p>
                
                {{-- 社交链接 --}}
                <div class="flex items-center gap-3">
                    @foreach($socials = [
                        ['name' => 'GitHub', 'icon' => 'github', 'url' => config('blog.social.github')],
                        ['name' => 'Twitter', 'icon' => 'twitter', 'url' => config('blog.social.twitter')],
                        ['name' => 'Email', 'icon' => 'mail', 'url' => 'mailto:' . config('blog.contact.email')],
                    ] as $social)
                        @if($social['url'])
                            <a href="{{ $social['url'] }}" 
                               target="_blank"
                               rel="noopener noreferrer"
                               class="p-2 rounded-lg bg-gray-800 hover:bg-primary-600 text-gray-400 hover:text-white transition-colors"
                               aria-label="{{ $social['name'] }}">
                                @switch($social['icon'])
                                    @case('github')
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                                        </svg>
                                        @break
                                    @case('twitter')
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                        @break
                                    @case('mail')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        @break
                                @endswitch
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            
            {{-- 快速链接 --}}
            <div>
                <h3 class="text-white font-semibold mb-4">快速链接</h3>
                <ul class="space-y-2">
                    @foreach($quickLinks = [
                        ['route' => 'home', 'label' => '首页'],
                        ['route' => 'posts.index', 'label' => '全部文章'],
                        ['route' => 'categories.index', 'label' => '文章分类'],
                        ['route' => 'tags.index', 'label' => '标签云'],
                        ['route' => 'about', 'label' => '关于本站'],
                    ] as $link)
                        <li>
                            <a href="{{ route($link['route']) }}" 
                               class="text-sm text-gray-400 hover:text-white transition-colors">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            {{-- 分类导航 --}}
            <div>
                <h3 class="text-white font-semibold mb-4">热门分类</h3>
                <ul class="space-y-2">
                    @foreach($categories ?? [] as $category)
                        <li>
                            <a href="{{ route('categories.show', $category->slug) }}" 
                               class="text-sm text-gray-400 hover:text-white transition-colors">
                                {{ $category->name }}
                                <span class="ml-1 text-gray-500">({{ $category->posts_count ?? 0 }})</span>
                            </a>
                        </li>
                    @endforeach
                    @if(empty($categories))
                        <li><span class="text-sm text-gray-500">暂无分类</span></li>
                    @endif
                </ul>
            </div>
            
            {{-- RSS & 订阅 --}}
            <div>
                <h3 class="text-white font-semibold mb-4">订阅更新</h3>
                <p class="text-sm text-gray-400 mb-4">
                    订阅 RSS Feed，第一时间获取最新文章。
                </p>
                <a href="{{ route('feed') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-primary-600 text-white rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6.18 15.64a2.18 2.18 0 012.18 2.18C8.36 19.01 7.38 20 6.18 20C4.98 20 4 19.01 4 17.82a2.18 2.18 0 012.18-2.18M4 4.44A15.56 15.56 0 0119.56 20h-2.83A12.73 12.73 0 004 7.27V4.44m0 5.66a9.9 9.9 0 019.9 9.9h-2.83A7.07 7.07 0 004 12.93V10.1z"/>
                    </svg>
                    RSS 订阅
                </a>
                
                <div class="mt-4">
                    <p class="text-xs text-gray-500">
                        © {{ date('Y') }} {{ config('app.name', '博客') }}.
                        All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    {{-- 底部信息栏 --}}
    <div class="border-t border-gray-800">
        <div class="container-blog py-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                
                {{-- 版权信息 --}}
                <div class="text-sm text-gray-500">
                    Built with 
                    <a href="https://laravel.com" target="_blank" rel="noopener" class="text-gray-400 hover:text-white">Laravel</a>
                    &amp;
                    <a href="https://livewire.laravel.com" target="_blank" rel="noopener" class="text-gray-400 hover:text-white">Livewire</a>
                </div>
                
                {{-- 备案信息 --}}
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    @if(config('blog.icp'))
                        <a href="https://beian.miit.gov.cn/" 
                           target="_blank" 
                           rel="noopener"
                           class="hover:text-gray-400 transition-colors">
                            {{ config('blog.icp') }}
                        </a>
                    @endif
                    
                    @if(config('blog.police.record'))
                        <a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode={{ substr(config('blog.police.record'), 2) }}" 
                           target="_blank" 
                           rel="noopener"
                           class="hover:text-gray-400 transition-colors">
                            {{ config('blog.police.record') }}
                        </a>
                    @endif
                </div>
                
                {{-- 运行时统计 --}}
                <div class="text-xs text-gray-600 dark:text-gray-500">
                    @php
                        $startTime = microtime(true);
                    @endphp
                    Page rendered in {{ number_format((microtime(true) - LARAVEL_START) * 1000, 0) }}ms
                </div>
            </div>
        </div>
    </div>
</footer>
