<?php

namespace App\Models;

use App\Enums\LocationScope;
use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string $label
 * @property LocationScope $applies_to
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'code',
    'label',
    'applies_to',
    'is_active',
])]
class Location extends Model
{
    /** @use HasFactory<LocationFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'applies_to' => LocationScope::class,
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope a query to locations visible to customers.
     *
     * @param  Builder<Location>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to locations usable as a pickup point.
     *
     * @param  Builder<Location>  $query
     */
    public function scopeForPickup(Builder $query): void
    {
        $query->whereIn('applies_to', [LocationScope::Pickup, LocationScope::Both]);
    }

    /**
     * Scope a query to locations usable as a return point.
     *
     * @param  Builder<Location>  $query
     */
    public function scopeForReturn(Builder $query): void
    {
        $query->whereIn('applies_to', [LocationScope::Return, LocationScope::Both]);
    }

    /**
     * Bookings that picked this location up, matched by code — used only to
     * guard deletion; never eager-loaded for display.
     *
     * @return HasMany<Booking, $this>
     */
    public function pickupBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'pickup_location', 'code');
    }

    /**
     * Bookings that returned to this location, matched by code — see
     * pickupBookings().
     *
     * @return HasMany<Booking, $this>
     */
    public function returnBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'return_location', 'code');
    }
}
