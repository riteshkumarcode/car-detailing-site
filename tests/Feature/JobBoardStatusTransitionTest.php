<?php

use App\Livewire\Staff\StaffHome;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('one tap status advancement updates booking and writes status log', function () {
    $booking = Booking::create([
        'branch_id'           => 1,
        'service_name'        => 'Essential Wash',
        'vehicle_type'        => 'hatchback',
        'price'               => 499,
        'booking_date'        => Carbon::today()->format('Y-m-d'),
        'booking_time'        => '10:00',
        'duration_minutes'    => 30,
        'slots_count'         => 1,
        'name'                => 'Sahil Kapoor',
        'mobile'              => '9419177777',
        'registration_number' => 'JK02DD5555',
        'status'              => 'new',
    ]);

    // Initial status log was created
    expect($booking->statusLogs()->count())->toBe(1);
    expect($booking->statusLogs()->first()->to_status)->toBe('new');

    // 1. Advance to 'arrived'
    Livewire::test(StaffHome::class)
        ->call('updateBookingStatus', $booking->id, 'arrived')
        ->assertDispatched('jobStatusUpdated');

    $booking->refresh();
    expect($booking->status)->toBe('arrived');
    expect($booking->statusLogs()->count())->toBe(2);

    $latestLog = $booking->statusLogs()->first();
    expect($latestLog->from_status)->toBe('new');
    expect($latestLog->to_status)->toBe('arrived');
    expect($latestLog->created_at)->not->toBeNull();

    // 2. Advance to 'in_service'
    Livewire::test(StaffHome::class)
        ->call('updateBookingStatus', $booking->id, 'in_service');

    $booking->refresh();
    expect($booking->status)->toBe('in_service');
    expect($booking->statusLogs()->count())->toBe(3);

    // 3. Advance to 'completed'
    Livewire::test(StaffHome::class)
        ->call('updateBookingStatus', $booking->id, 'completed');

    $booking->refresh();
    expect($booking->status)->toBe('completed');
    expect($booking->statusLogs()->count())->toBe(4);
});
