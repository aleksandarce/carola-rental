@extends('layouts.public')

@section('title', 'Home')

@section('content')

    {{-- Small hero --}}
    <section class="hero-section" style="background-image: url('{{ asset('carola/assets/images/popular-car/koli-nasobrani.jpg') }}');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="hero-content">
                        <h6 class="d-inline-block">Car Rental</h6>
                        <h1>Find Affordable Dream Cars for Rental</h1>
                        <p>Fulfill your automotive fantasies without breaking the bank. Browse our fleet for an affordable, comfortable ride.</p>
                        <a href="{{ route('cars.index') }}" class="btn-style-two">Browse Cars</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Car search form --}}
    <section class="booking-section">
        <div class="container">
            <div class="filter-wrapper">
                <div class="filter-group">
                    <form action="{{ route('cars.index') }}" method="GET">
                        <div class="filter-area">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                    <div class="divider">
                                        <div class="single-search-box">
                                            <div class="icon"><i class="icon-24"></i></div>
                                            <div class="searchbox-input">
                                                <label for="search">Search Cars</label>
                                                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search by name or brand">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                    <div class="divider">
                                        <div class="single-search-box">
                                            <div class="icon"><i class="icon-24"></i></div>
                                            <div class="searchbox-input">
                                                <label for="start_date">Pick-up Date &amp; Time</label>
                                                <input type="datetime-local" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                    <div class="single-search-box">
                                        <div class="icon"><i class="icon-24"></i></div>
                                        <div class="searchbox-input">
                                            <label for="end_date">Return Date &amp; Time</label>
                                            <input type="datetime-local" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured cars --}}
    <section class="featured-car-section pt_120 pb_90">
        <div class="container">
            <div class="section-title centred mb_60">
                <span class="sub-title">Featured Cars</span>
                <h2 class="title">Featured Cars</h2>
            </div>
            <div class="row">
                @forelse ($featuredCars ?? [] as $car)
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <x-car-card :car="$car" />
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center">Cars are coming soon &mdash; check back shortly.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Simple call to action --}}
    <section class="cta-section pt_120 pb_90">
        <div class="container">
            <h3>Ready to hit the road?</h3>
            <a href="{{ route('cars.index') }}" class="btn-style-two">View All Cars</a>
        </div>
    </section>

@endsection
