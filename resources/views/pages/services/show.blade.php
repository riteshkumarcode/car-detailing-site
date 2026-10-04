@extends('layouts.public')

@section('title', $service->meta_title ?? ($service->name . ' — The Drive Clinic Jammu'))
@section('meta_description', $service->meta_description ?? $service->short_description)

@section('content')
<div class="py-10 sm:py-14 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[14px] text-muted mb-6">
        <a href="{{ route('home') }}" class="hover:text-teal">Home</a>
        <span>/</span>
        <a href="{{ route('services.index') }}" class="hover:text-teal">Services</a>
        <span>/</span>
        <span class="text-ink font-semibold">{{ $service->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
        {{-- Main Content Column --}}
        <div class="lg:col-span-8 space-y-12">
            {{-- Service Header --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="text-[13px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-mint text-teal">
                        {{ $service->category->name ?? 'Studio Service' }}
                    </span>
                    <span class="text-[14px] text-muted font-semibold">
                        ⏱ Duration: ~{{ $service->duration_minutes }} minutes
                    </span>
                </div>
                <h1 class="font-display font-extrabold text-[34px] sm:text-[46px] text-ink tracking-tight leading-[1.05]">
                    {{ $service->name }}
                </h1>
                <p class="font-body text-[19px] text-muted leading-relaxed">
                    {{ $service->full_description ?? $service->short_description }}
                </p>
            </div>

            {{-- Service Visual Showcase --}}
            @if(str_contains($service->slug, 'paint') || str_contains($service->slug, 'ceramic'))
                <div class="space-y-3">
                    <span class="text-[12px] font-bold uppercase tracking-wider text-teal block">Real Studio Result • Drag to Compare</span>
                    <x-before-after-slider
                        title="Swirl Removal & High-Gloss Ceramic Finish"
                        carModel="Mahindra Thar (Napoli Black)"
                        problem="Swirl marks & spiderweb wash scratches"
                        service="{{ $service->name }}"
                        beforeImage="images/paint-before.jpg"
                        afterImage="images/paint-after.jpg"
                    />
                </div>
            @else
                <div class="rounded-3xl overflow-hidden shadow-card border border-line relative group">
                    <img
                        src="{{ asset('images/hero-studio-bay.jpg') }}"
                        alt="{{ $service->name }} Detailing Bay in Jammu"
                        class="w-full aspect-[21/9] object-cover object-center group-hover:scale-[1.02] transition-transform duration-500"
                    >
                    <div class="absolute bottom-3 left-4 right-4 bg-ink/80 backdrop-blur-sm text-white px-4 py-2 rounded-xl text-[13px] font-semibold flex items-center justify-between">
                        <span>Clinic Bay 01 • Nanak Nagar, Jammu</span>
                        <span class="text-amber">2-Bucket Swirl-Free Guarantee</span>
                    </div>
                </div>
            @endif

            {{-- The Problem Solved & Who It's For --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="card-panel bg-white space-y-2 border-l-4 border-brick">
                    <span class="text-[12px] font-bold uppercase tracking-wider text-brick">The Problem It Solves</span>
                    <p class="text-[15px] text-ink font-medium leading-relaxed">
                        {{ $service->target_problem ?? 'Accumulated contamination and paint defects that degrade vehicle aesthetics and protection.' }}
                    </p>
                </div>
                <div class="card-panel bg-white space-y-2 border-l-4 border-teal">
                    <span class="text-[12px] font-bold uppercase tracking-wider text-teal">Who It's For</span>
                    <p class="text-[15px] text-ink font-medium leading-relaxed">
                        {{ $service->who_its_for ?? 'Owners who demand swirl-free maintenance and long-term vehicle preservation in Jammu.' }}
                    </p>
                </div>
            </div>

            {{-- Step-by-Step Clinic Process Timeline --}}
            @if(!empty($service->process_steps))
                <div class="space-y-6">
                    <x-section-heading
                        title="Step-by-step treatment process"
                        lead="How our trained technicians execute this service in our dedicated bay."
                    />
                    <div class="space-y-4">
                        @foreach($service->process_steps as $step)
                            <div class="card-panel bg-white p-6 flex items-start gap-4">
                                <div class="w-9 h-9 rounded-full bg-amber text-ink font-display font-extrabold flex items-center justify-center shrink-0 text-[15px]">
                                    {{ $step['step_number'] ?? $loop->iteration }}
                                </div>
                                <div>
                                    <h4 class="font-display font-bold text-[18px] text-ink">{{ $step['title'] }}</h4>
                                    <p class="text-[15px] text-muted leading-relaxed mt-1">{{ $step['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Service-Specific FAQs --}}
            @if(!empty($service->faqs))
                <div class="space-y-6" x-data="{ openFaq: null }">
                    <x-section-heading
                        title="Service FAQs"
                        lead="Common questions about this treatment."
                    />
                    <div class="space-y-3">
                        @foreach($service->faqs as $idx => $faq)
                            <div class="card-panel bg-white p-0 overflow-hidden">
                                <button
                                    type="button"
                                    @click="openFaq = openFaq === {{ $idx }} ? null : {{ $idx }}"
                                    class="w-full text-left p-5 flex items-center justify-between font-display font-bold text-[17px] text-ink hover:text-teal"
                                >
                                    <span>{{ $faq['question'] }}</span>
                                    <span class="text-teal font-bold" x-text="openFaq === {{ $idx }} ? '−' : '+'"></span>
                                </button>
                                <div x-show="openFaq === {{ $idx }}" x-collapse class="px-5 pb-5 text-[15px] text-muted border-t border-line/40 pt-3" style="display: none;">
                                    {{ $faq['answer'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sticky Sidebar --}}
        <div class="lg:col-span-4 sticky top-28 space-y-6">
            <div class="card-panel bg-white border-2 border-line/80 shadow-brand p-6 space-y-6">
                <div>
                    <span class="text-[12px] font-bold uppercase tracking-wider text-muted block mb-1">Pricing By Vehicle Type</span>
                    <div class="border border-line rounded-[8px] overflow-hidden divide-y divide-line">
                        <div class="p-3 flex items-center justify-between text-[15px] bg-paper">
                            <span class="font-semibold text-ink">Hatchback</span>
                            <span class="font-display font-extrabold text-[18px] text-ink">₹{{ number_format($service->price_hatchback ?? 0) }}</span>
                        </div>
                        <div class="p-3 flex items-center justify-between text-[15px] bg-white">
                            <span class="font-semibold text-ink">Sedan</span>
                            <span class="font-display font-extrabold text-[18px] text-ink">₹{{ number_format($service->price_sedan ?? 0) }}</span>
                        </div>
                        <div class="p-3 flex items-center justify-between text-[15px] bg-paper">
                            <span class="font-semibold text-ink">SUV / 4x4</span>
                            <span class="font-display font-extrabold text-[18px] text-ink">₹{{ number_format($service->price_suv ?? 0) }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <x-button variant="primary" size="lg" class="w-full justify-center" :href="route('book', ['service' => $service->slug])">
                        Book this service
                    </x-button>
                    <p class="text-[12px] text-muted text-center mt-2">Walk-ins also welcome • Instant slot confirmation</p>
                </div>

                {{-- Complete What's Included --}}
                <div class="space-y-2 border-t border-line/60 pt-4">
                    <span class="text-[13px] font-bold uppercase tracking-wider text-ink block">What's Included:</span>
                    <ul class="space-y-2 text-[14px] text-ink">
                        @foreach($service->whats_included ?? [] as $item)
                            <li class="flex items-start gap-2">
                                <span class="text-teal font-bold shrink-0">✓</span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Add-ons list --}}
                @if(!empty($service->add_ons))
                    <div class="space-y-2 border-t border-line/60 pt-4">
                        <span class="text-[13px] font-bold uppercase tracking-wider text-ink block">Recommended Add-Ons:</span>
                        <div class="space-y-2">
                            @foreach($service->add_ons as $addon)
                                <div class="p-2.5 rounded-[6px] bg-mist text-[13px] flex items-center justify-between">
                                    <span class="font-semibold text-ink">{{ $addon['name'] }}</span>
                                    <span class="font-bold text-teal">+₹{{ number_format($addon['price']) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
