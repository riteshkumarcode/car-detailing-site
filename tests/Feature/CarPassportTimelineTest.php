<?php

use App\Models\Customer;
use App\Models\HealthCheck;
use App\Models\Setting;
use App\Models\Vehicle;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    $this->invoiceService = app(InvoiceService::class);
});

test('vehicle with 2 visits 2 invoices and 1 health check displays complete chronological passport timeline', function () {
    Setting::set('features.passport_public_view', true);

    $customer = Customer::create([
        'branch_id'   => 1,
        'name'        => 'Vikramaditya Jamwal',
        'mobile'      => '9419199888',
        'date_joined' => Carbon::parse('2026-06-01'),
    ]);

    $vehicle = Vehicle::create([
        'branch_id'           => 1,
        'customer_id'         => $customer->id,
        'registration_number' => 'JK02ZZ9999',
        'make'                => 'BMW',
        'model'               => 'M340i xDrive',
        'vehicle_type'        => 'sedan',
        'colour'              => 'Tanzanite Blue',
    ]);

    // 1. Visit 1 / Invoice 1 (1 June 2026)
    $inv1 = $this->invoiceService->createInvoice([
        'customer_id' => $customer->id,
        'vehicle_id'  => $vehicle->id,
        'date'        => Carbon::parse('2026-06-01'),
        'items'       => [
            ['item_name' => 'Essential Diagnostic Foam Wash', 'quantity' => 1, 'unit_price' => 649.00],
        ],
    ]);
    $inv1->created_at = Carbon::parse('2026-06-01 10:00:00');
    $inv1->saveQuietly();

    // 2. Health Check (15 July 2026)
    $check = HealthCheck::create([
        'branch_id'         => 1,
        'customer_id'       => $customer->id,
        'vehicle_id'        => $vehicle->id,
        'check_date'        => Carbon::parse('2026-07-15 11:30:00'),
        'overall_score'     => 88,
        'protection_type'   => 'ceramic',
        'category_scores'   => ['exterior' => 26, 'interior' => 28, 'wheels' => 12, 'glass' => 12, 'protection' => 10],
        'weights_snapshot'  => ['exterior' => 30, 'interior' => 30, 'wheels' => 15, 'glass' => 15, 'protection' => 10],
        'checklist_data'    => [],
        'recommended_today' => [['name' => 'Multi-Stage Paint Correction', 'price' => 4999]],
        'recommended_later' => [['name' => '9H Ceramic Top-Up', 'price' => 2999]],
        'technician_notes'  => 'Swirl marks visible on bonnet.',
        'photos'            => ['/storage/demo/bonnet.jpg', '/storage/demo/wheel.jpg'],
    ]);

    // 3. Visit 2 / Invoice 2 (20 August 2026)
    $inv2 = $this->invoiceService->createInvoice([
        'customer_id' => $customer->id,
        'vehicle_id'  => $vehicle->id,
        'date'        => Carbon::parse('2026-08-20'),
        'items'       => [
            ['item_name' => 'Multi-Stage Paint Correction & Gloss Restoration', 'quantity' => 1, 'unit_price' => 4999.00],
        ],
    ]);
    $inv2->created_at = Carbon::parse('2026-08-20 14:00:00');
    $inv2->saveQuietly();

    // Fetch Passport by registration plate
    $response = $this->get(route('passport.show', ['token' => 'JK02ZZ9999']));

    $response->assertStatus(200);
    $response->assertSee('BMW M340i');
    $response->assertSee('JK 02 ZZ 9999');
    $response->assertSee('88/100'); // latest health score
    $response->assertSee('5,648'); // Total spend: 649 + 4999 = 5648
    $response->assertSee('Multi-Stage Paint Correction & Gloss Restoration');
    $response->assertSee('25-Point Diagnostic Health Check');
    $response->assertSee(HealthCheck::getMandatoryDisclaimer());
});

test('public passport route returns 404 when public passport feature setting is disabled', function () {
    Setting::set('features.passport_public_view', false);

    $response = $this->get(route('passport.show', ['token' => 'JK02ZZ9999']));
    $response->assertStatus(404);
});
