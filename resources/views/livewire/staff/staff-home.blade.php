<div class="space-y-6">
    {{-- Search-First Header Band --}}
    <div class="bg-white rounded-3xl p-5 md:p-7 border border-brand-line shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-teal">The Drive Clinic • Studio Floor</span>
                <h1 class="font-display font-extrabold text-2xl md:text-3xl text-brand-ink">Staff Operations & Job Board</h1>
            </div>

            {{-- Quick Walk-In Button --}}
            <button 
                type="button" 
                wire:click="openWalkInModal"
                class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-brand-amber text-brand-ink font-display font-extrabold text-sm shadow-md hover:brightness-105 transition-all w-full sm:w-auto"
            >
                <span class="text-lg">+</span>
                <span>New Walk-In Arrival</span>
            </button>
        </div>

        {{-- Big Omnisearch Box --}}
        <div class="relative">
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-muted text-lg">🔍</span>
                <input 
                    type="search" 
                    wire:model.live.debounce.250ms="searchQuery"
                    placeholder="Search by Plate (JK02...), Mobile Number, Customer Name, or Car Model..."
                    class="w-full pl-12 pr-10 py-4 rounded-2xl border-2 border-brand-line focus:border-brand-teal focus:ring-2 focus:ring-brand-mint text-brand-ink font-medium text-base shadow-inner bg-brand-paper"
                    autofocus
                />
                @if($searchQuery)
                    <button 
                        type="button" 
                        wire:click="clearSearch" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 font-bold text-sm bg-gray-200 hover:bg-gray-300 w-6 h-6 rounded-full flex items-center justify-center"
                    >
                        ✕
                    </button>
                @endif
            </div>

            {{-- Search Results Dropdown Overlay --}}
            @if($isSearching && (!empty($searchResults['vehicles']) || !empty($searchResults['customers'])))
                <div class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl border-2 border-brand-teal shadow-2xl z-50 max-h-[480px] overflow-y-auto p-4 space-y-4">
                    <div class="flex items-center justify-between border-b border-brand-line pb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-teal">Search Results</span>
                        <button type="button" wire:click="clearSearch" class="text-xs text-brand-muted hover:text-brand-ink font-bold">Close Results ✕</button>
                    </div>

                    {{-- Matched Vehicles --}}
                    @if(!empty($searchResults['vehicles']))
                        <div>
                            <span class="text-xs font-bold text-brand-muted uppercase block mb-2">Vehicles Found</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                                @foreach($searchResults['vehicles'] as $v)
                                    <div class="p-3 rounded-xl border border-brand-line bg-brand-mist/50 hover:bg-brand-mint/40 transition-all flex items-center justify-between">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-display font-bold text-sm tracking-wide bg-white px-2 py-0.5 rounded border border-brand-line text-brand-ink block w-fit">
                                                    {{ \App\Domain\Normalizers\RegistrationNormalizer::format($v['registration_number']) }}
                                                </span>
                                                @if(!empty($v['active_membership']))
                                                    <x-member-badge :planName="$v['active_membership']['plan_name']" size="sm" :showCount="false" />
                                                @endif
                                            </div>
                                            <span class="text-xs text-brand-muted block mt-1">
                                                {{ $v['make'] ?? '' }} {{ $v['model'] ?? '' }} • {{ $v['customer']['name'] ?? 'Owner' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <button 
                                                type="button" 
                                                wire:click="openWalkInModal(null, '{{ $v['registration_number'] }}')"
                                                class="px-3 py-1.5 rounded-lg bg-brand-amber text-brand-ink font-bold text-xs hover:brightness-105"
                                            >
                                                Start Job
                                            </button>
                                            @if(!empty($v['customer_id']))
                                                <button 
                                                    type="button" 
                                                    wire:click="viewCustomer({{ $v['customer_id'] }})"
                                                    class="px-3 py-1.5 rounded-lg bg-brand-teal text-white font-bold text-xs hover:bg-brand-teal-deep"
                                                >
                                                    Profile
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Matched Customers --}}
                    @if(!empty($searchResults['customers']))
                        <div>
                            <span class="text-xs font-bold text-brand-muted uppercase block mb-2">Customers Found</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                                @foreach($searchResults['customers'] as $c)
                                    <div class="p-3 rounded-xl border border-brand-line bg-brand-mist/50 hover:bg-brand-mint/40 transition-all flex items-center justify-between">
                                        <div>
                                            <span class="font-bold text-sm text-brand-ink block">{{ $c['name'] }}</span>
                                            <span class="text-xs text-brand-muted font-mono block">{{ $c['mobile'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <button 
                                                type="button" 
                                                wire:click="openWalkInModal('{{ $c['mobile'] }}')"
                                                class="px-3 py-1.5 rounded-lg bg-brand-amber text-brand-ink font-bold text-xs hover:brightness-105"
                                            >
                                                Walk-In
                                            </button>
                                            <button 
                                                type="button" 
                                                wire:click="viewCustomer({{ $c['id'] }})"
                                                class="px-3 py-1.5 rounded-lg bg-brand-teal text-white font-bold text-xs hover:bg-brand-teal-deep"
                                            >
                                                View CRM
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @elseif($isSearching && empty($searchResults['vehicles']) && empty($searchResults['customers']))
                <div class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl border border-brand-line shadow-lg z-50 p-6 text-center space-y-3">
                    <p class="text-sm text-brand-muted">No existing records matching "<strong>{{ $searchQuery }}</strong>".</p>
                    <button 
                        type="button" 
                        wire:click="openWalkInModal(null, '{{ $searchQuery }}')"
                        class="px-5 py-2.5 rounded-xl bg-brand-amber text-brand-ink font-bold text-xs hover:brightness-105"
                    >
                        Register New Customer / Vehicle Walk-In →
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Today's Status Filter Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2">
        <button 
            type="button" 
            wire:click="$set('activeStatusTab', 'active')"
            class="px-4 py-2.5 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2 {{ $activeStatusTab === 'active' ? 'bg-brand-teal text-white shadow-md' : 'bg-white text-brand-ink border border-brand-line hover:bg-brand-mist' }}"
        >
            <span>Active Queue</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $activeStatusTab === 'active' ? 'bg-white text-brand-teal' : 'bg-brand-mist text-brand-ink' }}">
                {{ $counts['active'] }}
            </span>
        </button>

        <button 
            type="button" 
            wire:click="$set('activeStatusTab', 'arrived')"
            class="px-4 py-2.5 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2 {{ $activeStatusTab === 'arrived' ? 'bg-brand-teal text-white shadow-md' : 'bg-white text-brand-ink border border-brand-line hover:bg-brand-mist' }}"
        >
            <span>Arrived</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $activeStatusTab === 'arrived' ? 'bg-white text-brand-teal' : 'bg-brand-mist text-brand-ink' }}">
                {{ $counts['arrived'] }}
            </span>
        </button>

        <button 
            type="button" 
            wire:click="$set('activeStatusTab', 'in_service')"
            class="px-4 py-2.5 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2 {{ $activeStatusTab === 'in_service' ? 'bg-brand-teal text-white shadow-md' : 'bg-white text-brand-ink border border-brand-line hover:bg-brand-mist' }}"
        >
            <span>In Service (Bays)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $activeStatusTab === 'in_service' ? 'bg-white text-brand-teal' : 'bg-brand-mist text-brand-ink' }}">
                {{ $counts['in_service'] }}
            </span>
        </button>

        <button 
            type="button" 
            wire:click="$set('activeStatusTab', 'completed')"
            class="px-4 py-2.5 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2 {{ $activeStatusTab === 'completed' ? 'bg-brand-teal text-white shadow-md' : 'bg-white text-brand-ink border border-brand-line hover:bg-brand-mist' }}"
        >
            <span>Completed</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $activeStatusTab === 'completed' ? 'bg-white text-brand-teal' : 'bg-brand-mist text-brand-ink' }}">
                {{ $counts['completed'] }}
            </span>
        </button>

        <button 
            type="button" 
            wire:click="$set('activeStatusTab', 'today')"
            class="px-4 py-2.5 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2 {{ $activeStatusTab === 'today' ? 'bg-brand-teal text-white shadow-md' : 'bg-white text-brand-ink border border-brand-line hover:bg-brand-mist' }}"
        >
            <span>All Today</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $activeStatusTab === 'today' ? 'bg-white text-brand-teal' : 'bg-brand-mist text-brand-ink' }}">
                {{ $counts['all_today'] }}
            </span>
        </button>
    </div>

    {{-- Job Board Grid --}}
    @if($bookings->isEmpty())
        <div class="bg-white rounded-3xl p-12 border border-brand-line text-center space-y-3">
            <div class="text-4xl">🚗</div>
            <h3 class="font-display font-bold text-lg text-brand-ink">No Bookings in this Category Today</h3>
            <p class="text-sm text-brand-muted max-w-sm mx-auto">Use the search box above or tap "+ New Walk-In Arrival" to log a customer drive-in.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($bookings as $job)
                <div class="bg-white rounded-3xl p-5 border-2 border-brand-line/80 shadow-sm flex flex-col justify-between space-y-4 hover:border-brand-teal/40 transition-all">
                    {{-- Header Row: Plate & Status Badge --}}
                    <div>
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="font-display font-extrabold text-base tracking-wide bg-brand-paper px-3 py-1 rounded-lg border border-brand-line text-brand-ink">
                                    {{ $job->formatted_plate }}
                                </span>
                                @if($job->vehicle?->is_member || $job->customer?->is_member)
                                    <x-member-badge :membership="$job->vehicle?->activeMembership ?? $job->customer?->activeMembership" size="sm" :showCount="false" />
                                @endif
                            </div>

                            {{-- Status Chip --}}
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider
                                @if($job->status === 'new') bg-blue-100 text-blue-800
                                @elseif($job->status === 'confirmed') bg-purple-100 text-purple-800
                                @elseif($job->status === 'arrived') bg-amber-100 text-amber-900
                                @elseif($job->status === 'inspection') bg-orange-100 text-orange-900
                                @elseif($job->status === 'in_service') bg-emerald-100 text-emerald-900
                                @elseif($job->status === 'completed') bg-brand-mint text-brand-teal
                                @else bg-gray-100 text-gray-700
                                @endif
                            ">
                                {{ str_replace('_', ' ', $job->status) }}
                            </span>
                        </div>

                        {{-- Service & Vehicle Info --}}
                        <h3 class="font-display font-bold text-lg text-brand-ink">{{ $job->service_name }}</h3>
                        <div class="text-xs text-brand-muted space-y-0.5 mt-1">
                            <div>Customer: <strong class="text-brand-ink">{{ $job->name }}</strong> ({{ $job->mobile }})</div>
                            @if($job->make_model)
                                <div>Car: {{ $job->make_model }} ({{ ucfirst($job->vehicle_type) }})</div>
                            @endif
                            <div>Time: ⏱ {{ \Carbon\Carbon::parse($job->booking_time)->format('g:i A') }} • Duration: ~{{ $job->duration_minutes }}m</div>
                        </div>

                        @if($job->notes)
                            <div class="mt-2 p-2 rounded-lg bg-brand-mist/60 text-[11px] text-brand-muted italic">
                                "{{ $job->notes }}"
                            </div>
                        @endif
                    </div>

                    {{-- One-Tap Status Action Buttons --}}
                    <div class="pt-3 border-t border-brand-line/60 space-y-2">
                        <div class="flex items-center justify-between text-xs text-brand-muted mb-1">
                            <span>One-Tap Status Advance:</span>
                            <span class="font-bold text-brand-ink">₹{{ number_format($job->price) }}</span>
                        </div>

                        <div class="grid grid-cols-4 gap-1.5">
                            {{-- Step 1: Arrived --}}
                            <button 
                                type="button" 
                                wire:click="updateBookingStatus({{ $job->id }}, 'arrived')"
                                class="py-2 px-1 rounded-xl text-center text-xs font-bold transition-all {{ $job->status === 'arrived' ? 'bg-brand-teal text-white shadow-sm' : 'bg-brand-mist text-brand-ink hover:bg-brand-mint' }}"
                            >
                                Arrived
                            </button>

                            {{-- Step 2: In Service --}}
                            <button 
                                type="button" 
                                wire:click="updateBookingStatus({{ $job->id }}, 'in_service')"
                                class="py-2 px-1 rounded-xl text-center text-xs font-bold transition-all {{ $job->status === 'in_service' ? 'bg-brand-teal text-white shadow-sm' : 'bg-brand-mist text-brand-ink hover:bg-brand-mint' }}"
                            >
                                In Bay
                            </button>

                            {{-- Step 3: Fast Complete --}}
                            <button 
                                type="button" 
                                wire:click="updateBookingStatus({{ $job->id }}, 'completed')"
                                class="py-2 px-1 rounded-xl text-center text-xs font-bold transition-all {{ $job->status === 'completed' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-brand-mist text-brand-ink hover:bg-brand-mint' }}"
                            >
                                Done ✓
                            </button>

                            {{-- Step 4: Bill & Complete Modal --}}
                            <button 
                                type="button" 
                                @click="$dispatch('openInvoiceCreator', { bookingId: {{ $job->id }} })"
                                class="py-2 px-1 rounded-xl text-center text-xs font-bold bg-brand-amber text-brand-ink hover:brightness-105 shadow-sm transition-all flex items-center justify-center gap-1"
                            >
                                <span>₹ Bill</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Walk-In Modal Dialog --}}
    <livewire:staff.walk-in-modal />

    {{-- Invoice Creator Modal Dialog --}}
    <livewire:staff.invoice-creator />

    {{-- Assign Membership Modal Dialog --}}
    <livewire:staff.assign-membership-modal />

    {{-- Customer Profile Modal Overlay --}}
    @if($viewingCustomerId)
        <div class="fixed inset-0 bg-brand-ink/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 md:p-8 shadow-2xl relative">
                <button 
                    type="button" 
                    wire:click="closeCustomerModal"
                    class="absolute right-5 top-5 text-gray-400 hover:text-gray-700 font-bold text-lg bg-gray-100 hover:bg-gray-200 w-8 h-8 rounded-full flex items-center justify-center"
                >
                    ✕
                </button>
                <livewire:staff.customer-profile :customerId="$viewingCustomerId" />
            </div>
        </div>
    @endif
</div>
