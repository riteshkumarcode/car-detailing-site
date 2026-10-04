@props([
    'title' => 'Paint Correction Transformation',
    'carModel' => 'Mahindra Thar (Napoli Black)',
    'problem' => 'Heavy Swirls & Road Film',
    'service' => '2-Stage Machine Polish',
    'beforeImage' => null,
    'afterImage' => null,
])

<div
    x-data="{
        sliderPos: 50,
        isDragging: false,
        updatePos(e) {
            const rect = this.$refs.container.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const x = Math.max(0, Math.min(clientX - rect.left, rect.width));
            this.sliderPos = Math.round((x / rect.width) * 100);
        }
    }"
    class="card-panel overflow-hidden p-0 bg-white shadow-card flex flex-col group"
    data-motion="before-after-slider"
>
    {{-- Comparison Viewport --}}
    <div
        x-ref="container"
        @mousedown="isDragging = true; updatePos($event)"
        @mousemove="if (isDragging) updatePos($event)"
        @mouseup="isDragging = false"
        @mouseleave="isDragging = false"
        @touchstart="isDragging = true; updatePos($event)"
        @touchmove="if (isDragging) updatePos($event)"
        @touchend="isDragging = false"
        class="relative w-full aspect-[16/10] sm:aspect-[16/9] overflow-hidden select-none cursor-ew-resize bg-mist"
    >
        {{-- AFTER IMAGE (Background / Full Width) --}}
        <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center text-center p-6 bg-paper">
            @if($afterImage)
                <img src="{{ asset($afterImage) }}" alt="{{ $title }} After" class="w-full h-full object-cover">
            @else
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-mint text-teal font-bold text-[13px]">
                        <span>AFTER: {{ $service }}</span>
                    </div>
                    <p class="font-display font-extrabold text-[22px] text-ink">90%+ Swirls Removed</p>
                    <p class="text-[14px] text-muted">Mirror clarity & deep candy gloss restored</p>
                </div>
            @endif
            <span class="absolute bottom-3 right-3 bg-teal text-white font-display font-bold text-[12px] px-2.5 py-1 rounded-[6px] tracking-wider shadow-sm">
                AFTER
            </span>
        </div>

        {{-- BEFORE IMAGE (Clipped Overlay by Slider Position) --}}
        <div
            class="absolute inset-0 h-full overflow-hidden bg-[#E2E8E5] border-r-2 border-white shadow-lg"
            :style="`width: ${sliderPos}%;`"
        >
            <div class="absolute inset-0 w-full h-full min-w-[300px] flex flex-col items-center justify-center text-center p-6" style="background-image: repeating-linear-gradient(45deg, #CBD5D1 0, #CBD5D1 10px, #DDE8E3 10px, #DDE8E3 20px);">
                @if($beforeImage)
                    <img src="{{ asset($beforeImage) }}" alt="{{ $title }} Before" class="w-full h-full object-cover">
                @else
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brick text-white font-bold text-[13px]">
                            <span>BEFORE: {{ $problem }}</span>
                        </div>
                        <p class="font-display font-extrabold text-[22px] text-ink">Spiderweb Swirls & Oxidation</p>
                        <p class="text-[14px] text-muted">Faded reflection from roadside washes</p>
                    </div>
                @endif
                <span class="absolute bottom-3 left-3 bg-ink text-white font-display font-bold text-[12px] px-2.5 py-1 rounded-[6px] tracking-wider shadow-sm">
                    BEFORE
                </span>
            </div>
        </div>

        {{-- Draggable Handle Bar --}}
        <div
            class="absolute top-0 bottom-0 w-1 bg-white cursor-ew-resize flex items-center justify-center pointer-events-none"
            :style="`left: ${sliderPos}%; transform: translateX(-50%);`"
        >
            <div class="w-9 h-9 rounded-full bg-amber text-ink font-bold shadow-xl border-2 border-white flex items-center justify-center">
                <svg class="w-4 h-4 text-ink stroke-current stroke-[2.5]" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7l-5 5 5 5m8-10l5 5-5 5" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Caption Footer --}}
    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-line/60 bg-white">
        <div>
            <h4 class="font-display font-bold text-[17px] text-ink leading-tight">{{ $title }}</h4>
            <p class="text-[14px] text-muted mt-0.5">Vehicle: <strong class="text-ink">{{ $carModel }}</strong> • {{ $service }}</p>
        </div>
        <x-button variant="secondary" size="sm" :href="route('services.index')">
            Explore Service
        </x-button>
    </div>
</div>
