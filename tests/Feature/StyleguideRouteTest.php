<?php

beforeEach(function () {
    $this->seed();
});

test('styleguide route renders with http 200 and displays brand elements', function () {
    $response = $this->get(route('styleguide'));

    $response->assertStatus(200);
    $response->assertSee('Design System & Component Library', false);
    $response->assertSee('The Drive Clinic');
    $response->assertSee('out of 100');
    $response->assertSee('JK 02 AB 1234');
    $response->assertSee('Needs attention');
});

test('home page renders with http 200', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('healthcare centre.');
    $response->assertSee('Book your car');
});
