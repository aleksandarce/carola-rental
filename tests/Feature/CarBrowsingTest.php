<?php

use App\Enums\BookingStatus;
use App\Enums\CarType;
use App\Models\Booking;
use App\Models\Car;

// Risk: filtering happens incorrectly, or inactive inventory becomes publicly visible.
test('public car listing filters correctly and hides inactive cars', function () {
    $matching = Car::factory()->create([
        'type' => CarType::Suv,
        'is_active' => true,
    ]);
    $nonMatching = Car::factory()->create([
        'type' => CarType::Sedan,
        'is_active' => true,
    ]);
    $inactive = Car::factory()->create([
        'type' => CarType::Suv,
        'is_active' => false,
    ]);

    $response = $this->get(route('cars.index', ['type' => CarType::Suv->value]));

    $response->assertSee($matching->name);
    $response->assertDontSee($nonMatching->name);
    $response->assertDontSee($inactive->name);
});

// Risk: incorrect route binding, or direct access to inactive inventory.
test('car detail page resolves by slug and hides inactive or unknown cars', function () {
    $car = Car::factory()->create(['is_active' => true]);
    $inactive = Car::factory()->create(['is_active' => false]);

    $this->get(route('cars.show', $car))
        ->assertOk()
        ->assertSee($car->name);

    $this->get('/cars/this-slug-does-not-exist')
        ->assertNotFound();

    $this->get(route('cars.show', $inactive))
        ->assertNotFound();
});

// Risk: a car with a conflicting pending/confirmed booking is offered as available.
test('the homepage date search hides cars already booked for the requested dates', function () {
    $booked = Car::factory()->create(['is_active' => true]);
    $free = Car::factory()->create(['is_active' => true]);

    Booking::factory()->for($booked)->create([
        'start_date' => '2026-09-05',
        'end_date' => '2026-09-10',
        'status' => BookingStatus::Confirmed,
    ]);

    $response = $this->get(route('cars.index', [
        'start_date' => '2026-09-07',
        'end_date' => '2026-09-08',
    ]));

    $response->assertSee($free->name);
    $response->assertDontSee($booked->name);
});
