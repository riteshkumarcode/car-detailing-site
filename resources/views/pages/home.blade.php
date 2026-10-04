@extends('layouts.public')

@section('title', 'The Drive Clinic — Your Car\'s Healthcare Centre in Jammu')
@section('meta_description', 'Premium car wash, machine detailing and digital health check diagnostics in Nanak Nagar, Jammu. 2-bucket safe wash from ₹499.')

@section('content')
<x-schema-jsonld />

{{-- ========================================================================= --}}
{{-- 1. HERO SECTION --}}
{{-- ========================================================================= --}}
<section class="relative pt-12 pb-16 lg:pt-20 lg:pb-24 overflow-hidden" data-motion="hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            {{-- Left Column: Copy & CTAs --}}
            <div class="lg:col-span-6 space-y-6 text-left">
                {{-- Location Pill --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-mint text-teal font-semibold text-[14px] border border-teal/20 select-none shadow-xs">
                    <svg class="w-4 h-4 text-teal stroke-current stroke-[2]" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    <span>Nanak Nagar, Jammu</span>
                    <span class="text-muted/60">•</span>
                    <span class="text-teal font-bold">Launching Soon</span>
                </div>

                {{-- Main Headline (Masked rise animation) --}}
                <h1 class="font-display font-extrabold text-[38px] sm:text-[54px] lg:text-[62px] text-ink tracking-tight leading-[1.02]">
                    Your car's <br class="hidden sm:inline">
                    <span class="text-teal">healthcare centre.</span>
                </h1>

                {{-- Lead Text --}}
                <p class="font-body text-[18px] sm:text-[20px] text-muted leading-relaxed max-w-xl">
                    We don't just wash cars; we inspect, detail, protect and maintain them. Experience Jammu's first diagnostic studio where every visit comes with a digital health record.
                </p>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <x-button variant="primary" size="lg" :href="route('book')">
                        Book your car
                    </x-button>
                    <x-button variant="secondary" size="lg" :href="route('health-check.form')">
                        Get a free health check
                    </x-button>
                </div>

                {{-- 3 Key Facts --}}
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-line/70 max-w-lg">
                    <div>
                        <span class="font-display font-extrabold text-[24px] sm:text-[28px] text-ink block leading-none">₹499</span>
                        <span class="text-[13px] text-muted font-medium mt-1 block">Starting price</span>
                    </div>
                    <div>
                        <span class="font-display font-extrabold text-[24px] sm:text-[28px] text-ink block leading-none">~25 min</span>
                        <span class="text-[13px] text-muted font-medium mt-1 block">Quick bay turnaround</span>
                    </div>
                    <div>
                        <span class="font-display font-extrabold text-[24px] sm:text-[28px] text-teal block leading-none">Welcome</span>
                        <span class="text-[13px] text-muted font-medium mt-1 block">Walk-ins accepted</span>
                    </div>
                </div>
            </div>

            {{-- Right Column: Signature Car Health Check Card --}}
            <div class="lg:col-span-6 relative">
                {{-- Floating Callout Badges (Desktop only) --}}
                <div class="hidden xl:flex items-center gap-2 absolute -top-4 -left-6 z-20 bg-white border border-line px-3.5 py-2 rounded-[10px] shadow-card text-[13px] font-bold text-ink">
                    <span class="w-2.5 h-2.5 rounded-full bg-brick animate-pulse"></span>
                    <span>3 issues photographed</span>
                </div>
                <div class="hidden xl:flex items-center gap-2 absolute -bottom-4 -right-4 z-20 bg-teal text-white border border-teal-deep px-3.5 py-2 rounded-[10px] shadow-card text-[13px] font-bold">
                    <span>✓</span>
                    <span>Saved to Car Passport</span>
                </div>

                {{-- Main Diagnostic Card Panel --}}
                <div class="card-panel bg-white border-2 border-line/80 shadow-brand p-6 sm:p-8 space-y-6">
                    {{-- Card Header: Plate & Score --}}
                    <div class="flex items-center justify-between gap-4 pb-5 border-b border-line">
                        <div class="space-y-1">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-muted block">Digital Diagnosis</span>
                            <x-number-plate number="JK02AB1234" size="default" />
                            <span class="text-[13px] text-muted font-semibold block pt-1">Hyundai Creta • 2024</span>
                        </div>
                        <div class="shrink-0">
                            <x-score-gauge :score="72" :size="150" :showDisclaimer="false" />
                        </div>
                    </div>

                    {{-- Top-down Car Visual Diagram --}}
                    <div class="py-2">
                        <x-car-diagram />
                    </div>

                    {{-- 5 Category Health Breakdown Rows --}}
                    <div class="space-y-2.5 pt-2 border-t border-line/60">
                        <div class="flex items-center justify-between text-[14px] py-1 border-b border-line/40">
                            <span class="font-semibold text-ink">Exterior & Paint (30%)</span>
                            <x-status-chip status="fair" label="Fair (Swirls)" />
                        </div>
                        <div class="flex items-center justify-between text-[14px] py-1 border-b border-line/40">
                            <span class="font-semibold text-ink">Interior Cabin (30%)</span>
                            <x-status-chip status="needs_attention" label="Needs Attention" />
                        </div>
                        <div class="flex items-center justify-between text-[14px] py-1 border-b border-line/40">
                            <span class="font-semibold text-ink">Wheels & Tyres (15%)</span>
                            <x-status-chip status="fair" label="Fair" />
                        </div>
                        <div class="flex items-center justify-between text-[14px] py-1 border-b border-line/40">
                            <span class="font-semibold text-ink">Glass & Windshield (15%)</span>
                            <x-status-chip status="good" label="Good" />
                        </div>
                        <div class="flex items-center justify-between text-[14px] py-1">
                            <span class="font-semibold text-ink">Surface Protection (10%)</span>
                            <x-status-chip status="needs_attention" label="None Known" />
                        </div>
                    </div>

                    {{-- Recommendation Buckets --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div class="bg-[#F6E0DB]/60 border border-brick/20 rounded-[8px] p-3">
                            <span class="text-[11px] font-bold text-brick uppercase tracking-wider block">Recommended Today</span>
                            <span class="text-[14px] font-bold text-ink block mt-0.5">Interior Deep Clean</span>
                            <span class="text-[12px] text-muted">₹1,499 (Removes coffee stain)</span>
                        </div>
                        <div class="bg-mint/50 border border-teal/20 rounded-[8px] p-3">
                            <span class="text-[11px] font-bold text-teal uppercase tracking-wider block">Can Wait (Next Visit)</span>
                            <span class="text-[14px] font-bold text-ink block mt-0.5">Paint Correction</span>
                            <span class="text-[12px] text-muted">Recommended within 60 days</span>
                        </div>
                    </div>

                    {{-- Mandatory Disclaimer --}}
                    <p class="text-[11px] text-muted/75 italic text-center pt-2 leading-tight">
                        * The Drive Health Score describes cosmetic condition only. It is not a certified mechanical, roadworthiness or safety inspection.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ECG Diagnostic Heartbeat Line & Infinite Marquee Ticker --}}
<div class="relative bg-ink text-white py-3.5 border-y border-teal-deep/30 overflow-hidden select-none" data-motion="ecg-pulse">
    {{-- ECG Telemetry Line Animation Background --}}
    <div class="absolute inset-0 opacity-25 flex items-center pointer-events-none">
        <svg viewBox="0 0 1200 40" class="w-full h-10 stroke-teal fill-none overflow-visible" preserveAspectRatio="none">
            <path class="ecg-pulse-path" stroke-width="1.5" stroke-linecap="round" d="M 0 20 L 300 20 L 315 20 L 325 5 L 335 35 L 345 10 L 355 25 L 365 20 L 700 20 L 715 20 L 725 5 L 735 35 L 745 10 L 755 25 L 765 20 L 1200 20" />
        </svg>
    </div>

    {{-- Infinite Scrolling Service Marquee --}}
    <div class="relative z-10 flex overflow-hidden whitespace-nowrap" data-motion="marquee" data-duration="26">
        <div class="marquee-track flex items-center gap-8 font-display font-extrabold text-[14px] sm:text-[16px] tracking-wider uppercase">
            <div class="flex items-center gap-8 text-amber">
                <span>Diagnostic Foam Wash</span>
                <span class="text-white/30">•</span>
                <span class="text-white">9H Graphene Ceramic</span>
                <span class="text-white/30">•</span>
                <span>Interior Steam Sanitization</span>
                <span class="text-white/30">•</span>
                <span class="text-white">2-Stage Paint Correction</span>
                <span class="text-white/30">•</span>
                <span>Digital Car Passport</span>
                <span class="text-white/30">•</span>
                <span class="text-white">Underbody Anti-Rust</span>
                <span class="text-white/30">•</span>
                <span>Zero-Scratch 2-Bucket Method</span>
                <span class="text-white/30">•</span>
            </div>
            <div class="flex items-center gap-8 text-amber">
                <span>Diagnostic Foam Wash</span>
                <span class="text-white/30">•</span>
                <span class="text-white">9H Graphene Ceramic</span>
                <span class="text-white/30">•</span>
                <span>Interior Steam Sanitization</span>
                <span class="text-white/30">•</span>
                <span class="text-white">2-Stage Paint Correction</span>
                <span class="text-white/30">•</span>
                <span>Digital Car Passport</span>
                <span class="text-white/30">•</span>
                <span class="text-white">Underbody Anti-Rust</span>
                <span class="text-white/30">•</span>
                <span>Zero-Scratch 2-Bucket Method</span>
                <span class="text-white/30">•</span>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 2. SERVICES PRICE-LIST SECTION --}}
{{-- ========================================================================= --}}
<section class="py-16 sm:py-24 bg-white border-t border-line" id="services">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <x-section-heading
                title="Studio Services & Diagnostic Care"
                lead="Transparent, vehicle-tiered pricing. Every service includes safe 2-bucket wash methods and condition logging."
                class="mb-0"
                data-motion="section-heading"
            />
            <x-button variant="secondary" :href="route('services.index')">
                View all service details
            </x-button>
        </div>

        {{-- Price-List Layout --}}
        <div class="border border-line rounded-[12px] divide-y divide-line overflow-hidden bg-white shadow-xs" data-motion="card-grid">
            @foreach($services as $service)
                <div class="p-6 sm:p-7 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:bg-mist/40 transition-colors group">
                    <div class="space-y-1.5 md:max-w-xl">
                        <div class="flex items-center gap-3">
                            <span class="text-[12px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-mint text-teal">
                                {{ $service->category->name ?? 'Studio Service' }}
                            </span>
                            <span class="text-[13px] text-muted flex items-center gap-1.5 font-medium">
                                <svg class="w-3.5 h-3.5 text-teal stroke-current stroke-[2]" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span>{{ $service->duration_minutes }} mins</span>
                            </span>
                        </div>
                        <h3 class="font-display font-bold text-[20px] sm:text-[22px] text-ink group-hover:text-teal transition-colors">
                            {{ $service->name }}
                        </h3>
                        <p class="text-[15px] text-muted leading-relaxed">
                            {{ $service->short_description }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between md:justify-end gap-6 shrink-0 border-t md:border-t-0 pt-4 md:pt-0 border-line/60">
                        <div class="text-left md:text-right">
                            <span class="text-[12px] text-muted uppercase font-bold tracking-wider block">Starting from</span>
                            <span class="font-display font-extrabold text-[24px] sm:text-[28px] text-ink block leading-tight">
                                ₹{{ number_format($service->starting_price ?? $service->price_hatchback ?? 499) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-button variant="primary" size="sm" :href="route('book', ['service' => $service->slug])">
                                Book now
                            </x-button>
                            <x-button variant="secondary" size="sm" :href="route('services.show', $service->slug)">
                                Details
                            </x-button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 3. FREE HEALTH CHECK EXPLAINER (DARK TEAL SECTION) --}}
{{-- ========================================================================= --}}
<section class="py-16 sm:py-24 bg-teal-deep text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-6 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-amber font-semibold text-[13px] border border-white/15">
                    <span>Complimentary Diagnostic</span>
                </div>
                <h2 class="font-display font-extrabold text-[32px] sm:text-[44px] text-white tracking-tight leading-[1.05]">
                    Free 5-Point Digital <br class="hidden sm:inline">Car Health Check.
                </h2>
                <p class="text-[18px] text-white/80 leading-relaxed max-w-lg">
                    Before touching any vehicle, our technicians inspect 5 cosmetic condition zones and calculate your vehicle's Drive Health Score. You get a transparent report on WhatsApp with zero sales pressure.
                </p>
                <div class="pt-2">
                    <x-button variant="primary" size="lg" :href="route('health-check.form')">
                        Get a free health check
                    </x-button>
                </div>
            </div>

            <div class="lg:col-span-6 space-y-3">
                <div class="bg-white/5 border border-white/10 rounded-[10px] p-4 flex items-start gap-4 hover:bg-white/10 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-amber text-ink font-display font-extrabold flex items-center justify-center shrink-0 text-[14px]">1</div>
                    <div>
                        <h4 class="font-display font-bold text-[18px] text-white">Exterior Paint & Clear Coat</h4>
                        <p class="text-[14px] text-white/70 mt-0.5">Scanned with optical lights for swirl marks, scratches, acid rain oxidation, and clear coat depth.</p>
                    </div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-[10px] p-4 flex items-start gap-4 hover:bg-white/10 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-amber text-ink font-display font-extrabold flex items-center justify-center shrink-0 text-[14px]">2</div>
                    <div>
                        <h4 class="font-display font-bold text-[18px] text-white">Interior Cabin & AC Vents</h4>
                        <p class="text-[14px] text-white/70 mt-0.5">Checked for fabric stains, leather dryness, microbial buildup in air vents, and odour sources.</p>
                    </div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-[10px] p-4 flex items-start gap-4 hover:bg-white/10 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-amber text-ink font-display font-extrabold flex items-center justify-center shrink-0 text-[14px]">3</div>
                    <div>
                        <h4 class="font-display font-bold text-[18px] text-white">Wheels, Tyres & Brake Dust</h4>
                        <p class="text-[14px] text-white/70 mt-0.5">Inspected for corrosive metallic brake dust pitting, tyre wall oxidation, and tread depth.</p>
                    </div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-[10px] p-4 flex items-start gap-4 hover:bg-white/10 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-amber text-ink font-display font-extrabold flex items-center justify-center shrink-0 text-[14px]">4</div>
                    <div>
                        <h4 class="font-display font-bold text-[18px] text-white">Glass & Windshield Clarity</h4>
                        <p class="text-[14px] text-white/70 mt-0.5">Assessed for hard water mineral etching, wiper blade micro-scratches, and night glare film.</p>
                    </div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-[10px] p-4 flex items-start gap-4 hover:bg-white/10 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-amber text-ink font-display font-extrabold flex items-center justify-center shrink-0 text-[14px]">5</div>
                    <div>
                        <h4 class="font-display font-bold text-[18px] text-white">Surface Protection Status</h4>
                        <p class="text-[14px] text-white/70 mt-0.5">Tested for water beading contact angle to determine if existing wax, sealant or ceramic coat is active.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 4. "WE DON'T JUST WASH. WE DIAGNOSE." (HAIRLINE-DIVIDED PANEL) --}}
{{-- ========================================================================= --}}
<section class="py-16 sm:py-24 bg-mist">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-section-heading
            title="We don't just wash. We diagnose."
            lead="Six clinic principles that protect your car's value and prevent permanent clear coat damage."
            align="center"
        />

        <div class="border border-line rounded-[12px] bg-white shadow-card overflow-hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-line">
            {{-- Point 1 --}}
            <div class="p-8 space-y-3">
                <span class="text-teal font-display font-extrabold text-[20px] block">01</span>
                <h4 class="font-display font-bold text-[19px] text-ink">Diagnostic Pre-Assessment</h4>
                <p class="text-[15px] text-muted leading-relaxed">
                    We map paint defects and high-wear areas before spraying water, ensuring the right treatment for your specific paint softness.
                </p>
            </div>

            {{-- Point 2 --}}
            <div class="p-8 space-y-3">
                <span class="text-teal font-display font-extrabold text-[20px] block">02</span>
                <h4 class="font-display font-bold text-[19px] text-ink">2-Bucket Swirl-Free Wash</h4>
                <p class="text-[15px] text-muted leading-relaxed">
                    Separated wash and rinse buckets with heavy-duty grit guards ensure no dirt particles are rubbed back into your clear coat.
                </p>
            </div>

            {{-- Point 3 --}}
            <div class="p-8 space-y-3 border-t lg:border-t-0 border-line">
                <span class="text-teal font-display font-extrabold text-[20px] block">03</span>
                <h4 class="font-display font-bold text-[19px] text-ink">pH-Neutral Safe Chemistry</h4>
                <p class="text-[15px] text-muted leading-relaxed">
                    We exclusively use imported, biodegradable, pH-balanced snow foams that dissolve grime without stripping protective waxes or coatings.
                </p>
            </div>

            {{-- Point 4 --}}
            <div class="p-8 space-y-3 border-t border-line">
                <span class="text-teal font-display font-extrabold text-[20px] block">04</span>
                <h4 class="font-display font-bold text-[19px] text-ink">Zone-Specific Clean Tools</h4>
                <p class="text-[15px] text-muted leading-relaxed">
                    Wheels, lower body sills, and upper paintwork each use separate dedicated microfiber mitts to eliminate cross-contamination.
                </p>
            </div>

            {{-- Point 5 --}}
            <div class="p-8 space-y-3 border-t border-line">
                <span class="text-teal font-display font-extrabold text-[20px] block">05</span>
                <h4 class="font-display font-bold text-[19px] text-ink">Digital Car Passport</h4>
                <p class="text-[15px] text-muted leading-relaxed">
                    Every service, health score, and photo is securely logged to your vehicle's digital passport, proving maintenance pedigree for resale value.
                </p>
            </div>

            {{-- Point 6 --}}
            <div class="p-8 space-y-3 border-t border-line">
                <span class="text-teal font-display font-extrabold text-[20px] block">06</span>
                <h4 class="font-display font-bold text-[19px] text-ink">Transparent Condition Scoring</h4>
                <p class="text-[15px] text-muted leading-relaxed">
                    We separate recommendations into 'Today' vs 'Can Wait', giving you complete control over your vehicle's maintenance budget.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 5. DRIVE CLUB TEASER --}}
{{-- ========================================================================= --}}
<section class="py-16 sm:py-24 bg-white border-t border-line" id="drive-club">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <x-section-heading
                title="Drive Club Memberships"
                lead="Year-round vehicle maintenance at guaranteed discounted rates. Priority bay scheduling on weekends."
                class="mb-0"
            />
            <x-button variant="secondary" :href="route('drive-club')">
                Compare full plan benefits
            </x-button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            @foreach($plans as $plan)
                <div class="card-panel flex flex-col justify-between relative {{ $plan->is_featured ? 'border-2 border-amber shadow-brand bg-paper' : 'bg-white' }}">
                    @if($plan->is_featured)
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-amber text-ink font-display font-extrabold text-[12px] px-3.5 py-1 rounded-full uppercase tracking-wider shadow-sm">
                            Most Popular Plan
                        </div>
                    @endif

                    <div class="space-y-4">
                        <h3 class="font-display font-extrabold text-[22px] text-ink">{{ $plan->name }}</h3>
                        <div>
                            <span class="font-display font-extrabold text-[36px] text-ink">₹{{ number_format($plan->price) }}</span>
                            <span class="text-muted text-[14px]">/ year</span>
                        </div>
                        <p class="text-[14px] text-muted leading-relaxed border-b border-line/60 pb-4">
                            {{ $plan->benefits_description }}
                        </p>

                        <ul class="space-y-2.5 text-[15px] text-ink font-medium">
                            @foreach($plan->features ?? [] as $feat)
                                <li class="flex items-start gap-2">
                                    <span class="text-teal font-bold shrink-0">✓</span>
                                    <span>{{ $feat }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="pt-8">
                        <x-button
                            :variant="$plan->is_featured ? 'primary' : 'secondary'"
                            class="w-full justify-center"
                            :href="route('drive-club')"
                        >
                            Enquire About Plan
                        </x-button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 6. BEFORE & AFTER TRANSFORMATIONS STRIP --}}
{{-- ========================================================================= --}}
<section class="py-16 sm:py-24 bg-mist border-t border-line">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <x-section-heading
                title="Real Clinic Results"
                lead="Interactive before & after wipes from actual transformations completed in our studio."
                class="mb-0"
            />
            <x-button variant="secondary" :href="route('gallery')">
                View full gallery
            </x-button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($galleryItems->take(2) as $item)
                <x-before-after-slider
                    :title="$item->title"
                    :carModel="$item->car_model"
                    :problem="$item->problem_description"
                    :service="$item->service_name"
                />
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 7. HOW A VISIT WORKS (4 NUMBERED STEPS) --}}
{{-- ========================================================================= --}}
<section class="py-16 sm:py-24 bg-white border-t border-line">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-section-heading
            title="How a clinic visit works"
            lead="A transparent 4-step workflow from arrival to key handover."
            align="center"
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-4">
            <div class="card-panel bg-mist/60 space-y-3">
                <div class="w-10 h-10 rounded-full bg-ink text-white font-display font-extrabold flex items-center justify-center text-[16px]">
                    1
                </div>
                <h4 class="font-display font-bold text-[19px] text-ink">Arrival & Check-in</h4>
                <p class="text-[14px] text-muted leading-relaxed">
                    Drive in as a walk-in or arrive at your booked slot. Your vehicle registration is looked up in under 60 seconds.
                </p>
            </div>

            <div class="card-panel bg-mist/60 space-y-3">
                <div class="w-10 h-10 rounded-full bg-ink text-white font-display font-extrabold flex items-center justify-center text-[16px]">
                    2
                </div>
                <h4 class="font-display font-bold text-[19px] text-ink">5-Point Diagnosis</h4>
                <p class="text-[14px] text-muted leading-relaxed">
                    Our detailer inspects paint, interior, glass, and wheels, sending your Drive Health Score and photos to your phone.
                </p>
            </div>

            <div class="card-panel bg-mist/60 space-y-3">
                <div class="w-10 h-10 rounded-full bg-ink text-white font-display font-extrabold flex items-center justify-center text-[16px]">
                    3
                </div>
                <h4 class="font-display font-bold text-[19px] text-ink">Precision Treatment</h4>
                <p class="text-[14px] text-muted leading-relaxed">
                    Your car enters a dedicated bay with clean tools, pH-safe chemistry, and zero contact rush methods.
                </p>
            </div>

            <div class="card-panel bg-mist/60 space-y-3">
                <div class="w-10 h-10 rounded-full bg-teal text-white font-display font-extrabold flex items-center justify-center text-[16px]">
                    4
                </div>
                <h4 class="font-display font-bold text-[19px] text-ink">Passport Handover</h4>
                <p class="text-[14px] text-muted leading-relaxed">
                    Final inspection check, instant digital GST invoice on WhatsApp, and permanent entry in your Car Passport.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 8. REVIEWS & TESTIMONIALS --}}
{{-- ========================================================================= --}}
@if($testimonials->count() > 0)
<section class="py-16 sm:py-24 bg-mist border-t border-line">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-section-heading
            title="What Jammu drivers say"
            lead="Authentic feedback from daily commuters and car enthusiasts across Jammu."
            align="center"
        />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
            @foreach($testimonials as $testi)
                <div class="card-panel bg-white shadow-card flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center gap-1 text-amber">
                            @for($i = 0; $i < $testi->rating; $i++)
                                <svg class="w-4 h-4 fill-current text-amber" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="text-[15px] text-ink leading-relaxed italic">
                            "{{ $testi->review_text }}"
                        </p>
                    </div>
                    <div class="border-t border-line/60 pt-3">
                        <h4 class="font-display font-bold text-[16px] text-ink">{{ $testi->customer_name }}</h4>
                        <p class="text-[13px] text-muted">{{ $testi->vehicle_model }} • {{ $testi->location }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ========================================================================= --}}
{{-- 9. STUDIO LOCATION & MAP --}}
{{-- ========================================================================= --}}
<section class="py-16 sm:py-24 bg-white border-t border-line" id="location">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-5 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-mint text-teal font-semibold text-[13px]">
                    <span>Studio Location</span>
                </div>
                <h2 class="font-display font-extrabold text-[32px] sm:text-[40px] text-ink tracking-tight">
                    Visit Our Nanak Nagar Studio
                </h2>
                <div class="space-y-3 text-[16px] text-ink">
                    <p class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal shrink-0 mt-0.5 stroke-current stroke-[2]" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span><strong>Address:</strong> Nanak Nagar, Jammu, Jammu & Kashmir 180004</span>
                    </p>
                    <p class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal shrink-0 mt-0.5 stroke-current stroke-[2]" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span><strong>Hours:</strong> Mon–Fri: 9 AM – 7 PM | Sat–Sun: 9 AM – 8 PM</span>
                    </p>
                    <p class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal shrink-0 mt-0.5 stroke-current stroke-[2]" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                        </svg>
                        <span><strong>Phone:</strong> +91 94191 00000</span>
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <x-button variant="primary" :href="'https://maps.google.com/?q=Nanak+Nagar+Jammu'" target="_blank">
                        Get Directions on Map
                    </x-button>
                    <x-button variant="secondary" :href="'tel:+919419100000'">
                        Call Studio
                    </x-button>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="card-panel overflow-hidden p-0 border-2 border-line aspect-[16/10] bg-mist relative flex items-center justify-center text-center">
                    {{-- Google Maps Embed / Static Map Preview --}}
                    <div class="p-8 space-y-4">
                        <div class="w-12 h-12 rounded-full bg-teal text-white flex items-center justify-center mx-auto shadow-md">
                            <svg class="w-6 h-6 stroke-current stroke-[2]" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>
                        <h4 class="font-display font-bold text-[22px] text-ink">The Drive Clinic Studio Map</h4>
                        <p class="text-[15px] text-muted max-w-sm mx-auto">
                            Conveniently located in Nanak Nagar, Jammu with dedicated 3–4 bay entrance.
                        </p>
                        <x-button variant="teal" size="sm" :href="'https://maps.google.com/?q=Nanak+Nagar+Jammu'" target="_blank">
                            Open Interactive Google Map
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 10. FAQ ACCORDION (5 HOME QUESTIONS) --}}
{{-- ========================================================================= --}}
<section class="py-16 sm:py-24 bg-mist border-t border-line">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-section-heading
            title="Frequently Asked Questions"
            lead="Everything you need to know about our wash methods, pricing, and health check diagnostics."
            align="center"
        />

        <div class="space-y-4 pt-4" x-data="{ openFaq: null }">
            @foreach($faqs as $faq)
                <div class="card-panel bg-white p-0 overflow-hidden shadow-xs border border-line">
                    <button
                        type="button"
                        @click="openFaq = openFaq === {{ $faq->id }} ? null : {{ $faq->id }}"
                        class="w-full text-left p-5 sm:p-6 flex items-center justify-between gap-4 font-display font-bold text-[17px] sm:text-[19px] text-ink hover:text-teal transition-colors"
                    >
                        <span>{{ $faq->question }}</span>
                        <span class="text-teal font-bold text-[22px] leading-none shrink-0" x-text="openFaq === {{ $faq->id }} ? '−' : '+'"></span>
                    </button>
                    <div
                        x-show="openFaq === {{ $faq->id }}"
                        x-collapse
                        class="px-5 sm:px-6 pb-6 text-[16px] text-muted leading-relaxed border-t border-line/40 pt-4"
                        style="display: none;"
                    >
                        {{ $faq->answer }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <x-button variant="secondary" :href="route('faq')">
                View all FAQs
            </x-button>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 11. CONTACT & ENQUIRY SECTION --}}
{{-- ========================================================================= --}}
<section class="py-16 sm:py-24 bg-white border-t border-line" id="contact">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <div class="lg:col-span-5 space-y-6">
                <x-section-heading
                    title="Get in touch with our studio"
                    lead="Have a question about paint correction, ceramic coating, or want to book over phone?"
                />
                <div class="space-y-4 pt-2">
                    <a href="https://wa.me/919419100000" class="btn-teal w-full justify-center">
                        Chat on WhatsApp (Instant Reply)
                    </a>
                    <a href="tel:+919419100000" class="btn-secondary w-full justify-center">
                        Call +91 94191 00000
                    </a>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="card-panel bg-paper border border-line shadow-card p-6 sm:p-8">
                    <h3 class="font-display font-bold text-[22px] text-ink mb-2">Send an Enquiry</h3>
                    <p class="text-[14px] text-muted mb-6">Our studio manager will respond within 30 minutes during working hours.</p>

                    @if(session('success'))
                        <div class="p-4 mb-6 rounded-[8px] bg-mint text-teal font-semibold text-[15px] border border-teal/20">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-[14px] font-bold text-ink mb-1">Your Name *</label>
                            <input type="text" name="name" id="name" required class="w-full px-4 py-3 rounded-[8px] border border-line bg-white text-ink text-[16px] focus:outline-none focus:ring-2 focus:ring-amber">
                        </div>
                        <div>
                            <label for="mobile" class="block text-[14px] font-bold text-ink mb-1">Mobile Number (10 Digits) *</label>
                            <input type="tel" name="mobile" id="mobile" pattern="[6-9][0-9]{9}" required placeholder="94191XXXXX" class="w-full px-4 py-3 rounded-[8px] border border-line bg-white text-ink text-[16px] focus:outline-none focus:ring-2 focus:ring-amber">
                        </div>
                        <div>
                            <label for="message" class="block text-[14px] font-bold text-ink mb-1">Message or Car Model *</label>
                            <textarea name="message" id="message" rows="3" required placeholder="e.g. Creta interior deep clean quote" class="w-full px-4 py-3 rounded-[8px] border border-line bg-white text-ink text-[16px] focus:outline-none focus:ring-2 focus:ring-amber"></textarea>
                        </div>
                        <x-button variant="primary" type="submit" class="w-full justify-center">
                            Submit Enquiry
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 12. FINAL CALL TO ACTION BAND --}}
{{-- ========================================================================= --}}
<section class="py-16 sm:py-20 bg-teal-deep text-white text-center border-t border-white/10 relative overflow-hidden" data-motion="final-cta">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 relative z-10">
        <h2 class="font-display font-extrabold text-[34px] sm:text-[48px] text-white tracking-tight leading-[1.05]">
            Ready to give your car <br class="hidden sm:inline">
            <span class="text-amber">the care it deserves?</span>
        </h2>
        <p class="font-body text-[18px] sm:text-[20px] text-white/80 max-w-xl mx-auto leading-relaxed">
            Walk in today at Nanak Nagar or schedule your preferred slot online. No waiting, no swirl marks.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
            <x-button variant="primary" size="lg" :href="route('book')">
                Book your car
            </x-button>
            <x-button variant="light" size="lg" :href="route('health-check.form')">
                Get a free health check
            </x-button>
        </div>
    </div>
</section>
@endsection
