<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoice->invoice_number }} — The Drive Clinic</title>
    <style>
        @page {
            margin: 20mm 15mm;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1a1a1a;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
        }
        .brand-title {
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            margin: 0;
        }
        .brand-subtitle {
            font-size: 10px;
            font-weight: 700;
            color: #d97706; /* Amber */
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 3px;
        }
        .clinic-details {
            font-size: 10px;
            color: #475569;
            line-height: 1.3;
            margin-top: 6px;
        }
        .invoice-title-block {
            text-align: right;
        }
        .invoice-main-heading {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
        }
        .invoice-status-badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 3px;
            margin-top: 4px;
        }
        .badge-issued {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-cancelled {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-box {
            width: 50%;
            vertical-align: top;
            padding: 10px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .info-box-title {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 6px;
        }
        .info-box-content {
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
        }
        .plate-box {
            display: inline-block;
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            font-family: monospace;
            font-weight: bold;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 3px;
            margin-top: 3px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            text-align: left;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .items-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .summary-table td {
            padding: 4px 8px;
            font-size: 11px;
        }
        .grand-total-row td {
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            padding: 8px;
            background-color: #f8fafc;
        }
        .notes-section {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8fafc;
            border-left: 3px solid #d97706;
            font-size: 10px;
            color: #475569;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <div class="brand-title">The Drive Clinic</div>
                <div class="brand-subtitle">Your Car's Healthcare Centre</div>
                <div class="clinic-details">
                    Opp. Fire Station, Nanak Nagar, Jammu (J&K) — 180004<br/>
                    Contact: +91 94191 66777 | care@thedriveclinic.in<br/>
                    @if($invoice->is_gst_enabled && $invoice->gstin)
                        <strong>GSTIN: {{ $invoice->gstin }}</strong>
                    @endif
                </div>
            </td>
            <td style="width: 45%; vertical-align: top;" class="invoice-title-block">
                <div class="invoice-main-heading">TAX INVOICE</div>
                <div style="font-size: 13px; font-weight: bold; color: #0f172a; margin-top: 4px;">
                    {{ $invoice->invoice_number }}
                </div>
                <div>
                    @if($invoice->is_cancelled)
                        <span class="invoice-status-badge badge-cancelled">CANCELLED</span>
                    @else
                        <span class="invoice-status-badge badge-issued">PAID &amp; ISSUED</span>
                    @endif
                </div>
                <div style="font-size: 10px; color: #64748b; margin-top: 4px;">
                    Date: {{ $invoice->created_at->format('d M Y, h:i A') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Info Box: Billed To & Vehicle Details -->
    <table class="info-grid">
        <tr>
            <td class="info-box" style="margin-right: 10px;">
                <div class="info-box-title">Billed To (Customer)</div>
                <div class="info-box-content">
                    <strong style="font-size: 12px; color: #0f172a;">{{ $invoice->customer?->name ?? 'Walk-in Customer' }}</strong><br/>
                    Mobile: +91 {{ $invoice->customer?->mobile }}<br/>
                    @if($invoice->customer?->email)
                        Email: {{ $invoice->customer->email }}<br/>
                    @endif
                    Area: {{ $invoice->customer?->area ?? 'Jammu' }}
                </div>
            </td>
            <td style="width: 2%;"></td>
            <td class="info-box">
                <div class="info-box-title">Vehicle Diagnosed &amp; Serviced</div>
                <div class="info-box-content">
                    <div class="plate-box">{{ $invoice->formatted_plate }}</div><br/>
                    <strong>{{ $invoice->vehicle?->make }} {{ $invoice->vehicle?->model }}</strong> ({{ strtoupper($invoice->vehicle?->vehicle_type ?? 'Car') }})<br/>
                    Colour: {{ $invoice->vehicle?->colour ?? 'Standard' }}<br/>
                    Payment: <strong>{{ strtoupper($invoice->payment_method) }}</strong>
                    @if($invoice->payment_reference)
                        ({{ $invoice->payment_reference }})
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 50%;">Service / Treatment Description</th>
                <th style="width: 15%;" class="text-center">Qty</th>
                <th style="width: 15%;" class="text-right">Rate (₹)</th>
                <th style="width: 15%;" class="text-right">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->item_name }}</strong>
                        <div style="font-size: 9px; color: #64748b; text-transform: uppercase;">{{ $item->item_type }}</div>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Breakdown -->
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                @if(!empty($invoice->assigned_staff_names))
                    <div style="font-size: 10px; color: #475569; margin-bottom: 6px;">
                        <strong>Attending Specialists:</strong> {{ implode(', ', $invoice->assigned_staff_names) }}
                    </div>
                @endif
                @if($invoice->staff_notes)
                    <div class="notes-section">
                        <strong>Clinical Observations / Notes:</strong><br/>
                        {{ $invoice->staff_notes }}
                    </div>
                @endif
                @if($invoice->is_cancelled)
                    <div class="notes-section" style="border-left-color: #ef4444; background-color: #fef2f2; color: #991b1b;">
                        <strong>CANCELLATION RECORD:</strong><br/>
                        Reason: {{ $invoice->cancellation_reason }}<br/>
                        Cancelled on: {{ $invoice->cancelled_at?->format('d M Y, h:i A') }}
                    </div>
                @endif
            </td>
            <td style="width: 50%; vertical-align: top;">
                <table class="summary-table">
                    <tr>
                        <td class="text-right" style="color: #475569;">Subtotal:</td>
                        <td class="text-right" style="width: 40%; font-weight: 600;">₹{{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    @if($invoice->discount_amount > 0)
                        <tr>
                            <td class="text-right" style="color: #15803d;">
                                Discount @if($invoice->discount_type === 'percentage')({{ $invoice->discount_value }}%)@endif:
                                @if($invoice->discount_reason)
                                    <br/><span style="font-size: 9px; color: #64748b;">({{ $invoice->discount_reason }})</span>
                                @endif
                            </td>
                            <td class="text-right" style="color: #15803d; font-weight: 600;">-₹{{ number_format($invoice->discount_amount, 2) }}</td>
                        </tr>
                    @endif

                    @if($invoice->is_gst_enabled)
                        <tr>
                            <td class="text-right" style="color: #475569;">CGST ({{ $invoice->cgst_rate }}%):</td>
                            <td class="text-right" style="font-weight: 600;">₹{{ number_format($invoice->cgst_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right" style="color: #475569;">SGST ({{ $invoice->sgst_rate }}%):</td>
                            <td class="text-right" style="font-weight: 600;">₹{{ number_format($invoice->sgst_amount, 2) }}</td>
                        </tr>
                    @endif

                    <tr class="grand-total-row">
                        <td class="text-right">TOTAL AMOUNT:</td>
                        <td class="text-right">₹{{ number_format($invoice->total_amount, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <table class="footer-table">
        <tr>
            <td style="width: 70%;">
                <strong>Thank you for choosing The Drive Clinic.</strong><br/>
                All detailing and protective treatments are logged to your digital <strong>Car Passport</strong>.<br/>
                For warranty inquiries and maintenance washes, visit <em>thedriveclinic.in</em> or call +91 94191 66777.
            </td>
            <td style="width: 30%; text-align: right;">
                <div style="border-top: 1px solid #94a3b8; width: 140px; display: inline-block; padding-top: 4px; text-align: center;">
                    Authorised Signatory
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
