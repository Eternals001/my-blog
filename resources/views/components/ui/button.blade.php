{{-- resources/views/components/ui/button.blade.php --}}
{{-- Button 组件 --}}

@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variantClasses = [
    'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 dark:bg-primary-500 dark:hover:bg-primary-600',
    'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
    'accent' => 'bg-accent-500 text-white hover:bg-accent-600 focus:ring-accent-500',
    'ghost' => 'bg-transparent text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
];

$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm gap-1.5',
    'md' => 'px-4 py-2 text-sm gap-2',
    'lg' => 'px-6 py-3 text-base gap-2',
    'xl' => 'px-8 py-4 text-lg gap-3',
];

$classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
@endphp

@if($href)
    <a href="{{ $href }}" 
       class="{{ $classes }} {{ $attributes->get('class') }}"
       @if($disabled) disabled @endif
       {{ $attributes->except('class') }}>
        @if($icon && $iconPosition === 'left')
            <span class="shrink-0">{!! $icon !!}</span>
        @endif
        <span>{{ $slot }}</span>
        @if($icon && $iconPosition === 'right')
            <span class="shrink-0">{!! $icon !!}</span>
        @endif
    </a>
@else
    <button type="{{ $type }}" 
            class="{{ $classes }} {{ $attributes->get('class') }}"
            @if($disabled || $loading) disabled @endif
            {{ $attributes->except('class') }}>
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <span class="shrink-0">{!! $icon !!}</span>
        @endif
        <span>{{ $slot }}</span>
        @if($icon && $iconPosition === 'right')
            <span class="shrink-0">{!! $icon !!}</span>
        @endif
    </button>
@endif