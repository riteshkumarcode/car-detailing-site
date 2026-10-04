<?php

use App\Models\Customer;
use App\Models\Vehicle;
use App\Services\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    $this->invoiceService = app(InvoiceService::class);

    $this->customer = Customer::create([
        'branch_id' => 1,
        'name'      => 'Karan Jamwal',
        'mobile'    => '9419177889',
    ]);

    $this->vehicle = Vehicle::create([
        'branch_id'           => 1,
        'customer_id'         => $this->customer->id,
        'registration_number' => 'JK02PQ1122',
        'make'                => 'Toyota',
        'model'               => 'Fortuner Legender',
        'vehicle_type'        => 'suv',
    ]);
});

test('digital invoice web view renders with HTTP 200 and invoice details', function () {
    $invoice = $this->invoiceService->createInvoice([
        'customer_id' => $this->customer->id,
        'vehicle_id'  => $this->vehicle->id,
        'items'       => [
            ['item_name' => 'Essential Foam Wash', 'quantity' => 1, 'unit_price' => 799.00],
        ],
    ]);

    $response = $this->get(route('invoices.show', ['token' => $invoice->share_token]));

    $response->assertStatus(200);
    $response->assertSee($invoice->invoice_number);
    $response->assertSee('Karan Jamwal');
    $response->assertSee('JK 02 PQ 1122');
    $response->assertSee('Essential Foam Wash');
    $response->assertSee('Download PDF');
});

test('invoice PDF endpoint returns HTTP 200 with application/pdf stream', function () {
    $invoice = $this->invoiceService->createInvoice([
        'customer_id' => $this->customer->id,
        'vehicle_id'  => $this->vehicle->id,
        'items'       => [
            ['item_name' => 'Essential Foam Wash', 'quantity' => 1, 'unit_price' => 799.00],
        ],
    ]);

    $response = $this->get(route('invoices.pdf', ['token' => $invoice->share_token]));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});
