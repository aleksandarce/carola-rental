@component('layouts.admin', ['title' => 'Bookings'])

    @if (session('status'))
        <p class="mb-6 rounded-lg bg-success-50 px-4 py-3 text-theme-sm text-success-600 dark:bg-success-500/15 dark:text-success-500">
            {{ session('status') }}
        </p>
    @endif

    @if (session('error'))
        <p class="mb-6 rounded-lg bg-error-50 px-4 py-3 text-theme-sm text-error-600 dark:bg-error-500/15 dark:text-error-500">
            {{ session('error') }}
        </p>
    @endif

    @if ($errors->any())
        <p class="mb-6 rounded-lg bg-error-50 px-4 py-3 text-theme-sm text-error-600 dark:bg-error-500/15 dark:text-error-500">
            {{ $errors->first() }}
        </p>
    @endif

    <form method="GET" action="{{ route('admin.bookings.index') }}" class="mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label class="mb-1.5 block text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</label>
            <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                <option value="">Any</option>
                @foreach (\App\Enums\BookingStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                        {{ str($status->value)->headline() }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1.5 block text-theme-xs font-medium text-gray-500 dark:text-gray-400">Customer Email</label>
            <input type="text" name="customer_email" value="{{ request('customer_email') }}" placeholder="name@example.com"
                class="rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
        </div>

        <div>
            <label class="mb-1.5 block text-theme-xs font-medium text-gray-500 dark:text-gray-400">Car</label>
            <select name="car_id" class="rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                <option value="">Any</option>
                @foreach ($cars as $id => $name)
                    <option value="{{ $id }}" @selected(request('car_id') == $id)>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1.5 block text-theme-xs font-medium text-gray-500 dark:text-gray-400">From</label>
            <input type="date" name="from" value="{{ request('from') }}"
                class="rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
        </div>

        <div>
            <label class="mb-1.5 block text-theme-xs font-medium text-gray-500 dark:text-gray-400">To</label>
            <input type="date" name="to" value="{{ request('to') }}"
                class="rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
        </div>

        <div>
            <label class="mb-1.5 block text-theme-xs font-medium text-gray-500 dark:text-gray-400">Pickup Location</label>
            <select name="pickup_location" class="rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                <option value="">Any</option>
                @foreach ($pickupLocations as $location)
                    <option value="{{ $location->code }}" @selected(request('pickup_location') === $location->code)>{{ $location->label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1.5 block text-theme-xs font-medium text-gray-500 dark:text-gray-400">Return Location</label>
            <select name="return_location" class="rounded-lg border border-gray-300 px-3 py-2 text-theme-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                <option value="">Any</option>
                @foreach ($returnLocations as $location)
                    <option value="{{ $location->code }}" @selected(request('return_location') === $location->code)>{{ $location->label }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
            Filter
        </button>

        @if (request()->anyFilled(['status', 'customer_email', 'car_id', 'from', 'to', 'pickup_location', 'return_location']))
            <a href="{{ route('admin.bookings.index') }}" class="text-theme-sm text-gray-500 hover:underline dark:text-gray-400">
                Clear
            </a>
        @endif
    </form>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
        <div class="w-full overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-y border-gray-100 dark:border-gray-800">
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Ref</p></th>
                        <x-admin.sortable-header route="admin.bookings.index" column="customer" label="Customer" :current-sort="$sort" :current-direction="$direction" />
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Car</p></th>
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Dates</p></th>
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Total</p></th>
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Insurance</p></th>
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Route</p></th>
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</p></th>
                        <x-admin.sortable-header route="admin.bookings.index" column="created_at" label="Created" :current-sort="$sort" :current-direction="$direction" />
                        <th class="py-3 text-right"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Actions</p></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($bookings as $booking)
                        @php
                            $isOpen = in_array($booking->status, [
                                \App\Enums\BookingStatus::Pending,
                                \App\Enums\BookingStatus::Confirmed,
                            ], true);
                            $canDelete = in_array($booking->status, [
                                \App\Enums\BookingStatus::Pending,
                                \App\Enums\BookingStatus::Rejected,
                            ], true);
                        @endphp
                        <tr>
                            <td class="px-3 py-3"><p class="text-theme-sm text-gray-500 dark:text-gray-400">#{{ $booking->id }}</p></td>
                            <td class="px-3 py-3">
                                <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $booking->user->name }}</p>
                                <p class="text-theme-xs text-gray-500 dark:text-gray-400">{{ $booking->user->email }}</p>
                            </td>
                            <td class="px-3 py-3"><p class="text-theme-sm text-gray-500 dark:text-gray-400">{{ $booking->car->name }}</p></td>
                            <td class="px-3 py-3">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                                    {{ $booking->start_date->format('j M, G:i') }} &ndash; {{ $booking->end_date->format('j M, Y G:i') }}
                                </p>
                            </td>
                            <td class="px-3 py-3"><p class="text-theme-sm text-gray-500 dark:text-gray-400">${{ number_format($booking->total_price, 2) }}</p></td>
                            <td class="px-3 py-3">
                                {{-- Frozen at booking/change time, not a live settings lookup —
                                     shows exactly what this booking was actually charged. --}}
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">{{ str($booking->insurance->value)->headline() }}</p>
                                @if ($booking->insurance_daily_rate_snapshot > 0)
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">+${{ number_format($booking->insurance_daily_rate_snapshot, 2) }}/day</p>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                                    {{ $booking->pickupLocation->label }} &rarr; {{ $booking->returnLocation->label }}
                                </p>
                            </td>
                            <td class="px-3 py-3">
                                @if ($isOpen)
                                    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="rounded-lg border border-gray-300 px-2 py-1 text-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                            @foreach (\App\Enums\BookingStatus::cases() as $status)
                                                <option value="{{ $status->value }}" @selected($booking->status === $status)>
                                                    {{ str($status->value)->headline() }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="text-theme-xs font-medium text-brand-500 hover:underline">Save</button>
                                    </form>
                                @else
                                    <p @class([
                                        'w-fit rounded-full px-2 py-0.5 text-theme-xs font-medium',
                                        'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' => $booking->status === \App\Enums\BookingStatus::Completed,
                                        'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500' => in_array($booking->status, [\App\Enums\BookingStatus::Rejected, \App\Enums\BookingStatus::Cancelled], true),
                                    ])>
                                        {{ str($booking->status->value)->headline() }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-3 py-3"><p class="text-theme-sm text-gray-500 dark:text-gray-400">{{ $booking->created_at->format('j M, Y') }}</p></td>
                            <td class="px-3 py-3">
                                @if ($canDelete)
                                    <form
                                        method="POST"
                                        action="{{ route('admin.bookings.destroy', $booking) }}"
                                        onsubmit="return confirm('Delete booking #{{ $booking->id }}? This cannot be undone.');"
                                        class="flex justify-end"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-theme-sm font-medium text-error-600 hover:underline">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-6 text-center text-theme-sm text-gray-500 dark:text-gray-400">
                                No bookings match your filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    </div>

@endcomponent
