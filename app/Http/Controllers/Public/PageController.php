<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }
}
