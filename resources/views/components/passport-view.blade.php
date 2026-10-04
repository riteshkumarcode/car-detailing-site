@props(['vehicle', 'timeline', 'isStaffView' => false])

<div class="space-y-8">
    <!-- Vehicle Summary HUD -->
    <div class="bg-clinical-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 pb-6 border-b border-slate-800">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-mono font-extrabold text-lg sm:text-xl rounded-lg tracking-wider">
                        {{ $vehicle->formatted_plate }}
                    </span>
                    <span class="px-2.5 py-1 bg-slate-800 text-slate-300 text-xs font-semibold uppercase tracking-wider rounded-md border border-slate-700">
                        {{ $vehicle->vehicle_type }}
                    </span>
                    @if($vehicle->is_member || $vehicle->customer?->is_member)
                        <x-member-badge :membership="$vehicle->activeMembership ?? $vehicle->customer?->activeMembership" size="sm" />
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-white mt-3">
                    {{ $vehicle->make }} {{ $vehicle->model }}
                    @if($vehicle->variant)
                        <span class="text-slate-400 font-normal text-lg sm:text-xl">{{ $vehicle->variant }}</span>
                    @endif
                </h1>

                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-400 mt-2 font-mono">
                    <span>Colour: <strong class="text-slate-200 capitalize">{{ $vehicle->colour ?? 'Standard' }}</strong></span>
                    <span>&bull;</span>
                    <span>Owner: <strong class="text-slate-200">{{ $vehicle->customer?->name ?? 'Walk-in' }}</strong> (+91 {{ $vehicle->customer?->mobile }})</span>
                </div>
            </div>

            <!-- Health Score Widget -->
            @php
                $latestCheck = $vehicle->latestHealthCheck;
                $score = $latestCheck?->overall_score ?? null;
            @endphp
            <div class="flex items-center gap-4 bg-clinical-950/80 border border-slate-800 p-4 rounded-xl self-stretch sm:self-auto">
                <div class="text-center">
                    <div class="text-[10px] uppercase font-mono tracking-widest text-slate-400">Drive Health Score</div>
                    <div class="text-3xl sm:text-4xl font-extrabold font-mono {{ $score ? ($score >= 80 ? 'text-emerald-400' : ($score >= 60 ? 'text-amber-400' : 'text-red-400')) : 'text-slate-500' }}">
                        {{ $score ? $score . '/100' : 'N/A' }}
                    </div>
                </div>
                <div class="h-10 w-px bg-slate-800"></div>
                <div class="text-xs text-slate-400 space-y-0.5">
                    <div>Status: <strong class="text-slate-200">{{ $score ? ($score >= 80 ? 'Optimal' : ($score >= 60 ? 'Moderate' : 'Attention Required')) : 'Uninspected' }}</strong></div>
                    <div>Last Check: <span class="font-mono text-slate-300">{{ $latestCheck ? $latestCheck->check_date->format('d M Y') : 'None' }}</span></div>
                </div>
            </div>
        </div>

        <!-- Metric Counters -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-6">
            <div class="bg-clinical-950/60 border border-slate-800/60 p-4 rounded-xl">
                <div class="text-[11px] text-slate-400 uppercase tracking-wider font-mono">Total Visits</div>
                <div class="text-2xl font-bold text-white font-mono mt-1">{{ $vehicle->total_visits }}</div>
            </div>
            <div class="bg-clinical-950/60 border border-slate-800/60 p-4 rounded-xl">
                <div class="text-[11px] text-slate-400 uppercase tracking-wider font-mono">Lifetime Spend</div>
                <div class="text-2xl font-bold text-amber-400 font-mono mt-1">₹{{ number_format($vehicle->total_spend, 0) }}</div>
            </div>
            <div class="bg-clinical-950/60 border border-slate-800/60 p-4 rounded-xl">
                <div class="text-[11px] text-slate-400 uppercase tracking-wider font-mono">First Visit</div>
                <div class="text-sm font-semibold text-slate-200 font-mono mt-2">{{ $vehicle->first_visit ?? 'N/A' }}</div>
            </div>
            <div class="bg-clinical-950/60 border border-slate-800/60 p-4 rounded-xl">
                <div class="text-[11px] text-slate-400 uppercase tracking-wider font-mono">Last Visit</div>
                <div class="text-sm font-semibold text-slate-200 font-mono mt-2">{{ $vehicle->last_visit ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Active Recommendations Callout -->
    @if($latestCheck && (!empty($latestCheck->recommended_today) || !empty($latestCheck->recommended_later)))
        <div class="bg-clinical-900/90 border border-amber-500/30 rounded-2xl p-6 shadow-xl relative">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <h3 class="text-base font-bold text-white">Recommended Clinical Treatments</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if(!empty($latestCheck->recommended_today))
                    <div class="bg-clinical-950/80 border border-slate-800 p-4 rounded-xl">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 font-mono">Immediate / Next Visit</span>
                        <ul class="mt-2 space-y-2">
                            @foreach($latestCheck->recommended_today as $rec)
                                <li class="text-xs text-slate-300 flex justify-between items-start gap-2">
                                    <span>&bull; {{ $rec['name'] ?? $rec['service'] }}</span>
                                    <span class="font-mono text-amber-400 font-semibold">₹{{ number_format($rec['price'] ?? 0) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(!empty($latestCheck->recommended_later))
                    <div class="bg-clinical-950/80 border border-slate-800 p-4 rounded-xl">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">Long-Term Maintenance Plan</span>
                        <ul class="mt-2 space-y-2">
                            @foreach($latestCheck->recommended_later as $rec)
                                <li class="text-xs text-slate-300 flex justify-between items-start gap-2">
                                    <span>&bull; {{ $rec['name'] ?? $rec['service'] }}</span>
                                    <span class="font-mono text-slate-300">₹{{ number_format($rec['price'] ?? 0) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Chronological Passport Timeline -->
    <div class="bg-clinical-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl">
        <div class="flex items-center justify-between pb-6 border-b border-slate-800 mb-8">
            <div>
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Chronological Service &amp; Health History
                </h2>
                <p class="text-xs text-slate-400 mt-1">Complete authenticated timeline of ramp checks, treatments and invoices.</p>
            </div>
            <span class="text-xs font-mono text-slate-500">{{ count($timeline) }} Records Logged</span>
        </div>

        @if(empty($timeline))
            <div class="text-center py-12 text-slate-500 text-sm">
                No service history or diagnostic records logged for this vehicle yet.
            </div>
        @else
            <div class="relative border-l-2 border-slate-800 ml-4 sm:ml-6 space-y-8">
                @foreach($timeline as $entry)
                    <div class="relative pl-6 sm:pl-8 group">
                        
                        <!-- Timeline Node Dot -->
                        @if($entry['type'] === 'health_check')
                            <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-amber-500 border-4 border-clinical-900 shadow-md"></div>
                        @elseif($entry['type'] === 'invoice')
                            <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-emerald-500 border-4 border-clinical-900 shadow-md"></div>
                        @else
                            <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-cyan-500 border-4 border-clinical-900 shadow-md"></div>
                        @endif

                        <div class="bg-clinical-950/80 border border-slate-800/90 rounded-xl p-5 hover:border-slate-700 transition">
                            
                            <!-- Entry Header -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 pb-3 border-b border-slate-800/60">
                                <div class="flex items-center gap-2">
                                    @if($entry['type'] === 'health_check')
                                        <span class="px-2 py-0.5 text-[10px] font-bold font-mono uppercase rounded bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                            Diagnostic Health Check
                                        </span>
                                    @elseif($entry['type'] === 'invoice')
                                        <span class="px-2 py-0.5 text-[10px] font-bold font-mono uppercase rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                            Tax Invoice &bull; {{ $entry['invoice_number'] }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] font-bold font-mono uppercase rounded bg-cyan-500/10 text-cyan-400 border border-cyan-500/30">
                                            Wash Service Visit
                                        </span>
                                    @endif

                                    <h4 class="text-sm font-bold text-white">{{ $entry['title'] }}</h4>
                                </div>

                                <div class="text-xs font-mono text-slate-400">
                                    {{ \Carbon\Carbon::parse($entry['date'])->format('d M Y, h:i A') }}
                                </div>
                            </div>

                            <!-- Entry Body -->
                            <div class="mt-4">
                                @if($entry['type'] === 'health_check')
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <span class="text-2xl font-extrabold font-mono text-amber-400">{{ $entry['overall_score'] }}/100</span>
                                            <div class="text-xs text-slate-400">
                                                <div>Protection: <strong class="text-slate-200 capitalize">{{ $entry['protection_type'] ?? 'Standard' }}</strong></div>
                                                @if(!empty($entry['technician_notes']))
                                                    <div class="italic text-slate-400">"{{ \Illuminate\Support\Str::limit($entry['technician_notes'], 60) }}"</div>
                                                @endif
                                            </div>
                                        </div>

                                        @if(!empty($entry['share_url']))
                                            <a href="{{ $entry['share_url'] }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 rounded-lg border border-slate-700 transition">
                                                View Health Report →
                                            </a>
                                        @endif
                                    </div>

                                    @if(!empty($entry['photos']))
                                        <div class="flex gap-2 mt-3 overflow-x-auto pb-1">
                                            @foreach(array_slice($entry['photos'], 0, 4) as $photo)
                                                <img src="{{ $photo }}" alt="Inspection Photo" class="w-14 h-14 object-cover rounded-lg border border-slate-700">
                                            @endforeach
                                        </div>
                                    @endif

                                @elseif($entry['type'] === 'invoice')
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                        <div>
                                            <div class="text-xs text-slate-300 font-medium">
                                                @foreach($entry['items'] as $item)
                                                    <span class="inline-block mr-2">&bull; {{ $item->item_name }} (₹{{ number_format($item->total_price, 0) }})</span>
                                                @endforeach
                                            </div>
                                            <div class="text-[11px] text-slate-400 font-mono mt-1">
                                                Payment: <strong class="text-slate-300 uppercase">{{ $entry['payment_method'] }}</strong>
                                                &bull; Attended by: {{ !empty($entry['staff_names']) ? implode(', ', $entry['staff_names']) : 'Studio Team' }}
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <div class="text-right">
                                                <div class="text-xs text-slate-400 font-mono">Total Paid</div>
                                                <div class="text-base font-bold font-mono text-emerald-400">₹{{ number_format($entry['total_amount'], 2) }}</div>
                                            </div>

                                            @if(!empty($entry['share_url']))
                                                <a href="{{ $entry['share_url'] }}" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 rounded-lg border border-slate-700 transition">
                                                    View Invoice →
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-xs text-slate-300">
                                        Status: <strong class="capitalize">{{ $entry['status'] }}</strong>
                                        @if(!empty($entry['bay']))
                                            &bull; Bay #{{ $entry['bay'] }}
                                        @endif
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Mandatory Score Disclaimer -->
    <div class="p-4 bg-slate-900/60 border border-slate-800 rounded-xl text-[11px] text-slate-500 leading-relaxed text-center">
        <strong>Official Cosmetic Disclaimer:</strong> {{ \App\Models\HealthCheck::getMandatoryDisclaimer() }}
    </div>
</div>
