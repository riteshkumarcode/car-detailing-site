<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Authorize that the current user has permission to export CRM data.
     */
    protected function authorizeExport(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isOwner() && !$user->isManager() && !$user->can('export_data'))) {
            abort(403, 'Unauthorized. Only Studio Admins and Managers can export customer data.');
        }
    }

    /**
     * Export all customers to CSV format.
     */
    public function exportCustomers(Request $request): StreamedResponse
    {
        $this->authorizeExport();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="thedriveclinic_customers_' . date('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');

            // Header Row
            fputcsv($handle, [
                'Customer ID',
                'Name',
                'Mobile Number',
                'Email',
                'Area',
                'Referral Source',
                'Referral Code',
                'Date Joined',
                'Tags',
                'Total Completed Visits',
                'Total Lifetime Spend (INR)',
                'Registered Vehicles Count',
            ]);

            Customer::with(['vehicles', 'bookings'])->chunk(250, function ($customers) use ($handle) {
                foreach ($customers as $c) {
                    $completedVisits = $c->bookings->where('status', 'completed')->count();
                    $totalSpend = $c->bookings->where('status', 'completed')->sum('price');
                    $tagsStr = is_array($c->tags) ? implode(', ', $c->tags) : '';

                    fputcsv($handle, [
                        $c->id,
                        $c->name,
                        $c->mobile,
                        $c->email,
                        $c->area,
                        $c->referral_source,
                        $c->referral_code,
                        $c->date_joined ? $c->date_joined->format('Y-m-d') : '',
                        $tagsStr,
                        $completedVisits,
                        $totalSpend,
                        $c->vehicles->count(),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export all vehicles to CSV format.
     */
    public function exportVehicles(Request $request): StreamedResponse
    {
        $this->authorizeExport();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="thedriveclinic_vehicles_' . date('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');

            // Header Row
            fputcsv($handle, [
                'Vehicle ID',
                'Normalized Plate',
                'Formatted Plate',
                'Make',
                'Model',
                'Variant',
                'Body Type',
                'Colour',
                'Customer Name',
                'Customer Mobile',
                'Total Visits',
            ]);

            Vehicle::with(['customer', 'bookings'])->chunk(250, function ($vehicles) use ($handle) {
                foreach ($vehicles as $v) {
                    fputcsv($handle, [
                        $v->id,
                        $v->registration_number,
                        $v->formatted_plate,
                        $v->make,
                        $v->model,
                        $v->variant,
                        $v->vehicle_type,
                        $v->colour,
                        $v->customer->name ?? '',
                        $v->customer->mobile ?? '',
                        $v->bookings->count(),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
