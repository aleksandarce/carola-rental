<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * All counts/sums are computed in SQL — never fetch every row just to
     * count them in PHP.
     */
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'totalCars' => Car::count(),
            'activeCars' => Car::where('is_active', true)->count(),
            'pendingBookings' => Booking::where('status', BookingStatus::Pending)->count(),
            'confirmedRevenue' => Booking::where('status', BookingStatus::Confirmed)->sum('total_price'),
            'latestBookings' => Booking::with('car')->latest()->limit(5)->get(),
        ]);
    }
}
