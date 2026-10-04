<div class="max-w-7xl mx-auto">
    {{-- Stepper Progress Bar --}}
    @if($step < 5)
        <div class="mb-8 md:mb-12">
            <div class="bg-white rounded-2xl p-4 md:p-6 border border-brand-line shadow-sm">
                <div class="grid grid-cols-4 gap-2 md:gap-4 relative">
                    {{-- Step 1 --}}
                    <button type="button" wire:click="goToStep(1)" class="flex flex-col md:flex-row items-center gap-2 text-left group transition-all text-left">
                        <span class="w-8 h-8 md:w-9 md:h-9 rounded-full flex items-center justify-center font-bold text-sm transition-all {{ $step === 1 ? 'bg-brand-teal text-white shadow-md' : ($step > 1 ? 'bg-brand-mint text-brand-teal' : 'bg-gray-100 text-gray-400') }}">
                            @if($step > 1) ✓ @else 1 @endif
                        </span>
                        <div>
                            <span class="hidden md:block text-[11px] uppercase tracking-wider font-bold {{ $step >= 1 ? 'text-brand-teal' : 'text-gray-400' }}">Step 1</span>
                            <span class="text-xs md:text-sm font-bold block truncate {{ $step === 1 ? 'text-brand-ink' : 'text-brand-muted' }}">Service</span>
                        </div>
                    </button>

                    {{-- Step 2 --}}
                    <button type="button" wire:click="goToStep(2)" class="flex flex-col md:flex-row items-center gap-2 text-left group transition-all {{ $step < 2 ? 'pointer-events-none opacity-60' : '' }}">
                        <span class="w-8 h-8 md:w-9 md:h-9 rounded-full flex items-center justify-center font-bold text-sm transition-all {{ $step === 2 ? 'bg-brand-teal text-white shadow-md' : ($step > 2 ? 'bg-brand-mint text-brand-teal' : 'bg-gray-100 text-gray-400') }}">
                            @if($step > 2) ✓ @else 2 @endif
                        </span>
                        <div>
                            <span class="hidden md:block text-[11px] uppercase tracking-wider font-bold {{ $step >= 2 ? 'text-brand-teal' : 'text-gray-400' }}">Step 2</span>
                            <span class="text-xs md:text-sm font-bold block truncate {{ $step === 2 ? 'text-brand-ink' : 'text-brand-muted' }}">Vehicle Type</span>
                        </div>
                    </button>

                    {{-- Step 3 --}}
                    <button type="button" wire:click="goToStep(3)" class="flex flex-col md:flex-row items-center gap-2 text-left group transition-all {{ $step < 3 ? 'pointer-events-none opacity-60' : '' }}">
                        <span class="w-8 h-8 md:w-9 md:h-9 rounded-full flex items-center justify-center font-bold text-sm transition-all {{ $step === 3 ? 'bg-brand-teal text-white shadow-md' : ($step > 3 ? 'bg-brand-mint text-brand-teal' : 'bg-gray-100 text-gray-400') }}">
                            @if($step > 3) ✓ @else 3 @endif
                        </span>
                        <div>
                            <span class="hidden md:block text-[11px] uppercase tracking-wider font-bold {{ $step >= 3 ? 'text-brand-teal' : 'text-gray-400' }}">Step 3</span>
                            <span class="text-xs md:text-sm font-bold block truncate {{ $step === 3 ? 'text-brand-ink' : 'text-brand-muted' }}">Date & Time</span>
                        </div>
                    </button>

                    {{-- Step 4 --}}
                    <button type="button" wire:click="goToStep(4)" class="flex flex-col md:flex-row items-center gap-2 text-left group transition-all {{ $step < 4 ? 'pointer-events-none opacity-60' : '' }}">
                        <span class="w-8 h-8 md:w-9 md:h-9 rounded-full flex items-center justify-center font-bold text-sm transition-all {{ $step === 4 ? 'bg-brand-teal text-white shadow-md' : 'bg-gray-100 text-gray-400' }}">
                            4
                        </span>
                        <div>
                            <span class="hidden md:block text-[11px] uppercase tracking-wider font-bold {{ $step >= 4 ? 'text-brand-teal' : 'text-gray-400' }}">Step 4</span>
                            <span class="text-xs md:text-sm font-bold block truncate {{ $step === 4 ? 'text-brand-ink' : 'text-brand-muted' }}">Details</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Main Interaction Column --}}
        <div class="{{ $step === 5 ? 'lg:col-span-12 max-w-2xl mx-auto' : 'lg:col-span-8' }}">
            
            {{-- STEP 1: SERVICE SELECTION --}}
            @if($step === 1)
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-brand-line shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="font-display font-extrabold text-2xl text-brand-ink tracking-tight">Select Care Treatment</h2>
                            <p class="text-brand-muted text-sm mt-1">Choose a dedicated clinic package or maintenance wash</p>
                        </div>

                        {{-- Category Filter Chips --}}
                        <div class="flex items-center gap-2 overflow-x-auto pb-1">
                            <button type="button" wire:click="$set('selectedCategory', 'all')" class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all {{ $selectedCategory === 'all' ? 'bg-brand-teal text-white' : 'bg-brand-mist text-brand-ink hover:bg-brand-mint' }}">
                                All Services
                            </button>
                            <button type="button" wire:click="$set('selectedCategory', 'wash')" class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all {{ $selectedCategory === 'wash' ? 'bg-brand-teal text-white' : 'bg-brand-mist text-brand-ink hover:bg-brand-mint' }}">
                                Wash
                            </button>
                            <button type="button" wire:click="$set('selectedCategory', 'detailing')" class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all {{ $selectedCategory === 'detailing' ? 'bg-brand-teal text-white' : 'bg-brand-mist text-brand-ink hover:bg-brand-mint' }}">
                                Detailing
                            </button>
                            <button type="button" wire:click="$set('selectedCategory', 'protection')" class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all {{ $selectedCategory === 'protection' ? 'bg-brand-teal text-white' : 'bg-brand-mist text-brand-ink hover:bg-brand-mint' }}">
                                Protection
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($services as $srv)
                            <div 
                                wire:click="selectService({{ $srv->id }})"
                                class="p-5 rounded-2xl border-2 transition-all cursor-pointer flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 {{ $selectedServiceId === $srv->id ? 'border-brand-teal bg-brand-mist/40 ring-1 ring-brand-teal' : 'border-brand-line/70 hover:border-brand-teal/50 hover:bg-brand-paper' }}"
                            >
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-brand-mint text-brand-teal">
                                            {{ $srv->category->name ?? 'Service' }}
                                        </span>
                                        <span class="text-xs text-brand-muted flex items-center gap-1">
                                            ⏱ ~{{ $srv->duration_minutes }} mins
                                        </span>
                                    </div>
                                    <h3 class="font-display font-bold text-lg text-brand-ink">{{ $srv->name }}</h3>
                                    <p class="text-sm text-brand-muted mt-0.5 line-clamp-1">{{ $srv->tagline ?? $srv->description }}</p>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4 pt-3 sm:pt-0 border-t sm:border-t-0 border-brand-line/40">
                                    <div class="text-left sm:text-right">
                                        <div class="text-[11px] text-brand-muted uppercase font-bold">Starts at</div>
                                        <div class="font-display font-extrabold text-xl text-brand-ink">
                                            ₹{{ number_format($srv->price_hatchback ?? 499) }}
                                        </div>
                                    </div>
                                    <button type="button" class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all {{ $selectedServiceId === $srv->id ? 'bg-brand-teal text-white shadow-sm' : 'bg-brand-amber text-brand-ink hover:brightness-105' }}">
                                        Select
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- STEP 2: VEHICLE TYPE --}}
            @if($step === 2)
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-brand-line shadow-sm">
                    <div class="mb-6">
                        <button type="button" wire:click="goToStep(1)" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-teal hover:underline mb-2">
                            ← Change Service ({{ $selectedService->name ?? '' }})
                        </button>
                        <h2 class="font-display font-extrabold text-2xl text-brand-ink tracking-tight">Select Vehicle Category</h2>
                        <p class="text-brand-muted text-sm mt-1">Pricing and bay allocation are calibrated to vehicle dimensions</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        {{-- Hatchback --}}
                        <div 
                            wire:click="selectVehicleType('hatchback')"
                            class="p-6 rounded-2xl border-2 transition-all cursor-pointer flex flex-col justify-between text-center {{ $vehicleType === 'hatchback' ? 'border-brand-teal bg-brand-mist/50 ring-1 ring-brand-teal' : 'border-brand-line/70 hover:border-brand-teal/50 hover:bg-brand-paper' }}"
                        >
                            <div>
                                <div class="w-16 h-16 mx-auto mb-3 bg-brand-mint/60 rounded-2xl flex items-center justify-center text-2xl">
                                    🚗
                                </div>
                                <h3 class="font-display font-bold text-lg text-brand-ink">Hatchback</h3>
                                <p class="text-xs text-brand-muted mt-1">Swift, i20, Polo, Baleno, Tiago, Mini</p>
                            </div>
                            <div class="mt-6 pt-4 border-t border-brand-line/60">
                                <span class="text-xs text-brand-muted">Package Price</span>
                                <div class="font-display font-extrabold text-2xl text-brand-ink mt-0.5">
                                    ₹{{ number_format($selectedService->price_hatchback ?? 499) }}
                                </div>
                            </div>
                        </div>

                        {{-- Sedan --}}
                        <div 
                            wire:click="selectVehicleType('sedan')"
                            class="p-6 rounded-2xl border-2 transition-all cursor-pointer flex flex-col justify-between text-center {{ $vehicleType === 'sedan' ? 'border-brand-teal bg-brand-mist/50 ring-1 ring-brand-teal' : 'border-brand-line/70 hover:border-brand-teal/50 hover:bg-brand-paper' }}"
                        >
                            <div>
                                <div class="w-16 h-16 mx-auto mb-3 bg-brand-mint/60 rounded-2xl flex items-center justify-center text-2xl">
                                    🏎️
                                </div>
                                <h3 class="font-display font-bold text-lg text-brand-ink">Sedan / Compact SUV</h3>
                                <p class="text-xs text-brand-muted mt-1">City, Verna, Virtus, Creta, Seltos, Nexon</p>
                            </div>
                            <div class="mt-6 pt-4 border-t border-brand-line/60">
                                <span class="text-xs text-brand-muted">Package Price</span>
                                <div class="font-display font-extrabold text-2xl text-brand-ink mt-0.5">
                                    ₹{{ number_format($selectedService->price_sedan ?? ($selectedService->price_hatchback ?? 599)) }}
                                </div>
                            </div>
                        </div>

                        {{-- SUV / Large --}}
                        <div 
                            wire:click="selectVehicleType('suv')"
                            class="p-6 rounded-2xl border-2 transition-all cursor-pointer flex flex-col justify-between text-center {{ $vehicleType === 'suv' ? 'border-brand-teal bg-brand-mist/50 ring-1 ring-brand-teal' : 'border-brand-line/70 hover:border-brand-teal/50 hover:bg-brand-paper' }}"
                        >
                            <div>
                                <div class="w-16 h-16 mx-auto mb-3 bg-brand-mint/60 rounded-2xl flex items-center justify-center text-2xl">
                                    🚙
                                </div>
                                <h3 class="font-display font-bold text-lg text-brand-ink">Full SUV / Luxury</h3>
                                <p class="text-xs text-brand-muted mt-1">Fortuner, XUV700, Safari, Scorpio, German Luxury</p>
                            </div>
                            <div class="mt-6 pt-4 border-t border-brand-line/60">
                                <span class="text-xs text-brand-muted">Package Price</span>
                                <div class="font-display font-extrabold text-2xl text-brand-ink mt-0.5">
                                    ₹{{ number_format($selectedService->price_suv ?? ($selectedService->price_hatchback ?? 699)) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-brand-line">
                        <button type="button" wire:click="goToStep(1)" class="px-5 py-3 rounded-xl border border-brand-line font-bold text-sm text-brand-ink hover:bg-brand-mist">
                            Back
                        </button>
                        <button type="button" wire:click="goToStep(3)" class="px-8 py-3 rounded-xl bg-brand-teal text-white font-bold text-sm shadow-md hover:bg-brand-teal-deep">
                            Continue to Date & Time →
                        </button>
                    </div>
                </div>
            @endif

            {{-- STEP 3: DATE & TIME --}}
            @if($step === 3)
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-brand-line shadow-sm">
                    <div class="mb-6">
                        <button type="button" wire:click="goToStep(2)" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-teal hover:underline mb-2">
                            ← Change Vehicle Type ({{ ucfirst($vehicleType) }})
                        </button>
                        <h2 class="font-display font-extrabold text-2xl text-brand-ink tracking-tight">Choose Date & Clinic Slot</h2>
                        <p class="text-brand-muted text-sm mt-1">Live bay capacity calibrated for ~{{ $selectedService->duration_minutes ?? 30 }} min treatment</p>
                    </div>

                    {{-- 7-Day Picker Carousel --}}
                    <div class="mb-8">
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-muted mb-3">Available Dates (Next 7 Days)</label>
                        <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
                            @foreach($availableDays as $day)
                                <button
                                    type="button"
                                    wire:click="selectDate('{{ $day['date'] }}')"
                                    @if($day['is_closed']) disabled @endif
                                    class="p-3 rounded-2xl border text-center transition-all flex flex-col items-center justify-center {{ $selectedDate === $day['date'] ? 'border-brand-teal bg-brand-teal text-white shadow-md' : ($day['is_closed'] ? 'border-gray-200 bg-gray-100 text-gray-400 opacity-60 cursor-not-allowed' : 'border-brand-line/80 hover:border-brand-teal hover:bg-brand-paper text-brand-ink') }}"
                                >
                                    <span class="text-[11px] font-bold uppercase {{ $selectedDate === $day['date'] ? 'text-brand-mint' : 'text-brand-muted' }}">{{ $day['day_name'] }}</span>
                                    <span class="font-display font-extrabold text-lg my-0.5">{{ $day['day_number'] }}</span>
                                    <span class="text-[10px] {{ $selectedDate === $day['date'] ? 'text-white' : ($day['is_today'] ? 'text-brand-teal font-bold' : 'text-brand-muted') }}">
                                        {{ $day['is_today'] ? 'Today' : ($day['is_closed'] ? 'Closed' : $day['month_name']) }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Slot Grid --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-muted">
                                Available Time Slots on {{ \Carbon\Carbon::parse($selectedDate)->format('D, M j') }}
                            </label>
                            <span class="text-xs text-brand-muted flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-brand-teal inline-block"></span> Open Bays
                            </span>
                        </div>

                        @error('selectedTime')
                            <div class="p-3 mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-bold">
                                {{ $message }}
                            </div>
                        @enderror

                        @if(empty($slots))
                            <div class="p-8 rounded-2xl bg-brand-mist text-center text-brand-muted text-sm">
                                Studio is closed on this date. Please pick another day.
                            </div>
                        @else
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2.5">
                                @foreach($slots as $slot)
                                    <button
                                        type="button"
                                        wire:click="selectTime('{{ $slot['time'] }}')"
                                        @if(!$slot['available']) disabled @endif
                                        class="py-3 px-2 rounded-xl text-center font-bold text-xs md:text-sm border transition-all {{ $selectedTime === $slot['time'] ? 'border-brand-teal bg-brand-teal text-white shadow-md' : ($slot['available'] ? 'border-brand-line bg-white hover:border-brand-teal hover:bg-brand-mist text-brand-ink' : 'border-gray-200 bg-gray-100 text-gray-400 line-through opacity-50 cursor-not-allowed') }}"
                                    >
                                        {{ $slot['display_time'] }}
                                        @if(!$slot['available'])
                                            <span class="block text-[9px] font-normal no-underline text-red-500 mt-0.5">Full</span>
                                        @else
                                            <span class="block text-[9px] font-normal {{ $selectedTime === $slot['time'] ? 'text-brand-mint' : 'text-brand-teal' }} mt-0.5">
                                                Available
                                            </span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center pt-6 mt-8 border-t border-brand-line">
                        <button type="button" wire:click="goToStep(2)" class="px-5 py-3 rounded-xl border border-brand-line font-bold text-sm text-brand-ink hover:bg-brand-mist">
                            Back
                        </button>
                        @if($selectedTime)
                            <button type="button" wire:click="goToStep(4)" class="px-8 py-3 rounded-xl bg-brand-teal text-white font-bold text-sm shadow-md hover:bg-brand-teal-deep">
                                Proceed to Customer Details →
                            </button>
                        @endif
                    </div>
                </div>
            @endif

            {{-- STEP 4: CUSTOMER & VEHICLE DETAILS --}}
            @if($step === 4)
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-brand-line shadow-sm">
                    <div class="mb-6">
                        <button type="button" wire:click="goToStep(3)" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-teal hover:underline mb-2">
                            ← Change Time ({{ \Carbon\Carbon::parse($selectedDate)->format('M j') }} at {{ \Carbon\Carbon::parse($selectedTime)->format('g:i A') }})
                        </button>
                        <h2 class="font-display font-extrabold text-2xl text-brand-ink tracking-tight">Your Details & Vehicle Info</h2>
                        <p class="text-brand-muted text-sm mt-1">We link your history to your mobile and vehicle plate</p>
                    </div>

                    <form wire:submit.prevent="submitBooking" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Mobile Number --}}
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1.5">
                                    Mobile Number <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-brand-muted">+91</span>
                                    <input 
                                        type="tel" 
                                        wire:model.live.debounce.500ms="mobile" 
                                        placeholder="98765 43210" 
                                        maxlength="10"
                                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-brand-line focus:border-brand-teal focus:ring-1 focus:ring-brand-teal font-medium text-brand-ink text-sm"
                                        required
                                    />
                                </div>
                                @error('mobile') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Name --}}
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1.5">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="name" 
                                    placeholder="e.g. Vikram Sharma" 
                                    class="w-full px-4 py-3 rounded-xl border border-brand-line focus:border-brand-teal focus:ring-1 focus:ring-brand-teal font-medium text-brand-ink text-sm"
                                    required
                                />
                                @error('name') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Registration Number --}}
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1.5">
                                    Vehicle Plate / Reg No. <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        wire:model="registrationNumber" 
                                        placeholder="JK02AB1234" 
                                        class="w-full uppercase tracking-wider font-bold px-4 py-3 rounded-xl border border-brand-line focus:border-brand-teal focus:ring-1 focus:ring-brand-teal text-brand-ink text-sm placeholder:normal-case placeholder:font-normal"
                                        required
                                    />
                                </div>
                                <span class="text-[11px] text-brand-muted">Standard Indian plate (e.g. JK 02 AB 1234)</span>
                                @error('registrationNumber') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Make & Model --}}
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1.5">
                                    Car Make & Model <span class="text-brand-muted font-normal">(Optional)</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="makeModel" 
                                    placeholder="e.g. Hyundai Creta SX" 
                                    class="w-full px-4 py-3 rounded-xl border border-brand-line focus:border-brand-teal focus:ring-1 focus:ring-brand-teal font-medium text-brand-ink text-sm"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Email --}}
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1.5">
                                    Email Address <span class="text-brand-muted font-normal">(Optional for digital invoice)</span>
                                </label>
                                <input 
                                    type="email" 
                                    wire:model="email" 
                                    placeholder="vikram@example.com" 
                                    class="w-full px-4 py-3 rounded-xl border border-brand-line focus:border-brand-teal focus:ring-1 focus:ring-brand-teal font-medium text-brand-ink text-sm"
                                />
                                @error('email') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1.5">
                                    Special Requests / Notes <span class="text-brand-muted font-normal">(Optional)</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="notes" 
                                    placeholder="e.g. Extra attention to dog hair in boot" 
                                    class="w-full px-4 py-3 rounded-xl border border-brand-line focus:border-brand-teal focus:ring-1 focus:ring-brand-teal font-medium text-brand-ink text-sm"
                                />
                            </div>
                        </div>

                        {{-- Payment notice --}}
                        <div class="p-4 rounded-2xl bg-brand-mist border border-brand-line/60 flex items-start gap-3">
                            <span class="text-xl">💳</span>
                            <div class="text-xs text-brand-muted">
                                <strong class="text-brand-ink">No advance payment required online.</strong> Payment is collected at the studio via UPI, Cash or Card after your treatment is completed to your total satisfaction.
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-6 border-t border-brand-line">
                            <button type="button" wire:click="goToStep(3)" class="px-5 py-3 rounded-xl border border-brand-line font-bold text-sm text-brand-ink hover:bg-brand-mist">
                                Back
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="px-8 py-3.5 rounded-xl bg-brand-amber text-brand-ink font-extrabold text-sm shadow-md hover:brightness-105 transition-all flex items-center gap-2">
                                <span wire:loading.remove>Confirm Booking (Pay ₹{{ number_format($selectedService->getPriceForVehicleType($vehicleType) ?? 499) }} at Studio) →</span>
                                <span wire:loading>Securing Slot...</span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- STEP 5: CONFIRMED VIEW --}}
            @if($step === 5 && $confirmedBooking)
                <div class="bg-white rounded-3xl p-8 md:p-10 border border-brand-line shadow-lg text-center">
                    <div class="w-16 h-16 bg-brand-mint text-brand-teal rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                        ✓
                    </div>

                    <span class="inline-block px-3 py-1 rounded-full bg-brand-mint/60 text-brand-teal font-bold text-xs uppercase tracking-wider mb-2">
                        Booking Confirmed
                    </span>

                    <h2 class="font-display font-extrabold text-3xl text-brand-ink tracking-tight">
                        We're Ready for Your Car!
                    </h2>
                    <p class="text-brand-muted text-sm mt-2 max-w-md mx-auto">
                        Your slot is reserved at The Drive Clinic. A booking record has been logged in our system.
                    </p>

                    {{-- Booking Summary Card --}}
                    <div class="my-8 p-6 rounded-2xl bg-brand-paper border border-brand-line/80 text-left max-w-md mx-auto space-y-3">
                        <div class="flex items-center justify-between pb-3 border-b border-brand-line/60">
                            <span class="text-xs text-brand-muted uppercase font-bold">Booking Reference</span>
                            <span class="font-mono font-bold text-xs bg-white px-2.5 py-1 rounded-md border border-brand-line text-brand-ink">
                                {{ $confirmedBooking->booking_number }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-brand-muted">Service</span>
                            <span class="font-bold text-sm text-brand-ink">{{ $confirmedBooking->service_name }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-brand-muted">Vehicle Plate</span>
                            <span class="font-display font-bold text-sm tracking-wide bg-white px-2 py-0.5 rounded border border-brand-line">
                                {{ $confirmedBooking->formatted_plate }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-brand-muted">Date & Time</span>
                            <span class="font-bold text-sm text-brand-teal">
                                {{ \Carbon\Carbon::parse($confirmedBooking->booking_date)->format('D, M j, Y') }} at {{ \Carbon\Carbon::parse($confirmedBooking->booking_time)->format('g:i A') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-brand-line/60">
                            <span class="text-xs text-brand-muted font-bold">Pay at Studio</span>
                            <span class="font-display font-extrabold text-xl text-brand-ink">
                                ₹{{ number_format($confirmedBooking->price) }}
                            </span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 max-w-md mx-auto">
                        <a 
                            href="{{ $this->getWhatsAppUrl() }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="w-full sm:w-auto flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-[#25D366] text-white font-bold text-sm shadow-md hover:brightness-105 transition-all"
                        >
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.392-12.416c-5.514 0-10 4.486-10 10 0 1.761.459 3.417 1.261 4.864l-1.34 4.896 5.021-1.317c1.401.763 3.003 1.197 4.708 1.197 5.514 0 10-4.486 10-10s-4.486-10-10-10z"/></svg>
                            Confirm on WhatsApp
                        </a>

                        <a 
                            href="{{ route('home') }}" 
                            class="w-full sm:w-auto px-6 py-3.5 rounded-xl border border-brand-line text-brand-ink font-bold text-sm hover:bg-brand-mist transition-all text-center"
                        >
                            Return to Home
                        </a>
                    </div>
                </div>
            @endif

        </div>

        {{-- Sticky Booking Summary Sidebar (Steps 1 to 4) --}}
        @if($step < 5)
            <div class="lg:col-span-4 sticky top-28">
                <div class="bg-brand-teal-deep text-white rounded-3xl p-6 shadow-xl border border-brand-teal/40">
                    <div class="flex items-center justify-between pb-4 border-b border-white/10">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-mint">Booking Overview</span>
                        <span class="text-[11px] bg-brand-teal text-white px-2 py-0.5 rounded font-bold">Step {{ $step }}/4</span>
                    </div>

                    <div class="py-4 space-y-3.5 text-sm">
                        {{-- Service --}}
                        <div>
                            <span class="text-xs text-brand-mint/70 block">Selected Treatment</span>
                            <span class="font-bold text-base text-white block mt-0.5">{{ $selectedService->name ?? 'None Selected' }}</span>
                            @if($selectedService)
                                <span class="text-xs text-brand-mint block">⏱ Est. Duration: ~{{ $selectedService->duration_minutes }} min</span>
                            @endif
                        </div>

                        {{-- Vehicle Type --}}
                        <div class="pt-3 border-t border-white/10">
                            <span class="text-xs text-brand-mint/70 block">Vehicle Class</span>
                            <span class="font-bold text-white block mt-0.5 capitalize">{{ $vehicleType }}</span>
                        </div>

                        {{-- Date & Time --}}
                        @if($selectedDate)
                            <div class="pt-3 border-t border-white/10">
                                <span class="text-xs text-brand-mint/70 block">Scheduled Time</span>
                                <span class="font-bold text-white block mt-0.5">
                                    {{ \Carbon\Carbon::parse($selectedDate)->format('D, M j, Y') }}
                                    @if($selectedTime)
                                        <span class="text-brand-amber font-extrabold ml-1">@ {{ \Carbon\Carbon::parse($selectedTime)->format('g:i A') }}</span>
                                    @else
                                        <span class="text-brand-mint/60 text-xs italic block mt-0.5">Time slot pending selection</span>
                                    @endif
                                </span>
                            </div>
                        @endif

                        {{-- Studio Location --}}
                        <div class="pt-3 border-t border-white/10">
                            <span class="text-xs text-brand-mint/70 block">Studio Location</span>
                            <span class="text-xs text-white block mt-0.5 font-medium">Nanak Nagar, Jammu (J&K)</span>
                        </div>
                    </div>

                    {{-- Total Amount Box --}}
                    <div class="mt-4 pt-4 border-t border-white/15 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-brand-mint/80 block">Estimated Total</span>
                            <span class="text-[10px] text-white/50">Pay at studio</span>
                        </div>
                        <div class="font-display font-extrabold text-2xl text-brand-amber">
                            ₹{{ number_format($selectedService->getPriceForVehicleType($vehicleType) ?? 499) }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
