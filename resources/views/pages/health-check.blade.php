@extends('layouts.public')

@section('title', 'Free Digital Car Health Check in Jammu — The Drive Clinic')
@section('meta_description', 'Book a free 25-point cosmetic car health diagnosis in Nanak Nagar, Jammu. Get your 0-100 Drive Health Score and photographic report on WhatsApp.')

@section('content')
<div class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12">
    <div class="max-w-3xl mx-auto text-center space-y-4">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-brand-mint text-brand-teal font-semibold text-[13px]">
            <span>100% Free • No Obligation Diagnostic</span>
        </div>
        <h1 class="font-display font-extrabold text-[36px] sm:text-[50px] text-brand-ink tracking-tight leading-[1.05]">
            Free Digital Car Health Check
        </h1>
        <p class="font-body text-[18px] sm:text-[20px] text-brand-muted leading-relaxed">
            Get an objective cosmetic diagnosis of your vehicle's paint, interior, glass, and wheels before you spend a single rupee on cleaning or protection.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start max-w-5xl mx-auto">
        {{-- Livewire Diagnostic Form --}}
        <div class="lg:col-span-7">
            <livewire:public.health-check-lead-form />
        </div>

        {{-- Sidebar: Diagnostic Card & Explainer --}}
        <div class="lg:col-span-5 space-y-6">
            {{-- What we check card --}}
            <div class="bg-brand-teal-deep text-white rounded-3xl p-6 sm:p-7 shadow-lg border border-brand-teal/40 space-y-4">
                <div class="flex items-center gap-2 text-brand-amber font-display font-bold text-sm tracking-wide uppercase">
                    <span>⚡ 5 Inspection Checkpoints</span>
                </div>
                <h4 class="font-display font-bold text-xl text-white">What We Inspect on the Ramp</h4>
                <ul class="space-y-3 text-sm text-brand-mint/90 font-medium">
                    <li class="flex items-start gap-2.5">
                        <span class="text-brand-amber font-bold">1.</span>
                        <span><strong>Paint Condition:</strong> Swirls, micro-marring, clear coat oxidation and scratch depth.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-brand-amber font-bold">2.</span>
                        <span><strong>Cabin & Upholstery:</strong> Bacteria hotspots, fabric stains, leather hydration & AC odour.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-brand-amber font-bold">3.</span>
                        <span><strong>Glass & Visibility:</strong> Hard water scaling, wiper arc scratches & oil film buildup.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-brand-amber font-bold">4.</span>
                        <span><strong>Wheels & Brake Dust:</strong> Iron deposit contamination, tyre sidewall dry rot & fading.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-brand-amber font-bold">5.</span>
                        <span><strong>Protection Layer:</strong> Hydrophobic contact angle & ceramic coating degradation.</span>
                    </li>
                </ul>
            </div>

            {{-- What happens next card --}}
            <div class="bg-white rounded-3xl p-6 border border-brand-line shadow-sm space-y-4">
                <h4 class="font-display font-bold text-lg text-brand-ink">How Your Visit Works</h4>
                <div class="space-y-3 text-xs text-brand-muted">
                    <div class="flex gap-3">
                        <span class="w-6 h-6 rounded-full bg-brand-mint text-brand-teal font-bold flex items-center justify-center shrink-0">1</span>
                        <p><strong class="text-brand-ink">Drive In:</strong> Bring your car to our studio in Nanak Nagar (15 min slot).</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-6 h-6 rounded-full bg-brand-mint text-brand-teal font-bold flex items-center justify-center shrink-0">2</span>
                        <p><strong class="text-brand-ink">Multi-Point Ramp Scan:</strong> Certified technician scans 25 checkpoints with high-CRI inspection lights.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-6 h-6 rounded-full bg-brand-mint text-brand-teal font-bold flex items-center justify-center shrink-0">3</span>
                        <p><strong class="text-brand-ink">Instant WhatsApp Report:</strong> Receive your 0–100 Drive Health Score, photos, and itemized priority plan.</p>
                    </div>
                </div>

                {{-- Mandatory Disclaimer --}}
                <div class="pt-3 border-t border-brand-line/60">
                    <p class="text-[11px] text-brand-muted/80 leading-relaxed italic">
                        * Note: The Drive Health Score describes cosmetic condition only. It is not a certified mechanical, roadworthiness or safety inspection.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
