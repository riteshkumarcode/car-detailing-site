<?php

namespace App\Livewire\Public;

use App\Domain\Normalizers\RegistrationNormalizer;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Vehicle;
use App\Services\CapacityEngine;
use Carbon\Carbon;
use Livewire\Component;

class BookingStepper extends Component
{
    // Stepper State
    public int $step = 1;
    public ?string $initialService = null;

    // Step 1: Service
    public ?int $selectedServiceId = null;
    public string $selectedCategory = 'all';

    // Step 2: Vehicle Type
    public string $vehicleType = 'hatchback'; // hatchback, sedan, suv

    // Step 3: Date & Time
    public string $selectedDate = '';
    public string $selectedTime = '';

    // Step 4: Customer & Vehicle Details
    public string $name = '';
    public string $mobile = '';
    public string $email = '';
    public string $registrationNumber = '';
    public string $makeModel = '';
    public string $notes = '';

    // Step 5: Confirmed
    public ?Booking $confirmedBooking = null;

    public function mount(?string $initialService = null, CapacityEngine $capacityEngine = null): void
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');

        if ($initialService) {
            $service = Service::where('slug', $initialService)->where('is_active', true)->first();
            if ($service) {
                $this->selectedServiceId = $service->id;
                $this->step = 2; // Jump to vehicle type selection
            }
        }

        if (!$this->selectedServiceId) {
            $firstService = Service::where('is_active', true)->orderBy('sort_order')->first();
            if ($firstService) {
                $this->selectedServiceId = $firstService->id;
            }
        }
    }

    public function selectService(int $serviceId): void
    {
        $this->selectedServiceId = $serviceId;
        $this->selectedTime = ''; // reset slot when service changes
        $this->step = 2;
    }

    public function selectVehicleType(string $type): void
    {
        $this->vehicleType = in_array($type, ['hatchback', 'sedan', 'suv']) ? $type : 'hatchback';
        $this->step = 3;
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->selectedTime = '';
    }

    public function selectTime(string $time, CapacityEngine $capacityEngine): void
    {
        $service = $this->getSelectedService();
        $duration = $service ? (int) $service->duration_minutes : 30;

        if ($capacityEngine->isSlotAvailable($this->selectedDate, $time, $duration)) {
            $this->selectedTime = $time;
            $this->step = 4;
        } else {
            $this->addError('selectedTime', 'Selected time slot is no longer available. Please select another slot.');
        }
    }

    public function goToStep(int $step): void
    {
        if ($step >= 1 && $step <= 4) {
            $this->step = $step;
        }
    }

    public function updatedMobile(): void
    {
        // Clean mobile string
        $clean = preg_replace('/[^0-9]/', '', $this->mobile);
        if (str_starts_with($clean, '91') && strlen($clean) === 12) {
            $clean = substr($clean, 2);
        } elseif (str_starts_with($clean, '0') && strlen($clean) === 11) {
            $clean = substr($clean, 1);
        }
        $this->mobile = $clean;

        // Auto-match existing customer
        if (strlen($clean) === 10) {
            $customer = Customer::with('vehicles')->where('mobile', $clean)->first();
            if ($customer) {
                if (empty($this->name)) {
                    $this->name = $customer->name;
                }
                if (empty($this->email) && $customer->email) {
                    $this->email = $customer->email;
                }
                if (empty($this->registrationNumber) && $customer->vehicles->isNotEmpty()) {
                    $firstVeh = $customer->vehicles->first();
                    $this->registrationNumber = $firstVeh->registration_number;
                    if ($firstVeh->make || $firstVeh->model) {
                        $this->makeModel = trim("{$firstVeh->make} {$firstVeh->model}");
                    }
                }
            }
        }
    }

    public function submitBooking(CapacityEngine $capacityEngine): void
    {
        // Clean up inputs
        $this->updatedMobile();
        $this->registrationNumber = RegistrationNormalizer::normalize($this->registrationNumber);

        $this->validate([
            'selectedServiceId'  => 'required|exists:services,id',
            'vehicleType'        => 'required|in:hatchback,sedan,suv',
            'selectedDate'       => 'required|date|after_or_equal:today',
            'selectedTime'       => 'required|string',
            'name'               => 'required|string|min:2|max:100',
            'mobile'             => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'registrationNumber' => 'required|string|min:4|max:20',
            'email'              => 'nullable|email|max:100',
            'makeModel'          => 'nullable|string|max:100',
            'notes'              => 'nullable|string|max:500',
        ], [
            'mobile.regex' => 'Please enter a valid 10-digit Indian mobile number (e.g. 9876543210).',
            'registrationNumber.required' => 'Please enter your vehicle registration number.',
        ]);

        $service = Service::findOrFail($this->selectedServiceId);
        $duration = (int) $service->duration_minutes ?: 30;

        // Re-verify capacity before confirming
        if (!$capacityEngine->isSlotAvailable($this->selectedDate, $this->selectedTime, $duration)) {
            $this->step = 3;
            $this->addError('selectedTime', 'Sorry! That time slot just filled up. Please select a different slot.');
            return;
        }

        $price = $service->getPriceForVehicleType($this->vehicleType);
        $slotsCount = $capacityEngine->calculateSlotsCount($duration);

        // Find or create customer
        $customer = Customer::firstOrCreate(
            ['branch_id' => 1, 'mobile' => $this->mobile],
            [
                'name' => $this->name,
                'email' => $this->email ?: null,
                'date_joined' => now(),
            ]
        );

        // Find or create vehicle
        $vehicle = Vehicle::firstOrCreate(
            [
                'branch_id' => 1,
                'registration_number' => $this->registrationNumber,
            ],
            [
                'customer_id' => $customer->id,
                'vehicle_type' => $this->vehicleType,
                'make' => $this->makeModel,
            ]
        );

        // Create Booking
        $booking = Booking::create([
            'branch_id'           => 1,
            'customer_id'         => $customer->id,
            'vehicle_id'          => $vehicle->id,
            'service_id'          => $service->id,
            'service_name'        => $service->name,
            'vehicle_type'        => $this->vehicleType,
            'price'               => $price,
            'booking_date'        => $this->selectedDate,
            'booking_time'        => $this->selectedTime,
            'duration_minutes'    => $duration,
            'slots_count'         => $slotsCount,
            'name'                => $this->name,
            'mobile'              => $this->mobile,
            'email'               => $this->email ?: null,
            'registration_number' => $this->registrationNumber,
            'make_model'          => $this->makeModel ?: null,
            'status'              => 'new',
            'notes'               => $this->notes ?: null,
        ]);

        $this->confirmedBooking = $booking;
        $this->step = 5;

        // Dispatch analytics events to browser
        $this->dispatch('booking_confirmed', [
            'booking_number' => $booking->booking_number,
            'service'        => $booking->service_name,
            'price'          => $booking->price,
            'date'           => $booking->booking_date->format('Y-m-d'),
            'time'           => $booking->booking_time,
        ]);
    }

    public function getSelectedService(): ?Service
    {
        if (!$this->selectedServiceId) {
            return null;
        }
        return Service::find($this->selectedServiceId);
    }

    public function getWhatsAppUrl(): string
    {
        if (!$this->confirmedBooking) {
            return '#';
        }

        $studioWhatsApp = Setting::get('business.whatsapp', '919419100000');
        $dateFormatted = Carbon::parse($this->confirmedBooking->booking_date)->format('D, M j, Y');
        $timeFormatted = Carbon::parse($this->confirmedBooking->booking_time)->format('g:i A');

        $message = "Hi The Drive Clinic, I have booked a *{$this->confirmedBooking->service_name}* for my *{$this->confirmedBooking->formatted_plate}* on *{$dateFormatted} at {$timeFormatted}*.\n\nBooking ID: *{$this->confirmedBooking->booking_number}*.\nLooking forward to my visit!";

        return 'https://wa.me/' . $studioWhatsApp . '?text=' . urlencode($message);
    }

    public function render(CapacityEngine $capacityEngine)
    {
        $services = Service::where('is_active', true)
            ->when($this->selectedCategory !== 'all', fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $this->selectedCategory)))
            ->orderBy('sort_order')
            ->get();

        $selectedService = $this->getSelectedService();
        $duration = $selectedService ? (int) $selectedService->duration_minutes : 30;

        $availableDays = $capacityEngine->getNext7Days();
        $slots = $capacityEngine->getSlotsForDate($this->selectedDate, $duration);

        return view('livewire.public.booking-stepper', [
            'services'        => $services,
            'selectedService' => $selectedService,
            'availableDays'   => $availableDays,
            'slots'           => $slots,
        ]);
    }
}
