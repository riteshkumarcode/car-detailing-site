<?php

use App\Livewire\Public\HealthCheckLeadForm;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('health check lead form renders with diagnostic options', function () {
    Livewire::test(HealthCheckLeadForm::class)
        ->assertStatus(200)
        ->assertSee('Schedule Your Free Diagnostic')
        ->assertSee('Swirl Marks & Paint Haze');
});

test('health check lead form validates required fields', function () {
    Livewire::test(HealthCheckLeadForm::class)
        ->set('name', '')
        ->set('mobile', 'invalid')
        ->set('registrationNumber', '')
        ->set('mainConcerns', [])
        ->call('submit')
        ->assertHasErrors(['name', 'mobile', 'registrationNumber', 'mainConcerns']);

    expect(Lead::count())->toBe(0);
});

test('health check lead form creates lead in database with normalized plate and concerns array', function () {
    $tomorrow = Carbon::tomorrow()->format('Y-m-d');

    Livewire::test(HealthCheckLeadForm::class)
        ->set('name', 'Vikram Singh')
        ->set('mobile', '9876543210')
        ->set('email', 'vikram@example.com')
        ->set('registrationNumber', 'jk 02 bb 9999')
        ->set('makeModel', 'Mahindra Scorpio-N')
        ->set('vehicleType', 'suv')
        ->set('mainConcerns', ['paint_swirls', 'water_spots', 'interior_stains'])
        ->set('preferredDate', $tomorrow)
        ->set('preferredTime', 'morning')
        ->set('notes', 'Swirls visible under sunlight')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('isSubmitted', true)
        ->assertDispatched('generate_lead');

    $lead = Lead::where('mobile', '9876543210')->first();
    expect($lead)->not->toBeNull();
    expect($lead->source)->toBe('health_check_form');
    expect($lead->status)->toBe('new');
    expect($lead->registration_number)->toBe('JK02BB9999');
    expect($lead->vehicle_type)->toBe('suv');
    expect($lead->main_concerns)->toContain('paint_swirls', 'water_spots', 'interior_stains');
});
