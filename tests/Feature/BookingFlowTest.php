<?php

use App\Domain\Normalizers\RegistrationNormalizer;
use App\Livewire\Public\BookingStepper;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('registration normalizer cleans and formats Indian license plates', function () {
    expect(RegistrationNormalizer::normalize('jk-02-ab-1234'))->toBe('JK02AB1234');
    expect(RegistrationNormalizer::normalize('dl 01 c aa 1111'))->toBe('DL01CAA1111');
    expect(RegistrationNormalizer::normalize('hr26-dk-8337'))->toBe('HR26DK8337');
    expect(RegistrationNormalizer::normalize('  pb 08 bc 9999  '))->toBe('PB08BC9999');

    expect(RegistrationNormalizer::format('JK02AB1234'))->toBe('JK 02 AB 1234');
});

test('booking stepper renders step 1 with active services', function () {
    $service = Service::where('is_active', true)->first();

    Livewire::test(BookingStepper::class)
        ->assertStatus(200)
        ->assertSee($service->name)
        ->assertSee('Select Care Treatment');
});

test('booking stepper pre-selects service when initialService param is provided', function () {
    $service = Service::where('is_active', true)->first();

    Livewire::test(BookingStepper::class, ['initialService' => $service->slug])
        ->assertSet('selectedServiceId', $service->id)
        ->assertSet('step', 2);
});

test('booking stepper validates 10-digit Indian mobile number', function () {
    $service = Service::where('is_active', true)->first();
    $tomorrow = Carbon::tomorrow()->format('Y-m-d');

    Livewire::test(BookingStepper::class)
        ->set('selectedServiceId', $service->id)
        ->set('vehicleType', 'hatchback')
        ->set('selectedDate', $tomorrow)
        ->set('selectedTime', '10:00')
        ->set('name', 'Sunil Kumar')
        ->set('mobile', '1234567890') // Invalid: starts with 1
        ->set('registrationNumber', 'JK02AB1234')
        ->call('submitBooking')
        ->assertHasErrors(['mobile']);

    expect(Booking::count())->toBe(0);
});

test('booking stepper completes booking and auto-links customer and vehicle', function () {
    $service = Service::where('is_active', true)->first();
    $tomorrow = Carbon::tomorrow()->format('Y-m-d');

    Livewire::test(BookingStepper::class)
        ->set('selectedServiceId', $service->id)
        ->set('vehicleType', 'sedan')
        ->set('selectedDate', $tomorrow)
        ->set('selectedTime', '10:00')
        ->set('name', 'Amit Bakshi')
        ->set('mobile', '9419123456')
        ->set('registrationNumber', 'jk-02-cd-5678')
        ->set('makeModel', 'Honda City ZX')
        ->set('email', 'amit@example.com')
        ->set('notes', 'Please inspect alloy wheels')
        ->call('submitBooking')
        ->assertHasNoErrors()
        ->assertSet('step', 5)
        ->assertDispatched('booking_confirmed');

    // Verify Booking in database
    $booking = Booking::where('mobile', '9419123456')->first();
    expect($booking)->not->toBeNull();
    expect($booking->registration_number)->toBe('JK02CD5678');
    expect($booking->status)->toBe('new');
    expect($booking->booking_number)->toStartWith('TDC-BK-');

    // Verify Customer created & linked
    $customer = Customer::where('mobile', '9419123456')->first();
    expect($customer)->not->toBeNull();
    expect($customer->name)->toBe('Amit Bakshi');
    expect($booking->customer_id)->toBe($customer->id);

    // Verify Vehicle created & linked
    $vehicle = Vehicle::where('registration_number', 'JK02CD5678')->first();
    expect($vehicle)->not->toBeNull();
    expect($vehicle->customer_id)->toBe($customer->id);
    expect($booking->vehicle_id)->toBe($vehicle->id);
});
