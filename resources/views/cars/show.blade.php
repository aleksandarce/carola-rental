@extends('layouts.public')

@section('title', $car->name)

@section('content')

    <section class="car-details-section pt_120 pb_120">
        <div class="container">
            <div class="car-details-image mb_40">
                <img src="{{ $car->thumbnail }}" alt="{{ $car->name }}">
            </div>

            <div class="car-details-info-outer">
                <div class="row">
                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                        <div class="car-details-infobox">
                            <div class="car-details-feature-list">
                                <h3 class="car-details-title">{{ $car->name }}</h3>
                                <div class="row">
                                    <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6">
                                        <div class="car-details-feature-box">
                                            <div class="car-details-feature-icon"><i class="icon-8"></i></div>
                                            <div class="car-details-feature-content">
                                                <h6>Seats</h6>
                                                <span>{{ $car->seat_capacity }} People</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6">
                                        <div class="car-details-feature-box">
                                            <div class="car-details-feature-icon"><i class="icon-29"></i></div>
                                            <div class="car-details-feature-content">
                                                <h6>Doors</h6>
                                                <span>{{ $car->doors }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6">
                                        <div class="car-details-feature-box">
                                            <div class="car-details-feature-icon"><i class="icon-6"></i></div>
                                            <div class="car-details-feature-content">
                                                <h6>Fuel Type</h6>
                                                <span>{{ $car->fuel_type->value }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6">
                                        <div class="car-details-feature-box">
                                            <div class="car-details-feature-icon"><i class="icon-30"></i></div>
                                            <div class="car-details-feature-content">
                                                <h6>Transmission</h6>
                                                <span>{{ $car->transmission->value }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($car->engine_power)
                                        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6">
                                            <div class="car-details-feature-box">
                                                <div class="car-details-feature-icon"><i class="icon-4"></i></div>
                                                <div class="car-details-feature-content">
                                                    <h6>Engine Power</h6>
                                                    <span>{{ $car->engine_power }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($car->mileage)
                                        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6">
                                            <div class="car-details-feature-box">
                                                <div class="car-details-feature-icon"><i class="icon-25"></i></div>
                                                <div class="car-details-feature-content">
                                                    <h6>Mileage</h6>
                                                    <span>{{ $car->mileage }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($car->large_luggage)
                                        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6">
                                            <div class="car-details-feature-box">
                                                <div class="car-details-feature-icon"><i class="icon-18"></i></div>
                                                <div class="car-details-feature-content">
                                                    <h6>Large Luggage</h6>
                                                    <span>{{ $car->large_luggage }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($car->small_luggage)
                                        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6">
                                            <div class="car-details-feature-box">
                                                <div class="car-details-feature-icon"><i class="icon-26"></i></div>
                                                <div class="car-details-feature-content">
                                                    <h6>Small Luggage</h6>
                                                    <span>{{ $car->small_luggage }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="car-details-about-box">
                                <h4 class="car-details-sub-title">About this Car</h4>
                                <p class="car-details-about-text">{{ $car->description }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="car-details-right-sidebar">
                            <h4 class="car-details-sub-title">Book this Car</h4>
                            <p class="price-per-day">${{ number_format($car->daily_rate, 2) }} / day</p>

                            @if (session('status'))
                                <p class="alert-success">{{ session('status') }}</p>
                            @endif

                            @auth
                                <form method="POST" action="{{ route('cars.bookings.store', $car) }}">
                                    @csrf
                                    <div class="select-date-box mb_20">
                                        <label for="start_date">Pick-up Date &amp; Time</label>
                                        <input type="datetime-local" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                        @error('start_date')
                                            <span class="error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="select-date-box mb_20">
                                        <label for="end_date">Return Date &amp; Time</label>
                                        <input type="datetime-local" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                        @error('end_date')
                                            <span class="error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <p class="booking-note mb_20">Rentals are billed in full 24-hour blocks from your exact pickup time — any extra time beyond a full day rounds up to the next day.</p>

                                    <div class="select-date-box mb_20">
                                        <label for="pickup_location">Pickup Location</label>
                                        <select id="pickup_location" name="pickup_location" required>
                                            <option value="" disabled @selected(! old('pickup_location'))>Select pickup location</option>
                                            @foreach ($pickupLocations as $location)
                                                <option value="{{ $location->code }}" @selected(old('pickup_location') === $location->code)>{{ $location->label }}</option>
                                            @endforeach
                                        </select>
                                        @error('pickup_location')
                                            <span class="error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="select-date-box mb_20">
                                        <label for="return_location">Return Location</label>
                                        <select id="return_location" name="return_location" required>
                                            <option value="" disabled @selected(! old('return_location'))>Select return location</option>
                                            @foreach ($returnLocations as $location)
                                                <option value="{{ $location->code }}" @selected(old('return_location') === $location->code)>{{ $location->label }}</option>
                                            @endforeach
                                        </select>
                                        @error('return_location')
                                            <span class="error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="insurance-plan-group mb_20">
                                        <label>Insurance Package</label>
                                        <div class="insurance-plan-list">
                                            @foreach (\App\Enums\InsuranceOption::cases() as $option)
                                                @php $setting = $insuranceSettings[$option->value]; @endphp
                                                <label class="insurance-plan-option">
                                                    <input type="radio" name="insurance" value="{{ $option->value }}" @checked(old('insurance', 'standard') === $option->value) required>
                                                    <span class="insurance-plan-details">
                                                        <span class="insurance-plan-heading">
                                                            <span class="insurance-plan-name">{{ $setting->label }}</span>
                                                            <span class="insurance-plan-price">
                                                                {{ $setting->daily_rate > 0 ? '+$'.number_format($setting->daily_rate, 2).'/day' : 'Free' }}
                                                            </span>
                                                        </span>
                                                        <span class="insurance-plan-coverage">{{ $setting->coverage }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('insurance')
                                            <span class="error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn-style-four full-width">Book This Car</button>
                                </form>
                                <p class="booking-note">Final price is confirmed after availability is checked.</p>
                            @else
                                <p class="booking-note">
                                    <a href="{{ route('login') }}">Log in</a> to book this car.
                                </p>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
