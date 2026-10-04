<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $categories = ServiceCategory::with(['services' => function ($query) {
            $query->where('is_active', true)->orderBy('sort_order');
        }])->where('is_active', true)->orderBy('sort_order')->get();

        $allServices = Service::with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.services.index', compact('categories', 'allServices'));
    }

    public function show(string $slug): View
    {
        $service = Service::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedServices = Service::where('service_category_id', $service->service_category_id)
            ->where('id', '!=', $service->id)
            ->where('is_active', true)
            ->take(3)
            ->get();

        return view('pages.services.show', compact('service', 'relatedServices'));
    }
}
