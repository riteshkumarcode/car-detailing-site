<div>
    @if($isOpen)
        <div class="fixed inset-0 bg-brand-ink/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 md:p-8 shadow-2xl relative">
                <button 
                    type="button" 
                    wire:click="close"
                    class="absolute right-5 top-5 text-gray-400 hover:text-gray-700 font-bold text-lg bg-gray-100 hover:bg-gray-200 w-8 h-8 rounded-full flex items-center justify-center"
                >
                    ✕
                </button>

                <div class="border-b border-brand-line/60 pb-3 mb-5">
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-teal">≤ 60s Fast-Track Registration</span>
                    <h2 class="font-display font-extrabold text-2xl text-brand-ink">Log Walk-In Arrival</h2>
                </div>

                @if($duplicateWarning)
                    <div class="p-3 mb-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs font-medium flex items-center gap-2">
                        <span>ℹ️</span>
                        <span>{{ $duplicateWarning }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="createWalkIn" class="space-y-4">
                    {{-- Mobile & Name --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1">
                                Customer Mobile <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-brand-muted">+91</span>
                                <input 
                                    type="tel" 
                                    wire:model.live.debounce.400ms="mobile"
                                    placeholder="9876543210"
                                    maxlength="10"
                                    class="w-full pl-10 pr-3 py-2.5 rounded-xl border border-brand-line focus:border-brand-teal text-sm font-medium"
                                    required
                                    autofocus
                                />
                            </div>
                            @error('mobile') <p class="text-[11px] text-red-600 font-medium mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1">
                                Customer Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="name"
                                placeholder="e.g. Karan Dev"
                                class="w-full px-3 py-2.5 rounded-xl border border-brand-line focus:border-brand-teal text-sm font-medium"
                                required
                            />
                            @error('name') <p class="text-[11px] text-red-600 font-medium mt-0.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Vehicle Plate & Car Model --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1">
                                Vehicle Plate <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="registrationNumber"
                                placeholder="JK02AB1234"
                                class="w-full uppercase font-bold tracking-wider px-3 py-2.5 rounded-xl border border-brand-line focus:border-brand-teal text-sm"
                                required
                            />
                            @error('registrationNumber') <p class="text-[11px] text-red-600 font-medium mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1">
                                Car Model / Variant
                            </label>
                            <input 
                                type="text" 
                                wire:model="makeModel"
                                placeholder="e.g. Swift ZXi"
                                class="w-full px-3 py-2.5 rounded-xl border border-brand-line focus:border-brand-teal text-sm font-medium"
                            />
                        </div>
                    </div>

                    {{-- Saved Vehicles Pills (if any) --}}
                    @if(!empty($matchedVehicles))
                        <div class="p-2.5 rounded-xl bg-brand-mist/60 border border-brand-line/60">
                            <span class="text-[11px] font-bold text-brand-muted uppercase block mb-1.5">Customer's Saved Vehicles:</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($matchedVehicles as $mv)
                                    <button 
                                        type="button" 
                                        wire:click="selectSavedVehicle({{ $mv['id'] }})"
                                        class="px-2.5 py-1 rounded-lg bg-white border border-brand-line text-xs font-bold text-brand-ink hover:bg-brand-mint"
                                    >
                                        {{ \App\Domain\Normalizers\RegistrationNormalizer::format($mv['registration_number']) }} ({{ $mv['make'] ?? '' }} {{ $mv['model'] ?? '' }})
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Vehicle Body Type --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1">
                            Body Category <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach(['hatchback' => '🚗 Hatchback', 'sedan' => '🏎️ Sedan', 'suv' => '🚙 SUV'] as $typeKey => $typeLabel)
                                <button 
                                    type="button" 
                                    wire:click="$set('vehicleType', '{{ $typeKey }}')"
                                    class="py-2 px-2 rounded-xl text-xs font-bold border transition-all text-center {{ $vehicleType === $typeKey ? 'border-brand-teal bg-brand-mist text-brand-teal ring-1 ring-brand-teal' : 'border-brand-line text-brand-ink hover:bg-brand-paper' }}"
                                >
                                    {{ $typeLabel }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Service Selection --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1">
                            Select Service <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model="selectedServiceId"
                            class="w-full px-3 py-2.5 rounded-xl border border-brand-line focus:border-brand-teal text-sm font-medium bg-white"
                            required
                        >
                            @foreach($services as $srv)
                                <option value="{{ $srv->id }}">
                                    {{ $srv->name }} (~{{ $srv->duration_minutes }}m) — ₹{{ number_format($srv->getPriceForVehicleType($vehicleType) ?? 499) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Initial Dispatch Status --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink mb-1">
                            Initial Bay Dispatch Status
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <button 
                                type="button" 
                                wire:click="$set('statusChoice', 'in_service')"
                                class="py-2 px-2 rounded-xl text-xs font-bold border transition-all text-center {{ $statusChoice === 'in_service' ? 'border-emerald-600 bg-emerald-50 text-emerald-800 ring-1 ring-emerald-600' : 'border-brand-line text-brand-ink' }}"
                            >
                                🚀 Put In Service Now
                            </button>
                            <button 
                                type="button" 
                                wire:click="$set('statusChoice', 'arrived')"
                                class="py-2 px-2 rounded-xl text-xs font-bold border transition-all text-center {{ $statusChoice === 'arrived' ? 'border-amber-600 bg-amber-50 text-amber-900 ring-1 ring-amber-600' : 'border-brand-line text-brand-ink' }}"
                            >
                                ⏳ Mark Arrived (Queue)
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-3 border-t border-brand-line">
                        <button 
                            type="button" 
                            wire:click="close"
                            class="px-4 py-2.5 rounded-xl border border-brand-line text-xs font-bold text-brand-ink hover:bg-brand-mist"
                        >
                            Cancel
                        </button>

                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            class="px-6 py-2.5 rounded-xl bg-brand-amber text-brand-ink font-display font-extrabold text-sm shadow-md hover:brightness-105 transition-all"
                        >
                            <span wire:loading.remove>Create Walk-In Job →</span>
                            <span wire:loading>Creating Job...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
