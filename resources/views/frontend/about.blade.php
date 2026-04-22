{{-- resources/views/frontend/about.blade.php --}}
{{-- 关于页面 --}}

<x-layout.app title="关于我">

    {{-- Hero 区域 --}}
    <section class="hero-section">
        <div class="container-blog py-16 lg:py-24">
            <div class="max-w-4xl mx-auto text-center">
                {{-- 头像 --}}
                <div class="mb-8">
                    <img src="{{ $author->avatar ?? asset('images/default-avatar.png') }}" 
                         alt="{{ $author->name }}"
                         class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white dark:border-gray-800 shadow-xl">
                </div>
                
                {{-- 名称 --}}
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    你好，我是 {{ $author->name ?? '博主' }}
                </h1>
                
                {{-- 简介 --}}
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                    {{ $author->bio ?? '一个热爱技术、喜欢折腾的全栈开发者。在这里分享编程心得、读书笔记和生活感悟。' }}
                </p>
                
                {{-- 社交链接 --}}
                <div class="flex items-center justify-center gap-4">
                    @foreach([
                        ['name' => 'GitHub', 'icon' => 'github', 'url' => $author->github ?? config('blog.social.github')],
                        ['name' => 'Twitter', 'icon' => 'twitter', 'url' => $author->twitter ?? config('blog.social.twitter')],
                        ['name' => 'Email', 'icon' => 'mail', 'url' => 'mailto:' . ($author->email ?? config('blog.contact.email'))],
                    ] as $social)
                        @if($social['url'])
                            <a href="{{ $social['url'] }}" 
                               target="_blank"
                               rel="noopener noreferrer"
                               class="p-3 rounded-full bg-white dark:bg-gray-800 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400"
                               aria-label="{{ $social['name'] }}">
                                @switch($social['icon'])
                                    @case('github')
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                                        </svg>
                                        @break
                                    @case('twitter')
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                        @break
                                    @case('mail')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        @break
                                @endswitch
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    
    {{-- 技能 --}}
    @if(isset($skills) && $skills->isNotEmpty())
        <section class="py-12 bg-white dark:bg-gray-900">
            <div class="container-blog max-w-4xl">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">
                    技术栈
                </h2>
                <div class="flex flex-wrap justify-center gap-3">
                    @foreach($skills as $skill)
                        <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full">
                            {{ $skill }}
                        </span>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    
    {{-- 个人简历 --}}
    @if(isset($timeline) && $timeline->isNotEmpty())
        <section class="py-12 bg-gray-50 dark:bg-gray-800/50">
            <div class="container-blog max-w-4xl">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">
                    个人历程
                </h2>
                <div class="space-y-8">
                    @foreach($timeline as $item)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-24 text-right">
                                <span class="text-sm font-medium text-primary-600 dark:text-primary-400">
                                    {{ $item['year'] ?? '' }}
                                </span>
                            </div>
                            <div class="relative pl-8 border-l-2 border-gray-200 dark:border-gray-700">
                                <div class="absolute left-0 top-0 w-3 h-3 -translate-x-[7px] rounded-full bg-primary-500"></div>
                                <p class="text-gray-700 dark:text-gray-300">
                                    {{ $item['content'] ?? '' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    
    {{-- 联系方式 --}}
    <section class="py-12 bg-white dark:bg-gray-900">
        <div class="container-blog max-w-2xl text-center">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                联系我
            </h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                有任何问题或合作意向，欢迎通过以下方式联系我。
            </p>
            
            <div class="space-y-4">
                <a href="mailto:{{ $author->email ?? config('blog.contact.email') }}" 
                   class="flex items-center justify-center gap-2 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">
                        {{ $author->email ?? config('blog.contact.email') }}
                    </span>
                </a>
            </div>
        </div>
    </section>

</x-layout.app>
