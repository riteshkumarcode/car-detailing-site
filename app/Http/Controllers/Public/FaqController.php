<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $categories = [
            'all'          => 'All Questions',
            'general'      => 'General & Philosophy',
            'wash'         => 'Foam Washing',
            'detailing'    => 'Paint Correction',
            'protection'   => 'Ceramic & Graphene',
            'health_check' => 'Health Check & Scoring',
            'membership'   => 'Drive Club',
        ];

        return view('pages.faq', compact('faqs', 'categories'));
    }
}
