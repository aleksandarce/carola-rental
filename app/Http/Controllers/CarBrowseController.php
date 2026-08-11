<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\CarType;
use App\Enums\FuelType;
use App\Enums\Transmission;
use App\Models\Car;
use App\Models\InsuranceSetting;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CarBrowseController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', Rule::enum(CarType::class)],
            'transmission' => ['nullable', Rule::enum(Transmission::class)],
            'fuel_type' => ['nullable', Rule::enum(FuelType::class)],
            'seats' => ['nullable', 'integer', 'min:1', 'max:20'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date', 'required_with:end_date'],
            'end_date' => ['nullable', 'date', 'after:start_date', 'required_with:start_date'],
        ]);

        $cars = Car::query()
            ->active()
            ->search($filters['search'] ?? null)
            ->when(
                $filters['type'] ?? null,
                fn ($query, $type) => $query->where('type', $type)
            )
            ->when(
                $filters['transmission'] ?? null,
                fn ($query, $value) => $query->where('transmission', $value)
            )
            ->when(
                $filters['fuel_type'] ?? null,
                fn ($query, $value) => $query->where('fuel_type', $value)
            )
            ->when(
                $filters['seats'] ?? null,
                fn ($query, $seats) => $query->where('seat_capacity', '>=', $seats)
            )
            ->when(
                $filters['max_price'] ?? null,
                fn ($query, $price) => $query->where('daily_rate', '<=', $price)
            )
            ->when(
                $filters['min_price'] ?? null,
                fn ($query, $price) => $query->where('daily_rate', '>=', $price)
            )
            ->when(
                ($filters['start_date'] ?? null) && ($filters['end_date'] ?? null),
                fn ($query) => $query->whereDoesntHave(
                    'bookings',
                    fn ($query) => $query
                        ->overlapping($filters['start_date'], $filters['end_date'])
                        ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
                )
            )
            ->orderBy('name')
            ->paginate(6)
            ->withQueryString();

        // Rounded outward (floor/ceil) so the bounds never exclude a real
        // car's price, and so the filter box shows whole-dollar numbers.
        $priceRange = [
            'min' => (int) floor(Car::query()->active()->min('daily_rate') ?? 0),
            'max' => (int) ceil(Car::query()->active()->max('daily_rate') ?? 0),
        ];

        $seatRange = [
            'min' => (int) (Car::query()->active()->min('seat_capacity') ?? 1),
            'max' => (int) (Car::query()->active()->max('seat_capacity') ?? 20),
        ];

        return view('cars.index', compact('cars', 'priceRange', 'seatRange'));
    }

    public function show(Car $car): View
    {
        abort_unless($car->is_active, 404);

        $pickupLocations = Location::query()->active()->forPickup()->orderBy('label')->get();
        $returnLocations = Location::query()->active()->forReturn()->orderBy('label')->get();
        $insuranceSettings = InsuranceSetting::query()->get()->keyBy('code');

        return view('cars.show', compact('car', 'pickupLocations', 'returnLocations', 'insuranceSettings'));
    }
}
