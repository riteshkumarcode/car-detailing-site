<?php

use App\Models\Branch;
use App\Models\User;
use Database\Seeders\BranchSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([
        BranchSeeder::class,
        RoleAndPermissionSeeder::class,
        SettingSeeder::class,
        UserSeeder::class,
    ]);
});

test('owner can access revenue figures and studio settings', function () {
    $owner = User::where('role', 'owner')->first();

    $this->actingAs($owner)
        ->get(route('test.revenue'))
        ->assertStatus(200)
        ->assertJson(['revenue' => 150000]);

    $this->actingAs($owner)
        ->get(route('test.settings'))
        ->assertStatus(200)
        ->assertJson(['status' => 'ok']);
});

test('manager can view revenue but is forbidden from system settings', function () {
    $manager = User::where('role', 'manager')->first();

    $this->actingAs($manager)
        ->get(route('test.revenue'))
        ->assertStatus(200);

    $this->actingAs($manager)
        ->get(route('test.settings'))
        ->assertStatus(403);
});

test('staff user gets 403 forbidden on revenue and system settings', function () {
    $staff = User::where('role', 'staff')->first();

    $this->actingAs($staff)
        ->get(route('test.revenue'))
        ->assertStatus(403);

    $this->actingAs($staff)
        ->get(route('test.settings'))
        ->assertStatus(403);
});

test('guest is redirected when accessing protected endpoints', function () {
    $this->get(route('test.revenue'))
        ->assertRedirect(route('login'));
});

test('admin login page renders with http 200 and studio branding', function () {
    $this->get('/admin/login')
        ->assertStatus(200)
        ->assertSee('The Drive Clinic');
});

test('authenticated owner can access filament admin dashboard', function () {
    $owner = User::where('role', 'owner')->first();

    $this->actingAs($owner)
        ->get('/admin')
        ->assertStatus(200);
});

test('owner can submit login form and authenticate into admin panel', function () {
    \Livewire\Livewire::test(\Filament\Pages\Auth\Login::class)
        ->fillForm([
            'email' => 'owner@thedriveclinic.in',
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors()
        ->assertRedirect('/admin');

    expect(auth()->check())->toBeTrue();
    expect(auth()->user()->email)->toBe('owner@thedriveclinic.in');
});

