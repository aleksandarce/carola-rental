<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::enum(BookingStatus::class)],
            'customer_email' => ['nullable', 'string', 'max:255'],
            'car_id' => ['nullable', 'integer', 'exists:cars,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'pickup_location' => ['nullable', 'string', 'exists:locations,code'],
            'return_location' => ['nullable', 'string', 'exists:locations,code'],
            // Whitelisted — 'customer' isn't a real column (it's users.name,
            // reached via a join below), never passed straight into orderBy().
            'sort' => ['nullable', 'in:customer,created_at'],
            'direction' => ['nullable', 'in:asc,desc'],
        ]);

        $sort = $filters['sort'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';

        $bookings = Booking::query()
            ->with(['car', 'user', 'pickupLocation', 'returnLocation'])
            ->when(
                $filters['status'] ?? null,
                fn ($query, $status) => $query->where('status', $status)
            )
            ->when(
                $filters['customer_email'] ?? null,
                fn ($query, $email) => $query->whereHas(
                    'user',
                    fn ($query) => $query->where('email', 'like', "%{$email}%")
                )
            )
            ->when(
                $filters['car_id'] ?? null,
                fn ($query, $carId) => $query->where('car_id', $carId)
            )
            ->when(
                ($filters['from'] ?? null) || ($filters['to'] ?? null),
                fn ($query) => $query->overlapping($filters['from'] ?? null, $filters['to'] ?? null)
            )
            ->when(
                $filters['pickup_location'] ?? null,
                fn ($query, $code) => $query->where('pickup_location', $code)
            )
            ->when(
                $filters['return_location'] ?? null,
                fn ($query, $code) => $query->where('return_location', $code)
            )
            ->when(
                $sort === 'customer',
                // 'bookings.*' avoids column-name collisions with users
                // (both tables have id, created_at, updated_at, ...).
                fn ($query) => $query->join('users', 'users.id', '=', 'bookings.user_id')
                    ->select('bookings.*')
                    ->orderBy('users.name', $direction),
                fn ($query) => $query->orderBy($sort, $direction)
            )
            ->paginate(15)
            ->withQueryString();

        $cars = Car::orderBy('name')->pluck('name', 'id');
        $pickupLocations = Location::query()->forPickup()->orderBy('label')->get();
        $returnLocations = Location::query()->forReturn()->orderBy('label')->get();

        return view('admin.bookings.index', compact(
            'bookings', 'cars', 'sort', 'direction', 'pickupLocations', 'returnLocations'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * Only accepts moves allowed by allowedTransitions() — the booking's
     * current status decides what it may become next, regardless of what
     * the client submits.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        $newStatus = BookingStatus::from($request->validated('status'));

        if (! in_array($newStatus, $this->allowedTransitions($booking->status), true)) {
            throw ValidationException::withMessages([
                'status' => "Cannot change status from {$booking->status->value} to {$newStatus->value}.",
            ]);
        }

        $booking->update(['status' => $newStatus]);

        return redirect()
            ->route('admin.bookings.index')
            ->with('status', 'Booking status updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * Deletion is restricted to pending/rejected bookings — anything
     * confirmed/cancelled/completed is a business record and should be
     * cancelled (a status change), not deleted.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        if (! in_array($booking->status, [BookingStatus::Pending, BookingStatus::Rejected], true)) {
            return back()->with('error', 'Only pending or rejected bookings can be deleted.');
        }

        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('status', 'Booking deleted.');
    }

    /**
     * The statuses a booking may move to from its current status.
     *
     * @return array<int, BookingStatus>
     */
    private function allowedTransitions(BookingStatus $status): array
    {
        return match ($status) {
            BookingStatus::Pending => [BookingStatus::Confirmed, BookingStatus::Rejected, BookingStatus::Cancelled],
            BookingStatus::Confirmed => [BookingStatus::Completed, BookingStatus::Cancelled],
            default => [],
        };
    }
}
