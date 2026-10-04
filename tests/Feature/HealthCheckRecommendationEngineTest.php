<?php

use App\Services\HealthScoreCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    $this->calculator = app(HealthScoreCalculator::class);
});

test('recommendation engine generates calibrated service recommendations by vehicle type', function () {
    $checklistData = [
        'exterior' => [
            'swirl_marks' => ['rating' => 'needs_attention'],
        ],
        'interior' => [
            'stains' => ['rating' => 'needs_attention'],
        ],
        'glass' => [],
        'wheels' => [],
    ];

    // For SUV
    $recsSUV = $this->calculator->generateRecommendations($checklistData, 'none', 'suv');

    expect($recsSUV['today'])->not->toBeEmpty();
    expect($recsSUV['later'])->not->toBeEmpty();

    // Check pricing reflects SUV rates
    $firstRec = $recsSUV['today'][0];
    expect($firstRec['vehicle_type'])->toBe('suv');
    expect($firstRec['price'])->toBeGreaterThanOrEqual(499);
});
