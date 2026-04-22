{{-- resources/views/components/logo.blade.php --}}
{{-- Logo 组件 --}}

@props([
    'class' => 'h-8 w-8',
])

@if($svg = config('blog.logo.svg'))
    {{-- 自定义 SVG Logo --}}
    <div class="{{ $class }} {{ $attributes->get('class') }}">
        {!! $svg !!}
    </div>
@elseif($image = config('blog.logo.image'))
    {{-- 图片 Logo --}}
    <img src="{{ asset($image) }}" 
         alt="{{ config('app.name') }}" 
         class="{{ $class }}"
         {{ $attributes }}>
@else
    {{-- 默认文字 Logo --}}
    <div class="{{ $class }} {{ $attributes->get('class') }} flex items-center justify-center rounded-lg bg-gradient-primary text-white font-bold text-lg">
        {{ Str::substr(config('app.name'), 0, 1) }}
    </div>
@endif
