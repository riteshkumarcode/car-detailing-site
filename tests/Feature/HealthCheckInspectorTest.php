<?php

use App\Livewire\Staff\HealthCheckInspector;
use App\Models\Customer;
use App\Models\HealthCheck;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('staff inspector can perform ramp check and attach health report to vehicle', function () {
    $customer = Customer::create([
        'branch_id' => 1,
        'name'      => 'Varun Malhotra',
        'mobile'    => '9419144555',
    ]);

    $vehicle = Vehicle::create([
        'branch_id'           => 1,
        'customer_id'         => $customer->id,
        'registration_number' => 'JK02VM9999',
        'make'                => 'Skoda',
        'model'               => 'Slavia 1.5 TSI',
        'vehicle_type'        => 'sedan',
    ]);

    Livewire::test(HealthCheckInspector::class, ['vehicleId' => $vehicle->id, 'customerId' => $customer->id])
        ->call('setItemRating', 'exterior', 'swirl_marks', 'needs_attention')
        ->call('setItemRating', 'interior', 'stains', 'fair')
        ->call('setProtectionType', 'wax')
        ->set('technicianNotes', 'Minor door edge chip. Paint gloss 82 GU.')
        ->call('proceedToRecommendations')
        ->assertSet('step', 2)
        ->call('saveHealthCheck')
        ->assertSet('step', 3)
        ->assertHasNoErrors();

    // Verify database record
    expect(HealthCheck::count())->toBe(1);
    $check = HealthCheck::first();

    expect($check->vehicle_id)->toBe($vehicle->id);
    expect($check->customer_id)->toBe($customer->id);
    expect($check->check_number)->toStartWith('TDC-HC-');
    expect($check->share_token)->not->toBeEmpty();
    expect($check->protection_type)->toBe('wax');
    expect($check->technician_notes)->toContain('Minor door edge chip');

    // Verify vehicle relation
    $vehicle->refresh();
    expect($vehicle->healthChecks)->toHaveCount(1);
    expect($vehicle->latestHealthCheck->id)->toBe($check->id);

    // Verify WhatsApp URL
    expect($check->getWhatsAppShareUrl())->toContain('wa.me/919419144555');
});
