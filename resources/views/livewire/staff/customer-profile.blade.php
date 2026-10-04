<div class="space-y-6">
    {{-- Header Profile Card --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-line/80 pb-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="font-display font-extrabold text-2xl text-ink">{{ $customer->name }}</h2>
                @if($customer->is_member)
                    <x-member-badge :membership="$customer->activeMembership" size="md" />
                @endif
                @if(!empty($customer->tags))
                    @foreach($customer->tags as $tag)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-mint text-teal">
                            {{ $tag }}
                        </span>
                    @endforeach
                @endif
            </div>
            <div class="text-xs text-muted mt-1 space-x-3">
                <span>📱 <strong class="text-ink font-mono">{{ $customer->mobile }}</strong></span>
                @if($customer->email)
                    <span>✉️ {{ $customer->email }}</span>
                @endif
                @if($customer->area)
                    <span>📍 {{ $customer->area }}</span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button 
                type="button"
                @click="$dispatch('openAssignMembership', { customerId: {{ $customer->id }} })"
                class="px-3.5 py-2 rounded-xl bg-amber text-ink font-bold text-xs flex items-center gap-1.5 shadow-xs hover:bg-amber/90 cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                </svg>
                <span>{{ $customer->is_member ? 'Add/Change Membership' : 'Enroll in Drive Club' }}</span>
            </button>
            <a 
                href="https://wa.me/91{{ $customer->mobile }}" 
                target="_blank" 
                rel="noopener noreferrer"
                class="px-3.5 py-2 rounded-xl bg-[#25D366] text-white font-bold text-xs flex items-center gap-1.5 shadow-xs hover:brightness-105"
            >
                <span>WhatsApp</span>
            </a>
            <a 
                href="tel:{{ $customer->mobile }}" 
                class="px-3.5 py-2 rounded-xl bg-teal text-white font-bold text-xs flex items-center gap-1.5 shadow-xs hover:bg-teal-deep"
            >
                <span>Call</span>
            </a>
        </div>
    </div>

    @if($actionSuccessMessage)
        <div class="p-3 bg-teal/10 border border-teal/30 text-teal-deep text-xs font-bold rounded-xl flex items-center justify-between">
            <span>{{ $actionSuccessMessage }}</span>
            <button wire:click="$set('actionSuccessMessage', null)" class="text-teal text-xs hover:underline cursor-pointer">Dismiss</button>
        </div>
    @endif

    @if($actionErrorMessage)
        <div class="p-3 bg-brick/10 border border-brick/30 text-brick text-xs font-bold rounded-xl flex items-center justify-between">
            <span>{{ $actionErrorMessage }}</span>
            <button wire:click="$set('actionErrorMessage', null)" class="text-brick text-xs hover:underline cursor-pointer">Dismiss</button>
        </div>
    @endif

    {{-- Lifetime Metrics Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="p-3.5 rounded-2xl bg-paper border border-line/70">
            <span class="text-[11px] text-muted font-bold uppercase block">Completed Visits</span>
            <span class="font-display font-extrabold text-xl text-ink mt-0.5 block">{{ $customer->total_visits }}</span>
        </div>
        <div class="p-3.5 rounded-2xl bg-paper border border-line/70">
            <span class="text-[11px] text-muted font-bold uppercase block">Lifetime Spend</span>
            <span class="font-display font-extrabold text-xl text-teal-deep mt-0.5 block">₹{{ number_format($customer->total_spend) }}</span>
        </div>
        <div class="p-3.5 rounded-2xl bg-paper border border-line/70">
            <span class="text-[11px] text-muted font-bold uppercase block">Average Bill</span>
            <span class="font-display font-extrabold text-xl text-ink mt-0.5 block">₹{{ number_format($customer->average_bill) }}</span>
        </div>
        <div class="p-3.5 rounded-2xl bg-paper border border-line/70">
            <span class="text-[11px] text-muted font-bold uppercase block">First Joined</span>
            <span class="font-bold text-xs text-ink mt-1.5 block">{{ $customer->first_visit ?? ($customer->created_at ? $customer->created_at->format('d M Y') : 'Recent') }}</span>
        </div>
    </div>

    {{-- Drive Club Membership Status Card --}}
    @if($customer->memberships->isNotEmpty())
        <div class="p-5 rounded-2xl bg-gradient-to-br from-teal-deep to-ink text-white border border-teal/40 space-y-4 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-white/15 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber text-ink flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-display font-black text-lg text-white">Drive Club: {{ $customer->memberships->first()->plan_name }}</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $customer->memberships->first()->is_active ? 'bg-amber text-ink' : 'bg-brick text-white' }}">
                                {{ $customer->memberships->first()->status }}
                            </span>
                        </div>
                        <p class="text-xs text-mint/80 mt-0.5">
                            Valid until: <strong class="text-white">{{ $customer->memberships->first()->expires_at->format('d M Y') }}</strong>
                            ({{ max(0, now()->diffInDays($customer->memberships->first()->expires_at, false)) }} days remaining)
                        </p>
                    </div>
                </div>

                <div>
                    <button 
                        wire:click="renewCustomerMembership({{ $customer->memberships->first()->id }})"
                        type="button"
                        class="px-3.5 py-1.5 rounded-lg bg-amber hover:bg-amber/90 text-ink font-bold text-xs transition cursor-pointer shadow-xs"
                    >
                        Renew Plan
                    </button>
                </div>
            </div>

            <!-- Remaining Included Treatments -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-amber mb-2">Remaining Free Wash / Treatment Quota:</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                    @foreach($customer->memberships->first()->remaining_services ?? [] as $svc)
                        <div class="bg-white/10 rounded-xl p-3 border border-white/10 flex items-center justify-between">
                            <span class="text-xs font-medium text-white">{{ $svc['service_name'] ?? 'Service' }}</span>
                            <span class="font-display font-black text-sm px-2 py-0.5 rounded {{ ($svc['remaining_count'] ?? 0) > 0 ? 'bg-amber text-ink' : 'bg-white/20 text-white/60' }}">
                                {{ $svc['remaining_count'] ?? 0 }} / {{ $svc['total_count'] ?? 0 }} left
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Redemptions Log -->
            @if($customer->redemptions->isNotEmpty())
                <div class="border-t border-white/15 pt-3">
                    <h4 class="text-[11px] font-bold uppercase tracking-wider text-mint mb-2">Recent Plan Redemptions:</h4>
                    <div class="space-y-1.5 max-h-28 overflow-y-auto">
                        @foreach($customer->redemptions->take(5) as $rdm)
                            <div class="text-xs flex items-center justify-between bg-white/5 px-3 py-1 rounded-lg">
                                <span class="text-white font-medium">{{ $rdm->service_name }} ({{ $rdm->vehicle?->formatted_plate }})</span>
                                <span class="text-[11px] text-mint/70">{{ $rdm->redeemed_at->format('d M Y, h:i A') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Customer Vehicles Section --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-display font-bold text-base text-ink">Linked Vehicles ({{ $customer->vehicles->count() }})</h3>
            @if($selectedVehicleId)
                <button type="button" wire:click="filterByVehicle(null)" class="text-xs text-teal font-bold hover:underline cursor-pointer">
                    Show All Vehicles
                </button>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @forelse($customer->vehicles as $veh)
                <div 
                    wire:click="filterByVehicle({{ $veh->id }})"
                    class="p-4 rounded-2xl border-2 transition-all cursor-pointer flex items-center justify-between {{ $selectedVehicleId === $veh->id ? 'border-teal bg-mist/50 ring-1 ring-teal' : 'border-line hover:border-teal/40 bg-white' }}"
                >
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-display font-extrabold text-sm tracking-wide bg-paper px-2.5 py-0.5 rounded border border-line text-ink block w-fit">
                                {{ $veh->formatted_plate }}
                            </span>
                            @if($veh->is_member)
                                <x-member-badge :membership="$veh->activeMembership" size="sm" />
                            @endif
                        </div>
                        <span class="text-xs text-muted block mt-1">
                            {{ $veh->make }} {{ $veh->model }} ({{ ucfirst($veh->vehicle_type) }})
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold {{ $selectedVehicleId === $veh->id ? 'text-teal' : 'text-muted' }}">
                            {{ $veh->bookings->count() }} visits
                        </span>
                        <a href="{{ route('passport.show', $veh->registration_number) }}" 
                           target="_blank"
                           @click.stop
                           class="px-2 py-1 rounded bg-ink text-amber font-mono text-[10px] font-bold border border-teal hover:bg-teal-deep">
                            Passport ↗
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-4 rounded-2xl bg-mist text-xs text-muted text-center col-span-2">
                    No vehicles registered for this customer yet.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Service History Timeline --}}
    <div>
        <h3 class="font-display font-bold text-base text-ink mb-3">
            Service & Booking History {{ $selectedVehicleId ? '(Filtered)' : '' }}
        </h3>

        <div class="space-y-2.5 max-h-56 overflow-y-auto">
            @forelse($bookings as $b)
                <div class="p-3.5 rounded-xl border border-line bg-paper flex items-center justify-between text-xs">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-ink">{{ $b->service_name }}</span>
                            <span class="font-mono bg-white px-1.5 py-0.2 rounded border border-line text-[10px]">{{ $b->formatted_plate }}</span>
                        </div>
                        <span class="text-muted text-[11px] block mt-0.5">
                            {{ $b->booking_date->format('d M Y') }} • {{ \Carbon\Carbon::parse($b->booking_time)->format('g:i A') }}
                        </span>
                    </div>

                    <div class="text-right">
                        <span class="font-bold text-ink block">₹{{ number_format($b->price) }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider {{ $b->status === 'completed' ? 'text-teal font-black' : 'text-muted' }}">
                            {{ $b->status }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-4 rounded-xl bg-mist text-center text-xs text-muted">
                    No booking records found.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Communication Log Section --}}
    <div class="border-t border-line pt-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-display font-bold text-base text-ink">Communication & Notes Log</h3>
            <button 
                type="button" 
                wire:click="$toggle('showAddLogForm')"
                class="px-3 py-1.5 rounded-lg bg-teal text-white font-bold text-xs hover:bg-teal-deep cursor-pointer"
            >
                {{ $showAddLogForm ? 'Close Form' : '+ Add Log Entry' }}
            </button>
        </div>

        {{-- Add Log Entry Form --}}
        @if($showAddLogForm)
            <form wire:submit.prevent="addCommunicationLog" class="p-4 rounded-2xl bg-mist border border-line space-y-3 mb-4">
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['note' => '📝 Note', 'call' => '📞 Phone Call', 'whatsapp' => '💬 WhatsApp'] as $k => $lbl)
                        <button 
                            type="button" 
                            wire:click="$set('logType', '{{ $k }}')"
                            class="py-1.5 px-2 rounded-lg text-xs font-bold border transition-all text-center {{ $logType === $k ? 'border-teal bg-white text-teal' : 'border-line text-ink' }}"
                        >
                            {{ $lbl }}
                        </button>
                    @endforeach
                </div>

                <input 
                    type="text" 
                    wire:model="logSubject" 
                    placeholder="Subject (e.g. Followed up regarding Ceramic package)"
                    class="w-full px-3 py-2 rounded-xl border border-line text-xs font-medium bg-white"
                />

                <textarea 
                    wire:model="logContent" 
                    rows="2" 
                    placeholder="Log details / notes..."
                    class="w-full px-3 py-2 rounded-xl border border-line text-xs font-medium bg-white"
                    required
                ></textarea>
                @error('logContent') <p class="text-[11px] text-brick font-semibold">{{ $message }}</p> @enderror

                <div class="flex justify-end gap-2">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-amber text-ink font-bold text-xs hover:bg-amber/90 cursor-pointer">
                        Save Communication Log
                    </button>
                </div>
            </form>
        @endif

        {{-- Log Entries List --}}
        <div class="space-y-2 max-h-48 overflow-y-auto">
            @forelse($customer->communicationLogs as $log)
                <div class="p-3 rounded-xl border border-line/70 bg-white text-xs space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-ink flex items-center gap-1.5">
                            @if($log->type === 'call') 📞
                            @elseif($log->type === 'whatsapp') 💬
                            @else 📝
                            @endif
                            {{ $log->subject }}
                        </span>
                        <span class="text-[10px] text-muted">{{ $log->logged_at->format('d M, h:i A') }}</span>
                    </div>
                    <p class="text-muted text-[11px]">{{ $log->content }}</p>
                </div>
            @empty
                <div class="p-3 rounded-xl bg-mist text-center text-xs text-muted">
                    No communication history logged yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
