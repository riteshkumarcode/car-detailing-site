@props([
    'status' => 'good', // 'good', 'fair', 'needs_attention', 'attention'
    'label' => null,
])

@php
    $normalized = match(strtolower(str_replace(' ', '_', $status))) {
        'good', 'pass', 'ok' => 'good',
        'fair', 'moderate', 'warn' => 'fair',
        default => 'needs_attention',
    };

    $config = [
        'good' => [
            'bg' => 'bg-[#E1EEEA]',
            'text' => 'text-[#1F5E57]',
            'border' => 'border-[#1F5E57]/20',
            'dot' => 'bg-[#1F5E57]',
            'defaultLabel' => 'Good',
        ],
        'fair' => [
            'bg' => 'bg-[#FBEBCF]',
            'text' => 'text-[#7A4E07]',
            'border' => 'border-[#7A4E07]/20',
            'dot' => 'bg-[#7A4E07]',
            'defaultLabel' => 'Fair',
        ],
        'needs_attention' => [
            'bg' => 'bg-[#F6E0DB]',
            'text' => 'text-[#A63A2B]',
            'border' => 'border-[#A63A2B]/20',
            'dot' => 'bg-[#A63A2B]',
            'defaultLabel' => 'Needs attention',
        ],
    ];

    $style = $config[$normalized];
    $displayLabel = $label ?? $style['defaultLabel'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[13px] font-semibold tracking-wide border select-none {$style['bg']} {$style['text']} {$style['border']}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $style['dot'] }} shrink-0"></span>
    <span>{{ $displayLabel }}</span>
</span>
