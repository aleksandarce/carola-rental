<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Enums\InsuranceOption;
use App\Models\Booking;
use App\Models\Car;
use App\Models\InsuranceSetting;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * Pickup/return locations and the insurance daily rate are looked up
     * from the database (Location, InsuranceSetting) rather than an enum —
     * both tables are seeded directly by their migrations, so the rows
     * already exist by the time any factory runs, including under
     * RefreshDatabase in tests.
     */
    public function definition(): array
    {
        // Pickup and return share the same time-of-day, so the duration is
        // always a clean multiple of 24 hours by default — fake()->dateTime
        // callers wanting an off-the-hour edge case can override explicitly.
        $start = now()->addDays(fake()->numberBetween(-30, 30))->setTime(fake()->numberBetween(8, 18), 0);
        $end = $start->copy()->addDays(fake()->numberBetween(1, 10));
        $dailyRate = fake()->randomFloat(2, 35, 250);
        $days = Booking::rentalDays($start, $end);
        $insurance = fake()->randomElement(InsuranceOption::cases());
        $insuranceRate = (float) (InsuranceSetting::query()->where('code', $insurance->value)->value('daily_rate') ?? 0);

        return [
            'car_id' => Car::factory(),
            'user_id' => User::factory(),
            'start_date' => $start,
            'end_date' => $end,
            'daily_rate_snapshot' => $dailyRate,
            'total_price' => ($dailyRate * $days) + ($insuranceRate * $days),
            'status' => fake()->randomElement(BookingStatus::cases()),
            'insurance' => $insurance,
            'insurance_daily_rate_snapshot' => $insuranceRate,
            'pickup_location' => Location::query()->forPickup()->active()->inRandomOrder()->value('code'),
            'return_location' => Location::query()->forReturn()->active()->inRandomOrder()->value('code'),
            'notes' => fake()->boolean(30) ? fake()->sentence() : null,
        ];
    }
}
