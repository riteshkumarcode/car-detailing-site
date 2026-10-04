<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class InvoiceService
{
    public function __construct(
        protected InvoiceNumberGenerator $numberGenerator
    ) {}

    /**
     * Compute subtotal, discount, taxes and grand total.
     */
    public function calculateTotals(
        array $items,
        string $discountType = 'none',
        float $discountValue = 0.0,
        bool $gstEnabled = false,
        float $gstRate = 18.00
    ): array {
        $subtotal = 0.0;
        foreach ($items as $item) {
            $qty = max(1, (int) ($item['quantity'] ?? 1));
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $subtotal += ($qty * $unitPrice);
        }

        $discountAmount = 0.0;
        if ($discountType === 'percentage') {
            $discountAmount = ($subtotal * ($discountValue / 100));
        } elseif ($discountType === 'fixed') {
            $discountAmount = min($subtotal, $discountValue);
        }
        $discountAmount = round(max(0, $discountAmount), 2);

        $discountedSubtotal = max(0, $subtotal - $discountAmount);

        $cgstRate = 0.0;
        $sgstRate = 0.0;
        $cgstAmount = 0.0;
        $sgstAmount = 0.0;
        $totalTax = 0.0;

        if ($gstEnabled) {
            $cgstRate = round($gstRate / 2, 2);
            $sgstRate = round($gstRate / 2, 2);
            $cgstAmount = round($discountedSubtotal * ($cgstRate / 100), 2);
            $sgstAmount = round($discountedSubtotal * ($sgstRate / 100), 2);
            $totalTax = round($cgstAmount + $sgstAmount, 2);
        }

        $totalAmount = round($discountedSubtotal + $totalTax, 2);

        return [
            'subtotal'            => round($subtotal, 2),
            'discount_type'       => $discountType,
            'discount_value'      => round($discountValue, 2),
            'discount_amount'     => $discountAmount,
            'discounted_subtotal' => round($discountedSubtotal, 2),
            'is_gst_enabled'      => $gstEnabled,
            'cgst_rate'           => $cgstRate,
            'sgst_rate'           => $sgstRate,
            'cgst_amount'         => $cgstAmount,
            'sgst_amount'         => $sgstAmount,
            'total_tax'           => $totalTax,
            'total_amount'        => $totalAmount,
        ];
    }

    /**
     * Validate discount permissions by user role.
     */
    public function validateDiscountPermission(?User $user, string $discountType, float $discountValue, float $discountAmount, ?string $reason): void
    {
        if ($discountAmount > 0 || $discountValue > 0) {
            if (empty(trim((string) $reason))) {
                throw new InvalidArgumentException("A reason is mandatory for any applied discount.");
            }

            if ($user) {
                // Check role limits
                if ($user->isStaff() && !$user->isManager() && !$user->isOwner()) {
                    if ($discountType === 'percentage' && $discountValue > 10.0) {
                        throw new InvalidArgumentException("Staff role is restricted to a maximum 10% discount.");
                    }
                    if ($discountType === 'fixed' && $discountAmount > 500.0) {
                        throw new InvalidArgumentException("Staff role is restricted to a maximum ₹500 discount.");
                    }
                } elseif ($user->isManager() && !$user->isOwner()) {
                    if ($discountType === 'percentage' && $discountValue > 25.0) {
                        throw new InvalidArgumentException("Studio Manager role is restricted to a maximum 25% discount.");
                    }
                    if ($discountType === 'fixed' && $discountAmount > 2000.0) {
                        throw new InvalidArgumentException("Studio Manager role is restricted to a maximum ₹2,000 discount.");
                    }
                }
            }
        }
    }

    /**
     * Create and issue an invoice in an atomic transaction.
     */
    public function createInvoice(array $data, ?User $issuer = null): Invoice
    {
        $items = $data['items'] ?? [];
        if (empty($items)) {
            throw new InvalidArgumentException("An invoice must contain at least one line item.");
        }

        $branchId = (int) ($data['branch_id'] ?? 1);
        $discountType = $data['discount_type'] ?? 'none';
        $discountValue = (float) ($data['discount_value'] ?? 0);
        $discountReason = $data['discount_reason'] ?? null;

        // Fetch active GST configuration from settings if not overridden
        $gstEnabled = array_key_exists('is_gst_enabled', $data)
            ? (bool) $data['is_gst_enabled']
            : (bool) Setting::get('gst.enabled', false);

        $gstRate = (float) ($data['gst_rate'] ?? Setting::get('gst.rate', 18.00));
        $gstin = $data['gstin'] ?? (string) Setting::get('gst.gstin', '01AAAAA0000A1Z5');

        $calc = $this->calculateTotals($items, $discountType, $discountValue, $gstEnabled, $gstRate);

        // Validate discount permissions
        $this->validateDiscountPermission($issuer, $discountType, $discountValue, $calc['discount_amount'], $discountReason);

        return DB::transaction(function () use ($data, $branchId, $items, $calc, $issuer, $gstin, $discountReason) {
            $numData = $this->numberGenerator->generateNextNumber($branchId, $data['date'] ?? null);

            $invoice = Invoice::create([
                'branch_id'           => $branchId,
                'invoice_number'      => $numData['invoice_number'],
                'financial_year'      => $numData['financial_year'],
                'sequence_number'     => $numData['sequence_number'],
                'customer_id'         => $data['customer_id'],
                'vehicle_id'          => $data['vehicle_id'],
                'booking_id'          => $data['booking_id'] ?? null,
                'issued_by_user_id'   => $issuer?->id ?? $data['issued_by_user_id'] ?? 1,
                'status'              => 'issued',
                'payment_method'      => $data['payment_method'] ?? 'cash',
                'payment_reference'   => $data['payment_reference'] ?? null,
                'staff_notes'         => $data['staff_notes'] ?? null,
                'assigned_staff_ids'  => $data['assigned_staff_ids'] ?? [],
                'subtotal'            => $calc['subtotal'],
                'discount_type'       => $calc['discount_type'],
                'discount_value'      => $calc['discount_value'],
                'discount_amount'     => $calc['discount_amount'],
                'discount_reason'     => $discountReason,
                'is_gst_enabled'      => $calc['is_gst_enabled'],
                'gstin'               => $calc['is_gst_enabled'] ? $gstin : null,
                'cgst_rate'           => $calc['cgst_rate'],
                'sgst_rate'           => $calc['sgst_rate'],
                'cgst_amount'         => $calc['cgst_amount'],
                'sgst_amount'         => $calc['sgst_amount'],
                'total_tax'           => $calc['total_tax'],
                'total_amount'        => $calc['total_amount'],
            ]);

            foreach ($items as $item) {
                $qty = max(1, (int) ($item['quantity'] ?? 1));
                $unitPrice = (float) ($item['unit_price'] ?? 0);
                $totalPrice = round($qty * $unitPrice, 2);

                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'service_id'  => $item['service_id'] ?? null,
                    'item_name'   => $item['name'] ?? $item['item_name'] ?? 'Service',
                    'item_type'   => $item['item_type'] ?? 'service',
                    'quantity'    => $qty,
                    'unit_price'  => $unitPrice,
                    'total_price' => $totalPrice,
                ]);
            }

            // If attached to a booking, transition booking to completed
            if (!empty($data['booking_id'])) {
                $booking = Booking::find($data['booking_id']);
                if ($booking && $booking->status !== 'completed') {
                    $booking->update(['status' => 'completed']);
                    if (method_exists($booking, 'statusLogs')) {
                        $booking->statusLogs()->create([
                            'from_status' => $booking->getOriginal('status'),
                            'to_status'   => 'completed',
                            'changed_by'  => $issuer?->id,
                            'notes'       => "Completed with Invoice {$invoice->invoice_number}",
                        ]);
                    }
                }
            }

            return $invoice->load(['items', 'customer', 'vehicle', 'issuedBy']);
        });
    }

    /**
     * Cancel an issued invoice with a required reason.
     */
    public function cancelInvoice(Invoice $invoice, string $reason, ?User $user = null): Invoice
    {
        if ($invoice->status === 'cancelled') {
            throw new RuntimeException("Invoice {$invoice->invoice_number} is already cancelled.");
        }

        if (empty(trim($reason))) {
            throw new InvalidArgumentException("A cancellation reason is required to cancel an invoice.");
        }

        $invoice->update([
            'status'               => 'cancelled',
            'cancellation_reason'  => trim($reason),
            'cancelled_by_user_id' => $user?->id,
            'cancelled_at'         => Carbon::now(),
        ]);

        return $invoice;
    }

    /**
     * Reissue a cancelled invoice.
     */
    public function reissueInvoice(Invoice $cancelledInvoice, array $newData, ?User $user = null): Invoice
    {
        if ($cancelledInvoice->status !== 'cancelled') {
            throw new RuntimeException("Only cancelled invoices can be reissued.");
        }

        $newInvoice = $this->createInvoice($newData, $user);

        $cancelledInvoice->update([
            'reissued_invoice_id' => $newInvoice->id,
        ]);

        return $newInvoice;
    }
}
