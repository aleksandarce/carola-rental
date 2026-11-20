<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = Car::all();
        $customers = User::where('is_admin', false)->get();
        $statuses = BookingStatus::cases();

        for ($i = 0; $i < 8; $i++) {
            Booking::factory()
                ->for($cars->random())
                ->for($customers->random())
                ->state([
                    // First pass guarantees every status appears at least once.
                    'status' => $statuses[$i] ?? fake()->randomElement($statuses),
                ])
                ->create();
        }
    }
}
