<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header Sticky Card with Real-time Score Gauge --}}
    <div class="bg-white rounded-3xl p-5 md:p-6 border-2 border-brand-line/80 shadow-md sticky top-4 z-40">
        <div class="flex items-center justify-between gap-4">
            <div>
                @if($vehicle)
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-display font-extrabold text-base tracking-wide bg-brand-paper px-2.5 py-0.5 rounded border border-brand-line text-brand-ink">
                            {{ $vehicle->formatted_plate }}
                        </span>
                        <span class="text-xs text-brand-muted">{{ $vehicle->make }} {{ $vehicle->model }}</span>
                    </div>
                    <span class="text-xs text-brand-muted block">Owner: <strong class="text-brand-ink">{{ $vehicle->customer->name ?? 'Customer' }}</strong></span>
                @else
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-teal">Diagnostic Mode</span>
                    <h2 class="font-display font-extrabold text-xl text-brand-ink">Ramp Inspection</h2>
                @endif
            </div>

            {{-- Live Drive Health Score Badge --}}
            <div class="text-right flex items-center gap-3">
                <div>
                    <span class="text-[10px] uppercase font-bold text-brand-muted block">Drive Health Score</span>
                    <div class="flex items-baseline justify-end gap-1">
                        <span class="font-display font-extrabold text-3xl md:text-4xl 
                            @if($liveScore >= 80) text-brand-teal
                            @elseif($liveScore >= 50) text-brand-amber
                            @else text-[#A63A2B]
                            @endif
                        ">
                            {{ $liveScore }}
                        </span>
                        <span class="text-xs text-brand-muted font-bold">/ 100</span>
                    </div>
                </div>

                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-display font-bold text-lg shadow-inner
                    @if($liveScore >= 80) bg-brand-mint text-brand-teal
                    @elseif($liveScore >= 50) bg-amber-100 text-amber-900
                    @else bg-red-100 text-[#A63A2B]
                    @endif
                ">
                    @if($liveScore >= 80) 🛡️
                    @elseif($liveScore >= 50) ⚠️
                    @else 🔧
                    @endif
                </div>
            </div>
        </div>

        {{-- Mandatory Disclaimer Note --}}
        <div class="mt-3 pt-3 border-t border-brand-line/60 text-[11px] text-brand-muted italic flex items-center gap-1.5">
            <span>ℹ️</span>
            <span>{{ $disclaimer }}</span>
        </div>
    </div>

    {{-- STEP 1: RAMP INSPECTION CHECKLIST --}}
    @if($step === 1)
        <div class="space-y-6">
            {{-- Category 1: Exterior Paint & Body --}}
            <div class="bg-white rounded-3xl p-5 md:p-7 border border-brand-line shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-brand-line/60 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🚗</span>
                        <h3 class="font-display font-bold text-lg text-brand-ink">Exterior Paint & Body (Weight: 30%)</h3>
                    </div>
                    <span class="text-xs font-bold text-brand-teal">{{ $liveCategoryScores['exterior'] ?? 30 }} / 30 pts</span>
                </div>

                <div class="space-y-4 divide-y divide-brand-line/40">
                    @foreach($checklistSchema['exterior']['items'] as $itemKey => $itemLabel)
                        <div class="pt-3.5 first:pt-0 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm text-brand-ink">{{ $itemLabel }}</span>
                            </div>

                            {{-- 3-State Rating Selector --}}
                            <div class="grid grid-cols-3 gap-2">
                                <button 
                                    type="button" 
                                    wire:click="setItemRating('exterior', '{{ $itemKey }}', 'good')"
                                    class="py-2 px-1 rounded-xl text-xs font-bold border transition-all text-center {{ ($checklistData['exterior'][$itemKey]['rating'] ?? '') === 'good' ? 'bg-[#E1EEEA] text-[#1F5E57] border-[#1F5E57] ring-1 ring-[#1F5E57]' : 'bg-white text-brand-muted border-brand-line hover:bg-brand-paper' }}"
                                >
                                    ✓ Good (2 pts)
                                </button>
                                <button 
                                    type="button" 
                                    wire:click="setItemRating('exterior', '{{ $itemKey }}', 'fair')"
                                    class="py-2 px-1 rounded-xl text-xs font-bold border transition-all text-center {{ ($checklistData['exterior'][$itemKey]['rating'] ?? '') === 'fair' ? 'bg-[#FBEBCF] text-[#7A4E07] border-[#7A4E07] ring-1 ring-[#7A4E07]' : 'bg-white text-brand-muted border-brand-line hover:bg-brand-paper' }}"
                                >
                                    ⚡ Fair (1 pt)
                                </button>
                                <button 
                                    type="button" 
                                    wire:click="setItemRating('exterior', '{{ $itemKey }}', 'needs_attention')"
                                    class="py-2 px-1 rounded-xl text-xs font-bold border transition-all text-center {{ ($checklistData['exterior'][$itemKey]['rating'] ?? '') === 'needs_attention' ? 'bg-[#F6E0DB] text-[#A63A2B] border-[#A63A2B] ring-1 ring-[#A63A2B]' : 'bg-white text-brand-muted border-brand-line hover:bg-brand-paper' }}"
                                >
                                    ⚠️ Needs Attn (0)
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Category 2: Interior Cabin & Upholstery --}}
            <div class="bg-white rounded-3xl p-5 md:p-7 border border-brand-line shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-brand-line/60 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">💺</span>
                        <h3 class="font-display font-bold text-lg text-brand-ink">Interior Cabin & Sanitization (Weight: 30%)</h3>
                    </div>
                    <span class="text-xs font-bold text-brand-teal">{{ $liveCategoryScores['interior'] ?? 30 }} / 30 pts</span>
                </div>

                <div class="space-y-4 divide-y divide-brand-line/40">
                    @foreach($checklistSchema['interior']['items'] as $itemKey => $itemLabel)
                        <div class="pt-3.5 first:pt-0 space-y-2">
                            <span class="font-bold text-sm text-brand-ink">{{ $itemLabel }}</span>
                            <div class="grid grid-cols-3 gap-2">
                                <button 
                                    type="button" 
                                    wire:click="setItemRating('interior', '{{ $itemKey }}', 'good')"
                                    class="py-2 px-1 rounded-xl text-xs font-bold border transition-all text-center {{ ($checklistData['interior'][$itemKey]['rating'] ?? '') === 'good' ? 'bg-[#E1EEEA] text-[#1F5E57] border-[#1F5E57] ring-1 ring-[#1F5E57]' : 'bg-white text-brand-muted border-brand-line hover:bg-brand-paper' }}"
                                >
                                    ✓ Good
                                </button>
                                <button 
                                    type="button" 
                                    wire:click="setItemRating('interior', '{{ $itemKey }}', 'fair')"
                                    class="py-2 px-1 rounded-xl text-xs font-bold border transition-all text-center {{ ($checklistData['interior'][$itemKey]['rating'] ?? '') === 'fair' ? 'bg-[#FBEBCF] text-[#7A4E07] border-[#7A4E07] ring-1 ring-[#7A4E07]' : 'bg-white text-brand-muted border-brand-line hover:bg-brand-paper' }}"
                                >
                                    ⚡ Fair
                                </button>
                                <button 
                                    type="button" 
                                    wire:click="setItemRating('interior', '{{ $itemKey }}', 'needs_attention')"
                                    class="py-2 px-1 rounded-xl text-xs font-bold border transition-all text-center {{ ($checklistData['interior'][$itemKey]['rating'] ?? '') === 'needs_attention' ? 'bg-[#F6E0DB] text-[#A63A2B] border-[#A63A2B] ring-1 ring-[#A63A2B]' : 'bg-white text-brand-muted border-brand-line hover:bg-brand-paper' }}"
                                >
                                    ⚠️ Needs Attn
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Category 3: Wheels, Tyres & Arches --}}
            <div class="bg-white rounded-3xl p-5 md:p-7 border border-brand-line shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-brand-line/60 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🛞</span>
                        <h3 class="font-display font-bold text-lg text-brand-ink">Wheels & Tyres (Weight: 15%)</h3>
                    </div>
                    <span class="text-xs font-bold text-brand-teal">{{ $liveCategoryScores['wheels'] ?? 15 }} / 15 pts</span>
                </div>

                <div class="space-y-4 divide-y divide-brand-line/40">
                    @foreach($checklistSchema['wheels']['items'] as $itemKey => $itemLabel)
                        <div class="pt-3.5 first:pt-0 space-y-2">
                            <span class="font-bold text-sm text-brand-ink">{{ $itemLabel }}</span>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" wire:click="setItemRating('wheels', '{{ $itemKey }}', 'good')" class="py-2 px-1 rounded-xl text-xs font-bold border transition-all text-center {{ ($checklistData['wheels'][$itemKey]['rating'] ?? '') === 'good' ? 'bg-[#E1EEEA] text-[#1F5E57] border-[#1F5E57]' : 'bg-white text-brand-muted border-brand-line' }}">✓ Good</button>
                                <button type="button" wire:click="setItemRating('wheels', '{{ $itemKey }}', 'fair')" class="py-2 px-1 rounded-xl text-xs font-bold border transition-all text-center {{ ($checklistData['wheels'][$itemKey]['rating'] ?? '') === 'fair' ? 'bg-[#FBEBCF] text-[#7A4E07] border-[#7A4E07]' : 'bg-white text-brand-muted border-brand-line' }}">⚡ Fair</button>
                                <button type="button" wire:click="setItemRating('wheels', '{{ $itemKey }}', 'needs_attention')" class="py-2 px-1 rounded-xl text-xs font-bold border transition-all text-center {{ ($checklistData['wheels'][$itemKey]['rating'] ?? '') === 'needs_attention' ? 'bg-[#F6E0DB] text-[#A63A2B] border-[#A63A2B]' : 'bg-white text-brand-muted border-brand-line' }}">⚠️ Needs Attn</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Category 4: Glass & Visibility --}}
            <div class="bg-white rounded-3xl p-5 md:p-7 border border-brand-line shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-brand-line/60 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🪟</span>
                        <h3 class="font-display font-bold text-lg text-brand-ink">Glass & Visibility (Weight: 15%)</h3>
                    </div>
                    <span class="text-xs font-bold text-brand-teal">{{ $liveCategoryScores['glass'] ?? 15 }} / 15 pts</span>
                </div>

                <div class="space-y-4 divide-y divide-brand-line/40">
                    @foreach($checklistSchema['glass']['items'] as $itemKey => $itemLabel)
                        <div class="pt-3.5 first:pt-0 space-y-2">
                            <span class="font-bold text-sm text-brand-ink">{{ $itemLabel }}</span>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" wire:click="setItemRating('glass', '{{ $itemKey }}', 'good')" class="py-2 px-1 rounded-xl text-xs font-bold border transition-all text-center {{ ($checklistData['glass'][$itemKey]['rating'] ?? '') === 'good' ? 'bg-[#E1EEEA] text-[#1F5E57] border-[#1F5E57]' : 'bg-white text-brand-muted border-brand-line' }}">✓ Good</button>
                                <button type="button" wire:click="setItemRating('glass', '{{ $itemKey }}', 'fair')" class="py-2 px-1 rounded-xl text-xs font-bold border transition-all text-center {{ ($checklistData['glass'][$itemKey]['rating'] ?? '') === 'fair' ? 'bg-[#FBEBCF] text-[#7A4E07] border-[#7A4E07]' : 'bg-white text-brand-muted border-brand-line' }}">⚡ Fair</button>
                                <button type="button" wire:click="setItemRating('glass', '{{ $itemKey }}', 'needs_attention')" class="py-2 px-1 rounded-xl text-xs font-bold border transition-all text-center {{ ($checklistData['glass'][$itemKey]['rating'] ?? '') === 'needs_attention' ? 'bg-[#F6E0DB] text-[#A63A2B] border-[#A63A2B]' : 'bg-white text-brand-muted border-brand-line' }}">⚠️ Needs Attn</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Category 5: Protection Status --}}
            <div class="bg-white rounded-3xl p-5 md:p-7 border border-brand-line shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-brand-line/60 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🛡️</span>
                        <h3 class="font-display font-bold text-lg text-brand-ink">Protection Coating Status (Weight: 10%)</h3>
                    </div>
                    <span class="text-xs font-bold text-brand-teal">{{ $liveCategoryScores['protection'] ?? 0 }} / 10 pts</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    @foreach(['ceramic' => 'Ceramic Coating (Good)', 'wax' => 'Wax / Sealant (Fair)', 'none' => 'No Protection (Attn)', 'unknown' => 'Unknown / Degraded'] as $pKey => $pLabel)
                        <button 
                            type="button" 
                            wire:click="setProtectionType('{{ $pKey }}')"
                            class="py-3 px-2 rounded-xl text-xs font-bold border transition-all text-center {{ $protectionType === $pKey ? 'border-brand-teal bg-brand-teal text-white shadow-md' : 'border-brand-line bg-white text-brand-ink hover:bg-brand-paper' }}"
                        >
                            {{ $pLabel }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Technician Notes --}}
            <div class="bg-white rounded-3xl p-5 md:p-7 border border-brand-line shadow-sm space-y-3">
                <h4 class="font-display font-bold text-base text-brand-ink">Technician Inspection Notes</h4>
                <textarea 
                    wire:model="technicianNotes" 
                    rows="3" 
                    placeholder="e.g. Paint thickness measures 110 microns. Light holograms on hood from hand washing."
                    class="w-full px-4 py-3 rounded-xl border border-brand-line text-sm font-medium"
                ></textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 pt-4">
                <button 
                    type="button" 
                    wire:click="proceedToRecommendations"
                    class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-brand-amber text-brand-ink font-display font-extrabold text-base shadow-md hover:brightness-105 transition-all"
                >
                    Proceed to Recommendations Review →
                </button>
            </div>
        </div>
    @endif

    {{-- STEP 2: RECOMMENDATIONS REVIEW --}}
    @if($step === 2)
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-brand-line shadow-sm space-y-6">
            <div class="border-b border-brand-line pb-4 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-teal">Diagnostic Findings</span>
                    <h2 class="font-display font-extrabold text-2xl text-brand-ink">Actionable Recommendations</h2>
                </div>
                <button type="button" wire:click="$set('step', 1)" class="text-xs text-brand-teal font-bold hover:underline">
                    ← Edit Inspection Ratings
                </button>
            </div>

            {{-- Recommended Today Bucket --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-display font-bold text-base text-brand-ink flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
                        <span>Recommended Today (Urgent / High Priority)</span>
                    </h3>
                </div>

                <div class="space-y-2.5">
                    @forelse($recommendedToday as $idx => $rec)
                        <div class="p-4 rounded-2xl border-2 border-brand-line/80 bg-brand-paper flex items-center justify-between">
                            <div>
                                <h4 class="font-display font-bold text-base text-brand-ink">{{ $rec['name'] }}</h4>
                                <p class="text-xs text-brand-muted mt-0.5">{{ $rec['reason'] ?? '' }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-display font-extrabold text-lg text-brand-ink">₹{{ number_format($rec['price'] ?? 0) }}</span>
                                <button type="button" wire:click="removeRecommendation('today', {{ $idx }})" class="text-red-500 hover:text-red-700 font-bold text-xs p-1">
                                    ✕
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 rounded-xl bg-brand-mist text-xs text-brand-muted text-center">
                            No urgent services required today.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recommended Later Bucket --}}
            <div class="space-y-3 pt-4 border-t border-brand-line/60">
                <h3 class="font-display font-bold text-base text-brand-ink flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                    <span>Can Wait / Recommended Later (Long-term Care)</span>
                </h3>

                <div class="space-y-2.5">
                    @forelse($recommendedLater as $idx => $rec)
                        <div class="p-4 rounded-2xl border border-brand-line bg-white flex items-center justify-between">
                            <div>
                                <h4 class="font-display font-bold text-sm text-brand-ink">{{ $rec['name'] }}</h4>
                                <p class="text-xs text-brand-muted mt-0.5">{{ $rec['reason'] ?? '' }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-display font-bold text-base text-brand-muted">₹{{ number_format($rec['price'] ?? 0) }}</span>
                                <button type="button" wire:click="removeRecommendation('later', {{ $idx }})" class="text-red-500 hover:text-red-700 font-bold text-xs p-1">
                                    ✕
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 rounded-xl bg-brand-mist text-xs text-brand-muted text-center">
                            No deferred recommendations.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="flex justify-between items-center pt-6 border-t border-brand-line">
                <button type="button" wire:click="$set('step', 1)" class="px-5 py-3 rounded-xl border border-brand-line font-bold text-xs text-brand-ink hover:bg-brand-mist">
                    ← Back to Ratings
                </button>

                <button 
                    type="button" 
                    wire:click="saveHealthCheck" 
                    class="px-8 py-3.5 rounded-xl bg-brand-teal text-white font-display font-extrabold text-sm shadow-md hover:bg-brand-teal-deep transition-all"
                >
                    Finalize & Generate Diagnostic Report Card →
                </button>
            </div>
        </div>
    @endif

    {{-- STEP 3: COMPLETED HEALTH CHECK CARD --}}
    @if($step === 3 && $savedCheck)
        <div class="bg-white rounded-3xl p-8 md:p-10 border border-brand-line shadow-xl text-center space-y-6">
            <div class="w-16 h-16 bg-brand-mint text-brand-teal rounded-full flex items-center justify-center text-3xl mx-auto">
                ✓
            </div>

            <div>
                <span class="px-3 py-1 rounded-full bg-brand-mint/60 text-brand-teal font-bold text-xs uppercase tracking-wider">
                    Diagnostic Completed
                </span>
                <h2 class="font-display font-extrabold text-3xl text-brand-ink mt-2">
                    Drive Health Score: {{ $savedCheck->overall_score }}/100
                </h2>
                <p class="text-xs text-brand-muted mt-1">
                    Check Number: <strong class="font-mono">{{ $savedCheck->check_number }}</strong>
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 max-w-md mx-auto pt-4">
                <a 
                    href="{{ $savedCheck->getWhatsAppShareUrl() }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="w-full sm:w-auto flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-[#25D366] text-white font-bold text-sm shadow-md hover:brightness-105"
                >
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.392-12.416c-5.514 0-10 4.486-10 10 0 1.761.459 3.417 1.261 4.864l-1.34 4.896 5.021-1.317c1.401.763 3.003 1.197 4.708 1.197 5.514 0 10-4.486 10-10s-4.486-10-10-10z"/></svg>
                    Send to Customer via WhatsApp
                </a>

                <a 
                    href="{{ $savedCheck->getShareUrl() }}" 
                    target="_blank" 
                    class="w-full sm:w-auto px-6 py-3.5 rounded-xl border border-brand-line text-brand-ink font-bold text-sm hover:bg-brand-mist"
                >
                    View Report Card ↗
                </a>
            </div>

            <div class="pt-4">
                <a href="{{ route('staff.home') }}" class="text-xs text-brand-teal font-bold hover:underline">
                    ← Return to Staff Operations Floor
                </a>
            </div>
        </div>
    @endif
</div>
