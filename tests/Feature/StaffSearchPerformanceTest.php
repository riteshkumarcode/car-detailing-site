<?php

use App\Domain\Normalizers\RegistrationNormalizer;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('staff search executes in under 1 second across 5,000 customer records', function () {
    // 1. Bulk insert 5,000 realistic customer and vehicle records
    $customersData = [];
    $vehiclesData = [];
    $now = now();

    for ($i = 1; $i <= 5000; $i++) {
        $mobile = sprintf('94191%05d', $i);
        $plate = sprintf('JK02AB%04d', $i);

        $customersData[] = [
            'id'           => $i + 10,
            'branch_id'    => 1,
            'name'         => "Customer Name {$i}",
            'mobile'       => $mobile,
            'email'        => "user{$i}@example.com",
            'date_joined'  => '2026-01-01',
            'created_at'   => $now,
            'updated_at'   => $now,
        ];

        $vehiclesData[] = [
            'branch_id'           => 1,
            'customer_id'         => $i + 10,
            'registration_number' => $plate,
            'make'                => 'Hyundai',
            'model'               => ($i % 2 === 0) ? 'Creta SX' : 'i20 Asta',
            'vehicle_type'        => ($i % 2 === 0) ? 'suv' : 'hatchback',
            'created_at'          => $now,
            'updated_at'          => $now,
        ];
    }

    // Insert in chunks for speed
    foreach (array_chunk($customersData, 1000) as $chunk) {
        DB::table('customers')->insert($chunk);
    }
    foreach (array_chunk($vehiclesData, 1000) as $chunk) {
        DB::table('vehicles')->insert($chunk);
    }

    // 2. Benchmark plate search: "JK02AB4800"
    $startTime = microtime(true);
    $normalizedPlate = RegistrationNormalizer::normalize('jk-02-ab-4800');
    $vehicles = Vehicle::with('customer')
        ->where('registration_number', 'like', "%{$normalizedPlate}%")
        ->limit(10)
        ->get();
    $plateDuration = (microtime(true) - $startTime) * 1000; // in milliseconds

    expect($vehicles)->not->toBeEmpty();
    expect($vehicles[0]->registration_number)->toBe('JK02AB4800');
    expect($plateDuration)->toBeLessThan(1000); // Must be strictly under 1000ms

    // 3. Benchmark mobile search: "9419104800"
    $startTime = microtime(true);
    $customers = Customer::where('mobile', 'like', '%9419104800%')->limit(10)->get();
    $mobileDuration = (microtime(true) - $startTime) * 1000;

    expect($customers)->not->toBeEmpty();
    expect($customers[0]->mobile)->toBe('9419104800');
    expect($mobileDuration)->toBeLessThan(1000);

    // 4. Benchmark name search: "Customer Name 4800"
    $startTime = microtime(true);
    $namedCustomers = Customer::where('name', 'like', '%Customer Name 4800%')->limit(10)->get();
    $nameDuration = (microtime(true) - $startTime) * 1000;

    expect($namedCustomers)->not->toBeEmpty();
    expect($nameDuration)->toBeLessThan(1000);
});
