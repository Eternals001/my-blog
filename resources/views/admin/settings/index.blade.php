{{-- resources/views/admin/settings/index.blade.php --}}
{{-- 系统设置页面 --}}

<x-backend.layouts.app title="系统设置">

    {{-- 页面标题 --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            系统设置
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            配置博客的基础信息和功能选项
        </p>
    </div>

    {{-- 操作提示 --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Tab 导航 --}}
            <div class="lg:col-span-1">
                <div class="card p-2 sticky top-6">
                    <nav class="space-y-1" x-data="{ activeTab: 'basic' }">
                        <button type="button" 
                                @click="activeTab = 'basic'"
                                :class="activeTab === 'basic' ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            基础设置
                        </button>
                        
                        <button type="button" 
                                @click="activeTab = 'seo'"
                                :class="activeTab === 'seo' ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            SEO 设置
                        </button>
                        
                        <button type="button" 
                                @click="activeTab = 'comments'"
                                :class="activeTab === 'comments' ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            评论设置
                        </button>
                        
                        <button type="button" 
                                @click="activeTab = 'social'"
                                :class="activeTab === 'social' ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            社交链接
                        </button>
                    </nav>
                </div>
            </div>

            {{-- 设置内容 --}}
            <div class="lg:col-span-3" x-data="{ activeTab: 'basic' }">
                {{-- 基础设置 --}}
                <div x-show="activeTab === 'basic'" x-transition>
                    <div class="card p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            基础设置
                        </h2>

                        <div class="space-y-6">
                            {{-- 站点名称 --}}
                            <div>
                                <label for="site_name" class="form-label">
                                    站点名称 <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="site_name" 
                                       name="site[name]"
                                       value="{{ old('site.name', config('settings.site_name')) }}"
                                       required
                                       class="input @error('site.name') border-red-500 @enderror">
                                @error('site.name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 站点描述 --}}
                            <div>
                                <label for="site_description" class="form-label">站点描述</label>
                                <textarea id="site_description" 
                                          name="site[description]" 
                                          rows="3"
                                          class="input resize-none @error('site.description') border-red-500 @enderror">{{ old('site.description', config('settings.site_description')) }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">简短描述您的博客，用于首页展示</p>
                            </div>

                            {{-- Logo 上传 --}}
                            <div>
                                <label class="form-label">站点 Logo</label>
                                <div class="mt-2 flex items-start gap-6">
                                    <div class="flex-shrink-0">
                                        @if(config('settings.site_logo'))
                                            <img src="{{ config('settings.site_logo') }}" alt="Logo" class="h-16 w-auto rounded-lg">
                                        @else
                                            <div class="h-16 w-16 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <input type="url" 
                                               name="site[logo_url]"
                                               value="{{ old('site.logo_url', config('settings.site_logo')) }}"
                                               placeholder="或输入 Logo URL"
                                               class="input mb-2">
                                        <p class="text-xs text-gray-500">支持上传或输入图片 URL</p>
                                    </div>
                                </div>
                            </div>

                            {{-- 备案号 --}}
                            <div>
                                <label for="icp_number" class="form-label">ICP 备案号</label>
                                <input type="text" 
                                       id="icp_number" 
                                       name="site[icp_number]"
                                       value="{{ old('site.icp_number', config('settings.icp_number')) }}"
                                       class="input"
                                       placeholder="如：京ICP备12345678号">
                                <p class="mt-1 text-sm text-gray-500">显示在页面底部的备案信息</p>
                            </div>

                            {{-- 博主名称 --}}
                            <div>
                                <label for="author_name" class="form-label">博主名称</label>
                                <input type="text" 
                                       id="author_name" 
                                       name="site[author_name]"
                                       value="{{ old('site.author_name', config('settings.author_name')) }}"
                                       class="input">
                            </div>

                            {{-- 博主邮箱 --}}
                            <div>
                                <label for="author_email" class="form-label">博主邮箱</label>
                                <input type="email" 
                                       id="author_email" 
                                       name="site[author_email]"
                                       value="{{ old('site.author_email', config('settings.author_email')) }}"
                                       class="input">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO 设置 --}}
                <div x-show="activeTab === 'seo'" x-transition>
                    <div class="card p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            SEO 设置
                        </h2>

                        <div class="space-y-6">
                            {{-- 首页标题 --}}
                            <div>
                                <label for="seo_home_title" class="form-label">首页标题</label>
                                <input type="text" 
                                       id="seo_home_title" 
                                       name="seo[home_title]"
                                       value="{{ old('seo.home_title', config('settings.seo_home_title')) }}"
                                       class="input">
                                <p class="mt-1 text-sm text-gray-500">将显示在浏览器标签和搜索引擎结果中</p>
                            </div>

                            {{-- 首页描述 --}}
                            <div>
                                <label for="seo_home_description" class="form-label">首页描述</label>
                                <textarea id="seo_home_description" 
                                          name="seo[home_description]" 
                                          rows="3"
                                          class="input resize-none">{{ old('seo.home_description', config('settings.seo_home_description')) }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">建议 150-200 字符</p>
                            </div>

                            {{-- 关键词 --}}
                            <div>
                                <label for="seo_keywords" class="form-label">关键词</label>
                                <input type="text" 
                                       id="seo_keywords" 
                                       name="seo[keywords]"
                                       value="{{ old('seo.keywords', config('settings.seo_keywords')) }}"
                                       class="input"
                                       placeholder="技术博客, Laravel, Vue.js">
                                <p class="mt-1 text-sm text-gray-500">用英文逗号分隔</p>
                            </div>

                            {{-- Google Analytics --}}
                            <div>
                                <label for="google_analytics" class="form-label">Google Analytics ID</label>
                                <input type="text" 
                                       id="google_analytics" 
                                       name="seo[google_analytics]"
                                       value="{{ old('seo.google_analytics', config('settings.google_analytics')) }}"
                                       class="input"
                                       placeholder="G-XXXXXXXXXX">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 评论设置 --}}
                <div x-show="activeTab === 'comments'" x-transition>
                    <div class="card p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            评论设置
                        </h2>

                        <div class="space-y-6">
                            {{-- 开启评论 --}}
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="font-medium text-gray-900 dark:text-white">开启评论</label>
                                    <p class="text-sm text-gray-500 mt-1">允许访客在文章下发表评论</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="comments[enabled]"
                                           value="1"
                                           {{ old('comments.enabled', config('settings.comments_enabled', true)) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                                </label>
                            </div>

                            {{-- 需要审核 --}}
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="font-medium text-gray-900 dark:text-white">评论需要审核</label>
                                    <p class="text-sm text-gray-500 mt-1">新评论发布前需要管理员审核</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="comments[require_approval]"
                                           value="1"
                                           {{ old('comments.require_approval', config('settings.comments_require_approval', true)) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                                </label>
                            </div>

                            {{-- 游客评论 --}}
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="font-medium text-gray-900 dark:text-white">允许游客评论</label>
                                    <p class="text-sm text-gray-500 mt-1">未登录用户也可以发表评论</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="comments[allow_anonymous]"
                                           value="1"
                                           {{ old('comments.allow_anonymous', config('settings.comments_allow_anonymous', true)) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                                </label>
                            </div>

                            {{-- 反垃圾配置 --}}
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <h3 class="font-medium text-gray-900 dark:text-white mb-4">反垃圾设置</h3>
                                
                                <div class="space-y-4">
                                    {{-- 禁止关键词 --}}
                                    <div>
                                        <label for="spam_keywords" class="form-label">禁止关键词</label>
                                        <textarea id="spam_keywords" 
                                                  name="comments[spam_keywords]" 
                                                  rows="3"
                                                  class="input resize-none"
                                                  placeholder="每行一个关键词">{{ old('comments.spam_keywords', config('settings.spam_keywords')) }}</textarea>
                                        <p class="mt-1 text-sm text-gray-500">包含这些词的评论将被自动标记为垃圾</p>
                                    </div>

                                    {{-- 最低字数 --}}
                                    <div>
                                        <label for="min_comment_length" class="form-label">评论最低字数</label>
                                        <input type="number" 
                                               id="min_comment_length" 
                                               name="comments[min_length]"
                                               value="{{ old('comments.min_length', config('settings.min_comment_length', 5)) }}"
                                               min="1"
                                               class="input w-32">
                                        <p class="mt-1 text-sm text-gray-500">太短的评论将被拒绝</p>
                                    </div>

                                    {{-- 最高字数 --}}
                                    <div>
                                        <label for="max_comment_length" class="form-label">评论最高字数</label>
                                        <input type="number" 
                                               id="max_comment_length" 
                                               name="comments[max_length]"
                                               value="{{ old('comments.max_length', config('settings.max_comment_length', 2000)) }}"
                                               min="1"
                                               class="input w-32">
                                        <p class="mt-1 text-sm text-gray-500">超过限制的评论将被拒绝</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 社交链接 --}}
                <div x-show="activeTab === 'social'" x-transition>
                    <div class="card p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            社交链接
                        </h2>
                        <p class="text-sm text-gray-500 mb-6">设置您的社交媒体链接，这些链接将显示在博客各个位置</p>

                        <div class="space-y-6">
                            {{-- 微博 --}}
                            <div>
                                <label for="social_weibo" class="form-label flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M10.098 20c-4.612 0-8.353-2.807-8.353-6.27 0-1.235.76-2.742 1.877-3.615 1.087-.847 2.456-1.215 3.897-.81.37.104.734.25 1.088.437-.09-.37-.165-.747-.216-1.13-.13-.984.025-1.85.453-2.46.506-.722 1.47-1.18 2.52-1.18 1.84 0 3.343 1.52 3.343 3.38 0 .51-.116.98-.325 1.394.18.06.364.116.55.17 2.77.81 4.186 2.84 4.186 5.03 0 2.78-2.44 5.05-5.44 5.05-1.35 0-2.58-.47-3.58-1.25-.34.8-.87 1.45-1.5 1.95.34.1.7.15 1.05.15 1.2 0 2.4-.39 3.3-1.09-.05.2-.07.41-.07.63 0 1.93 1.37 3.29 3.15 3.29.89 0 1.68-.36 2.28-.93-.04.13-.06.26-.06.4 0 1.51 1.17 2.67 2.68 2.67.89 0 1.55-.35 2.03-.83.38.1.77.14 1.18.14 1.8 0 3.18-1.34 3.18-3.05 0-.35-.04-.68-.13-1 .46.26.95.4 1.48.4.28 0 .56-.04.83-.11.23.5.35 1.04.35 1.61 0 2.39-2.08 4.34-4.68 4.34-2.6 0-4.7-1.95-4.7-4.34 0-.27.03-.54.08-.8-.62.28-1.28.45-1.96.5.72-.43 1.2-1.11 1.2-1.9 0-.26-.04-.52-.11-.77-.38-.03-.74-.08-1.1-.16.13.03.27.05.4.05 1.65 0 3.02-1.5 3.02-3.25 0-.18-.01-.36-.05-.53-.44.26-.9.55-1.37.87.36-.12.72-.2 1.09-.24-.6.8-1.38 1.45-2.26 1.88 1.15-.14 2.23-.52 3.18-1.11-.17.53-.55.98-1.03 1.3.46-.07.91-.22 1.33-.45-.26.25-.58.46-.92.61-.16-.35-.4-.67-.71-.93-.32-.26-.68-.46-1.07-.6.3.16.57.36.8.6.24.24.44.51.58.8-.35-.1-.7-.18-1.07-.24-.04.35-.1.69-.18 1.02-.09.33-.2.65-.33.96-.14.31-.3.6-.47.88-.18.28-.37.54-.57.78-.2.24-.42.47-.65.67.3-.15.58-.33.85-.52.26-.2.51-.41.73-.64.23-.23.43-.47.61-.73.18-.26.33-.53.46-.81.13-.28.23-.57.31-.87.08-.3.14-.61.16-.92-.29.1-.6.18-.91.24.03-.24.05-.48.05-.73 0-2.21-1.58-4.06-3.68-4.48.09-.25.15-.5.18-.77-.45.26-.91.5-1.39.72.36-.53.63-1.1.8-1.71-.53.32-1.1.57-1.69.76.27-.53.46-1.1.58-1.69-.64.24-1.29.41-1.96.51.17-.6.26-1.23.26-1.87-.83.36-1.72.62-2.65.77.14-.58.25-1.17.31-1.77-1.08.21-2.18.32-3.3.32z"/>
                                    </svg>
                                    微博
                                </label>
                                <input type="url" 
                                       id="social_weibo" 
                                       name="social[weibo]"
                                       value="{{ old('social.weibo', config('settings.social_weibo')) }}"
                                       class="input"
                                       placeholder="https://weibo.com/yourname">
                            </div>

                            {{-- GitHub --}}
                            <div>
                                <label for="social_github" class="form-label flex items-center gap-2">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                    GitHub
                                </label>
                                <input type="url" 
                                       id="social_github" 
                                       name="social[github]"
                                       value="{{ old('social.github', config('settings.social_github')) }}"
                                       class="input"
                                       placeholder="https://github.com/yourname">
                            </div>

                            {{-- Twitter --}}
                            <div>
                                <label for="social_twitter" class="form-label flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                    Twitter / X
                                </label>
                                <input type="url" 
                                       id="social_twitter" 
                                       name="social[twitter]"
                                       value="{{ old('social.twitter', config('settings.social_twitter')) }}"
                                       class="input"
                                       placeholder="https://twitter.com/yourname">
                            </div>

                            {{-- 邮箱 --}}
                            <div>
                                <label for="social_email" class="form-label flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    邮箱
                                </label>
                                <input type="email" 
                                       id="social_email" 
                                       name="social[email]"
                                       value="{{ old('social.email', config('settings.social_email')) }}"
                                       class="input"
                                       placeholder="your@email.com">
                            </div>

                            {{-- 其他链接 --}}
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <h3 class="font-medium text-gray-900 dark:text-white mb-4">其他链接</h3>
                                
                                {{-- 掘金 --}}
                                <div class="mb-4">
                                    <label for="social_juejin" class="form-label flex items-center gap-2">
                                        <span class="text-lg">掘</span>
                                        掘金
                                    </label>
                                    <input type="url" 
                                           id="social_juejin" 
                                           name="social[juejin]"
                                           value="{{ old('social.juejin', config('settings.social_juejin')) }}"
                                           class="input"
                                           placeholder="https://juejin.cn/user/yourid">
                                </div>

                                {{-- 知乎 --}}
                                <div>
                                    <label for="social_zhihu" class="form-label flex items-center gap-2">
                                        <span class="text-lg font-bold text-blue-500">知</span>
                                        知乎
                                    </label>
                                    <input type="url" 
                                           id="social_zhihu" 
                                           name="social[zhihu]"
                                           value="{{ old('social.zhihu', config('settings.social_zhihu')) }}"
                                           class="input"
                                           placeholder="https://www.zhihu.com/people/yourid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 提交按钮 --}}
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="reset" class="btn-secondary">
                        重置
                    </button>
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        保存设置
                    </button>
                </div>
            </div>
        </div>
    </form>

</x-backend.layouts.app>
