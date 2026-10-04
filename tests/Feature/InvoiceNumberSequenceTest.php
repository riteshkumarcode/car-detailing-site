<?php

use App\Models\InvoiceSequence;
use App\Models\Setting;
use App\Services\InvoiceNumberGenerator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    $this->generator = app(InvoiceNumberGenerator::class);
});

test('computes Indian financial year correctly for different dates', function () {
    // October 2026 -> 2026-27
    expect(InvoiceNumberGenerator::getFinancialYear(Carbon::parse('2026-10-03')))->toBe('2026-27');

    // January 2027 -> 2026-27
    expect(InvoiceNumberGenerator::getFinancialYear(Carbon::parse('2027-01-15')))->toBe('2026-27');

    // March 31 2027 -> 2026-27
    expect(InvoiceNumberGenerator::getFinancialYear(Carbon::parse('2027-03-31')))->toBe('2026-27');

    // April 1 2027 -> 2027-28
    expect(InvoiceNumberGenerator::getFinancialYear(Carbon::parse('2027-04-01')))->toBe('2027-28');
});

test('generates sequential invoice numbers with standard format TDC/2026-27/0001', function () {
    $date = Carbon::parse('2026-10-03');

    $first = $this->generator->generateNextNumber(1, $date);
    expect($first['financial_year'])->toBe('2026-27');
    expect($first['sequence_number'])->toBe(1);
    expect($first['invoice_number'])->toBe('TDC/2026-27/0001');

    $second = $this->generator->generateNextNumber(1, $date);
    expect($second['sequence_number'])->toBe(2);
    expect($second['invoice_number'])->toBe('TDC/2026-27/0002');
});

test('resets sequence number to 0001 on new financial year rollover', function () {
    // Year 1 (FY 2026-27)
    $fy1Date = Carbon::parse('2026-10-03');
    $first = $this->generator->generateNextNumber(1, $fy1Date);
    expect($first['invoice_number'])->toBe('TDC/2026-27/0001');

    $second = $this->generator->generateNextNumber(1, $fy1Date);
    expect($second['invoice_number'])->toBe('TDC/2026-27/0002');

    // Year 2 (FY 2027-28)
    $fy2Date = Carbon::parse('2027-04-02');
    $nextFYFirst = $this->generator->generateNextNumber(1, $fy2Date);
    expect($nextFYFirst['financial_year'])->toBe('2027-28');
    expect($nextFYFirst['sequence_number'])->toBe(1);
    expect($nextFYFirst['invoice_number'])->toBe('TDC/2027-28/0001');
});

test('generates consecutive unique sequence numbers under simulated batch creation', function () {
    $date = Carbon::parse('2026-10-03');
    $numbers = [];

    for ($i = 1; $i <= 10; $i++) {
        $res = $this->generator->generateNextNumber(1, $date);
        $numbers[] = $res['invoice_number'];
    }

    expect(count($numbers))->toBe(10);
    expect(count(array_unique($numbers)))->toBe(10);
    expect($numbers[0])->toBe('TDC/2026-27/0001');
    expect($numbers[9])->toBe('TDC/2026-27/0010');
});
