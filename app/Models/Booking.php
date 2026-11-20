<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\InsuranceOption;
use Carbon\CarbonInterface;
use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $car_id
 * @property int $user_id
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property numeric-string $daily_rate_snapshot
 * @property numeric-string $total_price
 * @property BookingStatus $status
 * @property InsuranceOption $insurance
 * @property numeric-string $insurance_daily_rate_snapshot
 * @property string|null $pickup_location
 * @property string|null $return_location
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Car $car
 * @property-read User $user
 * @property-read Location|null $pickupLocation
 * @property-read Location|null $returnLocation
 */
#[Fillable([
    'car_id',
    'user_id',
    'start_date',
    'end_date',
    'daily_rate_snapshot',
    'total_price',
    'status',
    'insurance',
    'insurance_daily_rate_snapshot',
    'pickup_location',
    'return_location',
    'notes',
])]
class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'daily_rate_snapshot' => 'decimal:2',
            'total_price' => 'decimal:2',
            'status' => BookingStatus::class,
            'insurance' => InsuranceOption::class,
            'insurance_daily_rate_snapshot' => 'decimal:2',
        ];
    }

    /**
     * Get the car this booking is for.
     *
     * @return BelongsTo<Car, $this>
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Get the user who made this booking.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The number of billable days between a pickup and return datetime.
     *
     * Billing is by 24-hour block, not calendar day: pickup 15:00 today to
     * return 15:00 tomorrow is exactly 24 hours and charges 1 day; any time
     * beyond a full 24-hour multiple rounds up to an extra day (e.g. 15:00
     * to 16:00 the next day is 1 day and 1 hour, charged as 2 days).
     *
     * Carbon 3's diffInDays() already returns a precise fractional value,
     * so this only needs to round it up — no manual hour/minute math.
     *
     * Typed against CarbonInterface, not the concrete Carbon class — the
     * app configures CarbonImmutable as the default date class
     * (AppServiceProvider), so now()/today() return CarbonImmutable, while
     * Carbon::parse() calls elsewhere return plain mutable Carbon.
     */
    public static function rentalDays(CarbonInterface $start, CarbonInterface $end): int
    {
        return max(1, (int) ceil($start->diffInDays($end)));
    }

    /**
     * Get the pickup location, matched by its code rather than an id —
     * pickup_location stores the Location's stable 'code', not its
     * auto-incrementing key.
     *
     * @return BelongsTo<Location, $this>
     */
    public function pickupLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'pickup_location', 'code');
    }

    /**
     * Get the return location, matched by code — see pickupLocation().
     *
     * @return BelongsTo<Location, $this>
     */
    public function returnLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'return_location', 'code');
    }

    /**
     * Scope a query to bookings whose date range overlaps [$from, $to].
     *
     * Used both by the availability check when creating a booking and by
     * the admin listing's date-range filter — same predicate, one place.
     * Either bound may be null: with only $from, this degrades to
     * "still active on/after $from"; with only $to, "already active by
     * $to"; with neither, the scope is a no-op.
     *
     * @param  Builder<Booking>  $query
     */
    public function scopeOverlapping(Builder $query, ?string $from, ?string $to): void
    {
        $query
            ->when($to, fn ($q) => $q->where('start_date', '<=', $to))
            ->when($from, fn ($q) => $q->where('end_date', '>=', $from));
    }
}
