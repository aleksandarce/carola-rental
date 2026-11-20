<?php

use App\Enums\CarType;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// Risk: validated data is not persisted correctly, or CRUD uses unsafe input.
test('admin can create and update a car with validated data, and invalid input is rejected', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['is_admin' => true]);

    $payload = [
        'name' => 'Toyota Supra',
        'brand' => 'Toyota',
        'type' => CarType::Sports->value,
        'daily_rate' => 120.50,
        'seat_capacity' => 4,
        'doors' => 2,
        'fuel_type' => 'Petrol',
        'transmission' => 'Automatic',
        'location' => 'London',
        'short_description' => 'Fast and fun.',
        'description' => 'A proper sports car for a weekend away.',
        'image' => UploadedFile::fake()->image('supra.jpg'),
        'is_featured' => true,
        'is_active' => true,
    ];

    // Admin can create a car, and the stored values match what was validated.
    $this->actingAs($admin)
        ->post(route('admin.cars.store'), $payload)
        ->assertRedirect(route('admin.cars.index'));

    $car = Car::sole();
    expect($car->name)->toBe('Toyota Supra');
    expect($car->type)->toBe(CarType::Sports);
    expect((float) $car->daily_rate)->toBe(120.50);
    expect($car->is_active)->toBeTrue();
    Storage::disk('public')->assertExists($car->image_path);

    // Admin can update it.
    $this->actingAs($admin)
        ->put(route('admin.cars.update', $car), [
            ...$payload,
            'name' => 'Toyota Supra MK5',
            'daily_rate' => 135,
            'image' => UploadedFile::fake()->image('supra2.jpg'),
        ])
        ->assertRedirect(route('admin.cars.index'));

    expect($car->refresh()->name)->toBe('Toyota Supra MK5');
    expect((float) $car->daily_rate)->toBe(135.0);

    // Invalid input is rejected — nothing new is persisted.
    $this->actingAs($admin)
        ->post(route('admin.cars.store'), [
            ...$payload,
            'name' => '',
            'daily_rate' => -10,
            'image' => null,
        ])
        ->assertSessionHasErrors(['name', 'daily_rate']);

    expect(Car::count())->toBe(1);
});
