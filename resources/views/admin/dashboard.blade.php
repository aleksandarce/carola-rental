@component('layouts.admin', ['title' => 'Dashboard'])

    {{-- Metric cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <span class="text-sm text-gray-500 dark:text-gray-400">Total Cars</span>
            <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">{{ $totalCars }}</h4>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <span class="text-sm text-gray-500 dark:text-gray-400">Active Cars</span>
            <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">{{ $activeCars }}</h4>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <span class="text-sm text-gray-500 dark:text-gray-400">Pending Bookings</span>
            <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">{{ $pendingBookings }}</h4>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <span class="text-sm text-gray-500 dark:text-gray-400">Confirmed Revenue</span>
            <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">${{ number_format($confirmedRevenue, 2) }}</h4>
        </div>
    </div>

    {{-- Links to management --}}
    <div class="mb-6 flex flex-wrap gap-3">
        <a
            href="{{ route('admin.cars.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
        >
            Manage Cars
        </a>
        <a
            href="{{ route('admin.bookings.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
        >
            Manage Bookings
        </a>
    </div>

    {{-- Latest bookings --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Latest Bookings</h3>

        <div class="w-full overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-y border-gray-100 dark:border-gray-800">
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Car</p></th>
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Dates</p></th>
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Total</p></th>
                        <th class="py-3 text-left"><p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</p></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($latestBookings as $booking)
                        <tr>
                            <td class="py-3">
                                <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $booking->car->name }}</p>
                            </td>
                            <td class="py-3">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                                    {{ $booking->start_date->format('j M, G:i') }} &ndash; {{ $booking->end_date->format('j M, G:i') }}
                                </p>
                            </td>
                            <td class="py-3">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">${{ number_format($booking->total_price, 2) }}</p>
                            </td>
                            <td class="py-3">
                                <p @class([
                                    'w-fit rounded-full px-2 py-0.5 text-theme-xs font-medium',
                                    'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' => in_array($booking->status, [\App\Enums\BookingStatus::Confirmed, \App\Enums\BookingStatus::Completed]),
                                    'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500' => $booking->status === \App\Enums\BookingStatus::Pending,
                                    'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500' => in_array($booking->status, [\App\Enums\BookingStatus::Rejected, \App\Enums\BookingStatus::Cancelled]),
                                ])>
                                    {{ str($booking->status->value)->headline() }}
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-theme-sm text-gray-500 dark:text-gray-400">
                                No bookings yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endcomponent
