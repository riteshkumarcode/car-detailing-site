@props([
    'number' => null,
    'size' => 'default', // 'default', 'sm', 'lg'
])

@php
    $raw = $number ?? $slot->toHtml();
    $cleaned = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $raw));
    
    // Format e.g. JK02AB1234 -> JK 02 AB 1234
    if (preg_match('/^([A-Z]{2})([0-9]{1,2})([A-Z]{1,3})([0-9]{4})$/', $cleaned, $m)) {
        $formatted = "{$m[1]} {$m[2]} {$m[3]} {$m[4]}";
    } else {
        $formatted = $raw;
    }

    $sizeClasses = [
        'sm'      => 'text-[12px] h-[28px]',
        'default' => 'text-[15px] h-[36px]',
        'lg'      => 'text-[18px] h-[46px]',
    ];

    $fontSizes = [
        'sm'      => 'text-[8px] px-1',
        'default' => 'text-[10px] px-1.5',
        'lg'      => 'text-[12px] px-2',
    ];
@endphp

<div {{ $attributes->merge(['class' => "inline-flex items-stretch bg-white border-2 border-ink rounded-[6px] overflow-hidden shadow-sm select-none " . ($sizeClasses[$size] ?? $sizeClasses['default'])]) }}>
    <div class="bg-[#003399] text-white font-bold flex flex-col items-center justify-center leading-tight {{ $fontSizes[$size] ?? $fontSizes['default'] }}">
        <span class="text-[7px] leading-none mb-0.5">🇮🇳</span>
        <span class="tracking-widest">IND</span>
    </div>
    <div class="font-display font-extrabold text-ink px-3 flex items-center tracking-widest uppercase">
        {{ $formatted }}
    </div>
</div>
