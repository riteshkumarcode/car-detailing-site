<?php

use App\Models\Booking;
use App\Models\Setting;
use App\Services\CapacityEngine;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    $this->engine = app(CapacityEngine::class);
    $this->testDate = Carbon::today()->addDays(2)->format('Y-m-d');
});

test('slots are available by default according to operating hours', function () {
    $slots = $this->engine->getSlotsForDate($this->testDate, 30);

    expect($slots)->not->toBeEmpty();
    expect($slots[0]['available'])->toBeTrue();
    expect($slots[0]['capacity'])->toBe(3);
});

test('slot becomes unavailable when active bookings reach bay capacity limit', function () {
    $slotTime = '10:00';

    // Verify initially available
    expect($this->engine->isSlotAvailable($this->testDate, $slotTime, 30))->toBeTrue();

    // Create 3 active bookings at 10:00 (bay capacity = 3)
    for ($i = 1; $i <= 3; $i++) {
        Booking::create([
            'branch_id'           => 1,
            'service_name'        => 'Essential Wash',
            'vehicle_type'        => 'hatchback',
            'price'               => 499,
            'booking_date'        => $this->testDate,
            'booking_time'        => $slotTime,
            'duration_minutes'    => 30,
            'slots_count'         => 1,
            'name'                => "Customer {$i}",
            'mobile'              => "987654321{$i}",
            'registration_number' => "JK02AB000{$i}",
            'status'              => 'confirmed',
        ]);
    }

    // Now slot at 10:00 should be full (unavailable)
    expect($this->engine->isSlotAvailable($this->testDate, $slotTime, 30))->toBeFalse();

    // Adjacent slot at 10:30 should still be available
    expect($this->engine->isSlotAvailable($this->testDate, '10:30', 30))->toBeTrue();
});

test('changing bay capacity from 3 to 4 immediately frees full slots', function () {
    $slotTime = '11:00';

    // Fill all 3 bays
    for ($i = 1; $i <= 3; $i++) {
        Booking::create([
            'branch_id'           => 1,
            'service_name'        => 'Essential Wash',
            'vehicle_type'        => 'hatchback',
            'price'               => 499,
            'booking_date'        => $this->testDate,
            'booking_time'        => $slotTime,
            'duration_minutes'    => 30,
            'slots_count'         => 1,
            'name'                => "Customer {$i}",
            'mobile'              => "987654321{$i}",
            'registration_number' => "JK02AB000{$i}",
            'status'              => 'confirmed',
        ]);
    }

    // Confirmed full with 3 bays
    expect($this->engine->isSlotAvailable($this->testDate, $slotTime, 30))->toBeFalse();

    // Owner increases capacity to 4 bays in settings
    Setting::set('capacity.bays', 4);

    // Slot is now immediately available for a 4th vehicle!
    expect($this->engine->isSlotAvailable($this->testDate, $slotTime, 30))->toBeTrue();
});

test('multi-slot service duration occupies consecutive slots', function () {
    // 60-minute service requires 2 consecutive 30-min slots (e.g. 10:00 and 10:30)
    $slotTime = '10:00';

    // Put 3 bookings in the second slot (10:30)
    for ($i = 1; $i <= 3; $i++) {
        Booking::create([
            'branch_id'           => 1,
            'service_name'        => 'Essential Wash',
            'vehicle_type'        => 'hatchback',
            'price'               => 499,
            'booking_date'        => $this->testDate,
            'booking_time'        => '10:30',
            'duration_minutes'    => 30,
            'slots_count'         => 1,
            'name'                => "Customer {$i}",
            'mobile'              => "987654321{$i}",
            'registration_number' => "JK02AB000{$i}",
            'status'              => 'confirmed',
        ]);
    }

    // A 30-min wash at 10:00 is available (10:00 has 0/3)
    expect($this->engine->isSlotAvailable($this->testDate, '10:00', 30))->toBeTrue();

    // But a 60-min detailing at 10:00 is NOT available because its 2nd slot (10:30) is full (3/3)
    expect($this->engine->isSlotAvailable($this->testDate, '10:00', 60))->toBeFalse();
});

test('cancelled and no-show bookings immediately free up capacity', function () {
    $slotTime = '12:00';

    $bookings = [];
    for ($i = 1; $i <= 3; $i++) {
        $bookings[] = Booking::create([
            'branch_id'           => 1,
            'service_name'        => 'Essential Wash',
            'vehicle_type'        => 'hatchback',
            'price'               => 499,
            'booking_date'        => $this->testDate,
            'booking_time'        => $slotTime,
            'duration_minutes'    => 30,
            'slots_count'         => 1,
            'name'                => "Customer {$i}",
            'mobile'              => "987654321{$i}",
            'registration_number' => "JK02AB000{$i}",
            'status'              => 'confirmed',
        ]);
    }

    expect($this->engine->isSlotAvailable($this->testDate, $slotTime, 30))->toBeFalse();

    // Cancel one booking
    $bookings[0]->update(['status' => 'cancelled']);

    // Capacity is now immediately freed
    expect($this->engine->isSlotAvailable($this->testDate, $slotTime, 30))->toBeTrue();

    // Fill it again
    $bookings[0]->update(['status' => 'confirmed']);
    expect($this->engine->isSlotAvailable($this->testDate, $slotTime, 30))->toBeFalse();

    // Mark one as no-show
    $bookings[1]->update(['status' => 'no_show']);
    expect($this->engine->isSlotAvailable($this->testDate, $slotTime, 30))->toBeTrue();
});

test('blocked dates return no available slots', function () {
    $blockedDate = '2026-12-25';
    Setting::set('capacity.blocked_dates', [$blockedDate]);

    expect($this->engine->isDateBlockedOrClosed($blockedDate))->toBeTrue();
    expect($this->engine->getSlotsForDate($blockedDate, 30))->toBeEmpty();
});
