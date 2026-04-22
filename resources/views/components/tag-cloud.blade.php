{{-- resources/views/components/tag-cloud.blade.php --}}
{{-- 标签云组件 --}}

@props([
    'tags', // Collection of tags
    'maxItems' => 20,
    'sizes' => true, // 是否显示不同大小
    'showCount' => false,
    'colorScheme' => 'primary', // primary | accent | mixed
])

@php
    // 计算字体大小权重
    $maxCount = $tags->max('posts_count') ?: 1;
    $minCount = $tags->min('posts_count') ?: 0;
    $countRange = max($maxCount - $minCount, 1);
    
    // 颜色映射
    $colorMap = [
        'primary' => [
            'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-primary-100 dark:hover:bg-primary-900/30 hover:text-primary-700 dark:hover:text-primary-300',
            'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 hover:bg-primary-100 dark:hover:bg-primary-900/50 hover:text-primary-800 dark:hover:text-primary-200',
        ],
        'accent' => [
            'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-accent-100 dark:hover:bg-accent-900/30 hover:text-accent-700 dark:hover:text-accent-300',
            'bg-accent-50 dark:bg-accent-900/30 text-accent-700 dark:text-accent-300 hover:bg-accent-100 dark:hover:bg-accent-900/50 hover:text-accent-800 dark:hover:text-accent-200',
        ],
        'mixed' => [
            'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300',
            'bg-accent-50 dark:bg-accent-900/30 text-accent-700 dark:text-accent-300',
            'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300',
            'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300',
            'bg-pink-50 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300',
        ],
    ];
    
    $displayTags = $tags->take($maxItems);
@endphp

<div class="tag-cloud flex flex-wrap gap-2">
    @foreach($displayTags as $index => $tag)
        @php
            // 计算相对权重 (0-1)
            $weight = $countRange > 0 ? ($tag->posts_count - $minCount) / $countRange : 0;
            
            // 字体大小
            $fontSize = $sizes ? match(true):
                ($weight < 0.25 ? 'text-xs' : 
                ($weight < 0.5 ? 'text-sm' : 
                ($weight < 0.75 ? 'text-base' : 'text-lg'))) : 'text-sm';
            
            // 颜色
            $colors = $colorMap[$colorScheme];
            if ($colorScheme === 'mixed') {
                $colorIndex = $index % count($colors);
                $colorClass = $colors[$colorIndex];
            } else {
                $colorClass = $weight > 0.5 ? $colors[1] : $colors[0];
            }
        @endphp
        
        <a href="{{ route('tags.show', $tag->slug) }}"
           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full font-medium transition-all duration-200 {{ $fontSize }} {{ $colorClass }}"
           title="{{ $tag->name }}{{ $showCount ? " ({$tag->posts_count})" : '' }}">
            <span>{{ $tag->name }}</span>
            
            @if($showCount)
                <span class="text-xs opacity-75">({{ $tag->posts_count }})</span>
            @endif
        </a>
    @endforeach
</div>

{{-- 更多标签链接 --}}
@if($tags->count() > $maxItems)
    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('tags.index') }}" 
           class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
            查看全部 {{ $tags->count() }} 个标签 →
        </a>
    </div>
@endif
