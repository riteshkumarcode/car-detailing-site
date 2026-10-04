@props([
    'score' => 72,
    'showDisclaimer' => true,
    'size' => 200,
])

@php
    $sizeMap = [
        'sm' => 140,
        'md' => 200,
        'lg' => 240,
        'xl' => 300,
    ];
    $pixelSize = is_numeric($size) ? (int)$size : ($sizeMap[$size] ?? 200);
    $clampedScore = max(0, min(100, (int)$score));
    $radius = 70;
    $semiCircumference = pi() * $radius; // ~219.91
    $dashOffset = $semiCircumference - ($clampedScore / 100) * $semiCircumference;
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center text-center']) }}>
    <div class="relative flex flex-col items-center justify-end" style="width: {{ $pixelSize }}px; height: {{ (int)($pixelSize * 0.62) }}px;">
        <svg viewBox="0 0 180 100" class="w-full h-full overflow-visible">
            {{-- Background Mint Track --}}
            <path
                d="M 20 90 A 70 70 0 0 1 160 90"
                fill="none"
                stroke="#DDE8E3"
                stroke-width="16"
                stroke-linecap="round"
            />
            {{-- Amber Score Arc --}}
            <path
                d="M 20 90 A 70 70 0 0 1 160 90"
                fill="none"
                stroke="#F2A93B"
                stroke-width="16"
                stroke-linecap="round"
                stroke-dasharray="{{ $semiCircumference }}"
                stroke-dashoffset="{{ $dashOffset }}"
                class="transition-all duration-1000 ease-out"
                data-motion="score-arc"
            />
        </svg>

        <div class="absolute inset-0 flex flex-col items-center justify-end pb-1">
            <span class="font-display font-extrabold text-[42px] leading-none text-ink tracking-tight" data-motion="score-number">
                {{ $clampedScore }}
            </span>
            <span class="font-body text-[13px] text-muted font-medium mt-1">
                out of 100
            </span>
        </div>
    </div>

    @if($showDisclaimer)
        <p class="text-[12px] leading-relaxed text-muted/80 max-w-[280px] mt-3 italic text-center">
            * The Drive Health Score describes cosmetic condition only. It is not a certified mechanical, roadworthiness or safety inspection.
        </p>
    @endif
</div>
