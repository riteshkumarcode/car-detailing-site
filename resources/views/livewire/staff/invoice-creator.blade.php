<div>
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6"
             x-data
             x-trap.noscroll="true"
             @keydown.escape.window="$wire.close()">
            
            <div class="bg-clinical-900 border border-slate-800 rounded-2xl w-full max-w-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between bg-clinical-950/60">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 font-bold">
                            ₹
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-white">Generate Bill &amp; Complete Job</h3>
                                @if(!empty($membershipBenefits))
                                    <span class="px-2 py-0.5 rounded bg-amber-500 text-slate-950 font-black text-[10px] uppercase tracking-wider">
                                        {{ $membershipBenefits['plan_name'] }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 font-mono">
                                {{ $registrationNumber ? \App\Domain\Normalizers\RegistrationNormalizer::format($registrationNumber) : 'Walk-in' }} 
                                &bull; {{ $customerName ?: 'Customer' }}
                            </p>
                        </div>
                    </div>

                    <button wire:click="close" type="button" class="text-slate-400 hover:text-white text-lg font-bold p-1 cursor-pointer">
                        &times;
                    </button>
                </div>

                <!-- Modal Body Scrollable -->
                <div class="p-6 overflow-y-auto space-y-6 flex-1">
                    
                    @if($errorMessage)
                        <div class="p-3 bg-red-950/80 border border-red-800 text-red-300 text-xs rounded-lg">
                            <strong>Error:</strong> {{ $errorMessage }}
                        </div>
                    @endif

                    @if($issuedInvoiceSummary)
                        <div class="p-6 bg-emerald-950/80 border border-emerald-800 text-center rounded-xl space-y-4">
                            <div class="w-12 h-12 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto text-2xl font-bold">
                                &#10003;
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-white">Invoice Issued Successfully!</h4>
                                <div class="text-sm font-mono text-emerald-400 font-bold mt-1">
                                    {{ $issuedInvoiceSummary['invoice_number'] }} &bull; ₹{{ number_format($issuedInvoiceSummary['total_amount'], 2) }}
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                                <a href="{{ $issuedInvoiceSummary['show_url'] }}" target="_blank"
                                   class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-lg border border-slate-700 transition">
                                    View Digital Invoice →
                                </a>
                                <a href="{{ $issuedInvoiceSummary['pdf_url'] }}" target="_blank"
                                   class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold rounded-lg shadow transition">
                                    Download PDF
                                </a>
                                <button wire:click="close" type="button"
                                        class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-xs font-semibold rounded-lg transition cursor-pointer">
                                    Close
                                </button>
                            </div>
                        </div>
                    @else

                        <!-- Member Benefits Suggested Banner -->
                        @if(!empty($membershipBenefits))
                            <div class="p-4 rounded-xl bg-gradient-to-r from-teal-950 to-slate-900 border border-amber-500/40 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                                        </svg>
                                        <span class="text-xs font-bold text-white uppercase tracking-wider">
                                            Drive Club Benefits Detected ({{ $membershipBenefits['plan_name'] }})
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-amber-300 font-mono">Expires: {{ $membershipBenefits['expires_at'] }}</span>
                                </div>

                                <!-- Quick Discount Suggestion Pills -->
                                @if(!empty($membershipBenefits['category_discounts']))
                                    <div class="flex flex-wrap items-center gap-2 pt-1">
                                        <span class="text-[11px] text-slate-300">Suggested Discounts:</span>
                                        @foreach($membershipBenefits['category_discounts'] as $cat => $pct)
                                            <button 
                                                type="button"
                                                wire:click="applyCategoryDiscount('{{ ucfirst($cat) }}', {{ $pct }})"
                                                class="px-2 py-0.5 rounded bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/40 text-[11px] font-bold cursor-pointer transition"
                                            >
                                                Apply {{ ucfirst($cat) }} {{ $pct }}% Off
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Line Items Section -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400 font-mono">
                                    Services &amp; Treatments
                                </label>
                                <span class="text-xs text-slate-500 font-mono">{{ count($items) }} Items</span>
                            </div>

                            <div class="space-y-2">
                                @foreach($items as $index => $item)
                                    <div class="bg-clinical-950/80 border {{ !empty($item['is_membership_redemption']) ? 'border-amber-500/60 bg-amber-950/20' : 'border-slate-800' }} p-3 rounded-xl space-y-2">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-1 min-w-0">
                                                <div class="text-xs font-semibold text-white truncate flex items-center gap-2">
                                                    <span>{{ $item['item_name'] }}</span>
                                                    @if(!empty($item['is_membership_redemption']))
                                                        <span class="px-1.5 py-0.2 bg-amber-500 text-slate-950 text-[10px] font-black rounded uppercase">
                                                            Club Redeemed (Free)
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-[10px] text-slate-500 uppercase font-mono">{{ $item['item_type'] }}</div>
                                            </div>

                                            <div class="w-16">
                                                <input type="number" min="1" wire:model.live="items.{{ $index }}.quantity"
                                                       class="w-full bg-slate-900 border border-slate-700 rounded text-center text-xs py-1 text-white font-mono" />
                                            </div>

                                            <div class="w-24 text-right">
                                                <input type="number" step="0.01" wire:model.live="items.{{ $index }}.unit_price"
                                                       class="w-full bg-slate-900 border border-slate-700 rounded text-right text-xs py-1 {{ !empty($item['is_membership_redemption']) ? 'text-emerald-400 font-bold' : 'text-amber-400' }} font-mono" />
                                            </div>

                                            <button wire:click="removeItem({{ $index }})" type="button"
                                                    class="text-red-400 hover:text-red-300 p-1 text-sm font-bold cursor-pointer">
                                                &times;
                                            </button>
                                        </div>

                                        <!-- Redeem button if available in active membership -->
                                        @if(!empty($membershipBenefits['remaining_services']) && empty($item['is_membership_redemption']))
                                            @php
                                                $matchQuota = 0;
                                                foreach($membershipBenefits['remaining_services'] as $svc) {
                                                    if (($svc['service_id'] ?? 0) == ($item['service_id'] ?? -1) || strtolower($svc['service_name'] ?? '') === strtolower($item['item_name'] ?? '')) {
                                                        $matchQuota = (int) ($svc['remaining_count'] ?? 0);
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            @if($matchQuota > 0)
                                                <div class="flex items-center justify-between pt-1 border-t border-slate-800/60 text-[11px]">
                                                    <span class="text-amber-400/90 font-medium">Included in plan: {{ $matchQuota }} washes remaining</span>
                                                    <button 
                                                        type="button"
                                                        wire:click="redeemMemberWash({{ $index }})"
                                                        class="px-2.5 py-0.5 bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/40 rounded font-bold cursor-pointer transition"
                                                    >
                                                        Redeem Free Wash (₹0.00)
                                                    </button>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Add Item Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                <div class="flex gap-2">
                                    <select wire:model="selectedServiceToAdd" class="flex-1 bg-slate-900 border border-slate-700 rounded-lg text-xs py-2 px-3 text-slate-200">
                                        <option value="">+ Add Standard Service...</option>
                                        @foreach($services as $srv)
                                            <option value="{{ $srv->id }}">{{ $srv->name }} (₹{{ number_format($srv->getPriceForVehicleType($vehicleType), 0) }})</option>
                                        @endforeach
                                    </select>
                                    <button wire:click="addServiceItem" type="button"
                                            class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 rounded-lg border border-slate-700 transition cursor-pointer">
                                        Add
                                    </button>
                                </div>

                                <div class="flex gap-2">
                                    <input type="text" wire:model="customItemName" placeholder="Custom item / part name"
                                           class="flex-1 bg-slate-900 border border-slate-700 rounded-lg text-xs py-2 px-3 text-slate-200" />
                                    <input type="number" wire:model="customItemPrice" placeholder="₹"
                                           class="w-20 bg-slate-900 border border-slate-700 rounded-lg text-xs py-2 px-3 text-slate-200 font-mono text-right" />
                                    <button wire:click="addCustomItem" type="button"
                                            class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 rounded-lg border border-slate-700 transition cursor-pointer">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Attending Technicians -->
                        <div class="pt-4 border-t border-slate-800">
                            <label class="text-xs font-semibold uppercase tracking-wider text-slate-400 font-mono block mb-2">
                                Attending Detailing Specialists
                            </label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($staffUsers as $staff)
                                    <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border text-xs cursor-pointer transition {{ in_array($staff->id, $assignedStaffIds) ? 'bg-amber-500/10 border-amber-500/40 text-amber-400' : 'bg-slate-900/60 border-slate-800 text-slate-400' }}">
                                        <input type="checkbox" wire:model.live="assignedStaffIds" value="{{ $staff->id }}" class="rounded bg-slate-800 border-slate-700 text-amber-500 focus:ring-0">
                                        <span>{{ $staff->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Discount & Payment Details -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-800">
                            <!-- Discount Box -->
                            <div class="space-y-3">
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400 font-mono block">
                                    Discount Policy
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <select wire:model.live="discountType" class="bg-slate-900 border border-slate-700 rounded-lg text-xs py-2 px-3 text-slate-200">
                                        <option value="none">No Discount</option>
                                        <option value="percentage">Percentage (%)</option>
                                        <option value="fixed">Fixed Flat (₹)</option>
                                    </select>
                                    <input type="number" step="0.01" wire:model.live="discountValue" placeholder="0"
                                           class="bg-slate-900 border border-slate-700 rounded-lg text-xs py-2 px-3 text-white font-mono text-right"
                                           {{ $discountType === 'none' ? 'disabled' : '' }} />
                                </div>

                                @if($discountType !== 'none')
                                    <input type="text" wire:model="discountReason" placeholder="Mandatory discount reason (e.g. Drive Club Member Detailing discount)..."
                                           class="w-full bg-slate-900 border border-slate-700 rounded-lg text-xs py-2 px-3 text-slate-200" />
                                @endif
                            </div>

                            <!-- Payment Method Box -->
                            <div class="space-y-3">
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400 font-mono block">
                                    Payment Method
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <select wire:model="paymentMethod" class="bg-slate-900 border border-slate-700 rounded-lg text-xs py-2 px-3 text-slate-200">
                                        <option value="cash">Cash</option>
                                        <option value="upi">UPI (GPay/Paytm)</option>
                                        <option value="card">Card (POS)</option>
                                        <option value="other">Other / Cheque</option>
                                    </select>
                                    <input type="text" wire:model="paymentReference" placeholder="UPI Txn / Ref #"
                                           class="bg-slate-900 border border-slate-700 rounded-lg text-xs py-2 px-3 text-slate-200 font-mono" />
                                </div>
                                <input type="text" wire:model="staffNotes" placeholder="Technician / Floor notes (optional)..."
                                       class="w-full bg-slate-900 border border-slate-700 rounded-lg text-xs py-2 px-3 text-slate-200" />
                            </div>
                        </div>

                        <!-- Bill Breakdown Live Panel -->
                        <div class="bg-clinical-950/80 border border-slate-800 p-4 rounded-xl space-y-2 font-mono text-xs">
                            <div class="flex justify-between text-slate-400">
                                <span>Subtotal:</span>
                                <span>₹{{ number_format($calculations['subtotal'], 2) }}</span>
                            </div>

                            @if($calculations['discount_amount'] > 0)
                                <div class="flex justify-between text-emerald-400">
                                    <span>Discount ({{ $discountType === 'percentage' ? $discountValue.'%' : 'Flat' }}):</span>
                                    <span>-₹{{ number_format($calculations['discount_amount'], 2) }}</span>
                                </div>
                            @endif

                            @if($calculations['is_gst_enabled'])
                                <div class="flex justify-between text-slate-400">
                                    <span>CGST ({{ $calculations['cgst_rate'] }}%):</span>
                                    <span>₹{{ number_format($calculations['cgst_amount'], 2) }}</span>
                                </div>
                                <div class="flex justify-between text-slate-400">
                                    <span>SGST ({{ $calculations['sgst_rate'] }}%):</span>
                                    <span>₹{{ number_format($calculations['sgst_amount'], 2) }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center pt-2 border-t border-slate-800 text-sm font-bold text-white">
                                <span>Total Amount:</span>
                                <span class="text-amber-400 text-base">₹{{ number_format($calculations['total_amount'], 2) }}</span>
                            </div>
                        </div>

                    @endif

                </div>

                <!-- Modal Footer -->
                @if(!$issuedInvoiceSummary)
                    <div class="px-6 py-4 border-t border-slate-800 bg-clinical-950/60 flex items-center justify-end gap-3">
                        <button wire:click="close" type="button"
                                class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-lg transition cursor-pointer">
                            Cancel
                        </button>
                        <button wire:click="issueInvoice" type="button"
                                class="px-5 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold rounded-lg shadow-md transition flex items-center gap-2 cursor-pointer">
                            <span>Issue Tax Invoice &bull; ₹{{ number_format($calculations['total_amount'], 2) }}</span>
                        </button>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>
