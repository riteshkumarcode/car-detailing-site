<?php

use App\Models\Lead;
use App\Models\Service;
use App\Models\Setting;

beforeEach(function () {
    $this->seed();
});

test('all public marketing pages render with HTTP 200', function (string $routeName, ?array $params = null) {
    $url = $params ? route($routeName, $params) : route($routeName);
    $response = $this->get($url);

    $response->assertStatus(200);
})->with([
    ['home'],
    ['services.index'],
    ['health-check.form'],
    ['book'],
    ['drive-club'],
    ['gallery'],
    ['about'],
    ['contact'],
    ['faq'],
    ['privacy'],
    ['terms'],
]);

test('service detail page renders with service data and pricing', function () {
    $service = Service::where('is_active', true)->first();
    expect($service)->not->toBeNull();

    $response = $this->get(route('services.show', ['slug' => $service->slug]));

    $response->assertStatus(200);
    $response->assertSee($service->name);
    $response->assertSee('₹' . number_format($service->price_hatchback));
});

test('service detail page returns 404 for non-existent service slug', function () {
    $response = $this->get(route('services.show', ['slug' => 'non-existent-service-slug-12345']));

    $response->assertStatus(404);
});

test('contact form submits successfully and creates a lead in database', function () {
    $payload = [
        'name'    => 'Rohit Sharma',
        'mobile'  => '9876543210',
        'email'   => 'rohit@example.com',
        'subject' => 'Ceramic Coating Inquiry',
        'message' => 'Interested in 3-year ceramic coating for Mahindra XUV700.',
    ];

    $response = $this->post(route('contact.submit'), $payload);

    $response->assertSessionHas('success');
    $response->assertRedirect();

    $this->assertDatabaseHas('leads', [
        'name'    => 'Rohit Sharma',
        'mobile'  => '9876543210',
        'email'   => 'rohit@example.com',
        'subject' => 'Ceramic Coating Inquiry',
        'status'  => 'new',
    ]);
});

test('contact form validates mobile number format', function () {
    $payload = [
        'name'    => 'Invalid User',
        'mobile'  => '12345', // Invalid Indian 10-digit mobile
        'message' => 'Test message',
    ];

    $response = $this->post(route('contact.submit'), $payload);

    $response->assertSessionHasErrors(['mobile']);
    expect(Lead::where('name', 'Invalid User')->count())->toBe(0);
});

test('sitemap.xml generates valid xml structure with all public URLs', function () {
    $response = $this->get(route('sitemap'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/xml');
    $response->assertSee('<urlset', false);
    $response->assertSee(url('/services'), false);
    $response->assertSee(url('/drive-club'), false);
});

test('robots.txt returns crawl instructions and sitemap link', function () {
    $response = $this->get(route('robots'));

    $response->assertStatus(200);
    expect($response->headers->get('Content-Type'))->toContain('text/plain');
    $response->assertSee('User-agent: *');
    $response->assertSee('Disallow: /admin/');
    $response->assertSee('Sitemap:');
});

test('passport route returns 404 when public passport feature setting is disabled', function () {
    Setting::set('features.passport_public_view', false);

    $response = $this->get(route('passport.show', ['token' => 'TDC-TEST-123']));

    $response->assertStatus(404);
});

test('passport route renders with HTTP 200 when public passport feature setting is enabled', function () {
    Setting::set('features.passport_public_view', true);

    $response = $this->get(route('passport.show', ['token' => 'TDC-TEST-123']));

    $response->assertStatus(200);
    $response->assertSee('Verified Digital Passport');
    $response->assertSee('Drive Health Score');
});
