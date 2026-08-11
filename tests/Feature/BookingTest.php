<?php

use App\Enums\BookingStatus;
use App\Enums\InsuranceOption;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;

// Rentals are billed in 24-hour blocks, not calendar days — every test below
// submits real datetime-local strings (Y-m-d\TH:i), matching what
// <input type="datetime-local"> actually sends.
const DATETIME_LOCAL_FORMAT = 'Y-m-d\TH:i';

// Risk: a user can modify the submitted total, daily rate, or insurance rate.
test('the server computes and stores the booking price, ignoring any client-submitted total or rate', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create(['daily_rate' => 50, 'is_active' => true]);

    $pickup = now()->addDay()->setTime(15, 0);
    $return = $pickup->copy()->addDay(); // exactly 24 hours later

    $this->actingAs($user)->post(route('cars.bookings.store', $car), [
        'start_date' => $pickup->format(DATETIME_LOCAL_FORMAT),
        'end_date' => $return->format(DATETIME_LOCAL_FORMAT),
        'insurance' => 'performance',
        'pickup_location' => 'skp',
        'return_location' => 'hq',
        'total_price' => 1, // false total submitted by the client
        'daily_rate' => 9999, // false rate submitted by the client
        'insurance_daily_rate_snapshot' => 9999, // false insurance rate submitted by the client
        'status' => 'confirmed', // attempted status tampering
    ]);

    $booking = Booking::sole();
    expect((float) $booking->daily_rate_snapshot)->toBe(50.0); // the car's database rate, not the client's
    expect($booking->insurance)->toBe(InsuranceOption::Performance);
    expect((float) $booking->insurance_daily_rate_snapshot)->toBe(9.0); // the enum's rate, not the client's
    expect((float) $booking->total_price)->toBe(59.0); // server-calculated: exactly 24h = 1 day * ($50 + $9)
    expect($booking->status)->toBe(BookingStatus::Pending);
    expect($booking->pickup_location)->toBe('skp'); // the admin-managed Location code, not an enum
    expect($booking->return_location)->toBe('hq');
    expect($booking->start_date->format('H:i'))->toBe('15:00'); // the requested hour is kept, not truncated
});

// Risk: any time beyond a full 24-hour multiple should round up to a whole
// extra day, not be dropped — this is the actual business rule being tested.
test('bookings are billed in full 24-hour blocks, rounding up any partial day', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create(['daily_rate' => 100, 'is_active' => true]);

    $pickup = now()->addDay()->setTime(15, 0);
    $exactlyOneDayLater = $pickup->copy()->addDay(); // 24h 0m -> 1 day
    $oneHourOver = $pickup->copy()->addDay()->addHour(); // 24h 1h+ -> 2 days

    $this->actingAs($user)->post(route('cars.bookings.store', $car), [
        'start_date' => $pickup->format(DATETIME_LOCAL_FORMAT),
        'end_date' => $exactlyOneDayLater->format(DATETIME_LOCAL_FORMAT),
        'insurance' => 'standard',
        'pickup_location' => 'skp',
        'return_location' => 'hq',
    ]);
    expect((float) Booking::sole()->total_price)->toBe(100.0); // 1 day
    Booking::query()->delete();

    $this->actingAs($user)->post(route('cars.bookings.store', $car), [
        'start_date' => $pickup->format(DATETIME_LOCAL_FORMAT),
        'end_date' => $oneHourOver->format(DATETIME_LOCAL_FORMAT),
        'insurance' => 'standard',
        'pickup_location' => 'skp',
        'return_location' => 'hq',
    ]);
    expect((float) Booking::sole()->total_price)->toBe(200.0); // rounds up to 2 days
});

// Risk: a car can end up double-booked for overlapping date ranges.
test('overlapping bookings for the same car are rejected', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create(['is_active' => true]);

    Booking::factory()->for($car)->for($user)->create([
        'start_date' => now()->addDays(5),
        'end_date' => now()->addDays(10),
        'status' => BookingStatus::Confirmed,
    ]);

    $response = $this->actingAs($user)->post(route('cars.bookings.store', $car), [
        'start_date' => now()->addDays(7)->format(DATETIME_LOCAL_FORMAT),
        'end_date' => now()->addDays(8)->format(DATETIME_LOCAL_FORMAT),
        'insurance' => 'standard',
        'pickup_location' => 'skp',
        'return_location' => 'hq',
    ]);

    $response->assertSessionHasErrors('start_date');
    expect(Booking::count())->toBe(1);
});

// Risk: old cancelled reservations incorrectly prevent new bookings.
test('a cancelled booking does not block a new booking for the same dates', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create(['is_active' => true]);

    Booking::factory()->for($car)->for($user)->create([
        'start_date' => now()->addDays(5),
        'end_date' => now()->addDays(10),
        'status' => BookingStatus::Cancelled,
    ]);

    $response = $this->actingAs($user)->post(route('cars.bookings.store', $car), [
        'start_date' => now()->addDays(7)->format(DATETIME_LOCAL_FORMAT),
        'end_date' => now()->addDays(8)->format(DATETIME_LOCAL_FORMAT),
        'insurance' => 'standard',
        'pickup_location' => 'skp',
        'return_location' => 'hq',
    ]);

    $response->assertSessionHasNoErrors();
    expect(Booking::where('status', BookingStatus::Pending)->count())->toBe(1);
});

// Risk: past dates, equal dates, or reversed ranges produce invalid rentals or prices.
test('invalid booking date ranges are rejected', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create(['is_active' => true]);

    $this->actingAs($user)->post(route('cars.bookings.store', $car), [
        'start_date' => now()->subHour()->format(DATETIME_LOCAL_FORMAT), // past start time
        'end_date' => now()->addDay()->format(DATETIME_LOCAL_FORMAT),
    ])->assertSessionHasErrors('start_date');

    $sameInstant = now()->addDay()->format(DATETIME_LOCAL_FORMAT);
    $this->actingAs($user)->post(route('cars.bookings.store', $car), [
        'start_date' => $sameInstant,
        'end_date' => $sameInstant, // equal timestamps
    ])->assertSessionHasErrors('end_date');

    $this->actingAs($user)->post(route('cars.bookings.store', $car), [
        'start_date' => now()->addDays(3)->format(DATETIME_LOCAL_FORMAT),
        'end_date' => now()->addDays(2)->format(DATETIME_LOCAL_FORMAT), // reversed range
    ])->assertSessionHasErrors('end_date');

    expect(Booking::count())->toBe(0);
});
