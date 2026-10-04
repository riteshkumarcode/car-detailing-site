<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\MembershipPlan;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $services = Service::with('category')
            ->where('is_active', true)
            ->where('show_on_home', true)
            ->orderBy('sort_order')
            ->get();

        $plans = MembershipPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $galleryItems = GalleryItem::where('is_active', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $faqs = Faq::where('is_active', true)
            ->where('show_on_home', true)
            ->orderBy('sort_order')
            ->take(5)
            ->get();

        return view('pages.home', compact(
            'services',
            'plans',
            'galleryItems',
            'testimonials',
            'faqs'
        ));
    }

    public function healthCheck(): View
    {
        return view('pages.health-check');
    }

    public function book(): View
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        return view('pages.book', compact('services'));
    }
}
