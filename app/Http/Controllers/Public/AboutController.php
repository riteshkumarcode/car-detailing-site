<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $weights = Setting::get('health_check.weights', [
            'exterior' => 30,
            'interior' => 30,
            'wheels' => 15,
            'glass' => 15,
            'protection' => 10,
        ]);

        $disclaimer = Setting::get('health_check.disclaimer', 'The Drive Health Score describes cosmetic condition only. It is not a certified mechanical, roadworthiness or safety inspection.');

        return view('pages.about', compact('weights', 'disclaimer'));
    }
}
