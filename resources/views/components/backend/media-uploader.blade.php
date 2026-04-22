{{-- resources/views/components/backend/media-uploader.blade.php --}}
{{-- 媒体文件上传组件 --}}

@props([
    'uploadUrl' => route('backend.media.upload'),
    'maxSize' => 10 * 1024 * 1024, // 10MB
    'acceptedTypes' => '.jpg,.jpeg,.png,.gif,.webp,.svg,.mp4,.mov,.avi,.pdf,.doc,.docx,.xls,.xlsx',
])

<div x-data="mediaUploader({
    url: '{{ $uploadUrl }}',
    maxSize: {{ $maxSize }},
    acceptedTypes: '{{ $acceptedTypes }}'
})" 
     class="bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 p-8 transition-colors"
     :class="{ 'border-primary-500 dark:border-primary-400 bg-primary-50 dark:bg-primary-900/10': isDragging }"
     @dragover.prevent="isDragging = true"
     @dragleave.prevent="isDragging = false"
     @drop.prevent="handleDrop($event)">
    
    {{-- 关闭按钮 --}}
    @if(isset($closeable) && $closeable)
        <button @click="$dispatch('close')" 
                class="absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    @endif
    
    {{-- 上传区域 --}}
    <div class="text-center">
        {{-- 图标 --}}
        <div class="mb-4">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full transition-colors"
                 :class="isDragging ? 'bg-primary-100 dark:bg-primary-900/30' : 'bg-gray-100 dark:bg-gray-700'">
                <svg class="w-8 h-8 transition-colors"
                     :class="isDragging ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400'"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
        </div>
        
        {{-- 标题 --}}
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
            <span x-show="!isDragging">拖拽文件到此处上传</span>
            <span x-show="isDragging">松开以上传文件</span>
        </h3>
        
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            或点击下方按钮选择文件
        </p>
        
        {{-- 文件输入 --}}
        <input type="file" 
               id="file-input"
               x-ref="fileInput"
               @change="handleFileSelect($event)"
               :accept="acceptedTypes"
               multiple
               class="hidden">
        
        <button @click="$refs.fileInput.click()" 
                :disabled="uploading"
                class="btn-primary inline-flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            选择文件
        </button>
        
        <p class="mt-3 text-xs text-gray-400 dark:text-gray-500">
            支持格式: JPG, PNG, GIF, WebP, SVG, MP4, PDF, DOC, XLS 等<br>
            最大文件大小: {{ number_format($maxSize / 1024 / 1024, 0) }}MB
        </p>
    </div>
    
    {{-- 上传进度列表 --}}
    <div x-show="files.length > 0" class="mt-6 space-y-3">
        <template x-for="(file, index) in files" :key="index">
            <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-4">
                <div class="flex items-center gap-4">
                    {{-- 预览图 --}}
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-700">
                        <template x-if="file.preview">
                            <img :src="file.preview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!file.preview">
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </template>
                    </div>
                    
                    {{-- 文件信息 --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="file.name"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="formatSize(file.size)"></p>
                        
                        {{-- 进度条 --}}
                        <div class="mt-2">
                            <div class="h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-primary-500 rounded-full transition-all duration-300"
                                     :style="{ width: file.progress + '%' }"></div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- 状态指示器 --}}
                    <div class="flex-shrink-0">
                        <template x-if="file.status === 'uploading'">
                            <svg class="w-6 h-6 text-primary-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <template x-if="file.status === 'success'">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </template>
                        <template x-if="file.status === 'error'">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </template>
                    </div>
                    
                    {{-- 删除按钮 --}}
                    <button @click="removeFile(index)" 
                            x-show="file.status !== 'uploading'"
                            class="flex-shrink-0 p-1 text-gray-400 hover:text-red-500 rounded transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
                
                {{-- 错误信息 --}}
                <p x-show="file.error" x-text="file.error" class="mt-2 text-sm text-red-500"></p>
            </div>
        </template>
    </div>
    
    {{-- 上传按钮 --}}
    <div x-show="files.length > 0 && hasPendingFiles" class="mt-6 flex justify-end gap-3">
        <button @click="clearAll" 
                :disabled="uploading"
                class="btn-outline disabled:opacity-50">
            清除全部
        </button>
        <button @click="uploadAll" 
                :disabled="uploading"
                class="btn-primary inline-flex items-center gap-2 disabled:opacity-50">
            <template x-if="uploading">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </template>
            上传全部
        </button>
    </div>
</div>

@push('scripts')
<script>
    function mediaUploader(config) {
        return {
            url: config.url,
            maxSize: config.maxSize,
            acceptedTypes: config.acceptedTypes,
            isDragging: false,
            files: [],
            uploading: false,
            
            get hasPendingFiles() {
                return this.files.some(f => f.status === 'pending');
            },
            
            handleDrop(event) {
                this.isDragging = false;
                const files = Array.from(event.dataTransfer.files);
                this.addFiles(files);
            },
            
            handleFileSelect(event) {
                const files = Array.from(event.target.files);
                this.addFiles(files);
                event.target.value = '';
            },
            
            addFiles(files) {
                files.forEach(file => {
                    // 检查文件大小
                    if (file.size > this.maxSize) {
                        this.$dispatch('upload-error', { message: `${file.name} 超过最大文件大小限制` });
                        return;
                    }
                    
                    // 创建预览
                    const fileObj = {
                        file,
                        name: file.name,
                        size: file.size,
                        status: 'pending',
                        progress: 0,
                        error: null,
                        preview: null
                    };
                    
                    // 生成预览图
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            fileObj.preview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                    
                    this.files.push(fileObj);
                });
            },
            
            removeFile(index) {
                this.files.splice(index, 1);
            },
            
            clearAll() {
                this.files = [];
            },
            
            formatSize(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
            },
            
            async uploadAll() {
                const pendingFiles = this.files.filter(f => f.status === 'pending');
                if (pendingFiles.length === 0) return;
                
                this.uploading = true;
                
                for (const fileObj of pendingFiles) {
                    await this.uploadFile(fileObj);
                }
                
                this.uploading = false;
                
                // 检查是否全部成功
                const allSuccess = this.files.every(f => f.status === 'success');
                const hasErrors = this.files.some(f => f.status === 'error');
                
                if (allSuccess) {
                    this.$dispatch('upload-complete', { success: true });
                } else if (hasErrors) {
                    this.$dispatch('upload-error', { message: '部分文件上传失败' });
                }
            },
            
            async uploadFile(fileObj) {
                fileObj.status = 'uploading';
                fileObj.progress = 0;
                fileObj.error = null;
                
                const formData = new FormData();
                formData.append('file', fileObj.file);
                
                try {
                    const xhr = new XMLHttpRequest();
                    
                    // 进度监听
                    xhr.upload.addEventListener('progress', (e) => {
                        if (e.lengthComputable) {
                            fileObj.progress = Math.round((e.loaded / e.total) * 100);
                        }
                    });
                    
                    // 完成处理
                    await new Promise((resolve, reject) => {
                        xhr.onload = () => {
                            if (xhr.status >= 200 && xhr.status < 300) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    if (response.success) {
                                        fileObj.status = 'success';
                                        fileObj.progress = 100;
                                        resolve();
                                    } else {
                                        fileObj.status = 'error';
                                        fileObj.error = response.message || '上传失败';
                                        reject(new Error(response.message));
                                    }
                                } catch (e) {
                                    fileObj.status = 'success';
                                    fileObj.progress = 100;
                                    resolve();
                                }
                            } else {
                                fileObj.status = 'error';
                                fileObj.error = '服务器错误';
                                reject(new Error('Server error'));
                            }
                        };
                        
                        xhr.onerror = () => {
                            fileObj.status = 'error';
                            fileObj.error = '网络错误';
                            reject(new Error('Network error'));
                        };
                        
                        xhr.open('POST', this.url);
                        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                        xhr.send(formData);
                    });
                } catch (error) {
                    console.error('Upload error:', error);
                }
            }
        }
    }
</script>
@endpush
