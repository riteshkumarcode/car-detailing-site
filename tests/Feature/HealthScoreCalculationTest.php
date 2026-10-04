<?php

use App\Models\Setting;
use App\Services\HealthScoreCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    $this->calculator = app(HealthScoreCalculator::class);
    $this->schema = HealthScoreCalculator::getDefaultChecklistSchema();
});

test('perfect inspection with ceramic coating calculates to 100 out of 100', function () {
    $checklistData = [];
    foreach ($this->schema as $catKey => $catMeta) {
        if ($catKey === 'protection') continue;
        foreach ($catMeta['items'] as $itemKey => $label) {
            $checklistData[$catKey][$itemKey] = ['rating' => 'good'];
        }
    }

    $result = $this->calculator->calculateScore($checklistData, 'ceramic');

    expect($result['overall_score'])->toBe(100);
    expect($result['category_scores']['exterior'])->toBe(30.0);
    expect($result['category_scores']['interior'])->toBe(30.0);
    expect($result['category_scores']['wheels'])->toBe(15.0);
    expect($result['category_scores']['glass'])->toBe(15.0);
    expect($result['category_scores']['protection'])->toBe(10.0);
});

test('critical condition with no protection calculates to 0 out of 100', function () {
    $checklistData = [];
    foreach ($this->schema as $catKey => $catMeta) {
        if ($catKey === 'protection') continue;
        foreach ($catMeta['items'] as $itemKey => $label) {
            $checklistData[$catKey][$itemKey] = ['rating' => 'needs_attention'];
        }
    }

    $result = $this->calculator->calculateScore($checklistData, 'none');

    expect($result['overall_score'])->toBe(0);
    expect($result['category_scores']['exterior'])->toBe(0.0);
    expect($result['category_scores']['interior'])->toBe(0.0);
    expect($result['category_scores']['wheels'])->toBe(0.0);
    expect($result['category_scores']['glass'])->toBe(0.0);
    expect($result['category_scores']['protection'])->toBe(0.0);
});

test('protection type mapping assigns correct points according to brief', function () {
    // Ceramic => Good (2 pts, 10/10)
    expect($this->calculator->protectionToPoints('ceramic'))->toBe(2);

    // Wax / Sealant => Fair (1 pt, 5/10)
    expect($this->calculator->protectionToPoints('wax'))->toBe(1);
    expect($this->calculator->protectionToPoints('sealant'))->toBe(1);

    // None / Unknown => Needs Attention (0 pts, 0/10)
    expect($this->calculator->protectionToPoints('none'))->toBe(0);
    expect($this->calculator->protectionToPoints('unknown'))->toBe(0);
});

test('custom weights snapshot protects historical score calculation from global setting changes', function () {
    $checklistData = [];
    foreach ($this->schema as $catKey => $catMeta) {
        if ($catKey === 'protection') continue;
        foreach ($catMeta['items'] as $itemKey => $label) {
            $checklistData[$catKey][$itemKey] = ['rating' => 'good'];
        }
    }

    // Historical check saved with weights: Exterior 40, Interior 20, Wheels 20, Glass 10, Protection 10
    $historicalWeights = [
        'exterior'   => 40,
        'interior'   => 20,
        'wheels'     => 20,
        'glass'      => 10,
        'protection' => 10,
    ];

    $result = $this->calculator->calculateScore($checklistData, 'ceramic', $historicalWeights);
    expect($result['weights_snapshot'])->toBe($historicalWeights);
    expect($result['category_scores']['exterior'])->toBe(40.0);
    expect($result['category_scores']['interior'])->toBe(20.0);
});
