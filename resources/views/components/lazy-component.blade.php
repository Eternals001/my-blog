{{-- resources/views/components/lazy-component.blade.php --}}
{{-- 延迟加载组件 --}}

@props([
    'component',
    'params' => [],
    'loading' => true,
    'minHeight' => '200px',
])

<div x-data="{ 
    loaded: false, 
    visible: false,
    checkVisibility() {
        const el = this.$refs.container;
        const rect = el.getBoundingClientRect();
        const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
        if (isVisible && !this.loaded) {
            this.loaded = true;
            this.visible = true;
        }
    }
}" 
     x-init="checkVisibility()"
     x-ref="container"
     @scroll.window.passive="checkVisibility()"
     x-intersect.once="checkVisibility()"
     class="relative">
    
    {{-- 加载占位符 --}}
    @if($loading)
        <div x-show="!loaded" 
             class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-gray-800"
             :style="{ 'min-height': '{{ $minHeight }}' }">
            <div class="flex flex-col items-center gap-3">
                <svg class="w-8 h-8 text-primary-500 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm text-gray-500 dark:text-gray-400">加载中...</span>
            </div>
        </div>
    @endif
    
    {{-- 实际组件 --}}
    <div x-show="loaded" x-cloak>
        @if(is_string($component))
            @if(is_array($params) && count($params) > 0)
                @php
                    $componentString = $component;
                    $slotContent = trim($slots->toHtml());
                @endphp
                @if(View::exists($componentString))
                    <x-dynamic-component :component="$componentString" :params="$params" />
                @else
                    <x-dynamic-component :component="$componentString" />
                @endif
            @else
                <x-dynamic-component :component="$component" />
            @endif
        @else
            {{ $slot ?? '' }}
        @endif
    </div>
</div>

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // 注册 Alpine 拦截器插件
    document.addEventListener('alpine:init', () => {
        Alpine.directive('intersect', (el, { expression, modifiers }, { evaluateLater }) => {
            if (!('IntersectionObserver' in window)) return;
            
            const callback = evaluateLater(expression);
            const observer = new IntersectionObserver((entries) => {
                callback();
            }, {
                root: modifiers.includes('root') ? document.querySelector(modifiers[0]) : null,
                rootMargin: modifiers.includes('margin') ? modifiers[0] : '0px',
                threshold: modifiers.includes('threshold') ? parseFloat(modifiers[0]) : 0
            });
            
            observer.observe(el);
        });
    });
</script>
@endpush
