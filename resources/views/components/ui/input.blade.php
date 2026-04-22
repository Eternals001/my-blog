{{-- resources/views/components/ui/input.blade.php --}}
{{-- Input 输入框组件 --}}

@props([
    'type' => 'text',
    'name' => null,
    'id' => null,
    'value' => null,
    'placeholder' => '',
    'label' => null,
    'error' => false,
    'disabled' => false,
    'readonly' => false,
    'required' => false,
    'autocomplete' => null,
    'help' => null,
])

@php
$id = $id ?? $name;
$inputClasses = 'form-input w-full';
if ($error) {
    $inputClasses .= ' border-red-500 focus:ring-red-500 focus:border-red-500';
}
@endphp

<div class="space-y-1">
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input type="{{ $type }}" 
           id="{{ $id }}" 
           name="{{ $name }}"
           value="{{ $value }}"
           placeholder="{{ $placeholder }}"
           @if($disabled) disabled @endif
           @if($readonly) readonly @endif
           @if($required) required @endif
           @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
           class="{{ $inputClasses }} {{ $attributes->get('class') }}"
           {{ $attributes->except(['class']) }}>
    
    @if($help)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif
</div>