<div>
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-ink/70 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 md:p-8 shadow-2xl border border-line space-y-6 animate-in fade-in zoom-in duration-150">
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-line/60 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-teal-deep text-amber flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black font-display text-ink">Assign Drive Club Plan</h3>
                            <p class="text-xs text-muted">Enroll customer vehicle into annual healthcare plan</p>
                        </div>
                    </div>
                    <button wire:click="$set('isOpen', false)" class="text-muted hover:text-ink text-2xl font-bold">&times;</button>
                </div>

                @if($errorMessage)
                    <div class="p-3 bg-brick/10 border border-brick/30 text-brick rounded-lg text-xs font-semibold">
                        {{ $errorMessage }}
                    </div>
                @endif

                <div class="space-y-4 text-sm">
                    <!-- Customer & Vehicle Info -->
                    <div class="p-3.5 bg-mist/60 border border-line/60 rounded-xl space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-muted uppercase">Customer:</span>
                            <span class="font-bold text-ink">{{ $customerName }} ({{ $customerMobile }})</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-muted uppercase">Vehicle:</span>
                            @if(count($vehicles) > 1)
                                <select wire:model="vehicleId" class="text-xs font-bold px-2 py-1 bg-white border border-line rounded">
                                    @foreach($vehicles as $veh)
                                        <option value="{{ $veh->id }}">{{ $veh->formatted_plate }} ({{ $veh->make }} {{ $veh->model }})</option>
                                    @endforeach
                                </select>
                            @else
                                <span class="font-bold font-display text-teal-deep">{{ $registrationNumber }} ({{ $vehicleSummary }})</span>
                            @endif
                        </div>
                    </div>

                    <!-- Plan Selection -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-ink mb-1.5">Select Drive Club Plan *</label>
                        <div class="space-y-2">
                            @foreach($plans as $plan)
                                <label class="flex items-start justify-between p-3 rounded-xl border {{ $selectedPlanId == $plan->id ? 'border-amber bg-amber/5 ring-1 ring-amber' : 'border-line/70 hover:bg-mist/40' }} cursor-pointer transition">
                                    <div class="flex items-start gap-3">
                                        <input type="radio" wire:model.live="selectedPlanId" value="{{ $plan->id }}" class="mt-1 text-teal focus:ring-teal">
                                        <div>
                                            <div class="font-bold text-ink flex items-center gap-2">
                                                {{ $plan->name }}
                                                @if($plan->is_featured)
                                                    <span class="text-[10px] bg-amber text-ink font-black px-1.5 py-0.2 rounded">POPULAR</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-muted mt-0.5">{{ $plan->benefits_description }}</p>
                                            
                                            <!-- Included treatments snippet -->
                                            @if(!empty($plan->included_services))
                                                <div class="flex flex-wrap gap-1 mt-1.5">
                                                    @foreach($plan->included_services as $svc)
                                                        <span class="text-[10px] bg-mint/50 text-teal-deep font-semibold px-1.5 py-0.5 rounded">
                                                            {{ $svc['count'] }}x {{ $svc['service_name'] }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-base font-black font-display text-teal-deep">₹{{ number_format($plan->price, 0) }}</span>
                                        <span class="block text-[10px] text-muted">/ yr</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment & Pricing -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-ink mb-1">Payment Method</label>
                            <select wire:model="paymentMethod" class="w-full px-3 py-2 border border-line rounded-lg text-xs bg-white">
                                <option value="cash">Cash Payment</option>
                                <option value="upi">UPI / QR Code</option>
                                <option value="card">Debit / Credit Card</option>
                                <option value="other">Bank Transfer / Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-ink mb-1">Amount Paid (₹)</label>
                            <input type="number" wire:model="pricePaid" step="1" class="w-full px-3 py-2 border border-line rounded-lg text-xs bg-white font-bold text-ink">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-ink mb-1">Notes / Staff Remarks (Optional)</label>
                        <input type="text" wire:model="notes" class="w-full px-3 py-2 border border-line rounded-lg text-xs bg-white" placeholder="e.g. Paid at reception via GPay">
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-3 border-t border-line/60 pt-4">
                    <button wire:click="$set('isOpen', false)" type="button" class="px-4 py-2 border border-line text-ink text-xs font-bold rounded-lg hover:bg-mist cursor-pointer">
                        Cancel
                    </button>
                    <button wire:click="assignMembership" type="button" class="px-6 py-2 bg-amber hover:bg-amber/90 text-ink text-xs font-bold rounded-lg transition shadow-xs cursor-pointer flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Activate Membership
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
