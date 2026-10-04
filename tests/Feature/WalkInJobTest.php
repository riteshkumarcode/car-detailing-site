<?php

use App\Livewire\Staff\WalkInModal;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('walk-in modal creates customer, vehicle and booking in service status', function () {
    $service = Service::where('is_active', true)->first();

    Livewire::test(WalkInModal::class)
        ->call('open')
        ->assertSet('isOpen', true)
        ->set('mobile', '9419199999')
        ->set('name', 'Rajat Choudhary')
        ->set('registrationNumber', 'jk 02 az 1111')
        ->set('makeModel', 'Kia Seltos GT')
        ->set('vehicleType', 'suv')
        ->set('selectedServiceId', $service->id)
        ->set('statusChoice', 'in_service')
        ->call('createWalkIn')
        ->assertHasNoErrors()
        ->assertSet('isOpen', false)
        ->assertDispatched('walkInCreated');

    // Verify Customer created
    $customer = Customer::where('mobile', '9419199999')->first();
    expect($customer)->not->toBeNull();
    expect($customer->name)->toBe('Rajat Choudhary');

    // Verify Vehicle created with normalized plate
    $vehicle = Vehicle::where('registration_number', 'JK02AZ1111')->first();
    expect($vehicle)->not->toBeNull();
    expect($vehicle->customer_id)->toBe($customer->id);

    // Verify Booking created as in_service
    $booking = Booking::where('registration_number', 'JK02AZ1111')->first();
    expect($booking)->not->toBeNull();
    expect($booking->status)->toBe('in_service');
    expect($booking->customer_id)->toBe($customer->id);
    expect($booking->vehicle_id)->toBe($vehicle->id);
});

test('walk-in modal detects existing customer by mobile and shows duplicate warning', function () {
    $customer = Customer::create([
        'branch_id' => 1,
        'name'      => 'Aman Sharma',
        'mobile'    => '9419188888',
    ]);

    Vehicle::create([
        'branch_id'           => 1,
        'customer_id'         => $customer->id,
        'registration_number' => 'JK02CC4444',
        'make'                => 'Tata',
        'model'               => 'Nexon EV',
        'vehicle_type'        => 'suv',
    ]);

    Livewire::test(WalkInModal::class)
        ->call('open')
        ->set('mobile', '9419188888')
        ->assertSet('matchedCustomerId', $customer->id)
        ->assertSet('name', 'Aman Sharma')
        ->assertSet('registrationNumber', 'JK02CC4444')
        ->assertSee('Existing customer found: Aman Sharma');
});
