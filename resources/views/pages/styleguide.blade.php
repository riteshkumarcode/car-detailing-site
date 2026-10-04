@extends('layouts.public')

@section('title', 'Brand Styleguide — The Drive Clinic')
@section('meta_description', 'Living brand kit and component styleguide for The Drive Clinic.')

@section('content')
<div class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-16">
    {{-- Header Banner --}}
    <div class="border-b border-line pb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-mint text-teal font-semibold text-[13px] mb-3">
            <span>The Drive Clinic</span>
            <span>•</span>
            <span>Brand Kit & Design System</span>
        </div>
        <h1 class="font-display font-extrabold text-[36px] sm:text-[48px] text-ink tracking-tight">
            Design System & Component Library
        </h1>
        <p class="font-body text-[18px] text-muted max-w-2xl mt-2">
            Reference implementation of the approved brand identity, color tokens, typography scales, and atomic Blade components.
        </p>
    </div>

    {{-- Section 1: Logo & Mark --}}
    <section class="space-y-6">
        <x-section-heading
            title="1. Brand Mark & Wordmark"
            lead="Steering wheel ring with healthcare cross at the hub. Stacked Archivo wordmark."
        />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Light Background Variant --}}
            <div class="card-panel flex flex-col justify-between space-y-6">
                <div>
                    <span class="text-[13px] font-semibold text-muted uppercase tracking-wider block mb-4">Light Variant (Primary)</span>
                    <x-logo variant="light" :height="52" />
                </div>
                <div class="text-[14px] text-muted border-t border-line/60 pt-4">
                    Ring: <code class="text-teal font-bold">#1F5E57</code> | Cross: <code class="text-amber font-bold">#F2A93B</code> | Text: <code class="text-ink font-bold">#13262B</code>
                </div>
            </div>

            {{-- Dark Background Variant --}}
            <div class="bg-teal-deep rounded-[12px] p-6 flex flex-col justify-between space-y-6 shadow-sm">
                <div>
                    <span class="text-[13px] font-semibold text-white/60 uppercase tracking-wider block mb-4">Dark Variant (Header/Footer/Cards)</span>
                    <x-logo variant="dark" :height="52" />
                </div>
                <div class="text-[14px] text-white/70 border-t border-white/10 pt-4">
                    Ring: <code class="text-white font-bold">#FFFFFF</code> | Cross: <code class="text-amber font-bold">#F2A93B</code> | Text: <code class="text-white font-bold">#FFFFFF</code>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 2: Color Palette Tokens --}}
    <section class="space-y-6">
        <x-section-heading
            title="2. Color Palette Tokens"
            lead="Curated, high-contrast automotive healthcare palette. No generic colors or gradient washes."
        />

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            {{-- Teal --}}
            <div class="rounded-[10px] overflow-hidden border border-line bg-white shadow-xs">
                <div class="h-24 bg-teal flex items-end p-3 text-white font-bold text-[14px]">
                    #1F5E57
                </div>
                <div class="p-3">
                    <p class="font-bold text-ink text-[15px]">teal</p>
                    <p class="text-[13px] text-muted">Primary Links, Icons, Selected</p>
                </div>
            </div>

            {{-- Teal Deep --}}
            <div class="rounded-[10px] overflow-hidden border border-line bg-white shadow-xs">
                <div class="h-24 bg-teal-deep flex items-end p-3 text-white font-bold text-[14px]">
                    #163F3A
                </div>
                <div class="p-3">
                    <p class="font-bold text-ink text-[15px]">teal-deep</p>
                    <p class="text-[13px] text-muted">Dark Sections, Footer, Top Bar</p>
                </div>
            </div>

            {{-- Amber --}}
            <div class="rounded-[10px] overflow-hidden border border-line bg-white shadow-xs">
                <div class="h-24 bg-amber flex items-end p-3 text-ink font-bold text-[14px]">
                    #F2A93B
                </div>
                <div class="p-3">
                    <p class="font-bold text-ink text-[15px]">amber</p>
                    <p class="text-[13px] text-muted">Main CTA, Gauges, Logo Cross</p>
                </div>
            </div>

            {{-- Ink --}}
            <div class="rounded-[10px] overflow-hidden border border-line bg-white shadow-xs">
                <div class="h-24 bg-ink flex items-end p-3 text-white font-bold text-[14px]">
                    #13262B
                </div>
                <div class="p-3">
                    <p class="font-bold text-ink text-[15px]">ink</p>
                    <p class="text-[13px] text-muted">Body Text, Dark Panels</p>
                </div>
            </div>

            {{-- Mist --}}
            <div class="rounded-[10px] overflow-hidden border border-line bg-white shadow-xs">
                <div class="h-24 bg-mist flex items-end p-3 text-ink font-bold text-[14px]">
                    #EEF2F0
                </div>
                <div class="p-3">
                    <p class="font-bold text-ink text-[15px]">mist</p>
                    <p class="text-[13px] text-muted">Page Background</p>
                </div>
            </div>

            {{-- Mint --}}
            <div class="rounded-[10px] overflow-hidden border border-line bg-white shadow-xs">
                <div class="h-24 bg-mint flex items-end p-3 text-teal font-bold text-[14px]">
                    #DDE8E3
                </div>
                <div class="p-3">
                    <p class="font-bold text-ink text-[15px]">mint</p>
                    <p class="text-[13px] text-muted">Soft Panels, Score Track</p>
                </div>
            </div>

            {{-- Paper --}}
            <div class="rounded-[10px] overflow-hidden border border-line bg-white shadow-xs">
                <div class="h-24 bg-paper flex items-end p-3 text-ink font-bold text-[14px]">
                    #F7F9F8
                </div>
                <div class="p-3">
                    <p class="font-bold text-ink text-[15px]">paper</p>
                    <p class="text-[13px] text-muted">Inner Card Panels</p>
                </div>
            </div>

            {{-- Line --}}
            <div class="rounded-[10px] overflow-hidden border border-line bg-white shadow-xs">
                <div class="h-24 bg-line flex items-end p-3 text-ink font-bold text-[14px]">
                    #CBD5D1
                </div>
                <div class="p-3">
                    <p class="font-bold text-ink text-[15px]">line</p>
                    <p class="text-[13px] text-muted">Borders & Hairline Dividers</p>
                </div>
            </div>

            {{-- Muted --}}
            <div class="rounded-[10px] overflow-hidden border border-line bg-white shadow-xs">
                <div class="h-24 bg-muted flex items-end p-3 text-white font-bold text-[14px]">
                    #4A5D61
                </div>
                <div class="p-3">
                    <p class="font-bold text-ink text-[15px]">muted</p>
                    <p class="text-[13px] text-muted">Secondary Text (&ge;4.5:1)</p>
                </div>
            </div>

            {{-- Brick --}}
            <div class="rounded-[10px] overflow-hidden border border-line bg-white shadow-xs">
                <div class="h-24 bg-brick flex items-end p-3 text-white font-bold text-[14px]">
                    #A63A2B
                </div>
                <div class="p-3">
                    <p class="font-bold text-ink text-[15px]">brick</p>
                    <p class="text-[13px] text-muted">Needs Attention & Errors</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 3: Typography --}}
    <section class="space-y-6">
        <x-section-heading
            title="3. Typography Hierarchy"
            lead="Display font: Archivo (weight 800, width stretch 116%). Body font: Barlow (weights 400–700)."
        />

        <div class="card-panel space-y-8">
            <div>
                <span class="text-[13px] font-semibold text-muted uppercase tracking-wider block mb-2">Display Headings (Archivo)</span>
                <div class="space-y-4">
                    <div>
                        <span class="text-[12px] text-muted font-mono block">h1 • 48px/800</span>
                        <h1 class="text-[40px] sm:text-[48px]">Your car's healthcare centre.</h1>
                    </div>
                    <div>
                        <span class="text-[12px] text-muted font-mono block">h2 • 36px/800</span>
                        <h2 class="text-[30px] sm:text-[36px]">We don't just wash. We diagnose.</h2>
                    </div>
                    <div>
                        <span class="text-[12px] text-muted font-mono block">h3 • 24px/800</span>
                        <h3 class="text-[22px] sm:text-[24px]">Digital Car Health Check</h3>
                    </div>
                </div>
            </div>

            <div class="border-t border-line/60 pt-6">
                <span class="text-[13px] font-semibold text-muted uppercase tracking-wider block mb-2">Body & Interface Text (Barlow)</span>
                <p class="text-[17px] leading-relaxed text-ink mb-3">
                    <strong>Regular Body (17px / 1.55):</strong> Every vehicle entering our Nanak Nagar studio undergoes a systematic cosmetic inspection. Our technicians check 5 critical health categories to ensure your vehicle leaves immaculate and protected.
                </p>
                <p class="text-[15px] leading-relaxed text-muted">
                    <strong>Muted Secondary (15px / 1.5):</strong> The Drive Health Score describes cosmetic condition only. It is not a certified mechanical, roadworthiness or safety inspection.
                </p>
            </div>
        </div>
    </section>

    {{-- Section 4: Buttons --}}
    <section class="space-y-6">
        <x-section-heading
            title="4. Button System"
            lead="Min-height 52px, 10px radius, bold weight 700. Explicit action labels, no trailing arrows."
        />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Light Backgrounds --}}
            <div class="card-panel space-y-4">
                <span class="text-[13px] font-semibold text-muted uppercase tracking-wider block">On Light Backgrounds</span>
                <div class="flex flex-wrap items-center gap-4">
                    <x-button variant="primary">
                        Book your car
                    </x-button>
                    <x-button variant="secondary">
                        Get a free health check
                    </x-button>
                    <x-button variant="teal">
                        Confirm booking
                    </x-button>
                </div>
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <x-button variant="primary" size="sm">
                        Small (44px)
                    </x-button>
                    <x-button variant="secondary" size="sm">
                        Small Action
                    </x-button>
                </div>
            </div>

            {{-- Dark Backgrounds --}}
            <div class="bg-teal-deep rounded-[12px] p-6 space-y-4">
                <span class="text-[13px] font-semibold text-white/60 uppercase tracking-wider block">On Dark Backgrounds</span>
                <div class="flex flex-wrap items-center gap-4">
                    <x-button variant="primary">
                        Book your car
                    </x-button>
                    <x-button variant="light">
                        Explore Drive Club
                    </x-button>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 5: Indian Number Plate Badges --}}
    <section class="space-y-6">
        <x-section-heading
            title="5. Indian Registration Number Plate Badges"
            lead="White plate, 2px ink border, blue IND strip with chakra, bold letter-spaced Archivo."
        />

        <div class="card-panel flex flex-wrap items-center gap-6">
            <x-number-plate number="JK02AB1234" size="lg" />
            <x-number-plate number="JK 02 CD 5678" size="default" />
            <x-number-plate number="DL01CA9999" size="default" />
            <x-number-plate number="HR26DQ5555" size="sm" />
        </div>
    </section>

    {{-- Section 6: Status Chips --}}
    <section class="space-y-6">
        <x-section-heading
            title="6. Status Chips"
            lead="Status chips always show a text label as well as color for accessibility."
        />

        <div class="card-panel flex flex-wrap items-center gap-4">
            <x-status-chip status="good" />
            <x-status-chip status="fair" />
            <x-status-chip status="needs_attention" />
            <x-status-chip status="good" label="Paint: Protected" />
            <x-status-chip status="fair" label="Glass: Water Spots" />
            <x-status-chip status="needs_attention" label="Seats: Deep Stain" />
        </div>
    </section>

    {{-- Section 7: Drive Health Score Gauge --}}
    <section class="space-y-6">
        <x-section-heading
            title="7. Drive Health Score Gauge"
            lead="Semicircle SVG arc with amber score value on mint track. Displays mandatory cosmetic disclaimer."
        />

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="card-panel flex items-center justify-center p-8">
                <x-score-gauge :score="72" :size="220" />
            </div>
            <div class="card-panel flex items-center justify-center p-8">
                <x-score-gauge :score="94" :size="220" />
            </div>
            <div class="card-panel flex items-center justify-center p-8">
                <x-score-gauge :score="48" :size="220" />
            </div>
        </div>
    </section>

    {{-- Section 8: Photo Placeholder --}}
    <section class="space-y-6">
        <x-section-heading
            title="8. Photo Placeholder"
            lead="Diagonal hatch pattern with camera icon and label, used until real photos are uploaded."
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <x-photo-placeholder label="Before: Swirl Marks Inspection" aspect="16/9" />
            <x-photo-placeholder label="After: 2-Step Paint Correction" aspect="16/9" />
            <x-photo-placeholder label="Interior Leather Deep Clean" aspect="16/9" />
        </div>
    </section>
</div>
@endsection
