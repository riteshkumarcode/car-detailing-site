<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Setting;
use Carbon\Carbon;

class CapacityEngine
{
    /**
     * Get capacity configuration values from settings.
     */
    public function getBaysCapacity(): int
    {
        return (int) Setting::get('capacity.bays', 3);
    }

    public function getSlotLengthMinutes(): int
    {
        return (int) Setting::get('capacity.slot_length_minutes', 30);
    }

    public function getOpeningHours(): array
    {
        return (array) Setting::get('capacity.opening_hours', [
            'monday'    => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
            'tuesday'   => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
            'wednesday' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
            'thursday'  => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
            'friday'    => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
            'saturday'  => ['open' => '09:00', 'close' => '20:00', 'closed' => false],
            'sunday'    => ['open' => '09:00', 'close' => '20:00', 'closed' => false],
        ]);
    }

    public function getBlockedDates(): array
    {
        return (array) Setting::get('capacity.blocked_dates', []);
    }

    /**
     * Calculate how many slots a duration in minutes occupies.
     */
    public function calculateSlotsCount(int $durationMinutes): int
    {
        $slotLength = $this->getSlotLengthMinutes();
        if ($slotLength <= 0) {
            $slotLength = 30;
        }

        return (int) ceil(max(1, $durationMinutes) / $slotLength);
    }

    /**
     * Check if a specific calendar date is open / working.
     */
    public function isDateBlockedOrClosed(string $dateStr): bool
    {
        $date = Carbon::parse($dateStr);
        $dateFormatted = $date->format('Y-m-d');

        // Check blocked dates list
        $blockedDates = $this->getBlockedDates();
        if (in_array($dateFormatted, $blockedDates, true)) {
            return true;
        }

        // Check weekday opening hours
        $dayOfWeek = strtolower($date->format('l'));
        $hours = $this->getOpeningHours();

        if (isset($hours[$dayOfWeek]) && !empty($hours[$dayOfWeek]['closed'])) {
            return true;
        }

        return false;
    }

    /**
     * Generate baseline time grid for a specific date.
     */
    public function generateTimeGrid(string $dateStr): array
    {
        if ($this->isDateBlockedOrClosed($dateStr)) {
            return [];
        }

        $date = Carbon::parse($dateStr);
        $dayOfWeek = strtolower($date->format('l'));
        $hours = $this->getOpeningHours();

        $openTimeStr = $hours[$dayOfWeek]['open'] ?? '09:00';
        $closeTimeStr = $hours[$dayOfWeek]['close'] ?? '19:00';

        $slotLength = $this->getSlotLengthMinutes();
        $startTime = Carbon::parse("{$dateStr} {$openTimeStr}");
        $endTime = Carbon::parse("{$dateStr} {$closeTimeStr}");

        $slots = [];
        $current = $startTime->copy();

        while ($current->lt($endTime)) {
            $slots[] = $current->format('H:i');
            $current->addMinutes($slotLength);
        }

        return $slots;
    }

    /**
     * Calculate slot occupancy counts for each slot on a given date.
     * Returns an associative array of [ 'HH:MM' => active_occupancy_count ].
     */
    public function getSlotOccupancies(string $dateStr, ?int $excludeBookingId = null): array
    {
        $grid = $this->generateTimeGrid($dateStr);
        $occupancies = array_fill_keys($grid, 0);

        if (empty($grid)) {
            return [];
        }

        $slotLength = $this->getSlotLengthMinutes();

        $formattedDate = Carbon::parse($dateStr)->format('Y-m-d');

        // Fetch active bookings for this date (excluding cancelled / no_show)
        $query = Booking::where(function ($q) use ($formattedDate) {
                $q->whereDate('booking_date', $formattedDate)
                  ->orWhere('booking_date', 'like', "{$formattedDate}%");
            })
            ->whereNotIn('status', ['cancelled', 'no_show']);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        $bookings = $query->get();

        foreach ($bookings as $booking) {
            $bookingTime = Carbon::parse("{$dateStr} {$booking->booking_time}");
            $slotsCount = $booking->slots_count ?: $this->calculateSlotsCount($booking->duration_minutes);

            for ($i = 0; $i < $slotsCount; $i++) {
                $occupiedSlotTime = $bookingTime->copy()->addMinutes($i * $slotLength)->format('H:i');
                if (array_key_exists($occupiedSlotTime, $occupancies)) {
                    $occupancies[$occupiedSlotTime]++;
                }
            }
        }

        return $occupancies;
    }

    /**
     * Get all slots for a date with real-time availability evaluation for a given duration.
     */
    public function getSlotsForDate(string $dateStr, int $durationMinutes = 30, ?int $excludeBookingId = null): array
    {
        $grid = $this->generateTimeGrid($dateStr);
        if (empty($grid)) {
            return [];
        }

        $baysCapacity = $this->getBaysCapacity();
        $slotLength = $this->getSlotLengthMinutes();
        $requiredSlots = $this->calculateSlotsCount($durationMinutes);
        $occupancies = $this->getSlotOccupancies($dateStr, $excludeBookingId);

        $results = [];
        $totalSlots = count($grid);

        foreach ($grid as $index => $slotTime) {
            // Check if there are enough subsequent slots remaining in operating hours
            if ($index + $requiredSlots > $totalSlots) {
                $results[] = [
                    'time' => $slotTime,
                    'display_time' => Carbon::parse("{$dateStr} {$slotTime}")->format('g:i A'),
                    'available' => false,
                    'reason' => 'Exceeds closing time',
                    'current_occupancy' => $occupancies[$slotTime] ?? 0,
                    'capacity' => $baysCapacity,
                ];
                continue;
            }

            // Check each consecutive slot required for this job
            $isAvailable = true;
            $maxOccupancyInBlock = 0;

            for ($s = 0; $s < $requiredSlots; $s++) {
                $checkSlot = $grid[$index + $s];
                $occ = $occupancies[$checkSlot] ?? 0;
                $maxOccupancyInBlock = max($maxOccupancyInBlock, $occ);

                if ($occ >= $baysCapacity) {
                    $isAvailable = false;
                    break;
                }
            }

            $results[] = [
                'time' => $slotTime,
                'display_time' => Carbon::parse("{$dateStr} {$slotTime}")->format('g:i A'),
                'available' => $isAvailable,
                'current_occupancy' => $maxOccupancyInBlock,
                'capacity' => $baysCapacity,
                'slots_required' => $requiredSlots,
            ];
        }

        return $results;
    }

    /**
     * Check if a specific slot time is available for a booking.
     */
    public function isSlotAvailable(string $dateStr, string $timeStr, int $durationMinutes = 30, ?int $excludeBookingId = null): bool
    {
        $slots = $this->getSlotsForDate($dateStr, $durationMinutes, $excludeBookingId);

        foreach ($slots as $slot) {
            if ($slot['time'] === $timeStr) {
                return (bool) $slot['available'];
            }
        }

        return false;
    }

    /**
     * Returns an array of rolling next 7 days with day name, date, and open/closed state.
     */
    public function getNext7Days(): array
    {
        $days = [];
        $start = Carbon::today();

        for ($i = 0; $i < 7; $i++) {
            $d = $start->copy()->addDays($i);
            $dateFormatted = $d->format('Y-m-d');
            $isClosed = $this->isDateBlockedOrClosed($dateFormatted);

            $days[] = [
                'date' => $dateFormatted,
                'day_name' => $d->format('D'),
                'day_number' => $d->format('d'),
                'month_name' => $d->format('M'),
                'full_label' => $d->format('D, M j'),
                'is_today' => $i === 0,
                'is_closed' => $isClosed,
            ];
        }

        return $days;
    }
}
