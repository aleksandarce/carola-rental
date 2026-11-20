<?php

namespace Database\Seeders;

use App\Enums\CarType;
use App\Enums\FuelType;
use App\Enums\Transmission;
use App\Models\Car;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * The featured cars ship with a stock photo from the Carola template.
     * Cars serve images from the 'public' disk (see Car::thumbnail()), so
     * each stock photo is copied onto that disk under cars/ and image_path
     * is set to its disk-relative path — never a raw path into the
     * template's own asset directory.
     */
    public function run(): void
    {
        foreach ($this->featuredCars() as $car) {
            $imagePath = $this->copyStockPhotoToPublicDisk($car['image_path']);

            Car::create([
                ...$car,
                'slug' => Str::slug($car['name']),
                'image_path' => $imagePath,
            ]);
        }

        Car::factory()->count(5)->create();
    }

    /**
     * Copy a Carola template stock photo onto the 'public' disk so it's
     * reachable the same way an admin-uploaded image would be.
     */
    private function copyStockPhotoToPublicDisk(string $templateRelativePath): ?string
    {
        $source = public_path('carola/assets/images/'.$templateRelativePath);

        if (! is_file($source)) {
            return null;
        }

        $diskPath = 'cars/'.basename($templateRelativePath);

        Storage::disk('public')->put($diskPath, File::get($source));

        return $diskPath;
    }

    /**
     * Cars modeled on the ones shown in the supplied Carola template.
     *
     * @return array<int, array<string, mixed>>
     */
    private function featuredCars(): array
    {
        return [
            [
                'name' => 'Toyota Land Cruiser',
                'brand' => 'Toyota',
                'type' => CarType::Suv,
                'daily_rate' => 89.00,
                'seat_capacity' => 6,
                'doors' => 2,
                'large_luggage' => 1,
                'small_luggage' => 2,
                'fuel_type' => FuelType::Petrol,
                'transmission' => Transmission::Automatic,
                'mileage' => '12 kmpl',
                'engine_power' => '260 HP',
                'location' => 'Cardiff',
                'short_description' => 'Three-row SUV with a modern design and upscale interior.',
                'description' => 'A spacious three-row SUV with a modern design, upscale interior, and a plethora of safety features. Ideal for family trips and long-distance touring.',
                'image_path' => 'popular-car/suv.jfif',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Nissan GTR Turbo',
                'brand' => 'Nissan',
                'type' => CarType::Sports,
                'daily_rate' => 145.00,
                'seat_capacity' => 4,
                'doors' => 2,
                'large_luggage' => 1,
                'small_luggage' => 1,
                'fuel_type' => FuelType::Petrol,
                'transmission' => Transmission::Automatic,
                'mileage' => '9 kmpl',
                'engine_power' => '565 HP',
                'location' => 'London',
                'short_description' => 'Compact sports car renowned for its all-wheel-drive turbo engine.',
                'description' => 'The Nissan GTR Turbo pairs a hand-built twin-turbo engine with all-wheel drive for a genuine supercar experience on a rental budget.',
                'image_path' => 'popular-car/sports.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Mitsubishi Portan',
                'brand' => 'Mitsubishi',
                'type' => CarType::Suv,
                'daily_rate' => 76.00,
                'seat_capacity' => 5,
                'doors' => 4,
                'large_luggage' => 1,
                'small_luggage' => 2,
                'fuel_type' => FuelType::Diesel,
                'transmission' => Transmission::Automatic,
                'mileage' => '14 kmpl',
                'engine_power' => '190 HP',
                'location' => 'Bristol',
                'short_description' => 'Luxury compact SUV with a premium interior and advanced technology.',
                'description' => 'A luxury compact SUV featuring a premium interior, advanced driver-assist technology, and confident handling in any weather.',
                'image_path' => 'popular-car/vintage.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Jeep Wagner',
                'brand' => 'Jeep',
                'type' => CarType::Suv,
                'daily_rate' => 82.00,
                'seat_capacity' => 6,
                'doors' => 4,
                'large_luggage' => 2,
                'small_luggage' => 2,
                'fuel_type' => FuelType::Petrol,
                'transmission' => Transmission::Manual,
                'mileage' => '11 kmpl',
                'engine_power' => '270 HP',
                'location' => 'Manchester',
                'short_description' => 'Three-row SUV with a modern design and a plethora of safety features.',
                'description' => 'The Jeep Wagner seats six across three rows without sacrificing cargo room, built for family road trips over any terrain.',
                'image_path' => 'popular-car/vintage.jpg',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'BMW 740L Series',
                'brand' => 'BMW',
                'type' => CarType::Luxury,
                'daily_rate' => 210.00,
                'seat_capacity' => 5,
                'doors' => 4,
                'large_luggage' => 2,
                'small_luggage' => 1,
                'fuel_type' => FuelType::Petrol,
                'transmission' => Transmission::Automatic,
                'mileage' => '10 kmpl',
                'engine_power' => '335 HP',
                'location' => 'London',
                'short_description' => 'Iconic luxury sedan with a classic, powerful engine and agile handling.',
                'description' => 'The BMW 740L Series delivers flagship comfort with a long wheelbase, executive rear seating, and effortless motorway performance.',
                'image_path' => 'popular-car/sedan.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'BMW M5 Competition',
                'brand' => 'BMW',
                'type' => CarType::Sports,
                'daily_rate' => 235.00,
                'seat_capacity' => 5,
                'doors' => 4,
                'large_luggage' => 1,
                'small_luggage' => 1,
                'fuel_type' => FuelType::Petrol,
                'transmission' => Transmission::Automatic,
                'mileage' => '8 kmpl',
                'engine_power' => '625 HP',
                'location' => 'Birmingham',
                'short_description' => 'High-performance sports sedan with towering horsepower.',
                'description' => 'The BMW M5 Competition hides supercar-rivalling power inside a practical four-door body — a genuine everyday performance sedan.',
                'image_path' => 'popular-car/convertible.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Tesla Model 3 Roadstar',
                'brand' => 'Tesla',
                'type' => CarType::Sedan,
                'daily_rate' => 98.00,
                'seat_capacity' => 4,
                'doors' => 4,
                'large_luggage' => 1,
                'small_luggage' => 1,
                'fuel_type' => FuelType::Electric,
                'transmission' => Transmission::Automatic,
                'mileage' => '450 km range',
                'engine_power' => '400 HP',
                'location' => 'Cardiff',
                'short_description' => 'All-electric sedan with instant torque and a minimalist cockpit.',
                'description' => 'The Tesla Model 3 Roadstar trades a fuel tank for instant electric torque, a minimalist interior, and one of the lowest running costs in the fleet.',
                'image_path' => 'popular-car/hatchback.jpg',
                'is_featured' => true,
                'is_active' => true,
            ],
        ];
    }
}
