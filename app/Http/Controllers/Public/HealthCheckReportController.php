<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HealthCheck;
use Illuminate\Http\Request;

class HealthCheckReportController extends Controller
{
    /**
     * Display the verified digital health report card for a vehicle.
     */
    public function show(string $token)
    {
        $healthCheck = HealthCheck::with(['vehicle.customer', 'branch', 'user'])
            ->where('share_token', $token)
            ->firstOrFail();

        return view('pages.health-report', [
            'check' => $healthCheck,
            'disclaimer' => HealthCheck::getMandatoryDisclaimer(),
        ]);
    }
}
