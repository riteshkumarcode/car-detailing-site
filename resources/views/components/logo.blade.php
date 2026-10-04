@props([
    'variant' => 'light', // 'light' (for light bg, dark text) or 'dark' (for dark bg, white text)
    'height' => 40,
    'showText' => true,
])

@php
    $ringColor = $variant === 'dark' ? '#FFFFFF' : '#1F5E57';
    $textColor = $variant === 'dark' ? 'text-white' : 'text-ink';
    $crossColor = '#F2A93B';
@endphp

<a href="{{ url('/') }}" class="inline-flex items-center gap-3 group select-none text-decoration-none">
    {{-- Exact SVG Mark from Brand Brief --}}
    <svg style="height: {{ $height }}px; width: {{ $height }}px;" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 transition-transform duration-200 group-hover:scale-105">
        <circle cx="20" cy="20" r="16.5" fill="none" stroke="{{ $ringColor }}" stroke-width="4"/>
        <path d="M20 3.5v9M20 27.5v9M3.5 20h9M27.5 20h9" stroke="{{ $ringColor }}" stroke-width="4"/>
        <path d="M16.5 11h7v5.5H29v7h-5.5V29h-7v-5.5H11v-7h5.5z" fill="{{ $crossColor }}"/>
    </svg>

    @if($showText)
        <div class="flex flex-col leading-none text-left">
            <span class="font-display font-semibold text-[11px] tracking-wider uppercase {{ $variant === 'dark' ? 'text-white/80' : 'text-muted' }}">The</span>
            <span class="font-display font-extrabold text-[19px] tracking-tight {{ $textColor }}" style="font-stretch: 125%;">Drive Clinic</span>
        </div>
    @endif
</a>
