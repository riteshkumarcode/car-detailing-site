<?php

namespace App\Livewire\Staff;

use App\Domain\Normalizers\RegistrationNormalizer;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Vehicle;
use App\Services\CapacityEngine;
use Carbon\Carbon;
use Livewire\Component;

class WalkInModal extends Component
{
    public bool $isOpen = false;

    // Form inputs
    public string $mobile = '';
    public string $name = '';
    public string $registrationNumber = '';
    public string $makeModel = '';
    public string $vehicleType = 'hatchback';
    public ?int $selectedServiceId = null;
    public string $statusChoice = 'in_service'; // 'in_service' or 'arrived'
    public string $notes = '';

    // Customer matching & warning
    public ?int $matchedCustomerId = null;
    public ?string $duplicateWarning = null;
    public array $matchedVehicles = [];

    protected $listeners = [
        'openWalkIn' => 'open',
    ];

    public function mount(): void
    {
        $firstService = Service::where('is_active', true)->orderBy('sort_order')->first();
        if ($firstService) {
            $this->selectedServiceId = $firstService->id;
        }
    }

    public function open(?string $mobile = null, ?string $plate = null): void
    {
        $this->reset(['name', 'mobile', 'registrationNumber', 'makeModel', 'duplicateWarning', 'matchedCustomerId', 'matchedVehicles', 'notes']);
        $this->vehicleType = 'hatchback';
        $this->statusChoice = 'in_service';

        if ($mobile) {
            $this->mobile = $mobile;
            $this->updatedMobile();
        }

        if ($plate) {
            $this->registrationNumber = RegistrationNormalizer::normalize($plate);
        }

        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function updatedMobile(): void
    {
        $clean = preg_replace('/[^0-9]/', '', $this->mobile);
        if (str_starts_with($clean, '91') && strlen($clean) === 12) {
            $clean = substr($clean, 2);
        } elseif (str_starts_with($clean, '0') && strlen($clean) === 11) {
            $clean = substr($clean, 1);
        }
        $this->mobile = $clean;

        if (strlen($clean) === 10) {
            $existing = Customer::with('vehicles')->where('mobile', $clean)->first();
            if ($existing) {
                $this->matchedCustomerId = $existing->id;
                $this->name = $existing->name;
                $this->matchedVehicles = $existing->vehicles->toArray();

                if ($existing->vehicles->isNotEmpty() && empty($this->registrationNumber)) {
                    $firstVeh = $existing->vehicles->first();
                    $this->registrationNumber = $firstVeh->registration_number;
                    $this->vehicleType = $firstVeh->vehicle_type ?? 'hatchback';
                    $this->makeModel = trim("{$firstVeh->make} {$firstVeh->model}");
                }

                $this->duplicateWarning = "Existing customer found: {$existing->name}. History will be linked automatically.";
            } else {
                $this->matchedCustomerId = null;
                $this->duplicateWarning = null;
                $this->matchedVehicles = [];
            }
        } else {
            $this->matchedCustomerId = null;
            $this->duplicateWarning = null;
        }
    }

    public function selectSavedVehicle(int $vehicleId): void
    {
        $veh = Vehicle::find($vehicleId);
        if ($veh) {
            $this->registrationNumber = $veh->registration_number;
            $this->vehicleType = $veh->vehicle_type;
            $this->makeModel = trim("{$veh->make} {$veh->model}");
        }
    }

    public function createWalkIn(CapacityEngine $capacityEngine): void
    {
        $this->updatedMobile();
        $this->registrationNumber = RegistrationNormalizer::normalize($this->registrationNumber);

        $this->validate([
            'mobile'             => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'name'               => 'required|string|min:2|max:100',
            'registrationNumber' => 'required|string|min:4|max:20',
            'vehicleType'        => 'required|in:hatchback,sedan,suv,other',
            'selectedServiceId'  => 'required|exists:services,id',
            'statusChoice'       => 'required|in:in_service,arrived',
            'makeModel'          => 'nullable|string|max:100',
            'notes'              => 'nullable|string|max:500',
        ], [
            'mobile.regex' => 'Please enter a valid 10-digit Indian mobile number.',
            'registrationNumber.required' => 'Vehicle plate number is required.',
        ]);

        $service = Service::findOrFail($this->selectedServiceId);
        $duration = (int) $service->duration_minutes ?: 30;
        $price = $service->getPriceForVehicleType($this->vehicleType);
        $slotsCount = $capacityEngine->calculateSlotsCount($duration);

        // Find or create customer
        $customer = Customer::firstOrCreate(
            ['branch_id' => 1, 'mobile' => $this->mobile],
            [
                'name' => $this->name,
                'date_joined' => now(),
                'tags' => ['new', 'walk_in'],
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

        // Create booking with immediate walk-in status
        $booking = Booking::create([
            'branch_id'           => 1,
            'customer_id'         => $customer->id,
            'vehicle_id'          => $vehicle->id,
            'service_id'          => $service->id,
            'service_name'        => $service->name,
            'vehicle_type'        => $this->vehicleType,
            'price'               => $price,
            'booking_date'        => Carbon::today()->format('Y-m-d'),
            'booking_time'        => Carbon::now()->format('H:i'),
            'duration_minutes'    => $duration,
            'slots_count'         => $slotsCount,
            'name'                => $this->name,
            'mobile'              => $this->mobile,
            'registration_number' => $this->registrationNumber,
            'make_model'          => $this->makeModel ?: null,
            'status'              => $this->statusChoice,
            'notes'               => $this->notes ? "Walk-in: {$this->notes}" : 'Walk-in arrival',
        ]);

        $this->isOpen = false;
        $this->dispatch('walkInCreated', bookingId: $booking->id);
    }

    public function render()
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();

        return view('livewire.staff.walk-in-modal', [
            'services' => $services,
        ]);
    }
}
