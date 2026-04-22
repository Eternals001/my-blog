{{-- resources/views/components/performance/preload-hints.blade.php --}}
{{-- 性能优化：资源预加载组件 --}}

{{-- 关键 CSS 预加载 --}}
@if(isset($criticalCss) && $criticalCss)
    <link rel="preload" href="{{ $criticalCss }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ $criticalCss }}"></noscript>
@endif

{{-- 字体预加载 --}}
@if(isset($fonts) && is_array($fonts))
    @foreach($fonts as $font)
        <link rel="preload" 
               href="{{ $font['url'] }}" 
               as="font" 
               type="{{ $font['type'] ?? 'font/woff2' }}" 
               crossorigin>
    @endforeach
@endif

{{-- 关键图片预加载 --}}
@if(isset($preloadImages) && is_array($preloadImages))
    @foreach($preloadImages as $image)
        <link rel="preload" 
               href="{{ $image['url'] }}" 
               as="image"
               @if(isset($image['type']))
                   type="{{ $image['type'] }}"
               @endif
               @if(isset($image['media']))
                   media="{{ $image['media'] }}"
               @endif>
    @endforeach
@endif

{{-- 首屏图片预加载（延迟加载排除） --}}
@if(isset($aboveFoldImages) && is_array($aboveFoldImages))
    @foreach($aboveFoldImages as $image)
        <x-lazy-image 
            :src="'{{ $image['url'] }}'"
            alt="{{ $image['alt'] ?? '' }}"
            loading="eager"
            class="{{ $image['class'] ?? '' }}"
        />
    @endforeach
@endif

{{-- DNS 预解析 --}}
@if(isset($dnsPrefetch) && is_array($dnsPrefetch))
    @foreach($dnsPrefetch as $domain)
        <link rel="dns-prefetch" href="//{{ $domain }}">
    @endforeach
@endif

{{-- 预连接 --}}
@if(isset($preconnect) && is_array($preconnect))
    @foreach($preconnect as $url)
        <link rel="preconnect" href="{{ $url }}" crossorigin>
    @endforeach
@endif
