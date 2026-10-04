<?php

use App\Models\Customer;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    $this->invoiceService = app(InvoiceService::class);

    $this->customer = Customer::create([
        'branch_id' => 1,
        'name'      => 'Aarav Sharma',
        'mobile'    => '9419111222',
    ]);

    $this->vehicle = Vehicle::create([
        'branch_id'           => 1,
        'customer_id'         => $this->customer->id,
        'registration_number' => 'JK02AB1234',
        'make'                => 'Hyundai',
        'model'               => 'Creta',
        'vehicle_type'        => 'suv',
    ]);
});

test('discount requires a mandatory reason', function () {
    $items = [
        ['item_name' => 'Essential Foam Wash', 'quantity' => 1, 'unit_price' => 799.00],
    ];

    expect(fn () => $this->invoiceService->createInvoice([
        'customer_id'     => $this->customer->id,
        'vehicle_id'      => $this->vehicle->id,
        'discount_type'   => 'percentage',
        'discount_value'  => 10,
        'discount_reason' => '', // Empty reason
        'items'           => $items,
    ]))->toThrow(InvalidArgumentException::class, 'A reason is mandatory for any applied discount.');
});

test('staff user is prevented from applying excessive discount exceeding role limit', function () {
    $staffUser = User::where('email', 'staff@thedriveclinic.in')->first() ?: User::create([
        'name'     => 'Staff Tech',
        'email'    => 'staff@thedriveclinic.in',
        'password' => bcrypt('password'),
    ]);
    $staffUser->assignRole('staff');

    $items = [
        ['item_name' => 'Ceramic Coating 9H', 'quantity' => 1, 'unit_price' => 10000.00],
    ];

    // Staff attempts 20% discount (limit is 10%)
    expect(fn () => $this->invoiceService->createInvoice([
        'customer_id'     => $this->customer->id,
        'vehicle_id'      => $this->vehicle->id,
        'discount_type'   => 'percentage',
        'discount_value'  => 20,
        'discount_reason' => 'Friend request',
        'items'           => $items,
    ], $staffUser))->toThrow(InvalidArgumentException::class, 'Staff role is restricted to a maximum 10% discount.');
});

test('studio owner can apply any discount with reason', function () {
    $owner = User::where('email', 'owner@thedriveclinic.in')->first() ?: User::create([
        'name'     => 'Owner Admin',
        'email'    => 'owner@thedriveclinic.in',
        'password' => bcrypt('password'),
    ]);
    $owner->assignRole('owner');

    $items = [
        ['item_name' => 'Ceramic Coating 9H', 'quantity' => 1, 'unit_price' => 10000.00],
    ];

    $invoice = $this->invoiceService->createInvoice([
        'customer_id'     => $this->customer->id,
        'vehicle_id'      => $this->vehicle->id,
        'discount_type'   => 'percentage',
        'discount_value'  => 50,
        'discount_reason' => 'Owner personal goodwill discount',
        'items'           => $items,
        'is_gst_enabled'  => false,
    ], $owner);

    expect((float) $invoice->discount_amount)->toBe(5000.0);
    expect((float) $invoice->total_amount)->toBe(5000.0);
    expect($invoice->discount_reason)->toBe('Owner personal goodwill discount');
});

test('GST on calculates CGST and SGST accurately and snapshots tax config', function () {
    Setting::set('gst.enabled', true);
    Setting::set('gst.rate', 18.00);
    Setting::set('gst.gstin', '01ABCDE1234F1Z5');

    $items = [
        ['item_name' => 'Paint Correction & Detailing', 'quantity' => 1, 'unit_price' => 1000.00],
    ];

    $invoice = $this->invoiceService->createInvoice([
        'customer_id'    => $this->customer->id,
        'vehicle_id'     => $this->vehicle->id,
        'is_gst_enabled' => true,
        'gst_rate'       => 18.00,
        'gstin'          => '01ABCDE1234F1Z5',
        'items'          => $items,
    ]);

    expect($invoice->is_gst_enabled)->toBeTrue();
    expect((float) $invoice->subtotal)->toBe(1000.00);
    expect((float) $invoice->cgst_rate)->toBe(9.00);
    expect((float) $invoice->sgst_rate)->toBe(9.00);
    expect((float) $invoice->cgst_amount)->toBe(90.00);
    expect((float) $invoice->sgst_amount)->toBe(90.00);
    expect((float) $invoice->total_tax)->toBe(180.00);
    expect((float) $invoice->total_amount)->toBe(1180.00);
    expect($invoice->gstin)->toBe('01ABCDE1234F1Z5');
});

test('GST off produces simple zero-tax invoice', function () {
    $items = [
        ['item_name' => 'Essential Foam Wash', 'quantity' => 1, 'unit_price' => 799.00],
    ];

    $invoice = $this->invoiceService->createInvoice([
        'customer_id'    => $this->customer->id,
        'vehicle_id'     => $this->vehicle->id,
        'is_gst_enabled' => false,
        'items'          => $items,
    ]);

    expect($invoice->is_gst_enabled)->toBeFalse();
    expect((float) $invoice->subtotal)->toBe(799.00);
    expect((float) $invoice->total_tax)->toBe(0.00);
    expect((float) $invoice->total_amount)->toBe(799.00);
});
