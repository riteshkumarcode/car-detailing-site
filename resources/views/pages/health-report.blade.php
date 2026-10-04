@extends('layouts.public')

@section('title', 'Digital Car Health Report • ' . $check->formatted_plate . ' — The Drive Clinic')
@section('meta_description', 'Verified 25-point cosmetic diagnostic report card for vehicle ' . $check->formatted_plate . '. Drive Health Score: ' . $check->overall_score . '/100.')

@section('content')
<div class="py-10 sm:py-14 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto space-y-8">
    {{-- Verified Header Card --}}
    <div class="bg-white rounded-3xl p-6 sm:p-10 border-2 border-brand-line/80 shadow-lg space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-brand-line pb-6">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-mint text-brand-teal font-bold text-xs uppercase tracking-wider">
                    <span>Verified Diagnostic Report</span>
                </div>
                <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-brand-ink">
                    Digital Car Health Check
                </h1>
                <p class="text-xs text-brand-muted">
                    Conducted on {{ $check->check_date->format('l, d F Y') }} • Check No: <strong class="font-mono text-brand-ink">{{ $check->check_number }}</strong>
                </p>
            </div>

            {{-- Vehicle Plate --}}
            <div class="text-left sm:text-right">
                <span class="font-display font-extrabold text-xl sm:text-2xl tracking-wide bg-brand-paper px-4 py-1.5 rounded-xl border-2 border-brand-ink text-brand-ink inline-block">
                    {{ $check->formatted_plate }}
                </span>
                <span class="text-xs text-brand-muted block mt-1">
                    {{ $check->vehicle->make }} {{ $check->vehicle->model }} ({{ ucfirst($check->vehicle->vehicle_type) }})
                </span>
            </div>
        </div>

        {{-- Drive Health Score Hero Gauge --}}
        <div class="p-6 sm:p-8 rounded-3xl bg-brand-teal-deep text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="space-y-2 text-center sm:text-left">
                <span class="text-xs uppercase tracking-widest text-brand-mint font-bold block">Cosmetic Diagnostic Assessment</span>
                <h2 class="font-display font-extrabold text-2xl text-white">Overall Drive Health Score</h2>
                <p class="text-xs text-brand-mint/80 max-w-sm">
                    Calculated from 25 cosmetic checkpoints across body paint, cabin interior, wheels, glass, and protective barrier.
                </p>
            </div>

            {{-- Big Score Number Gauge --}}
            <div class="flex flex-col items-center justify-center p-5 rounded-2xl bg-white/10 border border-white/15 min-w-[160px] text-center">
                <span class="font-display font-black text-5xl sm:text-6xl text-brand-amber">
                    {{ $check->overall_score }}
                </span>
                <span class="text-xs text-brand-mint font-bold uppercase tracking-wider mt-1">out of 100</span>
                <span class="inline-block mt-2 px-3 py-0.5 rounded-full text-[11px] font-bold uppercase 
                    @if($check->overall_score >= 80) bg-brand-mint text-brand-teal
                    @elseif($check->overall_score >= 50) bg-amber-200 text-amber-900
                    @else bg-red-200 text-red-900
                    @endif
                ">
                    @if($check->overall_score >= 80) Excellent Condition
                    @elseif($check->overall_score >= 50) Fair • Needs Attention
                    @else Critical Attention
                    @endif
                </span>
            </div>
        </div>

        {{-- 5 Category Breakdown Bars --}}
        <div class="space-y-4 pt-2">
            <h3 class="font-display font-bold text-lg text-brand-ink">Category Score Breakdown</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Exterior --}}
                <div class="p-4 rounded-2xl bg-brand-paper border border-brand-line space-y-2">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-brand-ink">🚗 Exterior Paint & Body</span>
                        <span class="text-brand-teal">{{ $check->category_scores['exterior'] ?? 0 }} / 30 pts</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-brand-teal rounded-full" style="width: {{ (($check->category_scores['exterior'] ?? 0) / 30) * 100 }}%"></div>
                    </div>
                </div>

                {{-- Interior --}}
                <div class="p-4 rounded-2xl bg-brand-paper border border-brand-line space-y-2">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-brand-ink">💺 Interior Cabin & Sanitization</span>
                        <span class="text-brand-teal">{{ $check->category_scores['interior'] ?? 0 }} / 30 pts</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-brand-teal rounded-full" style="width: {{ (($check->category_scores['interior'] ?? 0) / 30) * 100 }}%"></div>
                    </div>
                </div>

                {{-- Wheels --}}
                <div class="p-4 rounded-2xl bg-brand-paper border border-brand-line space-y-2">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-brand-ink">🛞 Wheels & Tyres</span>
                        <span class="text-brand-teal">{{ $check->category_scores['wheels'] ?? 0 }} / 15 pts</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-brand-teal rounded-full" style="width: {{ (($check->category_scores['wheels'] ?? 0) / 15) * 100 }}%"></div>
                    </div>
                </div>

                {{-- Glass --}}
                <div class="p-4 rounded-2xl bg-brand-paper border border-brand-line space-y-2">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-brand-ink">🪟 Glass & Night Visibility</span>
                        <span class="text-brand-teal">{{ $check->category_scores['glass'] ?? 0 }} / 15 pts</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-brand-teal rounded-full" style="width: {{ (($check->category_scores['glass'] ?? 0) / 15) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recommendations Section --}}
        @if(!empty($check->recommended_today) || !empty($check->recommended_later))
            <div class="space-y-4 pt-4 border-t border-brand-line">
                <h3 class="font-display font-extrabold text-xl text-brand-ink">Recommended Treatment Plan</h3>

                {{-- Today --}}
                @if(!empty($check->recommended_today))
                    <div class="space-y-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-red-700 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-600 inline-block"></span>
                            Priority Treatments (Recommended Today)
                        </span>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($check->recommended_today as $item)
                                <div class="p-4 rounded-2xl border-2 border-brand-line/80 bg-brand-paper flex flex-col justify-between space-y-2">
                                    <div>
                                        <h4 class="font-display font-bold text-base text-brand-ink">{{ $item['name'] }}</h4>
                                        <p class="text-xs text-brand-muted mt-0.5">{{ $item['reason'] ?? '' }}</p>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-brand-line/50">
                                        <span class="font-display font-extrabold text-lg text-brand-ink">₹{{ number_format($item['price'] ?? 0) }}</span>
                                        <a 
                                            href="{{ route('book', ['service' => $item['slug'] ?? '']) }}" 
                                            class="px-3.5 py-1.5 rounded-xl bg-brand-amber text-brand-ink font-bold text-xs hover:brightness-105"
                                        >
                                            Book This →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Later --}}
                @if(!empty($check->recommended_later))
                    <div class="space-y-3 pt-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-700 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-600 inline-block"></span>
                            Maintenance & Long-Term Care (Can Wait)
                        </span>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($check->recommended_later as $item)
                                <div class="p-4 rounded-2xl border border-brand-line bg-white flex flex-col justify-between space-y-2">
                                    <div>
                                        <h4 class="font-display font-bold text-sm text-brand-ink">{{ $item['name'] }}</h4>
                                        <p class="text-xs text-brand-muted mt-0.5">{{ $item['reason'] ?? '' }}</p>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-brand-line/50">
                                        <span class="font-bold text-sm text-brand-muted">₹{{ number_format($item['price'] ?? 0) }}</span>
                                        <span class="text-xs text-brand-teal font-medium">Available on next visit</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Technician Notes --}}
        @if($check->technician_notes)
            <div class="p-4 rounded-2xl bg-brand-mist/70 border border-brand-line space-y-1">
                <span class="text-xs font-bold text-brand-ink uppercase block">Inspector Remarks</span>
                <p class="text-xs text-brand-muted italic leading-relaxed">"{{ $check->technician_notes }}"</p>
            </div>
        @endif

        {{-- Mandatory Legal Disclaimer --}}
        <div class="p-4 rounded-2xl bg-brand-paper border border-brand-line/80 space-y-1">
            <span class="text-[11px] font-bold text-brand-muted uppercase block">Legal Disclaimer</span>
            <p class="text-xs text-brand-muted italic leading-relaxed">
                {{ $disclaimer }}
            </p>
        </div>

        {{-- Footer Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-brand-line">
            <a 
                href="{{ route('home') }}" 
                class="text-xs text-brand-muted hover:text-brand-ink font-bold"
            >
                ← The Drive Clinic • Nanak Nagar, Jammu
            </a>

            <div class="flex items-center gap-3">
                <button 
                    type="button" 
                    onclick="window.print()" 
                    class="px-5 py-2.5 rounded-xl border border-brand-line text-brand-ink font-bold text-xs hover:bg-brand-mist"
                >
                    Print Report 🖨️
                </button>

                <a 
                    href="{{ route('book') }}" 
                    class="px-6 py-2.5 rounded-xl bg-brand-teal text-white font-bold text-xs shadow-md hover:bg-brand-teal-deep"
                >
                    Schedule Treatment →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
