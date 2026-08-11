@extends('layouts.public')

@section('title', 'Car Listing')

@section('content')

    <section class="breadcrumb-section" style="background-image: url('{{ asset('carola/assets/images/popular-car/koli-nasobrani.jpg') }}');">
        <div class="container">
            <div class="banner-content">
                <h1>Our Fleet</h1>
            </div>
        </div>
    </section>

    <section class="car-listing-section pt_100 pb_90">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-12 col-sm-12">
                    <div class="product-sidebar mr_30">
                        <div class="sidebar-search-widget">
                            <h3 class="sidebar-widget-title">Filter</h3>

                            <form action="{{ route('cars.index') }}" method="GET">
                                {{-- Preserves a date-range availability search started from the
                                     homepage when the sidebar's own filters are submitted. --}}
                                @if (request('start_date'))
                                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                @endif
                                @if (request('end_date'))
                                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                @endif

                                <div class="filter-group">
                                    <div class="sidebar-search-box">
                                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search by name...">
                                        <button type="submit"><i class="icon-18"></i></button>
                                    </div>
                                </div>

                                <div class="filter-group">
                                    <div class="filter-box">
                                        <div class="custom-select">
                                            <select name="type">
                                                <option value="">Any Type</option>
                                                @foreach (\App\Enums\CarType::cases() as $type)
                                                    <option value="{{ $type->value }}" @selected(request('type') === $type->value)>
                                                        {{ str($type->value)->headline() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="custom-select">
                                            <select name="transmission">
                                                <option value="">Any Transmission</option>
                                                @foreach (\App\Enums\Transmission::cases() as $transmission)
                                                    <option value="{{ $transmission->value }}" @selected(request('transmission') === $transmission->value)>
                                                        {{ $transmission->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="custom-select">
                                            <select name="fuel_type">
                                                <option value="">Any Fuel</option>
                                                @foreach (\App\Enums\FuelType::cases() as $fuelType)
                                                    <option value="{{ $fuelType->value }}" @selected(request('fuel_type') === $fuelType->value)>
                                                        {{ $fuelType->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-group">
                                    <label class="filter-group-label">Price per day</label>
                                    <div id="price-slider"></div>
                                    <div class="price-range-inputs mt_20">
                                        <input type="number" id="min_price" name="min_price" value="{{ request('min_price', $priceRange['min']) }}" placeholder="Min $/day" min="{{ $priceRange['min'] }}" max="{{ $priceRange['max'] }}">
                                        <input type="number" id="max_price" name="max_price" value="{{ request('max_price', $priceRange['max']) }}" placeholder="Max $/day" min="{{ $priceRange['min'] }}" max="{{ $priceRange['max'] }}">
                                    </div>
                                </div>

                                <div class="filter-group">
                                    <div class="price-range-inputs">
                                        <input type="number" name="seats" value="{{ request('seats') }}" placeholder="Min {{ $seatRange['min'] }} seats" min="{{ $seatRange['min'] }}" max="{{ $seatRange['max'] }}">
                                    </div>
                                </div>

                                <button type="submit" class="filter-btn btn-style-four mt_20">Apply Filters</button>
                                <a href="{{ route('cars.index') }}" class="clear-btn">Clear</a>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-lg-7 col-md-12 col-sm-12">
                    <div class="row">
                        @forelse ($cars as $car)
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                <x-car-card :car="$car" />
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="empty-state">
                                    <p>No cars match your filters right now.</p>
                                    <a href="{{ route('cars.index') }}" class="btn-style-three">Reset filters</a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if ($cars instanceof \Illuminate\Contracts\Pagination\Paginator)
                        <div class="pagination-wrapper">
                            {{ $cars->links() }}
                        </div>
                    @elseif ($cars->isEmpty())
                        <div class="pagination-wrapper">
                            <p class="text-center">No cars match the selected filters.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        $(function () {
            var $slider = $('#price-slider');
            if (!$slider.length) {
                return;
            }

            var minAvailable = {{ $priceRange['min'] }};
            var maxAvailable = {{ $priceRange['max'] }};
            var $minInput = $('#min_price');
            var $maxInput = $('#max_price');

            $slider.slider({
                range: true,
                min: minAvailable,
                max: maxAvailable,
                values: [
                    parseInt($minInput.val(), 10) || minAvailable,
                    parseInt($maxInput.val(), 10) || maxAvailable
                ],
                slide: function (event, ui) {
                    $minInput.val(ui.values[0]);
                    $maxInput.val(ui.values[1]);
                }
            });

            // Keep the slider handles in sync if the numbers are typed directly.
            $minInput.add($maxInput).on('change', function () {
                $slider.slider('values', [
                    parseInt($minInput.val(), 10) || minAvailable,
                    parseInt($maxInput.val(), 10) || maxAvailable
                ]);
            });
        });
    </script>
@endpush
