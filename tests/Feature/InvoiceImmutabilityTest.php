<?php

use App\Models\Customer;
use App\Models\Invoice;
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
        'name'      => 'Rajesh Jamwal',
        'mobile'    => '9419133444',
    ]);

    $this->vehicle = Vehicle::create([
        'branch_id'           => 1,
        'customer_id'         => $this->customer->id,
        'registration_number' => 'JK02CD5678',
        'make'                => 'Mahindra',
        'model'               => 'Thar Roxx',
        'vehicle_type'        => 'suv',
    ]);
});

test('issued invoice is immutable and prevents direct price or item modifications', function () {
    $invoice = $this->invoiceService->createInvoice([
        'customer_id' => $this->customer->id,
        'vehicle_id'  => $this->vehicle->id,
        'items'       => [
            ['item_name' => 'Essential Foam Wash', 'quantity' => 1, 'unit_price' => 799.00],
        ],
    ]);

    expect($invoice->status)->toBe('issued');
    expect((float) $invoice->total_amount)->toBe(799.00);

    // Attempt direct price tampering
    expect(function () use ($invoice) {
        $invoice->update([
            'subtotal'     => 500.00,
            'total_amount' => 500.00,
        ]);
    })->toThrow(RuntimeException::class, 'is immutable and cannot be modified once issued');
});

test('invoice can be cancelled with mandatory reason and cancellation is logged', function () {
    $owner = User::where('email', 'owner@thedriveclinic.in')->first() ?: User::create([
        'name'     => 'Owner Admin',
        'email'    => 'owner@thedriveclinic.in',
        'password' => bcrypt('password'),
    ]);

    $invoice = $this->invoiceService->createInvoice([
        'customer_id' => $this->customer->id,
        'vehicle_id'  => $this->vehicle->id,
        'items'       => [
            ['item_name' => 'Essential Foam Wash', 'quantity' => 1, 'unit_price' => 799.00],
        ],
    ]);

    // Attempt cancellation without reason
    expect(fn () => $this->invoiceService->cancelInvoice($invoice, '', $owner))
        ->toThrow(InvalidArgumentException::class, 'A cancellation reason is required');

    // Cancel with valid reason
    $cancelled = $this->invoiceService->cancelInvoice($invoice, 'Customer requested service change before bay entry', $owner);

    expect($cancelled->status)->toBe('cancelled');
    expect($cancelled->cancellation_reason)->toBe('Customer requested service change before bay entry');
    expect($cancelled->cancelled_by_user_id)->toBe($owner->id);
    expect($cancelled->cancelled_at)->not->toBeNull();
});

test('cancelled invoice can be reissued linking to new replacement invoice', function () {
    $owner = User::where('email', 'owner@thedriveclinic.in')->first() ?: User::create([
        'name'     => 'Owner Admin',
        'email'    => 'owner@thedriveclinic.in',
        'password' => bcrypt('password'),
    ]);

    $originalInvoice = $this->invoiceService->createInvoice([
        'customer_id' => $this->customer->id,
        'vehicle_id'  => $this->vehicle->id,
        'items'       => [
            ['item_name' => 'Essential Foam Wash', 'quantity' => 1, 'unit_price' => 799.00],
        ],
    ]);

    $this->invoiceService->cancelInvoice($originalInvoice, 'Wrong service selected on POS', $owner);

    // Reissue with correct service
    $newInvoice = $this->invoiceService->reissueInvoice($originalInvoice, [
        'customer_id' => $this->customer->id,
        'vehicle_id'  => $this->vehicle->id,
        'items'       => [
            ['item_name' => 'Interior Deep Clean', 'quantity' => 1, 'unit_price' => 2499.00],
        ],
    ], $owner);

    expect($newInvoice->id)->not->toBe($originalInvoice->id);
    expect($newInvoice->status)->toBe('issued');
    expect((float) $newInvoice->total_amount)->toBe(2499.00);

    $originalInvoice->refresh();
    expect($originalInvoice->reissued_invoice_id)->toBe($newInvoice->id);
});
