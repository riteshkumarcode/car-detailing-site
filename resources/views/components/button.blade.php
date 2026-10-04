@props([
    'variant' => 'primary', // 'primary', 'secondary', 'light', 'teal'
    'size' => 'default',    // 'default', 'sm', 'lg'
    'href' => null,
    'type' => 'button',
])

@php
    $baseStyles = "font-bold rounded-[10px] inline-flex items-center justify-center gap-2 select-none active:scale-[0.98] transition-all duration-150 focus:outline-none focus:ring-3 focus:ring-amber focus:ring-offset-2";

    $variants = [
        'primary'   => 'bg-amber text-ink hover:bg-amber-400 shadow-sm border border-transparent',
        'secondary' => 'bg-transparent text-ink border-2 border-ink hover:bg-ink hover:text-white',
        'light'     => 'bg-transparent text-white border-2 border-white/80 hover:bg-white hover:text-ink',
        'teal'      => 'bg-teal text-white hover:bg-teal-deep shadow-sm border border-transparent',
    ];

    $sizes = [
        'sm'      => 'min-h-[44px] px-4 py-2 text-[15px]',
        'default' => 'min-h-[52px] px-6 py-3 text-[17px]',
        'lg'      => 'min-h-[56px] px-8 py-4 text-[18px]',
    ];

    $classes = "{$baseStyles} " . ($variants[$variant] ?? $variants['primary']) . " " . ($sizes[$size] ?? $sizes['default']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
