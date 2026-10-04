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
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
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
        </div>

        <div class="lg:col-span-6">
            <div class="card-panel bg-white p-8 space-y-6 shadow-brand border-2 border-line">
                <h3 class="font-display font-bold text-[22px] text-ink">Four Things We Promise:</h3>
                <div class="space-y-4 text-[16px] text-ink font-medium">
                    <div class="flex items-start gap-3">
                        <span class="text-teal font-bold text-[20px]">1.</span>
                        <p><strong>Zero Swirl Guarantee:</strong> We never touch dry paint. 2-bucket wash methods with separate clean mitts per panel zone.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-teal font-bold text-[20px]">2.</span>
                        <p><strong>Pure pH-Neutral Chemistry:</strong> Shampoos and degreasers that clean deep without stripping waxes or ceramic bonds.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-teal font-bold text-[20px]">3.</span>
                        <p><strong>Transparent WhatsApp Diagnosis:</strong> Free 5-point cosmetic check with photos on every visit, with zero pressure.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-teal font-bold text-[20px]">4.</span>
                        <p><strong>Honest Pricing:</strong> No surprise add-on bills. You approve every service before work begins.</p>
                    </div>
                </div>
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
