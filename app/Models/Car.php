<?php

namespace App\Models;

use App\Enums\CarType;
use App\Enums\FuelType;
use App\Enums\Transmission;
use Database\Factories\CarFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $brand
 * @property CarType $type
 * @property numeric-string $daily_rate
 * @property int $seat_capacity
 * @property int $doors
 * @property int $large_luggage
 * @property int $small_luggage
 * @property FuelType $fuel_type
 * @property Transmission $transmission
 * @property string|null $mileage
 * @property string|null $engine_power
 * @property string $location
 * @property string $short_description
 * @property string $description
 * @property string|null $image_path
 * @property bool $is_featured
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read string $thumbnail
 */
#[Fillable([
    'name',
    'slug',
    'brand',
    'type',
    'daily_rate',
    'seat_capacity',
    'doors',
    'large_luggage',
    'small_luggage',
    'fuel_type',
    'transmission',
    'mileage',
    'engine_power',
    'location',
    'short_description',
    'description',
    'image_path',
    'is_featured',
    'is_active',
])]
class Car extends Model
{
    /** @use HasFactory<CarFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'daily_rate' => 'decimal:2',
            'seat_capacity' => 'integer',
            'doors' => 'integer',
            'large_luggage' => 'integer',
            'small_luggage' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'type' => CarType::class,
            'fuel_type' => FuelType::class,
            'transmission' => Transmission::class,
        ];
    }

    /**
     * Bind route parameters by slug instead of the numeric id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the bookings made for this car.
     *
     * @return HasMany<Booking, $this>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope a query to cars currently visible to customers.
     *
     * @param  Builder<Car>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * @param  Builder<Car>  $query
     */
    public function scopeSearch(Builder $query, ?string $term): void
    {
        $query->when($term, fn ($q) => $q->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('brand', 'like', "%{$term}%");
        }));
    }

    /**
     * Full URL to the car's listing image, falling back to a template stock photo.
     *
     * Uploaded images live on the 'public' disk (storage/app/public/cars, via
     * the storage:link symlink) — resolved with Storage::url(), never a raw
     * asset() path into the Carola template's own image directory.
     *
     * @return Attribute<string, never>
     */
    protected function thumbnail(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image_path
                ? Storage::url($this->image_path)
                : asset('carola/assets/images/popular-car/popular-car-1.jpg'),
        );
    }
}
