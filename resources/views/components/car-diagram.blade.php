@props([
    'activeSpots' => [
        ['number' => 1, 'x' => '48%', 'y' => '24%', 'title' => 'Swirl Marks & Haze', 'desc' => 'Light clear coat scratches on hood'],
        ['number' => 2, 'x' => '50%', 'y' => '48%', 'title' => 'Mineral Water Spots', 'desc' => 'Hard water deposits on glass'],
        ['number' => 3, 'x' => '32%', 'y' => '78%', 'title' => 'Paint Scratches', 'desc' => 'Rear left quarter panel marring'],
    ]
])

<div class="relative w-full max-w-[280px] sm:max-w-[320px] aspect-[1/2] mx-auto flex items-center justify-center p-4 select-none" data-motion="car-scan-wrapper">
    {{-- Top-down Car Blueprint SVG --}}
    <svg viewBox="0 0 200 400" class="w-full h-full drop-shadow-md overflow-visible" fill="none" xmlns="http://www.w3.org/2000/svg">
        {{-- Car Outer Body Silhouette --}}
        <path d="M 50 80 C 50 40, 70 20, 100 20 C 130 20, 150 40, 150 80 L 158 140 C 162 160, 162 240, 158 280 L 152 350 C 148 375, 130 385, 100 385 C 70 385, 52 375, 48 350 L 42 280 C 38 240, 38 160, 42 140 Z" fill="#FFFFFF" stroke="#CBD5D1" stroke-width="3"/>
        
        {{-- Windshield & Roof --}}
        <path d="M 56 120 C 60 105, 75 100, 100 100 C 125 100, 140 105, 144 120 L 140 170 L 60 170 Z" fill="#EEF2F0" stroke="#CBD5D1" stroke-width="2"/>
        {{-- Rear Glass --}}
        <path d="M 60 270 L 140 270 L 144 310 C 135 320, 115 322, 100 322 C 85 322, 65 320, 56 310 Z" fill="#EEF2F0" stroke="#CBD5D1" stroke-width="2"/>
        
        {{-- Side Mirrors --}}
        <path d="M 40 130 C 30 130, 28 142, 38 146 L 44 144 Z" fill="#1F5E57"/>
        <path d="M 160 130 C 170 130, 172 142, 162 146 L 156 144 Z" fill="#1F5E57"/>

        {{-- Hood Detail Crease Lines --}}
        <path d="M 72 40 L 76 95" stroke="#CBD5D1" stroke-width="2" stroke-linecap="round"/>
        <path d="M 128 40 L 124 95" stroke="#CBD5D1" stroke-width="2" stroke-linecap="round"/>

        {{-- Headlights & Taillights --}}
        <path d="M 52 48 C 55 35, 68 30, 72 45 Z" fill="#F2A93B"/>
        <path d="M 148 48 C 145 35, 132 30, 128 45 Z" fill="#F2A93B"/>
        <path d="M 52 360 C 55 372, 68 375, 72 362 Z" fill="#A63A2B"/>
        <path d="M 148 360 C 145 372, 132 375, 128 362 Z" fill="#A63A2B"/>

        {{-- Scan Line (Amber Horizontal Line that Sweeps on Loop) --}}
        <line x1="20" y1="180" x2="180" y2="180" stroke="#F2A93B" stroke-width="3" stroke-dasharray="6 4" class="opacity-80" data-motion="scan-line"/>
    </svg>

    {{-- Interactive Pulsing Numbered Markers --}}
    @foreach($activeSpots as $spot)
        <div
            class="absolute transform -translate-x-1/2 -translate-y-1/2 group cursor-pointer"
            style="left: {{ $spot['x'] }}; top: {{ $spot['y'] }};"
            data-motion="marker-spot"
        >
            {{-- Pulsing Radar Wave --}}
            <span class="absolute -inset-2 rounded-full bg-amber/50 animate-ping opacity-75"></span>
            
            {{-- Numbered Badge --}}
            <div class="relative w-7 h-7 rounded-full bg-amber text-ink font-display font-extrabold text-[13px] flex items-center justify-center shadow-md border-2 border-white group-hover:scale-110 transition-transform">
                {{ $spot['number'] }}
            </div>

            {{-- Floating Tooltip on Hover --}}
            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-ink text-white p-2.5 rounded-[8px] text-[12px] shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-30">
                <p class="font-bold text-amber leading-tight">{{ $spot['title'] }}</p>
                <p class="text-white/80 text-[11px] mt-0.5 leading-snug">{{ $spot['desc'] }}</p>
                <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-ink"></div>
            </div>
        </div>
    @endforeach
</div>
