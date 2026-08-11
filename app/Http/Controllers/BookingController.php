<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\InsuranceOption;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingInsuranceRequest;
use App\Models\Booking;
use App\Models\Car;
use App\Models\InsuranceSetting;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * List the authenticated user's own bookings.
     */
    public function index(): View
    {
        $bookings = Booking::query()
            ->where('user_id', Auth::id())
            ->with(['car', 'pickupLocation', 'returnLocation'])
            ->latest()
            ->paginate(10);

        $insuranceSettings = InsuranceSetting::query()->get()->keyBy('code');

        return view('bookings.index', compact('bookings', 'insuranceSettings'));
    }

    /**
     * Store a new booking for the given car.
     *
     * The price is always computed here from the car's current daily_rate —
     * never trusted from the browser. The availability check and the write
     * happen inside one transaction so a concurrent request can't slip a
     * conflicting booking in between the check and the create.
     */
    public function store(StoreBookingRequest $request, Car $car): RedirectResponse
    {
        abort_unless($car->is_active, 404);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $car) {
            // The actual requested hour is kept, not truncated to midnight —
            // it's part of what the customer is booking and what the price
            // calculation below depends on.
            $start = Carbon::parse($validated['start_date']);
            $end = Carbon::parse($validated['end_date']);

            $hasConflict = $car->bookings()
                ->overlapping($start->toDateTimeString(), $end->toDateTimeString())
                ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
                ->exists();

            if ($hasConflict) {
                throw ValidationException::withMessages([
                    'start_date' => 'This car is already booked for part of the selected dates.',
                ]);
            }

            $days = Booking::rentalDays($start, $end);
            $insurance = InsuranceOption::from($validated['insurance']);
            $insuranceRate = $this->insuranceDailyRate($insurance);

            $car->bookings()->create([
                'user_id' => Auth::id(),
                'start_date' => $start->toDateTimeString(),
                'end_date' => $end->toDateTimeString(),
                'daily_rate_snapshot' => $car->daily_rate,
                'total_price' => ($days * $car->daily_rate) + ($days * $insuranceRate),
                'status' => BookingStatus::Pending,
                'insurance' => $insurance,
                'insurance_daily_rate_snapshot' => $insuranceRate,
                'notes' => $validated['notes'] ?? null,
                'pickup_location' => $validated['pickup_location'],
                'return_location' => $validated['return_location'],
            ]);
        });

        return back()->with('status', 'Booking request sent — we will confirm availability shortly.');
    }

    /**
     * Change the insurance package on one of the authenticated user's own
     * bookings.
     *
     * Only pending or confirmed bookings are eligible — same set that can
     * be cancelled. The car-rental portion of the price never moves; only
     * the insurance portion is recalculated. If the rental has already
     * started, the customer only pays the new rate for the time remaining
     * from right now, not retroactively for time already used. A price
     * change on a previously confirmed booking sends it back to Pending so
     * the admin re-approves the new total.
     */
    public function updateInsurance(UpdateBookingInsuranceRequest $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === Auth::id(), 403);

        if (! in_array($booking->status, [BookingStatus::Pending, BookingStatus::Confirmed], true)) {
            return back()->with('error', 'This booking can no longer be changed.');
        }

        $newInsurance = InsuranceOption::from($request->validated('insurance'));

        if ($newInsurance === $booking->insurance) {
            return back()->with('status', 'That is already your selected insurance package.');
        }

        $now = Carbon::now();
        $days = Booking::rentalDays($booking->start_date, $booking->end_date);
        $insuranceDays = $now->gt($booking->start_date)
            ? Booking::rentalDays($now, $booking->end_date)
            : $days;
        $insuranceRate = $this->insuranceDailyRate($newInsurance);

        $booking->update([
            'insurance' => $newInsurance,
            'insurance_daily_rate_snapshot' => $insuranceRate,
            'total_price' => ($days * $booking->daily_rate_snapshot) + ($insuranceDays * $insuranceRate),
            'status' => $booking->status === BookingStatus::Confirmed
                ? BookingStatus::Pending
                : $booking->status,
        ]);

        return back()->with('status', 'Insurance package updated.');
    }

    /**
     * The current admin-configured daily rate for an insurance package.
     *
     * Reads from the insurance_settings table (admin-editable) rather than
     * the InsuranceOption enum — the enum only fixes which 3 codes exist.
     */
    private function insuranceDailyRate(InsuranceOption $option): float
    {
        return (float) (
            InsuranceSetting::query()->where('code', $option->value)->value('daily_rate') ?? 0
        );
    }

    /**
     * Cancel one of the authenticated user's own bookings.
     *
     * Only pending or confirmed bookings can be cancelled — anything else
     * (rejected, cancelled, completed) is already a closed record. The
     * customer never submits a status; this always sets Cancelled.
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === Auth::id(), 403);

        if (! in_array($booking->status, [BookingStatus::Pending, BookingStatus::Confirmed], true)) {
            return back()->with('error', 'This booking can no longer be cancelled.');
        }

        $booking->update(['status' => BookingStatus::Cancelled]);

        return back()->with('status', 'Booking cancelled.');
    }
}
