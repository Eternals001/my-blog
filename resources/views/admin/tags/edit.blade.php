{{-- resources/views/admin/tags/edit.blade.php --}}
{{-- 编辑标签页面 --}}

<x-backend.layouts.app title="编辑标签">

    {{-- 页面标题 --}}
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tags.index') }}" 
               class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    编辑标签
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    修改标签「{{ $tag->name }}」的设置
                </p>
            </div>
        </div>
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

    {{-- 表单 --}}
    <form action="{{ route('admin.tags.update', $tag) }}" 
          method="POST" 
          x-data="{ 
              name: '{{ $tag->name }}', 
              slug: '{{ $tag->slug }}',
              generatingSlug: false,
              
              generateSlug() {
                  if (this.slug) return;
                  this.generatingSlug = true;
                  this.slug = this.name
                      .toLowerCase()
                      .replace(/[^\w\s-]/g, '')
                      .replace(/[\s_-]+/g, '-')
                      .replace(/^-+|-+$/g, '');
                  this.generatingSlug = false;
              }
          }">
        @csrf
        @method('PUT')

        <div class="max-w-2xl">
            {{-- 基础信息卡片 --}}
            <div class="card p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                    标签信息
                </h2>

                <div class="space-y-6">
                    {{-- 标签名称 --}}
                    <div>
                        <label for="name" class="form-label">
                            标签名称 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               x-model="name"
                               @input="name.length > 0 && !slug ? generateSlug() : null"
                               value="{{ old('name', $tag->name) }}"
                               required
                               class="input @error('name') border-red-500 focus:ring-red-500 @enderror"
                               placeholder="例如：Laravel">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            标签的中文或英文名称
                        </p>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 标签别名 --}}
                    <div>
                        <label for="slug" class="form-label">
                            标签别名 <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-gray-500">
                                    /
                                </span>
                                <input type="text" 
                                       id="slug" 
                                       name="slug" 
                                       x-model="slug"
                                       value="{{ old('slug', $tag->slug) }}"
                                       required
                                       class="input pl-6 pr-4 @error('slug') border-red-500 focus:ring-red-500 @enderror"
                                       placeholder="例如：laravel">
                            </div>
                            <button type="button" 
                                    @click="generateSlug()"
                                    class="btn-secondary px-3 py-2 flex-shrink-0"
                                    title="自动生成">
                                <svg class="w-5 h-5" :class="{ 'animate-spin': generatingSlug }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            用于 URL，字母、数字、连字符和下划线
                        </p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 标签描述 --}}
                    <div>
                        <label for="description" class="form-label">标签描述</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="input resize-none @error('description') border-red-500 focus:ring-red-500 @enderror"
                                  placeholder="简要描述这个标签的内容...">{{ old('description', $tag->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SEO 设置卡片 --}}
            <div class="card p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                    SEO 设置
                </h2>

                <div class="space-y-6">
                    {{-- SEO 标题 --}}
                    <div>
                        <label for="seo_title" class="form-label">SEO 标题</label>
                        <input type="text" 
                               id="seo_title" 
                               name="seo_title" 
                               value="{{ old('seo_title', $tag->seo_title) }}"
                               class="input @error('seo_title') border-red-500 focus:ring-red-500 @enderror"
                               placeholder="自定义 SEO 标题">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            留空则使用标签名称
                        </p>
                    </div>

                    {{-- SEO 描述 --}}
                    <div>
                        <label for="seo_description" class="form-label">SEO 描述</label>
                        <textarea id="seo_description" 
                                  name="seo_description" 
                                  rows="3"
                                  class="input resize-none @error('seo_description') border-red-500 focus:ring-red-500 @enderror"
                                  placeholder="用于搜索引擎显示的描述">{{ old('seo_description', $tag->seo_description) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            建议 150-200 字符
                        </p>
                    </div>
                </div>
            </div>

            {{-- 统计信息卡片 --}}
            <div class="card p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                    标签统计
                </h2>
                <dl class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                        <dt class="text-sm text-gray-600 dark:text-gray-400">文章数量</dt>
                        <dd class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $tag->published_posts_count ?? 0 }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                        <dt class="text-sm text-gray-600 dark:text-gray-400">创建时间</dt>
                        <dd class="text-lg font-medium text-gray-900 dark:text-white mt-1">{{ $tag->created_at->format('Y-m-d') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- 提交按钮 --}}
            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.tags.index') }}" class="btn-secondary">
                    取消
                </a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    保存修改
                </button>
            </div>
        </div>
    </form>

</x-backend.layouts.app>
