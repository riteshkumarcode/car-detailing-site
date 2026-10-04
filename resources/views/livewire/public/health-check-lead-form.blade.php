<div class="bg-white rounded-3xl p-6 md:p-10 border border-brand-line shadow-lg">
    @if($isSubmitted)
        <div class="text-center py-6">
            <div class="w-16 h-16 bg-brand-mint text-brand-teal rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                ✓
            </div>

            <span class="inline-block px-3 py-1 rounded-full bg-brand-mint/60 text-brand-teal font-bold text-xs uppercase tracking-wider mb-2">
                Diagnostic Request Received
            </span>

            <h2 class="font-display font-extrabold text-3xl text-brand-ink tracking-tight">
                Your Health Check Slot is Requested!
            </h2>
            
            <p class="text-brand-muted text-sm mt-2 max-w-md mx-auto">
                Thank you, <strong>{{ $name }}</strong>. Our inspection team has received your 25-point diagnostic request for your <strong>{{ $makeModel }}</strong>.
            </p>

            <div class="my-8 p-6 rounded-2xl bg-brand-paper border border-brand-line/80 text-left max-w-md mx-auto space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-brand-line/60">
                    <span class="text-xs text-brand-muted uppercase font-bold">Preferred Slot</span>
                    <span class="font-bold text-sm text-brand-teal">
                        {{ \Carbon\Carbon::parse($preferredDate)->format('D, M j') }} ({{ ucfirst($preferredTime) }})
                    </span>
                </div>
                <div class="flex items-center justify-between pb-3 border-b border-brand-line/60">
                    <span class="text-xs text-brand-muted uppercase font-bold">Vehicle Plate</span>
                    <span class="font-display font-bold text-sm tracking-wide bg-white px-2 py-0.5 rounded border border-brand-line">
                        {{ \App\Domain\Normalizers\RegistrationNormalizer::format($registrationNumber) }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-brand-muted uppercase font-bold">Diagnostic Cost</span>
                    <span class="font-bold text-sm text-green-700 bg-green-50 px-2 py-0.5 rounded">
                        100% FREE (₹0)
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 max-w-md mx-auto">
                <a 
                    href="{{ $this->getWhatsAppUrl() }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="w-full sm:w-auto flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-[#25D366] text-white font-bold text-sm shadow-md hover:brightness-105 transition-all"
                >
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.392-12.416c-5.514 0-10 4.486-10 10 0 1.761.459 3.417 1.261 4.864l-1.34 4.896 5.021-1.317c1.401.763 3.003 1.197 4.708 1.197 5.514 0 10-4.486 10-10s-4.486-10-10-10z"/></svg>
                    Send to Studio on WhatsApp
                </a>

                <button 
                    type="button" 
                    wire:click="resetForm" 
                    class="w-full sm:w-auto px-6 py-3.5 rounded-xl border border-brand-line text-brand-ink font-bold text-sm hover:bg-brand-mist transition-all text-center"
                >
                    Submit Another Vehicle
                </button>
            </div>
        </div>
    @else
        <form wire:submit.prevent="submit" class="space-y-6">
            <div class="border-b border-brand-line/60 pb-4 mb-6">
                <h3 class="font-display font-extrabold text-xl text-brand-ink">Schedule Your Free Diagnostic</h3>
                <p class="text-xs text-brand-muted mt-1">Takes ~15 minutes on-ramp at our Nanak Nagar studio with a digital report card</p>
            </div>

            {{-- Row 1: Name & Mobile --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1.5">
                        Your Full Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        wire:model="name" 
                        placeholder="e.g. Rahul Gupta" 
                        class="w-full px-4 py-3 rounded-xl border border-brand-line focus:border-brand-teal focus:ring-1 focus:ring-brand-teal text-sm font-medium text-brand-ink"
                        required
                    />
                    @error('name') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1.5">
                        Mobile Number (WhatsApp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-brand-muted">+91</span>
                        <input 
                            type="tel" 
                            wire:model.live.debounce.500ms="mobile" 
                            placeholder="98765 43210" 
                            maxlength="10"
                            class="w-full pl-12 pr-4 py-3 rounded-xl border border-brand-line focus:border-brand-teal focus:ring-1 focus:ring-brand-teal text-sm font-medium text-brand-ink"
                            required
                        />
                    </div>
                    @error('mobile') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Row 2: Reg Plate & Make Model --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1.5">
                        Vehicle Plate Number <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        wire:model="registrationNumber" 
                        placeholder="JK02AB1234" 
                        class="w-full uppercase tracking-wider font-bold px-4 py-3 rounded-xl border border-brand-line focus:border-brand-teal focus:ring-1 focus:ring-brand-teal text-sm text-brand-ink placeholder:normal-case placeholder:font-normal"
                        required
                    />
                    @error('registrationNumber') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1.5">
                        Car Make & Model <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        wire:model="makeModel" 
                        placeholder="e.g. Mahindra XUV700 AX7" 
                        class="w-full px-4 py-3 rounded-xl border border-brand-line focus:border-brand-teal focus:ring-1 focus:ring-brand-teal text-sm font-medium text-brand-ink"
                        required
                    />
                    @error('makeModel') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Row 3: Vehicle Type Selection --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-2">
                    Vehicle Body Type <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach(['hatchback' => '🚗 Hatchback', 'sedan' => '🏎️ Sedan', 'suv' => '🚙 SUV', 'other' => '🚐 Other'] as $key => $label)
                        <button 
                            type="button" 
                            wire:click="$set('vehicleType', '{{ $key }}')" 
                            class="py-2.5 px-3 rounded-xl border text-xs font-bold transition-all text-center {{ $vehicleType === $key ? 'border-brand-teal bg-brand-mist text-brand-teal ring-1 ring-brand-teal' : 'border-brand-line text-brand-ink hover:bg-brand-paper' }}"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Row 4: Multi-Select Concern Chips --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-2">
                    Main Concerns to Inspect <span class="text-red-500">*</span> (Select all that apply)
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach($availableConcerns as $key => $label)
                        <button 
                            type="button" 
                            wire:click="toggleConcern('{{ $key }}')" 
                            class="px-3.5 py-2 rounded-xl text-xs font-medium border transition-all flex items-center gap-1.5 {{ in_array($key, $mainConcerns) ? 'border-brand-teal bg-brand-teal text-white shadow-sm' : 'border-brand-line bg-white text-brand-ink hover:border-brand-teal/50 hover:bg-brand-paper' }}"
                        >
                            <span>{{ in_array($key, $mainConcerns) ? '✓' : '+' }}</span>
                            <span>{{ $label }}</span>
                        </button>
                    @endforeach
                </div>
                @error('mainConcerns') <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Row 5: Preferred Date & Time Window --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1.5">
                        Preferred Date <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        wire:model="preferredDate" 
                        min="{{ date('Y-m-d') }}" 
                        class="w-full px-4 py-3 rounded-xl border border-brand-line focus:border-brand-teal focus:ring-1 focus:ring-brand-teal text-sm font-medium text-brand-ink"
                        required
                    />
                    @error('preferredDate') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1.5">
                        Preferred Time of Day <span class="text-red-500">*</span>
                    </label>
                    <select 
                        wire:model="preferredTime" 
                        class="w-full px-4 py-3 rounded-xl border border-brand-line focus:border-brand-teal focus:ring-1 focus:ring-brand-teal text-sm font-medium text-brand-ink bg-white"
                        required
                    >
                        <option value="morning">Morning (9:00 AM – 1:00 PM)</option>
                        <option value="afternoon">Afternoon (1:00 PM – 5:00 PM)</option>
                        <option value="evening">Evening (5:00 PM – 8:00 PM)</option>
                    </select>
                    @error('preferredTime') <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Free Diagnostic Guarantee Banner --}}
            <div class="p-4 rounded-2xl bg-brand-mint/40 border border-brand-teal/20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🛡️</span>
                    <div>
                        <span class="text-xs font-bold text-brand-teal block">No Obligation Guarantee</span>
                        <span class="text-[11px] text-brand-muted block">100% Free inspection & report. No treatment purchase required.</span>
                    </div>
                </div>
                <span class="font-display font-black text-brand-teal text-lg">₹0 Free</span>
            </div>

            {{-- Submit Button --}}
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="w-full py-4 rounded-2xl bg-brand-amber text-brand-ink font-display font-extrabold text-base shadow-md hover:brightness-105 transition-all flex items-center justify-center gap-2"
            >
                <span wire:loading.remove>Request Free Digital Health Check →</span>
                <span wire:loading>Booking Diagnostic Slot...</span>
            </button>
        </form>
    @endif
</div>
