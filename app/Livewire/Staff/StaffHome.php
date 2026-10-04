<?php

namespace App\Livewire\Staff;

use App\Domain\Normalizers\RegistrationNormalizer;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Component;

class StaffHome extends Component
{
    // Search state
    public string $searchQuery = '';
    public array $searchResults = [];
    public bool $isSearching = false;

    // Job Board filter
    public string $activeStatusTab = 'active'; // active, today, arrived, in_service, completed, all

    // Active customer modal / view
    public ?int $viewingCustomerId = null;
    public ?int $viewingVehicleId = null;

    // Walk-in modal state
    public bool $showWalkInModal = false;

    protected $listeners = [
        'jobStatusUpdated'   => '$refresh',
        'jobUpdated'         => '$refresh',
        'invoiceCreated'     => '$refresh',
        'walkInCreated'      => 'handleWalkInCreated',
        'closeCustomerModal' => 'closeCustomerModal',
    ];

    public function updatedSearchQuery(): void
    {
        $term = trim($this->searchQuery);
        if (strlen($term) < 2) {
            $this->searchResults = [];
            $this->isSearching = false;
            return;
        }

        $this->isSearching = true;
        $normalizedPlate = RegistrationNormalizer::normalize($term);

        // Search Vehicles by registration or model
        $vehicles = Vehicle::with(['customer.activeMembership', 'activeMembership'])
            ->where(function ($q) use ($term, $normalizedPlate) {
                if ($normalizedPlate) {
                    $q->where('registration_number', 'like', "%{$normalizedPlate}%");
                }
                $q->orWhere('model', 'like', "%{$term}%")
                  ->orWhere('make', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get();

        // Search Customers by mobile or name
        $cleanMobile = preg_replace('/[^0-9]/', '', $term);
        $customers = Customer::with(['vehicles.activeMembership', 'activeMembership'])
            ->where(function ($q) use ($term, $cleanMobile) {
                if (strlen($cleanMobile) >= 3) {
                    $q->where('mobile', 'like', "%{$cleanMobile}%");
                }
                $q->orWhere('name', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get();

        $this->searchResults = [
            'vehicles' => $vehicles->toArray(),
            'customers' => $customers->toArray(),
        ];
    }

    public function clearSearch(): void
    {
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->isSearching = false;
    }

    public function viewCustomer(int $customerId): void
    {
        $this->viewingCustomerId = $customerId;
    }

    public function closeCustomerModal(): void
    {
        $this->viewingCustomerId = null;
        $this->viewingVehicleId = null;
    }

    public function openWalkInModal(?string $prefillMobile = null, ?string $prefillPlate = null): void
    {
        $this->dispatch('openWalkIn', mobile: $prefillMobile, plate: $prefillPlate);
        $this->showWalkInModal = true;
    }

    public function handleWalkInCreated(): void
    {
        $this->showWalkInModal = false;
        $this->clearSearch();
        $this->activeStatusTab = 'in_service';
        session()->flash('success', 'Walk-in job dispatched successfully!');
    }

    public function updateBookingStatus(int $bookingId, string $newStatus): void
    {
        $booking = Booking::findOrFail($bookingId);
        $allowedStatuses = ['new', 'confirmed', 'arrived', 'inspection', 'in_service', 'completed', 'cancelled', 'no_show'];

        if (in_array($newStatus, $allowedStatuses)) {
            $booking->status = $newStatus;
            $booking->save();

            $this->dispatch('jobStatusUpdated');
        }
    }

    public function render()
    {
        $today = Carbon::today()->format('Y-m-d');

        $query = Booking::with(['customer.activeMembership', 'vehicle.activeMembership', 'service'])
            ->orderBy('booking_time', 'asc');

        if ($this->activeStatusTab === 'today') {
            $query->whereDate('booking_date', $today);
        } elseif ($this->activeStatusTab === 'active') {
            $query->whereDate('booking_date', $today)
                  ->whereIn('status', ['new', 'confirmed', 'arrived', 'inspection', 'in_service']);
        } elseif ($this->activeStatusTab === 'arrived') {
            $query->whereDate('booking_date', $today)
                  ->where('status', 'arrived');
        } elseif ($this->activeStatusTab === 'in_service') {
            $query->whereDate('booking_date', $today)
                  ->where('status', 'in_service');
        } elseif ($this->activeStatusTab === 'completed') {
            $query->whereDate('booking_date', $today)
                  ->where('status', 'completed');
        }

        $bookings = $query->get();

        // Counts for status tabs
        $counts = [
            'active'     => Booking::whereDate('booking_date', $today)->whereIn('status', ['new', 'confirmed', 'arrived', 'inspection', 'in_service'])->count(),
            'arrived'    => Booking::whereDate('booking_date', $today)->where('status', 'arrived')->count(),
            'in_service' => Booking::whereDate('booking_date', $today)->where('status', 'in_service')->count(),
            'completed'  => Booking::whereDate('booking_date', $today)->where('status', 'completed')->count(),
            'all_today'  => Booking::whereDate('booking_date', $today)->count(),
        ];

        return view('livewire.staff.staff-home', [
            'bookings' => $bookings,
            'counts'   => $counts,
        ]);
    }
}
