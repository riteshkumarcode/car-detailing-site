<div class="space-y-6">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-xl border border-line/60 shadow-xs">
        <div>
            <h1 class="text-2xl font-black font-display text-ink tracking-tight">Drive Club Plans Administration</h1>
            <p class="text-sm text-muted">Manage studio membership packages, included washes, discounts, and pricing tiers.</p>
        </div>
        <button 
            wire:click="openCreate"
            type="button"
            class="px-5 py-2.5 bg-amber hover:bg-amber/90 text-ink font-bold rounded-lg transition shadow-xs flex items-center gap-2 cursor-pointer text-sm"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Create New Plan
        </button>
    </div>

    @if($feedbackMessage)
        <div class="p-4 bg-teal/10 border border-teal/30 text-teal-deep rounded-lg text-sm font-semibold flex items-center justify-between">
            <span>{{ $feedbackMessage }}</span>
            <button wire:click="$set('feedbackMessage', null)" class="text-teal hover:underline text-xs cursor-pointer">Dismiss</button>
        </div>
    @endif

    <!-- Plans Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($plans as $plan)
            <div class="bg-white rounded-xl border {{ $plan->is_active ? ($plan->is_featured ? 'border-amber shadow-md' : 'border-line/70 shadow-xs') : 'border-line/40 opacity-75 bg-mist/30' }} p-6 flex flex-col justify-between relative transition">
                @if($plan->is_featured)
                    <div class="absolute -top-3 right-6 bg-amber text-ink text-[11px] font-black uppercase px-3 py-0.5 rounded-full shadow-xs">
                        Most Popular
                    </div>
                @endif

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2.5 h-2.5 rounded-full {{ $plan->is_active ? 'bg-teal' : 'bg-brick' }}"></span>
                            <span class="text-xs font-bold uppercase tracking-wider {{ $plan->is_active ? 'text-teal' : 'text-brick' }}">
                                {{ $plan->is_active ? 'Active Plan' : 'Deactivated' }}
                            </span>
                        </div>
                        <span class="text-xs text-muted font-medium capitalize">{{ $plan->billing_frequency }} ({{ $plan->duration_days }}d)</span>
                    </div>

                    <h2 class="text-xl font-black font-display text-ink">{{ $plan->name }}</h2>
                    <div class="mt-2 mb-4">
                        <span class="text-3xl font-black font-display text-teal-deep">₹{{ number_format($plan->price, 0) }}</span>
                        <span class="text-xs text-muted">/ {{ $plan->billing_frequency === 'yearly' ? 'year' : 'term' }}</span>
                    </div>

                    <p class="text-xs text-muted mb-4 line-clamp-2">{{ $plan->benefits_description }}</p>

                    <!-- Included Services -->
                    <div class="border-t border-line/40 pt-3 mb-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-ink mb-2">Included Treatments:</h4>
                        @if(!empty($plan->included_services))
                            <ul class="space-y-1.5 text-xs text-ink/90">
                                @foreach($plan->included_services as $svc)
                                    <li class="flex items-center justify-between">
                                        <span>{{ $svc['service_name'] ?? 'Service' }}</span>
                                        <span class="font-bold text-teal bg-mint/50 px-2 py-0.5 rounded text-[11px]">{{ $svc['count'] ?? 1 }}x / yr</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-xs text-muted italic">No free service allowance defined.</p>
                        @endif
                    </div>

                    <!-- Category Discounts -->
                    @if(!empty($plan->category_discounts))
                        <div class="border-t border-line/40 pt-3 mb-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-ink mb-1.5">Category Discounts:</h4>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($plan->category_discounts as $cat => $pct)
                                    <span class="text-[11px] font-semibold bg-amber/15 text-ink px-2 py-0.5 rounded border border-amber/30">
                                        {{ ucfirst($cat) }}: {{ $pct }}% off
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer Actions -->
                <div class="border-t border-line/50 pt-4 mt-2 flex items-center justify-between gap-2">
                    <button 
                        wire:click="toggleActive({{ $plan->id }})"
                        type="button"
                        class="text-xs font-bold px-3 py-1.5 rounded border {{ $plan->is_active ? 'border-brick text-brick hover:bg-brick/10' : 'border-teal text-teal hover:bg-teal/10' }} cursor-pointer transition"
                    >
                        {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                    </button>

                    <button 
                        wire:click="openEdit({{ $plan->id }})"
                        type="button"
                        class="text-xs font-bold px-3 py-1.5 bg-mist hover:bg-mint text-ink rounded border border-line cursor-pointer transition"
                    >
                        Edit Details
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Plan Edit / Create Modal -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-ink/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 md:p-8 shadow-2xl border border-line space-y-6 animate-in fade-in zoom-in duration-150">
                <div class="flex items-center justify-between border-b border-line/60 pb-4">
                    <h3 class="text-xl font-black font-display text-ink">
                        {{ $editingPlanId ? 'Edit Membership Plan' : 'Create New Drive Club Plan' }}
                    </h3>
                    <button wire:click="$set('isModalOpen', false)" class="text-muted hover:text-ink text-xl font-bold">&times;</button>
                </div>

                <div class="space-y-4 max-h-[65vh] overflow-y-auto pr-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-ink mb-1">Plan Name *</label>
                            <input type="text" wire:model="name" class="w-full px-3 py-2 border border-line rounded-lg text-sm focus:ring-2 focus:ring-teal" placeholder="e.g. Premium Healthcare Plan">
                            @error('name') <span class="text-xs text-brick font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-ink mb-1">Price (₹) *</label>
                            <input type="number" step="1" wire:model="price" class="w-full px-3 py-2 border border-line rounded-lg text-sm focus:ring-2 focus:ring-teal" placeholder="4999">
                            @error('price') <span class="text-xs text-brick font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-ink mb-1">Duration (Days)</label>
                            <input type="number" wire:model="duration_days" class="w-full px-3 py-2 border border-line rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-ink mb-1">Billing Frequency</label>
                            <select wire:model="billing_frequency" class="w-full px-3 py-2 border border-line rounded-lg text-sm">
                                <option value="yearly">Yearly</option>
                                <option value="half-yearly">Half-Yearly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-ink mb-1">Sort Order</label>
                            <input type="number" wire:model="sort_order" class="w-full px-3 py-2 border border-line rounded-lg text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-ink mb-1">Benefits Description</label>
                        <textarea wire:model="benefits_description" rows="2" class="w-full px-3 py-2 border border-line rounded-lg text-sm" placeholder="Summary of who this plan is tailored for..."></textarea>
                    </div>

                    <!-- Included Services Builder -->
                    <div class="p-4 bg-mist/50 border border-line/70 rounded-xl space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-ink">Included Services (Allowance Quota)</h4>
                        
                        <div class="flex items-center gap-2">
                            <select wire:model="addServiceId" class="flex-1 px-3 py-2 border border-line rounded-lg text-xs bg-white">
                                <option value="">Select a service to include...</option>
                                @foreach($services as $svc)
                                    <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" wire:model="addServiceCount" min="1" class="w-20 px-3 py-2 border border-line rounded-lg text-xs bg-white text-center" placeholder="Count">
                            <button wire:click="addIncludedService" type="button" class="px-3 py-2 bg-teal text-white font-bold rounded-lg text-xs cursor-pointer">
                                Add
                            </button>
                        </div>

                        @if(!empty($included_services))
                            <div class="space-y-1.5 pt-2">
                                @foreach($included_services as $index => $item)
                                    <div class="flex items-center justify-between bg-white px-3 py-1.5 rounded-lg border border-line/60 text-xs">
                                        <span class="font-medium text-ink">{{ $item['service_name'] }}</span>
                                        <div class="flex items-center gap-3">
                                            <span class="font-bold text-teal bg-mint/50 px-2 py-0.5 rounded">{{ $item['count'] }} washes</span>
                                            <button wire:click="removeIncludedService({{ $index }})" type="button" class="text-brick hover:underline font-bold">&times;</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Category Discounts Builder -->
                    <div class="p-4 bg-mist/50 border border-line/70 rounded-xl space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-ink">Category Discounts (% off)</h4>
                        <div class="flex items-center gap-2">
                            <select wire:model="addDiscountCategory" class="flex-1 px-3 py-2 border border-line rounded-lg text-xs bg-white">
                                <option value="detailing">Detailing</option>
                                <option value="protection">Protection & Coatings</option>
                                <option value="interior">Interior Deep Clean</option>
                                <option value="wash">Car Wash Treatments</option>
                            </select>
                            <input type="number" wire:model="addDiscountPercent" min="1" max="100" class="w-24 px-3 py-2 border border-line rounded-lg text-xs bg-white text-center" placeholder="% Off">
                            <button wire:click="addCategoryDiscount" type="button" class="px-3 py-2 bg-teal text-white font-bold rounded-lg text-xs cursor-pointer">
                                Add Discount
                            </button>
                        </div>

                        @if(!empty($category_discounts))
                            <div class="flex flex-wrap gap-2 pt-2">
                                @foreach($category_discounts as $cat => $pct)
                                    <span class="inline-flex items-center gap-1.5 bg-amber/20 text-ink text-xs font-bold px-2.5 py-1 rounded-md border border-amber/40">
                                        {{ ucfirst($cat) }}: {{ $pct }}%
                                        <button wire:click="removeCategoryDiscount('{{ $cat }}')" class="text-brick font-black hover:scale-110">&times;</button>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Toggles -->
                    <div class="flex items-center gap-6 pt-2">
                        <label class="flex items-center gap-2 text-xs font-bold text-ink cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-teal rounded border-line">
                            Active (Available for Sale)
                        </label>
                        <label class="flex items-center gap-2 text-xs font-bold text-ink cursor-pointer">
                            <input type="checkbox" wire:model="is_featured" class="w-4 h-4 text-amber rounded border-line">
                            Featured Plan (Highlight on Website)
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-line/60 pt-4">
                    <button wire:click="$set('isModalOpen', false)" type="button" class="px-4 py-2 border border-line text-ink text-xs font-bold rounded-lg hover:bg-mist cursor-pointer">
                        Cancel
                    </button>
                    <button wire:click="savePlan" type="button" class="px-6 py-2 bg-amber hover:bg-amber/90 text-ink text-xs font-bold rounded-lg transition shadow-xs cursor-pointer">
                        {{ $editingPlanId ? 'Save Changes' : 'Create Plan' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
