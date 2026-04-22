{{-- resources/views/components/lazy-image.blade.php --}}
{{-- 懒加载图片组件 --}}

@props([
    'src',
    'alt' => '',
    'width' => null,
    'height' => null,
    'class' => '',
    'loading' => 'lazy', // lazy | eager
    'placeholder' => null,
])

<div x-data="lazyImage()" 
     x-init="init()"
     class="relative overflow-hidden {{ $class }}"
     :class="{ 'bg-gray-100 dark:bg-gray-800 animate-pulse': !loaded }">
    
    {{-- 占位符 --}}
    <template x-if="!loaded && loading === 'lazy'">
        <div class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
            <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </template>
    
    {{-- 实际图片 --}}
    <img :src="loaded ? '{{ $src }}' : '{{ $placeholder ?? 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1 1"%3E%3Crect fill="%23f3f4f6" width="1" height="1"/%3E%3C/svg%3E' }}'"
         :alt="{{ json_encode($alt) }}"
         :width="{{ $width ? "'{$width}'" : 'null' }}"
         :height="{{ $height ? "'{$height}'" : 'null' }}"
         :loading="loading"
         :class="{ 'opacity-0': !loaded && loading === 'lazy' }"
         class="w-full h-full object-cover transition-opacity duration-300"
         @load="handleLoad()"
         @error="handleError()">
</div>

@push('scripts')
<script>
    function lazyImage() {
        return {
            loaded: false,
            
            init() {
                // 如果是立即加载，直接显示
                if ('{{ $loading }}' === 'eager') {
                    this.loaded = true;
                }
                
                // 使用 Intersection Observer 进行懒加载
                if ('{{ $loading }}' === 'lazy' && 'IntersectionObserver' in window) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                this.loaded = true;
                                observer.disconnect();
                            }
                        });
                    }, {
                        rootMargin: '100px',
                        threshold: 0.01
                    });
                    
                    observer.observe(this.$el);
                } else {
                    this.loaded = true;
                }
            },
            
            handleLoad() {
                this.loaded = true;
            },
            
            handleError() {
                console.warn('Failed to load image');
                this.loaded = true;
            }
        }
    }
</script>
@endpush
