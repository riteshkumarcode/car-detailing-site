@extends('layouts.public', ['title' => 'Invoice ' . $invoice->invoice_number . ' — The Drive Clinic'])

@section('content')
<div class="py-12 bg-clinical-950 min-h-screen text-slate-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Top Actions Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8 bg-clinical-900/80 backdrop-blur border border-slate-800 p-4 rounded-xl shadow-lg">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="text-xs text-amber-500 hover:text-amber-400 font-mono transition flex items-center gap-1">
                    ← Back to Studio
                </a>
                <span class="text-slate-600">|</span>
                <span class="text-xs text-slate-400">Digital Tax Invoice</span>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('invoices.pdf', $invoice->share_token) }}" 
                   class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-lg border border-slate-700 transition">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>

                @php
                    $waText = urlencode("Hello {$invoice->customer?->name}, your invoice {$invoice->invoice_number} from The Drive Clinic is ready: " . route('invoices.show', $invoice->share_token));
                    $waUrl = "https://wa.me/91" . ($invoice->customer?->mobile ?? '') . "?text=" . $waText;
                @endphp
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                   class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.97.529 1.777.781 2.796.781 3.182 0 5.768-2.587 5.768-5.766 0-3.18-2.586-5.767-5.768-5.767zm7.42 5.766c0 4.092-3.328 7.42-7.42 7.42-1.309 0-2.531-.341-3.601-.937l-4.43 1.162 1.183-4.32c-.675-1.127-1.062-2.441-1.062-3.825 0-4.092 3.328-7.42 7.42-7.42 4.092 0 7.42 3.328 7.42 7.42z"/>
                    </svg>
                    Share WhatsApp
                </a>
            </div>
        </div>

        <!-- Invoice Card Container -->
        <div class="bg-clinical-900 border border-slate-800 rounded-2xl shadow-2xl overflow-hidden p-6 sm:p-10">
            
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start pb-8 border-b border-slate-800 gap-6">
                <div>
                    <span class="text-xs font-mono uppercase tracking-widest text-amber-500 font-semibold">The Drive Clinic</span>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white mt-1">Tax Invoice</h1>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                        Opp. Fire Station, Nanak Nagar, Jammu (J&K) — 180004<br/>
                        Helpline: +91 94191 66777 | care@thedriveclinic.in
                        @if($invoice->is_gst_enabled && $invoice->gstin)
                            <br/><strong class="text-slate-300">GSTIN: {{ $invoice->gstin }}</strong>
                        @endif
                    </p>
                </div>
                
                <div class="sm:text-right flex flex-col sm:items-end">
                    <div class="inline-block px-3 py-1 rounded font-mono text-sm font-bold bg-slate-800 border border-slate-700 text-amber-400">
                        {{ $invoice->invoice_number }}
                    </div>
                    
                    <div class="mt-2">
                        @if($invoice->is_cancelled)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-950 text-red-400 border border-red-800">
                                CANCELLED
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-950 text-emerald-400 border border-emerald-800">
                                PAID &amp; ISSUED
                            </span>
                        @endif
                    </div>

                    <p class="text-xs text-slate-400 mt-2 font-mono">
                        Date: {{ $invoice->created_at->format('d M Y, h:i A') }}
                    </p>
                </div>
            </div>

            <!-- Client & Vehicle Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-8 p-5 bg-clinical-950/60 rounded-xl border border-slate-800/80">
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Billed To</h3>
                    <p class="text-base font-bold text-white">{{ $invoice->customer?->name ?? 'Walk-in Customer' }}</p>
                    <p class="text-xs text-slate-300 font-mono mt-1">+91 {{ $invoice->customer?->mobile }}</p>
                    @if($invoice->customer?->email)
                        <p class="text-xs text-slate-400">{{ $invoice->customer->email }}</p>
                    @endif
                    <p class="text-xs text-slate-400 mt-0.5">{{ $invoice->customer?->area ?? 'Jammu' }}</p>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Vehicle Serviced</h3>
                    <div class="flex items-center gap-3">
                        <span class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-mono font-bold text-sm rounded">
                            {{ $invoice->formatted_plate }}
                        </span>
                        <div>
                            <p class="text-sm font-bold text-white">{{ $invoice->vehicle?->make }} {{ $invoice->vehicle?->model }}</p>
                            <p class="text-xs text-slate-400 capitalize">{{ $invoice->vehicle?->vehicle_type ?? 'Car' }} &bull; {{ $invoice->vehicle?->colour ?? 'Standard' }}</p>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-slate-400">
                        Payment: <strong class="text-white uppercase">{{ $invoice->payment_method }}</strong>
                        @if($invoice->payment_reference)
                            <span class="font-mono text-slate-300">({{ $invoice->payment_reference }})</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Line Items Table -->
            <div class="overflow-x-auto my-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-xs font-semibold uppercase tracking-wider text-slate-400">
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">Treatment / Service</th>
                            <th class="py-3 px-4 text-center">Qty</th>
                            <th class="py-3 px-4 text-right">Unit Rate</th>
                            <th class="py-3 px-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-sm">
                        @foreach($invoice->items as $idx => $item)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="py-3.5 px-4 font-mono text-xs text-slate-500">{{ $idx + 1 }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-white">{{ $item->item_name }}</div>
                                    <div class="text-[11px] text-slate-500 uppercase">{{ $item->item_type }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono">{{ $item->quantity }}</td>
                                <td class="py-3.5 px-4 text-right font-mono text-slate-300">₹{{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3.5 px-4 text-right font-mono font-semibold text-white">₹{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Financials Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-slate-800">
                <div class="space-y-4">
                    @if(!empty($invoice->assigned_staff_names))
                        <div>
                            <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Attending Technicians:</span>
                            <p class="text-xs text-slate-300 mt-1">{{ implode(', ', $invoice->assigned_staff_names) }}</p>
                        </div>
                    @endif

                    @if($invoice->staff_notes)
                        <div class="p-3.5 bg-slate-950/70 border-l-2 border-amber-500 rounded-r-lg text-xs text-slate-300">
                            <strong class="text-slate-200 block mb-1">Clinical Notes:</strong>
                            {{ $invoice->staff_notes }}
                        </div>
                    @endif

                    @if($invoice->is_cancelled)
                        <div class="p-3.5 bg-red-950/40 border-l-2 border-red-500 rounded-r-lg text-xs text-red-300">
                            <strong class="text-red-200 block mb-1">CANCELLATION RECORD:</strong>
                            Reason: {{ $invoice->cancellation_reason }}<br/>
                            Cancelled on: {{ $invoice->cancelled_at?->format('d M Y, h:i A') }}
                        </div>
                    @endif
                </div>

                <div class="space-y-2.5">
                    <div class="flex justify-between text-xs text-slate-400 font-mono">
                        <span>Subtotal:</span>
                        <span>₹{{ number_format($invoice->subtotal, 2) }}</span>
                    </div>

                    @if($invoice->discount_amount > 0)
                        <div class="flex justify-between text-xs text-emerald-400 font-mono">
                            <span>
                                Discount @if($invoice->discount_type === 'percentage')({{ $invoice->discount_value }}%)@endif:
                                @if($invoice->discount_reason)
                                    <span class="text-[11px] text-slate-500">({{ $invoice->discount_reason }})</span>
                                @endif
                            </span>
                            <span>-₹{{ number_format($invoice->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    @if($invoice->is_gst_enabled)
                        <div class="flex justify-between text-xs text-slate-400 font-mono">
                            <span>CGST ({{ $invoice->cgst_rate }}%):</span>
                            <span>₹{{ number_format($invoice->cgst_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400 font-mono">
                            <span>SGST ({{ $invoice->sgst_rate }}%):</span>
                            <span>₹{{ number_format($invoice->sgst_amount, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center pt-3 border-t border-slate-800 text-base font-bold text-white">
                        <span>Grand Total Paid:</span>
                        <span class="text-amber-400 font-mono text-xl">₹{{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
