@props([
    'number' => null,
    'message' => 'Hi The Drive Clinic, I would like to enquire about car detailing and health check.',
])

@php
    $whatsappNumber = $number ?? \App\Models\Setting::get('business.whatsapp', '919419100000');
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);
@endphp

<a
    href="{{ $whatsappUrl }}"
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Chat with The Drive Clinic on WhatsApp"
    class="fixed bottom-6 right-6 z-50 flex items-center gap-2.5 bg-[#25D366] text-white px-4 py-3 rounded-full shadow-lg hover:bg-[#20ba5a] hover:scale-105 active:scale-95 transition-all duration-200 group"
    data-motion="whatsapp-fab"
>
    {{-- Ripple Ring --}}
    <span class="absolute -inset-1 rounded-full bg-[#25D366]/40 animate-ping opacity-75 -z-10 group-hover:hidden"></span>

    {{-- WhatsApp SVG Icon --}}
    <svg class="w-6 h-6 fill-current shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M12.004 2C6.48 2 2 6.48 2 12.004c0 1.944.56 3.76 1.53 5.305L2.08 22l4.834-1.42a9.96 9.96 0 0 0 5.09 1.428h.004c5.524 0 10.004-4.48 10.004-10.004C22.012 6.48 17.528 2 12.004 2Zm5.84 14.18c-.244.688-1.42 1.32-1.956 1.396-.51.072-1.168.104-3.79-0.984-2.82-1.168-4.63-4.04-4.77-4.228-.14-.188-1.134-1.51-1.134-2.88 0-1.37.718-2.044.974-2.324.254-.28.56-.35.748-.35.188 0 .376.002.54.01.176.008.41-.068.642.488.244.588.83 2.024.904 2.172.072.15.122.326.022.524-.098.2-.148.326-.296.5-.148.174-.31.39-.444.524-.148.15-.302.312-.13.608.172.296.764 1.26 1.638 2.04 1.124 1.002 2.072 1.314 2.368 1.462.296.15.47.126.642-.072.174-.2.744-.864.944-1.16.2-.298.398-.248.67-.148.272.098 1.728.816 2.024.964.296.15.494.224.568.35.074.124.074.724-.17 1.412Z"/>
    </svg>
    <span class="font-body font-bold text-[15px] hidden sm:inline-block pr-1">WhatsApp Us</span>
</a>
