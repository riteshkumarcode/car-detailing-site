<?php

use App\Models\Setting;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(SettingSeeder::class);
});

test('settings store retrieves baseline seed data accurately', function () {
    expect(Setting::get('business.name'))->toBe('The Drive Clinic');
    expect(Setting::get('capacity.bays'))->toBe(3);
    expect(Setting::get('capacity.slot_length_minutes'))->toBe(30);
    expect(Setting::get('billing.invoice_format'))->toBe('TDC/{FY}/{SEQ4}');
    
    $weights = Setting::get('health_check.weights');
    expect($weights)->toBeArray();
    expect($weights['exterior'])->toBe(30);
    expect($weights['interior'])->toBe(30);
    expect($weights['wheels'])->toBe(15);
    expect($weights['glass'])->toBe(15);
    expect($weights['protection'])->toBe(10);
});

test('settings store allows updating and reading values', function () {
    Setting::set('capacity.bays', 4, 'capacity');
    expect(Setting::get('capacity.bays'))->toBe(4);

    Setting::set('billing.gst_enabled', true, 'billing');
    expect(Setting::get('billing.gst_enabled'))->toBeTrue();
});
