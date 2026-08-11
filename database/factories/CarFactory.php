<?php

namespace Database\Factories;

use App\Enums\CarType;
use App\Enums\FuelType;
use App\Enums\Transmission;
use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Toyota Corolla Hybrid',
            'Honda Civic Type R',
            'Ford Focus ST',
            'Hyundai Elantra N',
            'Kia Sportage GT',
            'Mazda CX-5 Sport',
            'Volkswagen Golf GTI',
            'Chevrolet Malibu LT',
            'Nissan Altima SR',
            'Subaru Outback Wilderness',
            'Audi A4 Quattro',
            'Mercedes C-Class',
            'Skoda Octavia RS',
            'Peugeot 3008 GT',
            'Renault Megane RS',
            'Volvo XC60 Recharge',
            'Mini Cooper S',
            'Fiat 500X Sport',
            'Seat Leon Cupra',
            'Opel Astra Ultimate',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'brand' => Str::before($name, ' '),
            'type' => fake()->randomElement(CarType::cases()),
            'daily_rate' => fake()->randomFloat(2, 35, 250),
            'seat_capacity' => fake()->numberBetween(2, 7),
            'doors' => fake()->randomElement([2, 4]),
            'large_luggage' => fake()->numberBetween(0, 2),
            'small_luggage' => fake()->numberBetween(0, 3),
            'fuel_type' => fake()->randomElement(FuelType::cases()),
            'transmission' => fake()->randomElement(Transmission::cases()),
            'mileage' => fake()->numberBetween(8, 20).' kmpl',
            'engine_power' => fake()->numberBetween(90, 500).' HP',
            'location' => fake()->city(),
            'short_description' => fake()->sentence(10),
            'description' => fake()->paragraph(4),
            'image_path' => null,
            'is_featured' => fake()->boolean(30),
            'is_active' => true,
        ];
    }
}
