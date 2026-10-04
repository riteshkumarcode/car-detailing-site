<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('studio owner can export customers and vehicles as CSV', function () {
    $owner = User::where('email', 'owner@thedriveclinic.in')->first();
    expect($owner)->not->toBeNull();

    $response = $this->actingAs($owner)->get(route('admin.export.customers'));
    $response->assertStatus(200);
    expect($response->headers->get('Content-Type'))->toContain('text/csv');

    $responseVehicles = $this->actingAs($owner)->get(route('admin.export.vehicles'));
    $responseVehicles->assertStatus(200);
    expect($responseVehicles->headers->get('Content-Type'))->toContain('text/csv');
});

test('staff user is forbidden from exporting customer and vehicle CSV data (403)', function () {
    $staff = User::where('email', 'staff@thedriveclinic.in')->first();
    expect($staff)->not->toBeNull();

    $response = $this->actingAs($staff)->get(route('admin.export.customers'));
    $response->assertStatus(403);

    $responseVehicles = $this->actingAs($staff)->get(route('admin.export.vehicles'));
    $responseVehicles->assertStatus(403);
});

test('unauthenticated guest cannot access CSV export endpoints', function () {
    $response = $this->get(route('admin.export.customers'));
    $response->assertRedirect();
});
