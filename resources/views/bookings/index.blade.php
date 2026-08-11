@extends('layouts.public')

@section('title', 'My Bookings')

@section('content')

    <section class="breadcrumb-section" style="background-image: url('{{ asset('carola/assets/images/popular-car/koli-nasobrani.jpg') }}');">
        <div class="container">
            <div class="banner-content">
                <h1>My Bookings</h1>
            </div>
        </div>
    </section>

    <section class="pt_120 pb_90">
        <div class="container">

            @if (session('status'))
                <p class="alert-success">{{ session('status') }}</p>
            @endif

            @if (session('error'))
                <p class="alert-error">{{ session('error') }}</p>
            @endif

            @forelse ($bookings as $booking)
                @php
                    $canChange = in_array($booking->status, [\App\Enums\BookingStatus::Pending, \App\Enums\BookingStatus::Confirmed], true);
                @endphp
                <div class="booking-card mb_20">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <strong>{{ $booking->car->name }}</strong>
                        </div>
                        <div class="col-md-3">
                            {{ $booking->start_date->format('j M, G:i') }} &ndash; {{ $booking->end_date->format('j M, G:i') }}
                        </div>
                        <div class="col-md-2">
                            ${{ number_format($booking->total_price, 2) }}
                        </div>
                        <div class="col-md-2">
                            <span class="booking-status booking-status-{{ $booking->status->value }}">
                                {{ str($booking->status->value)->headline() }}
                            </span>
                        </div>
                        <div class="col-md-2 text-end">
                            @if ($canChange)
                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking?');" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-style-three">Cancel</button>
                                </form>
                            @endif

                            @if ($booking->status === \App\Enums\BookingStatus::Confirmed)
                                <button type="button" class="btn-style-three">Ask for Refund</button>
                            @endif
                        </div>
                    </div>

                    <div class="row align-items-center mt_20">
                        <div class="col-md-6">
                            {{-- Frozen at booking/change time — never a live price lookup,
                                 so this always reflects what was actually charged. --}}
                            <span class="booking-insurance-label">Insurance:</span>
                            {{ str($booking->insurance->value)->headline() }}
                            @if ($booking->insurance_daily_rate_snapshot > 0)
                                (+${{ number_format($booking->insurance_daily_rate_snapshot, 2) }}/day)
                            @else
                                (Free)
                            @endif
                        </div>
                        @if ($canChange)
                            <div class="col-md-6">
                                <form method="POST" action="{{ route('bookings.insurance.update', $booking) }}" class="booking-insurance-form">
                                    @csrf
                                    <select name="insurance">
                                        @foreach (\App\Enums\InsuranceOption::cases() as $option)
                                            @php $setting = $insuranceSettings[$option->value]; @endphp
                                            <option value="{{ $option->value }}" @selected($booking->insurance === $option)>
                                                {{ $setting->label }} &mdash; {{ $setting->daily_rate > 0 ? '+$'.number_format($setting->daily_rate, 2).'/day' : 'Free' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn-style-three">Update</button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="row align-items-center mt_20">
                        <div class="col-md-12">
                            <span class="booking-insurance-label">Route:</span>
                            {{ $booking->pickupLocation->label }} &rarr; {{ $booking->returnLocation->label }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p>You have no bookings yet.</p>
                    <a href="{{ route('cars.index') }}" class="btn-style-three">Browse Cars</a>
                </div>
            @endforelse

            @if ($bookings->hasPages())
                <div class="pagination-wrapper">
                    {{ $bookings->links('bootstrap-5') }}
                </div>
            @endif

        </div>
    </section>

@endsection
