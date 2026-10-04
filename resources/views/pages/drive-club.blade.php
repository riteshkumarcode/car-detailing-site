@extends('layouts.public')

@section('title', 'Drive Club Memberships — The Drive Clinic Jammu')
@section('meta_description', 'Annual car care memberships in Jammu. Unlimited swirl-free foam washes, interior deep cleans, and guaranteed discounts.')

@section('content')
<div class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-16">
    <div class="max-w-3xl mx-auto text-center space-y-4">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-mint text-teal font-semibold text-[13px]">
            <span>Annual Vehicle Care</span>
        </div>
        <h1 class="font-display font-extrabold text-[36px] sm:text-[50px] text-ink tracking-tight leading-[1.05]">
            Join the Drive Club.
        </h1>
        <p class="font-body text-[18px] sm:text-[20px] text-muted leading-relaxed">
            Predictable, high-grade car maintenance without the hassle of paying per wash. Priority scheduling and exclusive member perks all year round.
        </p>
    </div>

    {{-- 3-Tier Plans Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch max-w-6xl mx-auto">
        @foreach($plans as $plan)
            <div class="card-panel flex flex-col justify-between relative {{ $plan->is_featured ? 'border-2 border-amber shadow-brand bg-paper' : 'bg-white' }}">
                @if($plan->is_featured)
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-amber text-ink font-display font-extrabold text-[12px] px-3.5 py-1 rounded-full uppercase tracking-wider shadow-sm">
                        Most Popular Plan
                    </div>
                @endif

                <div class="space-y-4">
                    <h3 class="font-display font-extrabold text-[24px] text-ink">{{ $plan->name }}</h3>
                    <div>
                        <span class="font-display font-extrabold text-[40px] text-ink">₹{{ number_format($plan->price) }}</span>
                        <span class="text-muted text-[15px]">/ year</span>
                    </div>
                    <p class="text-[14px] text-muted leading-relaxed border-b border-line/60 pb-4">
                        {{ $plan->benefits_description }}
                    </p>

                    <ul class="space-y-3 text-[15px] text-ink font-medium">
                        @foreach($plan->features ?? [] as $feat)
                            <li class="flex items-start gap-2.5">
                                <span class="text-teal font-bold shrink-0">✓</span>
                                <span>{{ $feat }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="pt-8">
                    <a
                        href="https://wa.me/919419100000?text=Hi%20The%20Drive%20Clinic,%20I%20am%20interested%20in%20the%20{{ urlencode($plan->name) }}"
                        target="_blank"
                        class="{{ $plan->is_featured ? 'btn-primary' : 'btn-secondary' }} w-full justify-center"
                    >
                        Enquire on WhatsApp
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- How Membership Works (4 Steps) --}}
    <div class="card-panel bg-white p-8 sm:p-12 space-y-8 max-w-5xl mx-auto">
        <x-section-heading
            title="How Drive Club Works"
            lead="Simple, transparent membership designed around your vehicle."
            align="center"
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="space-y-2">
                <span class="w-8 h-8 rounded-full bg-teal text-white font-bold flex items-center justify-center text-[14px]">1</span>
                <h4 class="font-display font-bold text-[18px] text-ink">Choose Your Plan</h4>
                <p class="text-[14px] text-muted leading-relaxed">Select the tier that matches your monthly wash frequency and detailing needs.</p>
            </div>
            <div class="space-y-2">
                <span class="w-8 h-8 rounded-full bg-teal text-white font-bold flex items-center justify-center text-[14px]">2</span>
                <h4 class="font-display font-bold text-[18px] text-ink">Link Your Vehicle</h4>
                <p class="text-[14px] text-muted leading-relaxed">Membership attaches directly to your registration plate with automatic recognition.</p>
            </div>
            <div class="space-y-2">
                <span class="w-8 h-8 rounded-full bg-teal text-white font-bold flex items-center justify-center text-[14px]">3</span>
                <h4 class="font-display font-bold text-[18px] text-ink">Drive In & Relax</h4>
                <p class="text-[14px] text-muted leading-relaxed">Enjoy priority weekend bays and zero checkout delay — counts decrement automatically.</p>
            </div>
            <div class="space-y-2">
                <span class="w-8 h-8 rounded-full bg-teal text-white font-bold flex items-center justify-center text-[14px]">4</span>
                <h4 class="font-display font-bold text-[18px] text-ink">Passport Tracking</h4>
                <p class="text-[14px] text-muted leading-relaxed">View your remaining wash balance and condition logs on your Digital Passport.</p>
            </div>
        </div>
    </div>
</div>
@endsection
