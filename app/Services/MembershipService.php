<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\Invoice;
use App\Models\MembershipPlan;
use App\Models\MembershipRedemption;
use App\Models\Service;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class MembershipService
{
    /**
     * Assign a Drive Club plan to a customer and vehicle.
     */
    public function assignPlan(
        Customer $customer,
        Vehicle $vehicle,
        MembershipPlan $plan,
        string $paymentMethod = 'cash',
        ?float $pricePaid = null,
        ?User $staff = null,
        ?Carbon $startsAt = null,
        ?string $notes = null
    ): CustomerMembership {
        if (!$plan->is_active) {
            throw new InvalidArgumentException("Deactivated plan '{$plan->name}' cannot be sold to new customers.");
        }

        $startDate = $startsAt ? $startsAt->copy() : Carbon::now();
        $durationDays = $plan->duration_days ?: 365;
        $expiryDate = $startDate->copy()->addDays($durationDays);
        $amount = $pricePaid !== null ? $pricePaid : (float) $plan->price;

        $remainingServices = [];
        if (!empty($plan->included_services)) {
            foreach ($plan->included_services as $svc) {
                $remainingServices[] = [
                    'service_id'      => (int) ($svc['service_id'] ?? 0),
                    'service_name'    => (string) ($svc['service_name'] ?? 'Included Service'),
                    'total_count'     => (int) ($svc['count'] ?? 1),
                    'remaining_count' => (int) ($svc['count'] ?? 1),
                ];
            }
        }

        return DB::transaction(function () use (
            $customer,
            $vehicle,
            $plan,
            $paymentMethod,
            $amount,
            $startDate,
            $expiryDate,
            $remainingServices,
            $staff,
            $notes
        ) {
            return CustomerMembership::create([
                'branch_id'          => $customer->branch_id ?? 1,
                'customer_id'        => $customer->id,
                'vehicle_id'         => $vehicle->id,
                'membership_plan_id' => $plan->id,
                'plan_name'          => $plan->name,
                'price_paid'         => $amount,
                'payment_method'     => $paymentMethod,
                'starts_at'          => $startDate,
                'expires_at'         => $expiryDate,
                'status'             => 'active',
                'remaining_services' => $remainingServices,
                'category_discounts' => $plan->category_discounts ?? [],
                'notes'              => $notes,
                'created_by_user_id' => $staff?->id ?? auth()->id(),
            ]);
        });
    }

    /**
     * Renew an existing membership extending expiry date and refreshing service quotas.
     */
    public function renewMembership(
        CustomerMembership $membership,
        string $paymentMethod = 'cash',
        ?float $pricePaid = null,
        ?User $staff = null,
        ?string $notes = null
    ): CustomerMembership {
        $plan = $membership->plan;
        $durationDays = $plan?->duration_days ?: 365;
        $amount = $pricePaid !== null ? $pricePaid : (float) ($plan?->price ?? $membership->price_paid);

        // Extend from current expiry if still active in the future, otherwise from now
        $baseDate = ($membership->expires_at && $membership->expires_at->isFuture())
            ? $membership->expires_at->copy()
            : Carbon::now();

        $newExpiry = $baseDate->copy()->addDays($durationDays);

        // Refresh service quotas from plan or current structure
        $sourceServices = $plan?->included_services ?? $membership->remaining_services ?? [];
        $refreshedServices = [];
        foreach ($sourceServices as $svc) {
            $totalCount = (int) ($svc['count'] ?? $svc['total_count'] ?? 1);
            $refreshedServices[] = [
                'service_id'      => (int) ($svc['service_id'] ?? 0),
                'service_name'    => (string) ($svc['service_name'] ?? 'Included Service'),
                'total_count'     => $totalCount,
                'remaining_count' => $totalCount,
            ];
        }

        $membership->update([
            'status'             => 'active',
            'starts_at'          => Carbon::now(),
            'expires_at'         => $newExpiry,
            'price_paid'         => $amount,
            'payment_method'     => $paymentMethod,
            'remaining_services' => $refreshedServices,
            'notes'              => $notes ?: $membership->notes,
        ]);

        return $membership->fresh();
    }

    /**
     * Redeem an included service against a membership and log the transaction.
     */
    public function redeemService(
        CustomerMembership $membership,
        int $serviceId,
        ?int $bookingId = null,
        ?int $invoiceId = null,
        int $units = 1,
        ?User $staff = null,
        ?string $notes = null
    ): MembershipRedemption {
        if (!$membership->is_active) {
            throw new RuntimeException("Cannot redeem service on an inactive or expired Drive Club membership.");
        }

        $services = $membership->remaining_services ?? [];
        $foundKey = null;
        $serviceName = 'Treatment';

        foreach ($services as $key => $svc) {
            if ((int) ($svc['service_id'] ?? 0) === $serviceId) {
                $foundKey = $key;
                $serviceName = $svc['service_name'] ?? 'Service';
                break;
            }
        }

        if ($foundKey === null) {
            // Check if service name matches from database
            $svcModel = Service::find($serviceId);
            if ($svcModel) {
                foreach ($services as $key => $svc) {
                    if (strtolower(trim($svc['service_name'] ?? '')) === strtolower(trim($svcModel->name))) {
                        $foundKey = $key;
                        $serviceName = $svc['service_name'];
                        break;
                    }
                }
            }
        }

        if ($foundKey === null) {
            throw new RuntimeException("Service (ID: {$serviceId}) is not included in {$membership->plan_name}.");
        }

        $currentRemaining = (int) ($services[$foundKey]['remaining_count'] ?? 0);
        if ($currentRemaining < $units) {
            throw new RuntimeException("Insufficient quota remaining for '{$serviceName}'. Available: {$currentRemaining}, Requested: {$units}.");
        }

        return DB::transaction(function () use (
            $membership,
            $services,
            $foundKey,
            $currentRemaining,
            $units,
            $serviceId,
            $serviceName,
            $bookingId,
            $invoiceId,
            $staff,
            $notes
        ) {
            $services[$foundKey]['remaining_count'] = $currentRemaining - $units;
            $membership->remaining_services = $services;
            $membership->save();

            return MembershipRedemption::create([
                'branch_id'              => $membership->branch_id,
                'customer_membership_id' => $membership->id,
                'customer_id'            => $membership->customer_id,
                'vehicle_id'             => $membership->vehicle_id,
                'booking_id'             => $bookingId,
                'invoice_id'             => $invoiceId,
                'service_id'             => $serviceId,
                'service_name'           => $serviceName,
                'units_redeemed'         => $units,
                'performed_by_user_id'   => $staff?->id ?? auth()->id(),
                'redeemed_at'            => Carbon::now(),
                'notes'                  => $notes,
            ]);
        });
    }

    /**
     * Retrieve eligible benefits for customer & vehicle.
     */
    public function getEligibleBenefits(Customer $customer, Vehicle $vehicle): ?array
    {
        /** @var CustomerMembership|null $membership */
        $membership = $vehicle->activeMembership ?: $customer->activeMembership;

        if (!$membership || !$membership->is_active) {
            return null;
        }

        return [
            'membership'          => $membership,
            'membership_id'       => $membership->id,
            'plan_name'           => $membership->plan_name,
            'expires_at'          => $membership->expires_at->format('d M Y'),
            'days_left'           => (int) max(0, Carbon::now()->diffInDays($membership->expires_at, false)),
            'remaining_services'  => $membership->remaining_services ?? [],
            'category_discounts'  => $membership->category_discounts ?? [],
            'total_free_services' => $membership->total_remaining_services_count,
        ];
    }
}
