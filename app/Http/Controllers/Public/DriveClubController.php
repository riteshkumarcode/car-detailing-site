<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\View\View;

class DriveClubController extends Controller
{
    public function index(): View
    {
        $plans = MembershipPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.drive-club', compact('plans'));
    }
}
