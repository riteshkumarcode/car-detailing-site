<?php

use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\Invoice;
use App\Models\MembershipPlan;
use App\Models\MembershipRedemption;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\InvoiceService;
use App\Services\MembershipService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed branch
    $this->branch = Branch::create([
        'id'        => 1,
        'name'      => 'The Drive Clinic — Main Studio',
        'code'      => 'TDC-01',
        'city'      => 'Jammu',
        'address'   => 'Nanak Nagar, Jammu, J&K',
        'is_active' => true,
    ]);

    // Create staff & owner users
    $this->owner = User::factory()->create([
        'name'  => 'Studio Owner',
        'email' => 'owner@thedriveclinic.in',
        'role'  => 'owner',
    ]);

    $this->staff = User::factory()->create([
        'name'  => 'Detailing Specialist',
        'email' => 'staff@thedriveclinic.in',
        'role'  => 'staff',
    ]);

    // Create service category and services
    $this->washCategory = ServiceCategory::create([
        'name'       => 'Wash Treatments',
        'slug'       => 'wash',
        'sort_order' => 1,
    ]);

    $this->detailingCategory = ServiceCategory::create([
        'name'       => 'Detailing & Polish',
        'slug'       => 'detailing',
        'sort_order' => 2,
    ]);

    $this->foamWash = Service::create([
        'branch_id'           => 1,
        'service_category_id' => $this->washCategory->id,
        'name'                => 'Essential Foam Wash',
        'slug'                => 'essential-foam-wash',
        'short_description'   => 'Touchless snow foam pre-soak, two-bucket wash and blow dry.',
        'duration_minutes'    => 30,
        'price_hatchback'     => 599.00,
        'price_sedan'         => 649.00,
        'price_suv'           => 799.00,
        'is_active'           => true,
        'sort_order'          => 1,
    ]);

    $this->interiorClean = Service::create([
        'branch_id'           => 1,
        'service_category_id' => $this->detailingCategory->id,
        'name'                => 'Interior Deep Sanitization',
        'slug'                => 'interior-deep-clean',
        'short_description'   => 'Deep extraction steam sanitization and anti-bacterial cabin treatment.',
        'duration_minutes'    => 120,
        'price_hatchback'     => 2499.00,
        'price_sedan'         => 2999.00,
        'price_suv'           => 3499.00,
        'is_active'           => true,
        'sort_order'          => 2,
    ]);

    // Create standard plan
    $this->plan = MembershipPlan::create([
        'branch_id'            => 1,
        'name'                 => 'Essential Care Plan',
        'slug'                 => 'essential',
        'price'                => 1999.00,
        'billing_frequency'    => 'yearly',
        'duration_days'        => 365,
        'included_services'    => [
            ['service_id' => $this->foamWash->id, 'service_name' => 'Essential Foam Wash', 'count' => 12],
        ],
        'category_discounts'   => [
            'detailing' => 10,
            'protection' => 5,
        ],
        'features'             => ['12x Foam Washes', '10% off Detailing'],
        'benefits_description' => 'Great value annual maintenance.',
        'is_featured'          => true,
        'is_active'            => true,
        'sort_order'           => 1,
    ]);

    // Create Customer & Vehicle
    $this->customer = Customer::create([
        'branch_id'   => 1,
        'name'        => 'Vikramaditya Jamwal',
        'mobile'      => '9419166777',
        'email'       => 'vikram@example.com',
        'date_joined' => now(),
    ]);

    $this->vehicle = Vehicle::create([
        'branch_id'           => 1,
        'customer_id'         => $this->customer->id,
        'registration_number' => 'JK02ZZ9999',
        'make'                => 'BMW',
        'model'               => 'M340i',
        'vehicle_type'        => 'sedan',
    ]);
});

test('admin can create, edit, and toggle active status of membership plans', function () {
    $this->actingAs($this->owner);

    Livewire::test(\App\Livewire\Staff\MembershipPlanManager::class)
        ->assertSee('Essential Care Plan')
        ->set('name', 'Elite Concierge Plan')
        ->set('price', 9999)
        ->set('duration_days', 365)
        ->set('billing_frequency', 'yearly')
        ->set('included_services', [
            ['service_id' => $this->foamWash->id, 'service_name' => 'Essential Foam Wash', 'count' => 24],
        ])
        ->call('savePlan')
        ->assertSee('Elite Concierge Plan');

    $elitePlan = MembershipPlan::where('name', 'Elite Concierge Plan')->first();
    expect($elitePlan)->not->toBeNull()
        ->and((float) $elitePlan->price)->toBe(9999.00)
        ->and($elitePlan->is_active)->toBeTrue();

    // Toggle active
    Livewire::test(\App\Livewire\Staff\MembershipPlanManager::class)
        ->call('toggleActive', $elitePlan->id);

    expect($elitePlan->fresh()->is_active)->toBeFalse();
});

test('deactivated plan cannot be sold to new customers but existing members retain benefits', function () {
    /** @var MembershipService $service */
    $service = app(MembershipService::class);

    // 1. Assign while active to Customer 1
    $membership = $service->assignPlan(
        customer: $this->customer,
        vehicle: $this->vehicle,
        plan: $this->plan,
        paymentMethod: 'cash',
        staff: $this->staff
    );

    expect($membership->is_active)->toBeTrue()
        ->and($this->vehicle->fresh()->is_member)->toBeTrue();

    // 2. Deactivate the plan
    $this->plan->update(['is_active' => false]);

    // 3. New customer cannot purchase deactivated plan
    $newCustomer = Customer::create(['branch_id' => 1, 'name' => 'Priya Sharma', 'mobile' => '9419188999']);
    $newVehicle = Vehicle::create(['branch_id' => 1, 'customer_id' => $newCustomer->id, 'registration_number' => 'JK02AA1111', 'make' => 'Audi', 'model' => 'A4', 'vehicle_type' => 'sedan']);

    expect(fn () => $service->assignPlan(
        customer: $newCustomer,
        vehicle: $newVehicle,
        plan: $this->plan,
        paymentMethod: 'upi',
        staff: $this->staff
    ))->toThrow(\InvalidArgumentException::class, "Deactivated plan '{$this->plan->name}' cannot be sold to new customers.");

    // 4. Existing member can still redeem their wash treatment
    $redemption = $service->redeemService(
        membership: $membership,
        serviceId: $this->foamWash->id,
        units: 1,
        staff: $this->staff,
        notes: 'Redeemed during active term'
    );

    expect($redemption)->toBeInstanceOf(MembershipRedemption::class)
        ->and($membership->fresh()->getRemainingServiceCount($this->foamWash->id))->toBe(11);
});

test('assigning a membership initializes service counts and calculates expiry date accurately', function () {
    /** @var MembershipService $service */
    $service = app(MembershipService::class);

    $now = Carbon::parse('2026-06-01 10:00:00');
    Carbon::setTestNow($now);

    $membership = $service->assignPlan(
        customer: $this->customer,
        vehicle: $this->vehicle,
        plan: $this->plan,
        paymentMethod: 'upi',
        pricePaid: 1999.00,
        staff: $this->owner,
        startsAt: $now,
        notes: 'Annual enrollment via UPI'
    );

    expect($membership->status)->toBe('active')
        ->and($membership->starts_at->format('Y-m-d H:i:s'))->toBe('2026-06-01 10:00:00')
        ->and($membership->expires_at->format('Y-m-d H:i:s'))->toBe('2027-06-01 10:00:00')
        ->and($membership->getRemainingServiceCount($this->foamWash->id))->toBe(12)
        ->and($membership->getCategoryDiscount('detailing'))->toBe(10.0)
        ->and($membership->getCategoryDiscount('protection'))->toBe(5.0)
        ->and($membership->total_remaining_services_count)->toBe(12);

    Carbon::setTestNow();
});

test('service redemption decrements remaining count and links to booking/invoice', function () {
    /** @var MembershipService $service */
    $service = app(MembershipService::class);

    $membership = $service->assignPlan(
        customer: $this->customer,
        vehicle: $this->vehicle,
        plan: $this->plan,
        paymentMethod: 'cash',
        staff: $this->staff
    );

    // Initial quota is 12
    expect($membership->getRemainingServiceCount($this->foamWash->id))->toBe(12);

    // Perform redemption
    $redemption = $service->redeemService(
        membership: $membership,
        serviceId: $this->foamWash->id,
        bookingId: null,
        invoiceId: null,
        units: 1,
        staff: $this->staff,
        notes: 'Routine maintenance wash'
    );

    expect($redemption->units_redeemed)->toBe(1)
        ->and($redemption->service_name)->toBe('Essential Foam Wash')
        ->and($membership->fresh()->getRemainingServiceCount($this->foamWash->id))->toBe(11)
        ->and($membership->fresh()->total_remaining_services_count)->toBe(11);
});

test('attempting redemption on expired or depleted membership throws exception', function () {
    /** @var MembershipService $service */
    $service = app(MembershipService::class);

    $planWithOneWash = MembershipPlan::create([
        'branch_id'         => 1,
        'name'              => 'Trial Wash Pass',
        'slug'              => 'trial-pass',
        'price'             => 499.00,
        'duration_days'     => 30,
        'included_services' => [
            ['service_id' => $this->foamWash->id, 'service_name' => 'Essential Foam Wash', 'count' => 1],
        ],
        'is_active'         => true,
    ]);

    $membership = $service->assignPlan(
        customer: $this->customer,
        vehicle: $this->vehicle,
        plan: $planWithOneWash,
        paymentMethod: 'cash',
        staff: $this->staff
    );

    // 1st redemption succeeds
    $service->redeemService($membership, $this->foamWash->id, units: 1, staff: $this->staff);
    expect($membership->fresh()->getRemainingServiceCount($this->foamWash->id))->toBe(0);

    // 2nd redemption fails due to depleted quota
    expect(fn () => $service->redeemService($membership, $this->foamWash->id, units: 1, staff: $this->staff))
        ->toThrow(\RuntimeException::class, "Insufficient quota remaining for 'Essential Foam Wash'. Available: 0, Requested: 1.");

    // Expired membership test
    $membership->update([
        'expires_at' => Carbon::now()->subDay(),
        'status'     => 'expired',
    ]);

    expect(fn () => $service->redeemService($membership, $this->foamWash->id, units: 1, staff: $this->staff))
        ->toThrow(\RuntimeException::class, "Cannot redeem service on an inactive or expired Drive Club membership.");
});

test('membership renewal extends validity and restores service allowance', function () {
    /** @var MembershipService $service */
    $service = app(MembershipService::class);

    $now = Carbon::parse('2026-06-01 10:00:00');
    Carbon::setTestNow($now);

    $membership = $service->assignPlan(
        customer: $this->customer,
        vehicle: $this->vehicle,
        plan: $this->plan,
        paymentMethod: 'cash',
        staff: $this->staff
    );

    // Consume 5 washes
    for ($i = 0; $i < 5; $i++) {
        $service->redeemService($membership, $this->foamWash->id, units: 1, staff: $this->staff);
    }
    expect($membership->fresh()->getRemainingServiceCount($this->foamWash->id))->toBe(7);

    // Process renewal
    $renewed = $service->renewMembership(
        membership: $membership,
        paymentMethod: 'upi',
        pricePaid: 1999.00,
        staff: $this->owner,
        notes: 'Annual renewal for 2027-2028'
    );

    expect($renewed->status)->toBe('active')
        ->and($renewed->expires_at->format('Y-m-d H:i:s'))->toBe('2028-05-31 10:00:00') // Extended from 2027-06-01
        ->and($renewed->getRemainingServiceCount($this->foamWash->id))->toBe(12)
        ->and($renewed->total_remaining_services_count)->toBe(12);

    Carbon::setTestNow();
});

test('invoice creator detects member benefits, applies discount and decrements quota upon issuance', function () {
    /** @var MembershipService $membershipService */
    $membershipService = app(MembershipService::class);

    $membership = $membershipService->assignPlan(
        customer: $this->customer,
        vehicle: $this->vehicle,
        plan: $this->plan,
        paymentMethod: 'cash',
        staff: $this->owner
    );

    $this->actingAs($this->owner);

    // Test Livewire component
    $component = Livewire::test(\App\Livewire\Staff\InvoiceCreator::class)
        ->call('open', null, $this->customer->id, $this->vehicle->id)
        ->assertSet('membershipBenefits.plan_name', 'Essential Care Plan')
        ->assertSee('Essential Care Plan')
        // Redeem the included wash
        ->call('redeemMemberWash', 0)
        ->assertSet('items.0.unit_price', 0.0)
        ->assertSet('items.0.is_membership_redemption', true)
        ->call('issueInvoice');

    // Verify invoice created with total 0.00
    $invoice = Invoice::where('customer_id', $this->customer->id)->latest()->first();
    expect($invoice)->not->toBeNull()
        ->and((float) $invoice->total_amount)->toBe(0.00);

    // Verify quota was decremented and logged in redemptions table
    expect($membership->fresh()->getRemainingServiceCount($this->foamWash->id))->toBe(11);

    $redemption = MembershipRedemption::where('invoice_id', $invoice->id)->first();
    expect($redemption)->not->toBeNull()
        ->and($redemption->service_name)->toBe('Essential Foam Wash')
        ->and($redemption->customer_membership_id)->toBe($membership->id);
});

test('member badge and membership data are visible on vehicle and customer', function () {
    /** @var MembershipService $membershipService */
    $membershipService = app(MembershipService::class);

    $membership = $membershipService->assignPlan(
        customer: $this->customer,
        vehicle: $this->vehicle,
        plan: $this->plan,
        paymentMethod: 'cash',
        staff: $this->owner
    );

    expect($this->customer->fresh()->is_member)->toBeTrue()
        ->and($this->vehicle->fresh()->is_member)->toBeTrue()
        ->and($this->vehicle->fresh()->active_plan_name)->toBe('Essential Care Plan');

    // Customer profile Livewire component renders membership card and badge
    Livewire::test(\App\Livewire\Staff\CustomerProfile::class, ['customerId' => $this->customer->id])
        ->assertSee('Drive Club: Essential Care Plan')
        ->assertSee('Remaining Free Wash / Treatment Quota:')
        ->assertSee('Essential Foam Wash')
        ->assertSee('Renew Plan');
});
