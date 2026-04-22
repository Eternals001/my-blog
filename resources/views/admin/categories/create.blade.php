{{-- resources/views/admin/categories/create.blade.php --}}
{{-- 创建分类页面 --}}

<x-backend.layouts.app title="创建分类">

    {{-- 页面标题 --}}
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.index') }}" 
               class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    创建分类
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    添加一个新的文章分类
                </p>
            </div>
        </div>
    </div>

    {{-- 表单 --}}
    <form action="{{ route('admin.categories.store') }}" 
          method="POST" 
          x-data="{ 
              name: '', 
              slug: '',
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- 主表单区域 --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- 基础信息卡片 --}}
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        基础信息
                    </h2>

                    <div class="space-y-6">
                        {{-- 上级分类 --}}
                        <div>
                            <label for="parent_id" class="form-label">上级分类</label>
                            <select id="parent_id" 
                                    name="parent_id" 
                                    class="input">
                                <option value="">无（顶级分类）</option>
                                @foreach($categories as $parentCategory)
                                    <option value="{{ $parentCategory->id }}" 
                                            {{ old('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                        {{ str_repeat('├─', $parentCategory->depth ?? 0) }}{{ $parentCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                选择上级分类将创建子分类
                            </p>
                        </div>

                        {{-- 分类名称 --}}
                        <div>
                            <label for="name" class="form-label">
                                分类名称 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   x-model="name"
                                   @input="name.length > 0 && !slug ? generateSlug() : null"
                                   value="{{ old('name') }}"
                                   required
                                   class="input @error('name') border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="例如：前端开发">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 分类别名 --}}
                        <div>
                            <label for="slug" class="form-label">
                                分类别名 <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="text" 
                                       id="slug" 
                                       name="slug" 
                                       x-model="slug"
                                       value="{{ old('slug') }}"
                                       required
                                       class="input flex-1 @error('slug') border-red-500 focus:ring-red-500 @enderror"
                                       placeholder="例如：frontend">
                                <button type="button" 
                                        @click="generateSlug()"
                                        class="btn-secondary px-3 py-2"
                                        title="自动生成">
                                    <svg class="w-5 h-5" :class="{ 'animate-spin': generatingSlug }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                用于 URL，字母、数字和连字符
                            </p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 分类描述 --}}
                        <div>
                            <label for="description" class="form-label">分类描述</label>
                            <textarea id="description" 
                                     name="description" 
                                     rows="3"
                                     class="input resize-none @error('description') border-red-500 focus:ring-red-500 @enderror"
                                     placeholder="简要描述这个分类的内容...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- 设置侧边栏 --}}
            <div class="space-y-6">
                {{-- 高级设置卡片 --}}
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        其他设置
                    </h2>

                    <div class="space-y-6">
                        {{-- 排序 --}}
                        <div>
                            <label for="order" class="form-label">排序</label>
                            <input type="number" 
                                   id="order" 
                                   name="order" 
                                   value="{{ old('order', $maxOrder ?? 0) }}"
                                   min="0"
                                   class="input @error('order') border-red-500 focus:ring-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                数字越小越靠前
                            </p>
                        </div>

                        {{-- 分类图标/颜色 --}}
                        <div>
                            <label class="form-label">图标颜色</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['primary', 'accent', 'green', 'red', 'blue', 'purple', 'yellow', 'pink'] as $color)
                                    <label class="cursor-pointer">
                                        <input type="radio" 
                                               name="color" 
                                               value="{{ $color }}"
                                               {{ old('color', 'primary') === $color ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-8 h-8 rounded-lg bg-{{ $color }}-500 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-{{ $color }}-500 dark:peer-checked:ring-offset-gray-900 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">{{ Str::substr($color, 0, 1) }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- 状态 --}}
                        <div>
                            <label class="form-label">状态</label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="sr-only peer"
                                       id="is_active">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">启用此分类</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- SEO 设置卡片 --}}
                <div class="card p-6">
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
                                   value="{{ old('seo_title') }}"
                                   class="input @error('seo_title') border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="自定义 SEO 标题">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                留空则使用分类名称
                            </p>
                        </div>

                        {{-- SEO 描述 --}}
                        <div>
                            <label for="seo_description" class="form-label">SEO 描述</label>
                            <textarea id="seo_description" 
                                      name="seo_description" 
                                      rows="3"
                                      class="input resize-none @error('seo_description') border-red-500 focus:ring-red-500 @enderror"
                                      placeholder="用于搜索引擎显示的描述">{{ old('seo_description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                建议 150-200 字符
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 提交按钮 --}}
        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                取消
            </a>
            <button type="submit" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                创建分类
            </button>
        </div>
    </form>

</x-backend.layouts.app>
