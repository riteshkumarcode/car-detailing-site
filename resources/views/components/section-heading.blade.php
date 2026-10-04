@props([
    'title' => '',
    'lead' => null,
    'align' => 'left', // 'left', 'center'
    'dark' => false,
])

@php
    $alignClass = $align === 'center' ? 'text-center mx-auto' : 'text-left';
    $titleColor = $dark ? 'text-white' : 'text-ink';
    $leadColor = $dark ? 'text-white/70' : 'text-muted';
@endphp

<div {{ $attributes->merge(['class' => "max-w-3xl mb-8 sm:mb-12 {$alignClass}"]) }}>
    <h2 class="font-display font-extrabold text-[28px] sm:text-[38px] lg:text-[44px] tracking-tight leading-[1.05] {{ $titleColor }}">
        {{ $title ?: $slot }}
    </h2>
    @if($lead)
        <p class="font-body text-[17px] sm:text-[19px] leading-relaxed mt-3 {{ $leadColor }}">
            {{ $lead }}
        </p>
    @endif
</div>
