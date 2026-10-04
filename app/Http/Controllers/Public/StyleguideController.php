<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StyleguideController extends Controller
{
    public function index(): View
    {
        return view('pages.styleguide');
    }
}
