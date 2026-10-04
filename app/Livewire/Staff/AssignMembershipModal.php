<?php

namespace App\Livewire\Staff;

use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\MembershipPlan;
use App\Models\Vehicle;
use App\Services\MembershipService;
use Carbon\Carbon;
use Livewire\Component;

class AssignMembershipModal extends Component
{
    public bool $isOpen = false;

    public ?int $customerId = null;
    public ?int $vehicleId = null;
    public ?int $selectedPlanId = null;

    public string $customerName = '';
    public string $customerMobile = '';
    public string $registrationNumber = '';
    public string $vehicleSummary = '';

    public string $paymentMethod = 'cash';
    public string $pricePaid = '';
    public string $notes = '';
    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    protected $listeners = [
        'openAssignMembership' => 'open',
    ];

    public function open(int $customerId, ?int $vehicleId = null): void
    {
        $this->reset(['errorMessage', 'successMessage', 'notes', 'pricePaid']);
        $this->paymentMethod = 'cash';

        $customer = Customer::with('vehicles')->findOrFail($customerId);
        $this->customerId = $customer->id;
        $this->customerName = $customer->name;
        $this->customerMobile = $customer->mobile;

        $vehicle = null;
        if ($vehicleId) {
            $vehicle = Vehicle::find($vehicleId);
        } else {
            $vehicle = $customer->vehicles->first();
        }

        if ($vehicle) {
            $this->vehicleId = $vehicle->id;
            $this->registrationNumber = $vehicle->formatted_plate;
            $this->vehicleSummary = "{$vehicle->make} {$vehicle->model} ({$vehicle->vehicle_type})";
        }

        $firstPlan = MembershipPlan::where('is_active', true)->orderBy('sort_order')->first();
        if ($firstPlan) {
            $this->selectedPlanId = $firstPlan->id;
            $this->pricePaid = (string) $firstPlan->price;
        }

        $this->isOpen = true;
    }

    public function updatedSelectedPlanId($planId): void
    {
        if ($planId) {
            $plan = MembershipPlan::find($planId);
            if ($plan) {
                $this->pricePaid = (string) $plan->price;
            }
        }
    }

    public function assignMembership(MembershipService $membershipService): void
    {
        $this->errorMessage = null;

        if (!$this->customerId || !$this->vehicleId || !$this->selectedPlanId) {
            $this->errorMessage = 'Please select a valid customer, vehicle, and membership plan.';
            return;
        }

        $customer = Customer::findOrFail($this->customerId);
        $vehicle = Vehicle::findOrFail($this->vehicleId);
        $plan = MembershipPlan::findOrFail($this->selectedPlanId);

        try {
            $membership = $membershipService->assignPlan(
                customer: $customer,
                vehicle: $vehicle,
                plan: $plan,
                paymentMethod: $this->paymentMethod,
                pricePaid: !empty($this->pricePaid) ? (float) $this->pricePaid : null,
                staff: auth()->user(),
                startsAt: Carbon::now(),
                notes: $this->notes ?: null
            );

            $this->successMessage = "Successfully activated {$plan->name} for {$vehicle->registration_number}!";
            $this->dispatch('membershipAssigned', membershipId: $membership->id);
            $this->dispatch('customerUpdated');
            $this->dispatch('jobUpdated');

            $this->isOpen = false;
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        $plans = MembershipPlan::where('is_active', true)->orderBy('sort_order')->get();
        $vehicles = $this->customerId ? Vehicle::where('customer_id', $this->customerId)->get() : collect();

        return view('livewire.staff.assign-membership-modal', [
            'plans'    => $plans,
            'vehicles' => $vehicles,
        ]);
    }
}
