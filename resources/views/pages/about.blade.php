@extends('layouts.public')

@section('title', 'About Our Healthcare Philosophy — The Drive Clinic Jammu')
@section('meta_description', 'Discover why The Drive Clinic is Jammu\'s premier car healthcare studio. Learn about our diagnostic methodology, 2-bucket safety, and 0-100 score.')

@section('content')
<div class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-16">
    {{-- Header Banner --}}
    <div class="border-b border-line pb-8 text-center max-w-3xl mx-auto space-y-4">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-mint text-teal font-semibold text-[13px]">
            <span>Our Origin & Methodology</span>
        </div>
        <h1 class="font-display font-extrabold text-[36px] sm:text-[50px] text-ink tracking-tight leading-[1.05]">
            "Your Car's Healthcare Centre"
        </h1>
        <p class="font-body text-[18px] sm:text-[20px] text-muted leading-relaxed">
            We built The Drive Clinic in Nanak Nagar because Jammu needed a modern, scientific alternative to roadside washes that ruin clear coats with dirty rags and harsh detergents.
        </p>
    </div>

    {{-- Story Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
        <div class="lg:col-span-6 space-y-5">
            <h2 class="font-display font-extrabold text-[28px] sm:text-[36px] text-ink tracking-tight">
                Why Diagnostic Detailing Matters
            </h2>
            <p class="text-[17px] text-muted leading-relaxed">
                Most swirl marks, paint fading, and dullness on vehicles are not caused by age — they are caused by improper washing. In Jammu, roadside cleaners often reuse coarse cloths and hard water, essentially sanding your vehicle's clear coat every single morning.
            </p>
            <p class="text-[17px] text-muted leading-relaxed">
                At The Drive Clinic, we treat every vehicle like a patient entering a modern clinic. We diagnose paint condition, isolate dirt with snow foam pre-soaks, use 2-bucket grit-guarded contact washes, and log your vehicle's ongoing health into a Digital Car Passport.
            </p>
            <div class="pt-2">
                <x-button variant="primary" :href="route('book')">
                    Book a Studio Appointment
                </x-button>
            </div>
        </div>

        <div class="lg:col-span-6">
            <div class="rounded-3xl overflow-hidden shadow-2xl border-2 border-line/80 relative group">
                <img
                    src="{{ asset('images/hero-studio-bay.jpg') }}"
                    alt="The Drive Clinic Diagnostic Bay in Nanak Nagar, Jammu"
                    class="w-full aspect-[4/3] object-cover object-center group-hover:scale-105 transition-transform duration-500"
                >
                <div class="absolute bottom-4 left-4 right-4 bg-ink/90 backdrop-blur-md text-white p-4 rounded-2xl border border-white/10 flex items-center justify-between">
                    <div>
                        <span class="text-[12px] font-bold text-amber uppercase tracking-wider block">Dedicated Facility</span>
                        <span class="font-display font-bold text-[15px]">Nanak Nagar Detailing Studio</span>
                    </div>
                    <span class="text-[12px] font-bold px-2.5 py-1 rounded-full bg-teal text-white">Ramp 01</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Four Things We Promise --}}
    <div class="space-y-6 pt-4">
        <div class="text-center max-w-xl mx-auto">
            <h3 class="font-display font-extrabold text-[26px] text-ink">Four Things We Promise Every Owner</h3>
            <p class="text-muted text-[15px]">The non-negotiable standards upheld on every ramp inspection and wash.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="card-panel bg-white p-6 space-y-3 shadow-card border border-line">
                <span class="w-8 h-8 rounded-full bg-teal text-white font-display font-extrabold flex items-center justify-center text-[14px]">1</span>
                <h4 class="font-display font-bold text-[18px] text-ink">Zero Swirl Guarantee</h4>
                <p class="text-[14px] text-muted leading-relaxed">We never touch dry paint. 2-bucket wash methods with separate clean mitts per panel zone.</p>
            </div>
            <div class="card-panel bg-white p-6 space-y-3 shadow-card border border-line">
                <span class="w-8 h-8 rounded-full bg-teal text-white font-display font-extrabold flex items-center justify-center text-[14px]">2</span>
                <h4 class="font-display font-bold text-[18px] text-ink">pH-Neutral Chemistry</h4>
                <p class="text-[14px] text-muted leading-relaxed">Shampoos and degreasers that clean deep without stripping waxes or ceramic protective bonds.</p>
            </div>
            <div class="card-panel bg-white p-6 space-y-3 shadow-card border border-line">
                <span class="w-8 h-8 rounded-full bg-teal text-white font-display font-extrabold flex items-center justify-center text-[14px]">3</span>
                <h4 class="font-display font-bold text-[18px] text-ink">WhatsApp Diagnosis</h4>
                <p class="text-[14px] text-muted leading-relaxed">Free 5-point cosmetic check with photos on every visit, sent straight to your phone with zero pressure.</p>
            </div>
            <div class="card-panel bg-white p-6 space-y-3 shadow-card border border-line">
                <span class="w-8 h-8 rounded-full bg-teal text-white font-display font-extrabold flex items-center justify-center text-[14px]">4</span>
                <h4 class="font-display font-bold text-[18px] text-ink">Honest Pricing</h4>
                <p class="text-[14px] text-muted leading-relaxed">No surprise add-on bills or bait-and-switch tactics. You approve every service item before work begins.</p>
            </div>
        </div>
    </div>

    {{-- Drive Health Score Breakdown Table --}}
    <div class="card-panel bg-white p-8 sm:p-12 space-y-8 max-w-5xl mx-auto">
        <x-section-heading
            title="How the Drive Health Score Works"
            lead="Our proprietary 0–100 scoring algorithm evaluates five weighted cosmetic health categories."
            align="center"
        />

        <div class="border border-line rounded-[10px] overflow-hidden">
            <table class="w-full text-left border-collapse text-[15px]">
                <thead>
                    <tr class="bg-mist border-b border-line text-ink font-display font-bold">
                        <th class="p-4">Diagnostic Category</th>
                        <th class="p-4">Score Weight</th>
                        <th class="p-4">Inspection Checklist Items</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-muted">
                    <tr class="hover:bg-mist/30">
                        <td class="p-4 font-bold text-ink">Exterior / Paint</td>
                        <td class="p-4 font-display font-bold text-teal">30% Weight</td>
                        <td class="p-4">Swirl marks, scratches, acid rain spots, oxidation, clear coat gloss.</td>
                    </tr>
                    <tr class="hover:bg-mist/30">
                        <td class="p-4 font-bold text-ink">Interior Cabin</td>
                        <td class="p-4 font-display font-bold text-teal">30% Weight</td>
                        <td class="p-4">Seats upholstery, carpet stains, AC vent cleanliness, dashboard UV state.</td>
                    </tr>
                    <tr class="hover:bg-mist/30">
                        <td class="p-4 font-bold text-ink">Wheels & Tyres</td>
                        <td class="p-4 font-display font-bold text-teal">15% Weight</td>
                        <td class="p-4">Corrosive brake dust etching, alloy rim pitting, tyre wall conditioning.</td>
                    </tr>
                    <tr class="hover:bg-mist/30">
                        <td class="p-4 font-bold text-ink">Glass & Windshield</td>
                        <td class="p-4 font-display font-bold text-teal">15% Weight</td>
                        <td class="p-4">Hard water mineral deposits, wiper trails, glass clarity and night glare.</td>
                    </tr>
                    <tr class="hover:bg-mist/30">
                        <td class="p-4 font-bold text-ink">Surface Protection</td>
                        <td class="p-4 font-display font-bold text-teal">10% Weight</td>
                        <td class="p-4">Active hydrophobic beading status: Ceramic (Good), Wax (Fair), None (Needs attention).</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-paper p-5 rounded-[8px] border border-line text-center text-[13px] text-muted leading-relaxed">
            <strong>Mandatory Cosmetic Disclaimer:</strong> {{ $disclaimer }}
        </div>
    </div>
</div>
@endsection
