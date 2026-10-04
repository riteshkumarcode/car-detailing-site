@props([
    'membership' => null,
    'planName' => null,
    'size' => 'md',
    'showCount' => true,
])

@php
    $plan = $planName ?? ($membership?->plan_name ?? 'Drive Club Member');
    $remaining = $membership?->total_remaining_services_count;
    $isSm = $size === 'sm';
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 font-bold uppercase tracking-wider rounded-md border ' . ($isSm ? 'px-2 py-0.5 text-[10px]' : 'px-2.5 py-1 text-xs') . ' bg-teal-deep text-amber border-amber/40 shadow-xs']) }}>
    <!-- Club Crown / Shield Icon -->
    <svg class="{{ $isSm ? 'w-3 h-3' : 'w-3.5 h-3.5' }} text-amber fill-current" viewBox="0 0 24 24">
        <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
    </svg>
    <span class="font-black font-display tracking-tight text-white">{{ $plan }}</span>
    @if($showCount && $remaining !== null && $remaining > 0)
        <span class="ml-1 px-1.5 py-0.2 bg-amber text-ink rounded font-black text-[10px] lowercase tracking-normal">
            {{ $remaining }} left
        </span>
    @endif
</div>
