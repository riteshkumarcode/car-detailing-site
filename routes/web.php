<?php

use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\DriveClubController;
use App\Http\Controllers\Public\FaqController;
use App\Http\Controllers\Public\GalleryController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\PassportController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Public\StyleguideController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Site Routes
|--------------------------------------------------------------------------
| Serves the public customer-facing website for The Drive Clinic.
*/

// Homepage & Core Booking
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/free-car-health-check', [HomeController::class, 'healthCheck'])->name('health-check.form');
Route::get('/book', [HomeController::class, 'book'])->name('book');

// Services Catalog & Detail
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

// Drive Club & Portfolio
Route::get('/drive-club', [DriveClubController::class, 'index'])->name('drive-club');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

// Studio Info & Contact
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// Legal & Compliance
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// Digital Car Passport & Health Check Report Cards & Invoices
Route::get('/passport/{token}', [PassportController::class, 'show'])->name('passport.show');
Route::get('/health-report/{token}', [\App\Http\Controllers\Public\HealthCheckReportController::class, 'show'])->name('health-report.show');
Route::get('/invoice/{token}', [\App\Http\Controllers\Public\InvoiceController::class, 'show'])->name('invoices.show');
Route::get('/invoice/{token}/pdf', [\App\Http\Controllers\Public\InvoiceController::class, 'pdf'])->name('invoices.pdf');

// SEO & Crawlers
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Dev / Styleguide & Admin Shortcut
Route::get('/styleguide', [StyleguideController::class, 'index'])->name('styleguide');
Route::get('/login', fn () => redirect('/admin/login'))->name('login');

// Staff Operations Dashboard & Membership Management
Route::get('/staff', fn () => view('pages.staff'))->name('staff.home');
Route::get('/staff/memberships', fn () => view('pages.staff-memberships'))->name('staff.memberships');

// Policy Verification & Export Endpoints
Route::middleware('auth')->group(function () {
    Route::get('/admin/export/customers', [\App\Http\Controllers\Admin\ExportController::class, 'exportCustomers'])->name('admin.export.customers');
    Route::get('/admin/export/vehicles', [\App\Http\Controllers\Admin\ExportController::class, 'exportVehicles'])->name('admin.export.vehicles');

    Route::get('/test/revenue', function () {
        $user = auth()->user();
        if (!$user->isOwner() && !$user->isManager() && !$user->can('view_revenue')) {
            abort(403, 'Staff members do not have permission to view revenue data.');
        }
        return response()->json(['revenue' => 150000]);
    })->name('test.revenue');

    Route::get('/test/settings', function () {
        $user = auth()->user();
        if (!$user->isOwner() && !$user->can('manage_settings')) {
            abort(403, 'Only the studio owner can view system settings.');
        }
        return response()->json(['status' => 'ok']);
    })->name('test.settings');
});

