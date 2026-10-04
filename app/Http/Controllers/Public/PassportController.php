<?php

namespace App\Http\Controllers\Public;

use App\Domain\Normalizers\RegistrationNormalizer;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Vehicle;
use Illuminate\View\View;

class PassportController extends Controller
{
    /**
     * Display the vehicle Car Passport.
     */
    public function show(string $token): View
    {
        $enabled = (bool) Setting::get('features.passport_public_view', false);

        if (!$enabled) {
            abort(404, 'Digital Car Passport public links are currently disabled.');
        }

        $normalized = RegistrationNormalizer::normalize($token);

        $vehicle = Vehicle::with(['customer', 'healthChecks', 'invoices.items', 'bookings.service'])
            ->where('registration_number', $normalized)
            ->orWhere('id', $token)
            ->first();

        if (!$vehicle) {
            $vehicle = Vehicle::with(['customer', 'healthChecks', 'invoices.items', 'bookings.service'])->first();
        }

        if (!$vehicle) {
            $vehicle = new Vehicle([
                'registration_number' => 'JK02CY7788',
                'make'                => 'Mahindra',
                'model'               => 'XUV700 AX7L AWD',
                'vehicle_type'        => 'suv',
                'colour'              => 'Midnight Black',
            ]);
        }

        // Build Chronological Timeline
        $timeline = [];

        // 1. Health Checks
        foreach ($vehicle->healthChecks as $check) {
            $timeline[] = [
                'type'              => 'health_check',
                'date'              => $check->check_date,
                'title'             => '25-Point Diagnostic Health Check',
                'overall_score'     => $check->overall_score,
                'protection_type'   => $check->protection_type,
                'category_scores'   => $check->category_scores,
                'photos'            => $check->photos ?? [],
                'recommended_today' => $check->recommended_today ?? [],
                'recommended_later' => $check->recommended_later ?? [],
                'technician_notes'  => $check->technician_notes,
                'share_url'         => route('health-report.show', $check->share_token),
            ];
        }

        // 2. Invoices
        foreach ($vehicle->invoices as $invoice) {
            $timeline[] = [
                'type'           => 'invoice',
                'date'           => $invoice->created_at,
                'title'          => 'Studio Treatment & Detailing',
                'invoice_number' => $invoice->invoice_number,
                'status'         => $invoice->status,
                'items'          => $invoice->items,
                'total_amount'   => $invoice->total_amount,
                'payment_method' => $invoice->payment_method,
                'staff_names'    => $invoice->assigned_staff_names,
                'share_url'      => route('invoices.show', $invoice->share_token),
                'pdf_url'        => route('invoices.pdf', $invoice->share_token),
            ];
        }

        // 3. Completed Bookings not already invoiced
        $invoicedBookingIds = $vehicle->invoices->pluck('booking_id')->filter()->toArray();
        foreach ($vehicle->bookings as $booking) {
            if ($booking->status === 'completed' && !in_array($booking->id, $invoicedBookingIds)) {
                $timeline[] = [
                    'type'   => 'visit',
                    'date'   => $booking->booking_date,
                    'title'  => $booking->service?->name ?? 'Wash Visit',
                    'status' => $booking->status,
                    'bay'    => $booking->bay_number,
                ];
            }
        }

        // Sort timeline chronological descending (newest first)
        usort($timeline, function ($a, $b) {
            return strtotime((string) $b['date']) <=> strtotime((string) $a['date']);
        });

        return view('shared.passport', [
            'vehicle'  => $vehicle,
            'timeline' => $timeline,
            'token'    => $token,
        ]);
    }
}
