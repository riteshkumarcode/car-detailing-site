<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $galleryItems = GalleryItem::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.gallery', compact('galleryItems'));
    }
}
