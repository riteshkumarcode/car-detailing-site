<?php

use App\Livewire\Staff\CustomerProfile;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('customer with 2 vehicles maintains separate isolated histories', function () {
    $customer = Customer::create([
        'branch_id' => 1,
        'name'      => 'Vikramaditya Jamwal',
        'mobile'    => '9419111222',
        'email'     => 'vikram@example.com',
    ]);

    // Vehicle 1: Thar
    $thar = Vehicle::create([
        'branch_id'           => 1,
        'customer_id'         => $customer->id,
        'registration_number' => 'JK02TH0001',
        'make'                => 'Mahindra',
        'model'               => 'Thar 4x4',
        'vehicle_type'        => 'suv',
    ]);

    // Vehicle 2: City
    $city = Vehicle::create([
        'branch_id'           => 1,
        'customer_id'         => $customer->id,
        'registration_number' => 'JK02CT0002',
        'make'                => 'Honda',
        'model'               => 'City ZX',
        'vehicle_type'        => 'sedan',
    ]);

    // Create 2 completed bookings for Thar
    Booking::create([
        'branch_id'           => 1,
        'customer_id'         => $customer->id,
        'vehicle_id'          => $thar->id,
        'service_name'        => 'Ceramic Coating',
        'vehicle_type'        => 'suv',
        'price'               => 18999,
        'booking_date'        => Carbon::yesterday()->format('Y-m-d'),
        'booking_time'        => '10:00',
        'name'                => $customer->name,
        'mobile'              => $customer->mobile,
        'registration_number' => $thar->registration_number,
        'status'              => 'completed',
    ]);

    Booking::create([
        'branch_id'           => 1,
        'customer_id'         => $customer->id,
        'vehicle_id'          => $thar->id,
        'service_name'        => 'Essential Wash',
        'vehicle_type'        => 'suv',
        'price'               => 799,
        'booking_date'        => Carbon::today()->format('Y-m-d'),
        'booking_time'        => '11:00',
        'name'                => $customer->name,
        'mobile'              => $customer->mobile,
        'registration_number' => $thar->registration_number,
        'status'              => 'completed',
    ]);

    // Create 1 completed booking for City
    Booking::create([
        'branch_id'           => 1,
        'customer_id'         => $customer->id,
        'vehicle_id'          => $city->id,
        'service_name'        => 'Interior Deep Clean',
        'vehicle_type'        => 'sedan',
        'price'               => 1999,
        'booking_date'        => Carbon::today()->format('Y-m-d'),
        'booking_time'        => '14:00',
        'name'                => $customer->name,
        'mobile'              => $customer->mobile,
        'registration_number' => $city->registration_number,
        'status'              => 'completed',
    ]);

    // Check customer totals
    $customer->refresh();
    expect($customer->vehicles)->toHaveCount(2);
    expect($customer->total_visits)->toBe(3);
    expect($customer->total_spend)->toBe(21797.0);

    // Check Thar specific history
    expect($thar->bookings)->toHaveCount(2);
    expect((float) $thar->bookings()->sum('price'))->toBe(19798.0);

    // Check City specific history
    expect($city->bookings)->toHaveCount(1);
    expect((float) $city->bookings()->sum('price'))->toBe(1999.0);
});

test('staff can record communication logs on customer profile', function () {
    $customer = Customer::create([
        'branch_id' => 1,
        'name'      => 'Sanjay Dogra',
        'mobile'    => '9419133444',
    ]);

    Livewire::test(CustomerProfile::class, ['customerId' => $customer->id])
        ->set('logType', 'call')
        ->set('logSubject', 'Annual Maintenance Discussion')
        ->set('logContent', 'Customer called to enquire about Drive Club Elite membership.')
        ->call('addCommunicationLog')
        ->assertHasNoErrors();

    expect($customer->communicationLogs)->toHaveCount(1);
    $log = $customer->communicationLogs->first();
    expect($log->type)->toBe('call');
    expect($log->subject)->toBe('Annual Maintenance Discussion');
    expect($log->content)->toContain('Drive Club Elite');
});
