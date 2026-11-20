<?php

namespace Database\Factories;

use App\Enums\LocationScope;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->slug(2),
            'label' => fake()->city(),
            'applies_to' => fake()->randomElement(LocationScope::cases()),
            'is_active' => true,
        ];
    }
}
