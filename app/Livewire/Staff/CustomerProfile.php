<?php

namespace App\Livewire\Staff;

use App\Models\CommunicationLog;
use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\Vehicle;
use App\Services\MembershipService;
use Livewire\Component;

class CustomerProfile extends Component
{
    public ?int $customerId = null;

    // Log Form
    public string $logType = 'note'; // call, whatsapp, note
    public string $logSubject = '';
    public string $logContent = '';
    public bool $showAddLogForm = false;

    // Selected vehicle filter
    public ?int $selectedVehicleId = null;

    public ?string $actionSuccessMessage = null;
    public ?string $actionErrorMessage = null;

    protected $listeners = [
        'membershipAssigned' => '$refresh',
        'customerUpdated'    => '$refresh',
    ];

    public function mount(?int $customerId = null): void
    {
        $this->customerId = $customerId;
    }

    public function addCommunicationLog(): void
    {
        $this->validate([
            'logType'    => 'required|in:call,whatsapp,note,sms',
            'logContent' => 'required|string|min:2|max:1000',
            'logSubject' => 'nullable|string|max:150',
        ]);

        CommunicationLog::create([
            'branch_id'   => 1,
            'customer_id' => $this->customerId,
            'user_id'     => auth()->id() ?? null,
            'type'        => $this->logType,
            'subject'     => $this->logSubject ?: ucfirst($this->logType) . ' entry',
            'content'     => $this->logContent,
            'logged_at'   => now(),
        ]);

        $this->reset(['logContent', 'logSubject', 'showAddLogForm']);
        $this->actionSuccessMessage = 'Communication log recorded.';
    }

    public function renewCustomerMembership(int $membershipId, MembershipService $membershipService): void
    {
        $this->actionSuccessMessage = null;
        $this->actionErrorMessage = null;

        try {
            $membership = CustomerMembership::findOrFail($membershipId);
            $membershipService->renewMembership(
                membership: $membership,
                paymentMethod: 'cash',
                staff: auth()->user()
            );

            $this->actionSuccessMessage = "Successfully renewed {$membership->plan_name}!";
            $this->dispatch('jobUpdated');
        } catch (\Throwable $e) {
            $this->actionErrorMessage = $e->getMessage();
        }
    }

    public function filterByVehicle(?int $vehicleId = null): void
    {
        $this->selectedVehicleId = $vehicleId;
    }

    public function render()
    {
        if (!$this->customerId) {
            return '<div></div>';
        }

        $customer = Customer::with([
            'vehicles.activeMembership',
            'memberships.redemptions',
            'communicationLogs.user',
        ])->findOrFail($this->customerId);

        // Fetch bookings (filtered by vehicle if selected)
        $bookingsQuery = $customer->bookings()->with(['vehicle', 'service'])->orderByDesc('booking_date');
        if ($this->selectedVehicleId) {
            $bookingsQuery->where('vehicle_id', $this->selectedVehicleId);
        }
        $bookings = $bookingsQuery->get();

        return view('livewire.staff.customer-profile', [
            'customer' => $customer,
            'bookings' => $bookings,
        ]);
    }
}
