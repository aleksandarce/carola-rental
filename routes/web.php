<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarBrowseController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/cars', [CarBrowseController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarBrowseController::class, 'show'])->name('cars.show');

// Restores the 'dashboard' route name that Fortify's post-login redirect and
// the starter kit's shared app layout (layouts/app/sidebar.blade.php,
// layouts/app/header.blade.php) both expect to exist. Sends admins to the
// admin panel and everyone else back to the homepage.

Route::get('/dashboard', fn () => auth()->user()->is_admin
    ? redirect()->route('admin.dashboard')
    : redirect()->route('home')
)->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Admin\DashboardController::class)->name('dashboard');
    Route::resource('cars', Admin\CarController::class)->except('show');
    Route::resource('bookings', Admin\BookingController::class)->only(['index', 'update', 'destroy']);
    Route::resource('locations', Admin\LocationController::class)->except('show');
    Route::get('/insurance-settings', [Admin\InsuranceSettingController::class, 'index'])->name('insurance-settings.index');
    Route::put('/insurance-settings/{insurance_setting}', [Admin\InsuranceSettingController::class, 'update'])->name('insurance-settings.update');
});

// 'auth' is required here because bookings.user_id is NOT NULL — a booking
// always belongs to a signed-in user. 'throttle:5,1' permits 5 attempts per
// minute without needing a separately registered named rate limiter.
Route::post('/cars/{car}/bookings', [BookingController::class, 'store'])
    ->name('cars.bookings.store')
    ->middleware(['auth', 'throttle:5,1']);

Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/insurance', [BookingController::class, 'updateInsurance'])->name('bookings.insurance.update');
});

require __DIR__.'/settings.php';
