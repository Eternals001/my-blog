{{-- resources/views/admin/posts/edit.blade.php --}}
{{-- 编辑文章页面 --}}

<x-backend.layouts.app title="编辑文章">

    {{-- 页面标题 --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">编辑文章</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    修改文章「{{ $post->title }}」
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('posts.show', $post->slug) }}" 
                   target="_blank"
                   class="btn-secondary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    预览
                </a>
                <a href="{{ route('admin.posts.index') }}" class="btn-secondary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    返回列表
                </a>
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

    <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data" x-data="postEditor()">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- 左侧：主要编辑区 --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- 标题 --}}
                <div class="card p-6">
                    <label for="title" class="form-label">
                        文章标题 <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           x-model="title"
                           value="{{ old('title', $post->title) }}"
                           required
                           placeholder="输入文章标题..."
                           class="input text-lg font-semibold @error('title') border-red-500 @enderror"
                           @input="generateSlug()">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 内容编辑器 --}}
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <label class="form-label mb-0">
                            文章内容 <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <button type="button" 
                                    @click="showPreview = !showPreview"
                                    :class="showPreview ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400'"
                                    class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                预览
                            </button>
                        </div>
                    </div>
                    
                    {{-- 编辑器工具栏 --}}
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden mb-0">
                        <div class="flex flex-wrap items-center gap-0.5 p-2 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                            {{-- 标题 --}}
                            <div class="relative group">
                                <button type="button" class="toolbar-btn" title="标题">
                                    <span class="font-bold text-sm">H</span>
                                </button>
                                <div class="absolute top-full left-0 mt-1 py-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 hidden group-hover:block z-10 min-w-[100px]">
                                    <button type="button" @click="insertMarkdown('# ', '')" class="w-full px-3 py-1.5 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700">H1 标题</button>
                                    <button type="button" @click="insertMarkdown('## ', '')" class="w-full px-3 py-1.5 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700">H2 副标题</button>
                                    <button type="button" @click="insertMarkdown('### ', '')" class="w-full px-3 py-1.5 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700">H3 小标题</button>
                                </div>
                            </div>
                            
                            <span class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></span>
                            
                            {{-- 格式 --}}
                            <button type="button" @click="insertMarkdown('**', '**')" class="toolbar-btn" title="粗体">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/>
                                </svg>
                            </button>
                            <button type="button" @click="insertMarkdown('*', '*')" class="toolbar-btn" title="斜体">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4h4m-2 0v16m-4 0h8"/>
                                </svg>
                            </button>
                            <button type="button" @click="insertMarkdown('~~', '~~')" class="toolbar-btn" title="删除线">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v6m0 4v6M4 12h16"/>
                                </svg>
                            </button>
                            
                            <span class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></span>
                            
                            {{-- 列表 --}}
                            <button type="button" @click="insertMarkdown('- ', '')" class="toolbar-btn" title="无序列表">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            <button type="button" @click="insertMarkdown('1. ', '')" class="toolbar-btn" title="有序列表">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20h14M7 12h14M7 4h14M3 20h.01M3 12h.01M3 4h.01"/>
                                </svg>
                            </button>
                            
                            <span class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></span>
                            
                            {{-- 链接和图片 --}}
                            <button type="button" @click="insertMarkdown('[', '](url)')" class="toolbar-btn" title="链接">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                            </button>
                            <button type="button" @click="insertMarkdown('![alt](', ')')" class="toolbar-btn" title="图片">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </button>
                            
                            <span class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></span>
                            
                            {{-- 代码 --}}
                            <button type="button" @click="insertMarkdown('`', '`')" class="toolbar-btn" title="行内代码">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                </svg>
                            </button>
                            <button type="button" @click="insertMarkdown('\n```\n', '\n```\n')" class="toolbar-btn" title="代码块">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </button>
                            
                            <span class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></span>
                            
                            {{-- 引用 --}}
                            <button type="button" @click="insertMarkdown('> ', '')" class="toolbar-btn" title="引用">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10.5h3m-3 3h3m-3 3h3M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </button>
                            <button type="button" @click="insertMarkdown('\n---\n', '')" class="toolbar-btn" title="分隔线">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/>
                                </svg>
                            </button>
                        </div>
                        
                        {{-- 编辑器和预览切换 --}}
                        <div x-show="!showPreview">
                            <textarea id="content" 
                                      name="content" 
                                      x-ref="content"
                                      rows="20"
                                      required
                                      class="w-full px-4 py-3 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-mono text-sm focus:outline-none resize-y min-h-[400px] leading-relaxed">{{ old('content', $post->content) }}</textarea>
                        </div>
                        
                        {{-- 预览区域 --}}
                        <div x-show="showPreview" 
                             x-cloak
                             class="min-h-[400px] p-6 bg-white dark:bg-gray-900 prose dark:prose-invert max-w-none">
                            <div x-html="renderedContent" class="min-h-[400px]"></div>
                        </div>
                    </div>
                    
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        支持完整的 Markdown 语法
                    </p>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 摘要 --}}
                <div class="card p-6">
                    <label for="excerpt" class="form-label">
                        文章摘要
                    </label>
                    <textarea id="excerpt" 
                              name="excerpt" 
                              x-model="excerpt"
                              rows="3"
                              placeholder="输入文章摘要，留空则自动生成..."
                              class="input resize-none @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $post->excerpt) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        建议 150-200 字符，将显示在文章列表和 SEO 中
                    </p>
                </div>

                {{-- SEO 设置（可折叠） --}}
                <div class="card overflow-hidden" x-data="{ expanded: false }">
                    <button type="button" 
                            @click="expanded = !expanded"
                            class="w-full flex items-center justify-between p-6 text-left">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">SEO 设置</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">优化搜索引擎显示效果</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform" 
                             :class="{ 'rotate-180': expanded }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="expanded" x-collapse class="px-6 pb-6 space-y-4">
                        <div>
                            <label for="seo_title" class="form-label">SEO 标题</label>
                            <input type="text" 
                                   id="seo_title" 
                                   name="seo_title" 
                                   x-model="seoTitle"
                                   value="{{ old('seo_title', $post->seo_title) }}"
                                   placeholder="留空则使用文章标题"
                                   class="input">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                建议长度：60 字符以内
                            </p>
                        </div>
                        
                        <div>
                            <label for="meta_description" class="form-label">Meta 描述</label>
                            <textarea id="meta_description" 
                                      name="meta_description" 
                                      x-model="metaDescription"
                                      rows="3"
                                      placeholder="留空则使用文章摘要"
                                      class="input resize-none">{{ old('meta_description', $post->meta_description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                建议长度：150-160 字符以内
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 右侧：设置区 --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- 发布设置 --}}
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">发布设置</h3>
                    
                    {{-- 当前状态 --}}
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">当前状态</span>
                            <span class="px-2.5 py-1 text-xs rounded-full 
                                @if($post->status === 'published') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                @elseif($post->status === 'draft') bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                @endif">
                                @if($post->status === 'published') 已发布
                                @elseif($post->status === 'draft') 草稿
                                @else 定时发布 @endif
                            </span>
                        </div>
                        @if($post->published_at)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                发布于 {{ $post->published_at->format('Y-m-d H:i') }}
                            </p>
                        @endif
                    </div>
                    
                    {{-- 发布状态 --}}
                    <div class="mb-4">
                        <label class="form-label mb-3">更改状态</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50 dark:has-[:checked]:bg-primary-900/20">
                                <input type="radio" name="status" value="published" {{ old('status', $post->status) == 'published' ? 'checked' : '' }} class="text-primary-600 focus:ring-primary-500">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">立即发布</p>
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50 dark:has-[:checked]:bg-primary-900/20">
                                <input type="radio" name="status" value="draft" {{ old('status', $post->status) == 'draft' ? 'checked' : '' }} class="text-primary-600 focus:ring-primary-500">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">存为草稿</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    {{-- 提交按钮 --}}
                    <button type="submit" class="btn-primary w-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        保存修改
                    </button>
                    
                    {{-- 删除按钮 --}}
                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('确定要删除这篇文章吗？此操作不可撤销！')"
                                class="btn-danger w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            删除文章
                        </button>
                    </form>
                </div>

                {{-- 分类和标签 --}}
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">分类与标签</h3>
                    
                    {{-- 分类 --}}
                    <div class="mb-4">
                        <label for="category_id" class="form-label">
                            分类 <span class="text-red-500">*</span>
                        </label>
                        <select id="category_id" 
                                name="category_id" 
                                required
                                class="input @error('category_id') border-red-500 @enderror">
                            <option value="">选择分类</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- 标签 --}}
                    <div>
                        <label class="form-label">标签</label>
                        <div class="relative" x-data="{ 
                            selectedTags: {{ json_encode(old('tags', $post->tags->pluck('id')->toArray())) }},
                            searchQuery: '',
                            showDropdown: false,
                            
                            get filteredTags() {
                                return @json($tags).filter(tag => 
                                    tag.name.toLowerCase().includes(this.searchQuery.toLowerCase()) &&
                                    !this.selectedTags.includes(tag.id)
                                );
                            },
                            
                            selectTag(id) {
                                if (!this.selectedTags.includes(id)) {
                                    this.selectedTags.push(id);
                                }
                                this.searchQuery = '';
                                this.showDropdown = false;
                            },
                            
                            removeTag(id) {
                                this.selectedTags = this.selectedTags.filter(t => t !== id);
                            }
                        }">
                            {{-- 已选标签 --}}
                            <div class="min-h-[42px] p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 flex flex-wrap gap-2">
                                <template x-for="tagId in selectedTags" :key="tagId">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-lg text-sm">
                                        <span x-text="@json($tags).find(t => t.id === tagId)?.name"></span>
                                        <button type="button" @click="removeTag(tagId)" class="hover:text-primary-900 dark:hover:text-primary-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                                <input type="text"
                                       x-model="searchQuery"
                                       @focus="showDropdown = true"
                                       @blur="setTimeout(() => showDropdown = false, 200)"
                                       @keydown.enter.prevent="filteredTags[0] && selectTag(filteredTags[0].id)"
                                       placeholder="搜索或选择标签..."
                                       class="flex-1 min-w-[120px] border-none outline-none bg-transparent text-sm">
                            </div>
                            
                            {{-- 隐藏字段 --}}
                            <template x-for="tagId in selectedTags" :key="tagId">
                                <input type="hidden" name="tags[]" :value="tagId">
                            </template>
                            
                            {{-- 下拉选项 --}}
                            <div x-show="showDropdown && filteredTags.length > 0"
                                 x-transition
                                 class="absolute z-10 w-full mt-1 py-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 max-h-48 overflow-y-auto">
                                <template x-for="tag in filteredTags.slice(0, 10)" :key="tag.id">
                                    <button type="button"
                                            @click="selectTag(tag.id)"
                                            class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <span class="font-medium" x-text="tag.name"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 封面图 --}}
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">封面图片</h3>
                    
                    <div class="space-y-4">
                        {{-- 预览 --}}
                        <div x-show="featuredImage || '{{ $post->featured_image }}'" class="relative rounded-xl overflow-hidden">
                            <img :src="featuredImage || '{{ $post->featured_image }}'" alt="" class="w-full h-40 object-cover">
                            <button type="button" @click="featuredImage = null; $refs.featured_image.value = ''" class="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        
                        {{-- 上传 --}}
                        <div class="relative">
                            <input type="file" 
                                   id="featured_image" 
                                   name="featured_image" 
                                   x-ref="featured_image"
                                   accept="image/*"
                                   @change="previewImage($event)"
                                   class="hidden">
                            <label for="featured_image" 
                                   :class="(featuredImage || '{{ $post->featured_image }}') ? 'hidden' : 'flex'"
                                   class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl cursor-pointer hover:border-primary-500 dark:hover:border-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        点击上传封面图
                                    </p>
                                </div>
                            </label>
                            <label for="featured_image"
                                   x-show="featuredImage || '{{ $post->featured_image }}'"
                                   class="flex flex-col items-center justify-center w-full h-12 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl cursor-pointer hover:border-primary-500 dark:hover:border-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-colors">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    更换封面图
                                </p>
                            </label>
                        </div>
                    </div>
                </div>
                
                {{-- 文章统计 --}}
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">文章统计</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between items-center">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">浏览量</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($post->view_count) }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">评论数</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $post->comments_count ?? 0 }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">创建时间</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $post->created_at->format('Y-m-d') }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">更新时间</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $post->updated_at->format('Y-m-d') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </form>

</x-backend.layouts.app>

@push('styles')
<style>
    .toolbar-btn {
        @apply p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100 transition-colors;
    }
    [x-cloak] { display: none !important; }
    
    /* Markdown 预览样式增强 */
    .prose {
        @apply text-gray-900 dark:text-gray-100;
    }
    .prose h1 { @apply text-3xl font-bold mb-4 mt-8 first:mt-0; }
    .prose h2 { @apply text-2xl font-bold mb-3 mt-6; }
    .prose h3 { @apply text-xl font-semibold mb-2 mt-5; }
    .prose p { @apply mb-4 leading-relaxed; }
    .prose ul { @apply list-disc list-inside mb-4 space-y-1; }
    .prose ol { @apply list-decimal list-inside mb-4 space-y-1; }
    .prose li { @apply text-gray-700 dark:text-gray-300; }
    .prose blockquote {
        @apply border-l-4 border-primary-500 pl-4 py-2 my-4 bg-primary-50 dark:bg-primary-900/20 italic;
    }
    .prose code {
        @apply px-1.5 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-sm font-mono text-primary-600 dark:text-primary-400;
    }
    .prose pre {
        @apply bg-gray-900 dark:bg-gray-950 text-gray-100 p-4 rounded-xl overflow-x-auto my-4;
    }
    .prose pre code {
        @apply bg-transparent p-0 text-inherit;
    }
    .prose a {
        @apply text-primary-600 dark:text-primary-400 hover:underline;
    }
    .prose img {
        @apply rounded-xl my-4;
    }
    .prose hr {
        @apply my-8 border-gray-200 dark:border-gray-700;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('postEditor', () => ({
        title: '{{ $post->title }}',
        seoTitle: '{{ $post->seo_title ?? '' }}',
        metaDescription: '{{ $post->meta_description ?? '' }}',
        excerpt: '{{ $post->excerpt ?? '' }}',
        featuredImage: null,
        showPreview: false,
        renderedContent: '',
        
        init() {
            const content = this.$refs.content?.value || '';
            if (content) {
                this.renderedContent = this.parseMarkdown(content);
            }
        },
        
        generateSlug() {},
        
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.featuredImage = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        insertMarkdown(before, after) {
            const textarea = this.$refs.content;
            if (!textarea) return;
            
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            const selected = text.substring(start, end) || '文字';
            
            const newText = text.substring(0, start) + before + selected + after + text.substring(end);
            textarea.value = newText;
            
            const newCursorPos = start + before.length + selected.length;
            textarea.selectionStart = newCursorPos;
            textarea.selectionEnd = newCursorPos;
            textarea.focus();
            
            this.renderedContent = this.parseMarkdown(newText);
        },
        
        parseMarkdown(text) {
            if (!text) return '';
            
            let html = text
                .replace(/```(\w*)\n([\s\S]*?)```/g, '<pre><code class="language-$1">$2</code></pre>')
                .replace(/^### (.+)$/gm, '<h3>$1</h3>')
                .replace(/^## (.+)$/gm, '<h2>$1</h2>')
                .replace(/^# (.+)$/gm, '<h1>$1</h1>')
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.+?)\*/g, '<em>$1</em>')
                .replace(/~~(.+?)~~/g, '<del>$1</del>')
                .replace(/!\[(.+?)\]\((.+?)\)/g, '<img src="$2" alt="$1" loading="lazy">')
                .replace(/\[(.+?)\]\((.+?)\)/g, '<a href="$2" target="_blank" rel="noopener">$1</a>')
                .replace(/`([^`]+)`/g, '<code>$1</code>')
                .replace(/^> (.+)$/gm, '<blockquote>$1</blockquote>')
                .replace(/^- (.+)$/gm, '<li>$1</li>')
                .replace(/^---$/gm, '<hr>')
                .replace(/\n\n/g, '</p><p>')
                .replace(/\n/g, '<br>');
            
            html = '<p>' + html + '</p>';
            html = html.replace(/<p><\/p>/g, '');
            html = html.replace(/(<li>.*?<\/li>)/g, '<ul>$1</ul>').replace(/<\/ul><ul>/g, '');
            
            return html;
        }
    }));
});
</script>
@endpush
