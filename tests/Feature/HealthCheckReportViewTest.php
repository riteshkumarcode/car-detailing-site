<?php

use App\Models\Customer;
use App\Models\HealthCheck;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('public signed health check report renders with HTTP 200 and mandatory disclaimer', function () {
    $customer = Customer::create([
        'branch_id' => 1,
        'name'      => 'Kavita Jamwal',
        'mobile'    => '9419166777',
    ]);

    $vehicle = Vehicle::create([
        'branch_id'           => 1,
        'customer_id'         => $customer->id,
        'registration_number' => 'JK02KJ7777',
        'make'                => 'Volkswagen',
        'model'               => 'Taigun GT Plus',
        'vehicle_type'        => 'suv',
    ]);

    $check = HealthCheck::create([
        'branch_id'          => 1,
        'vehicle_id'         => $vehicle->id,
        'customer_id'        => $customer->id,
        'check_date'         => now(),
        'overall_score'      => 86,
        'protection_type'    => 'ceramic',
        'category_scores'    => ['exterior' => 26, 'interior' => 28, 'wheels' => 12, 'glass' => 10, 'protection' => 10],
        'weights_snapshot'   => ['exterior' => 30, 'interior' => 30, 'wheels' => 15, 'glass' => 15, 'protection' => 10],
        'checklist_data'     => [],
        'recommended_today'  => [['name' => 'Maintenance Safe Wash', 'price' => 799, 'reason' => 'Routine gloss wash']],
        'recommended_later'  => [],
        'technician_notes'   => 'Gloss readings excellent.',
    ]);

    $response = $this->get(route('health-report.show', ['token' => $check->share_token]));

    $response->assertStatus(200);
    $response->assertSee('Digital Car Health Check');
    $response->assertSee('86');
    $response->assertSee($check->formatted_plate);
    $response->assertSee(HealthCheck::getMandatoryDisclaimer());
});

test('health check report returns 404 for invalid token', function () {
    $response = $this->get(route('health-report.show', ['token' => 'invalid-non-existent-token-12345']));
    $response->assertStatus(404);
});
