<?php

namespace App\Livewire\Staff;

use App\Domain\Normalizers\RegistrationNormalizer;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\InvoiceService;
use App\Services\MembershipService;
use Livewire\Component;

class InvoiceCreator extends Component
{
    public bool $isOpen = false;

    // Target context
    public ?int $bookingId = null;
    public ?int $customerId = null;
    public ?int $vehicleId = null;

    public string $customerName = '';
    public string $customerMobile = '';
    public string $registrationNumber = '';
    public string $vehicleType = 'hatchback';
    public string $makeModel = '';

    // Membership benefits context
    public ?array $membershipBenefits = null;

    // Line items
    public array $items = [];
    public ?int $selectedServiceToAdd = null;
    public string $customItemName = '';
    public string $customItemPrice = '';

    // Attending Technicians
    public array $assignedStaffIds = [];

    // Payment & Discount
    public string $paymentMethod = 'cash';
    public string $paymentReference = '';
    public string $discountType = 'none';
    public float $discountValue = 0.0;
    public string $discountReason = '';
    public string $staffNotes = '';

    // Feedback
    public ?string $errorMessage = null;
    public ?array $issuedInvoiceSummary = null;

    protected $listeners = [
        'openInvoiceCreator' => 'open',
    ];

    public function mount(): void
    {
        // Default staff assignment to current authenticated user
        if (auth()->check()) {
            $this->assignedStaffIds = [auth()->id()];
        }
    }

    public function open(?int $bookingId = null, ?int $customerId = null, ?int $vehicleId = null): void
    {
        $this->reset([
            'errorMessage', 'issuedInvoiceSummary', 'items', 'customItemName',
            'customItemPrice', 'discountReason', 'staffNotes', 'paymentReference',
            'membershipBenefits'
        ]);
        $this->discountType = 'none';
        $this->discountValue = 0.0;
        $this->paymentMethod = 'cash';

        $this->bookingId = $bookingId;
        $this->customerId = $customerId;
        $this->vehicleId = $vehicleId;

        $customer = null;
        $vehicle = null;

        if ($bookingId) {
            $booking = Booking::with(['customer', 'vehicle', 'service'])->find($bookingId);
            if ($booking) {
                $this->customerId = $booking->customer_id;
                $this->vehicleId = $booking->vehicle_id;
                $this->customerName = $booking->customer?->name ?? $booking->name;
                $this->customerMobile = $booking->customer?->mobile ?? $booking->mobile;
                $this->registrationNumber = $booking->vehicle?->registration_number ?? $booking->registration_number;
                $this->vehicleType = $booking->vehicle?->vehicle_type ?? $booking->vehicle_type ?? 'hatchback';
                $this->makeModel = $booking->vehicle ? "{$booking->vehicle->make} {$booking->vehicle->model}" : ($booking->make_model ?? '');

                $customer = $booking->customer;
                $vehicle = $booking->vehicle;

                // Add booked service as first line item
                $price = (float) $booking->price;
                if ($price <= 0 && $booking->service) {
                    $price = (float) $booking->service->getPriceForVehicleType($this->vehicleType);
                }

                $this->items[] = [
                    'service_id'                => $booking->service_id,
                    'item_name'                 => $booking->service?->name ?? ($booking->service_name ?? 'Wash Treatment'),
                    'item_type'                 => 'service',
                    'quantity'                  => 1,
                    'unit_price'                => $price,
                    'original_price'            => $price,
                    'is_membership_redemption'  => false,
                ];
            }
        } elseif ($vehicleId) {
            $vehicle = Vehicle::with('customer')->find($vehicleId);
            if ($vehicle) {
                $this->vehicleId = $vehicle->id;
                $this->customerId = $vehicle->customer_id;
                $this->customerName = $vehicle->customer?->name ?? 'Walk-in Customer';
                $this->customerMobile = $vehicle->customer?->mobile ?? '';
                $this->registrationNumber = $vehicle->registration_number;
                $this->vehicleType = $vehicle->vehicle_type;
                $this->makeModel = trim("{$vehicle->make} {$vehicle->model}");
                $customer = $vehicle->customer;
            }
        } elseif ($customerId) {
            $customer = Customer::with('vehicles')->find($customerId);
            if ($customer) {
                $this->customerId = $customer->id;
                $this->customerName = $customer->name;
                $this->customerMobile = $customer->mobile;
                $vehicle = $customer->vehicles->first();
                if ($vehicle) {
                    $this->vehicleId = $vehicle->id;
                    $this->registrationNumber = $vehicle->registration_number;
                    $this->vehicleType = $vehicle->vehicle_type;
                    $this->makeModel = trim("{$vehicle->make} {$vehicle->model}");
                }
            }
        }

        // Check for active Drive Club Membership
        if ($customer && $vehicle) {
            /** @var MembershipService $membershipService */
            $membershipService = app(MembershipService::class);
            $this->membershipBenefits = $membershipService->getEligibleBenefits($customer, $vehicle);
        }

        if (empty($this->items)) {
            $firstService = Service::where('is_active', true)->orderBy('sort_order')->first();
            if ($firstService) {
                $price = (float) $firstService->getPriceForVehicleType($this->vehicleType);
                $this->items[] = [
                    'service_id'               => $firstService->id,
                    'item_name'                => $firstService->name,
                    'item_type'                => 'service',
                    'quantity'                 => 1,
                    'unit_price'               => $price,
                    'original_price'           => $price,
                    'is_membership_redemption' => false,
                ];
            }
        }

        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function addServiceItem(): void
    {
        if (!$this->selectedServiceToAdd) {
            return;
        }

        $service = Service::find($this->selectedServiceToAdd);
        if ($service) {
            $price = (float) $service->getPriceForVehicleType($this->vehicleType);
            $this->items[] = [
                'service_id'               => $service->id,
                'item_name'                => $service->name,
                'item_type'                => 'service',
                'quantity'                 => 1,
                'unit_price'               => $price,
                'original_price'           => $price,
                'is_membership_redemption' => false,
            ];
            $this->selectedServiceToAdd = null;
        }
    }

    public function addCustomItem(): void
    {
        $name = trim($this->customItemName);
        $price = (float) $this->customItemPrice;

        if (!empty($name) && $price > 0) {
            $this->items[] = [
                'service_id'               => null,
                'item_name'                => $name,
                'item_type'                => 'custom',
                'quantity'                 => 1,
                'unit_price'               => $price,
                'original_price'           => $price,
                'is_membership_redemption' => false,
            ];

            $this->customItemName = '';
            $this->customItemPrice = '';
        }
    }

    public function removeItem(int $index): void
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function redeemMemberWash(int $index): void
    {
        if (isset($this->items[$index])) {
            $this->items[$index]['unit_price'] = 0.00;
            $this->items[$index]['is_membership_redemption'] = true;
        }
    }

    public function applyCategoryDiscount(string $category, float $pct): void
    {
        $this->discountType = 'percentage';
        $this->discountValue = $pct;
        $this->discountReason = "Drive Club Member {$category} discount ({$pct}%)";
    }

    public function getCalculationsProperty(): array
    {
        /** @var InvoiceService $service */
        $service = app(InvoiceService::class);
        $gstEnabled = (bool) Setting::get('gst.enabled', false);
        $gstRate = (float) Setting::get('gst.rate', 18.00);

        return $service->calculateTotals(
            $this->items,
            $this->discountType,
            (float) $this->discountValue,
            $gstEnabled,
            $gstRate
        );
    }

    public function issueInvoice(InvoiceService $invoiceService, MembershipService $membershipService): void
    {
        $this->errorMessage = null;

        if (empty($this->items)) {
            $this->errorMessage = 'Please add at least one service or treatment item.';
            return;
        }

        if ($this->discountValue > 0 && empty(trim($this->discountReason))) {
            $this->errorMessage = 'A reason is mandatory for any applied discount.';
            return;
        }

        try {
            // Ensure customer exists
            if (!$this->customerId) {
                $cleanMob = preg_replace('/[^0-9]/', '', $this->customerMobile);
                $customer = Customer::firstOrCreate(
                    ['branch_id' => 1, 'mobile' => $cleanMob ?: '9999999999'],
                    ['name' => $this->customerName ?: 'Walk-in Customer', 'date_joined' => now()]
                );
                $this->customerId = $customer->id;
            }

            // Ensure vehicle exists
            if (!$this->vehicleId) {
                $normPlate = RegistrationNormalizer::normalize($this->registrationNumber ?: 'JK02XX0000');
                $vehicle = Vehicle::firstOrCreate(
                    ['branch_id' => 1, 'registration_number' => $normPlate],
                    [
                        'customer_id'  => $this->customerId,
                        'make'         => $this->makeModel ?: 'Vehicle',
                        'vehicle_type' => $this->vehicleType,
                    ]
                );
                $this->vehicleId = $vehicle->id;
            }

            $invoice = $invoiceService->createInvoice([
                'branch_id'          => 1,
                'customer_id'        => $this->customerId,
                'vehicle_id'         => $this->vehicleId,
                'booking_id'         => $this->bookingId,
                'payment_method'     => $this->paymentMethod,
                'payment_reference'  => $this->paymentReference ?: null,
                'staff_notes'        => $this->staffNotes ?: null,
                'assigned_staff_ids' => $this->assignedStaffIds,
                'discount_type'      => $this->discountType,
                'discount_value'     => (float) $this->discountValue,
                'discount_reason'    => $this->discountReason ?: null,
                'items'              => $this->items,
            ], auth()->user());

            // Process any membership redemptions if applied
            $customer = Customer::find($this->customerId);
            $vehicle = Vehicle::find($this->vehicleId);
            $membership = $vehicle?->activeMembership ?? $customer?->activeMembership;

            if ($membership && $membership->is_active) {
                foreach ($this->items as $item) {
                    if (!empty($item['is_membership_redemption']) && !empty($item['service_id'])) {
                        $membershipService->redeemService(
                            membership: $membership,
                            serviceId: (int) $item['service_id'],
                            bookingId: $this->bookingId,
                            invoiceId: $invoice->id,
                            units: max(1, (int) ($item['quantity'] ?? 1)),
                            staff: auth()->user(),
                            notes: "Redeemed with Invoice {$invoice->invoice_number}"
                        );
                    }
                }
            }

            $this->issuedInvoiceSummary = [
                'invoice_number' => $invoice->invoice_number,
                'total_amount'   => $invoice->total_amount,
                'share_token'    => $invoice->share_token,
                'show_url'       => route('invoices.show', $invoice->share_token),
                'pdf_url'        => route('invoices.pdf', $invoice->share_token),
            ];

            $this->dispatch('invoiceCreated', invoiceId: $invoice->id);
            $this->dispatch('jobUpdated');
            $this->dispatch('customerUpdated');

        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        $staffUsers = User::orderBy('name')->get();

        return view('livewire.staff.invoice-creator', [
            'services'     => $services,
            'staffUsers'   => $staffUsers,
            'calculations' => $this->calculations,
        ]);
    }
}
