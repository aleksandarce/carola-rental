@props(['car'])

<div class="single-featured-car-block">
    <div class="single-featured-car-image">
        <a href="{{ route('cars.show', $car) }}">
            <img src="{{ $car->thumbnail }}" alt="{{ $car->name }}">
        </a>
        <div class="single-featured-car-rent-per-day"><span>${{ number_format($car->daily_rate, 2) }}</span>/ Day</div>
    </div>
    <div class="single-featured-car-content">
        <h5 class="single-featured-car-title"><a href="{{ route('cars.show', $car) }}">{{ $car->name }}</a></h5>

        <p class="single-featured-car-text">{{ $car->short_description }}</p>

        <div class="border-divider"></div>
        <ul class="single-featured-car-info">
            <li><i class="icon-4"></i>{{ $car->doors }} Doors</li>
            <li><i class="icon-6"></i>{{ $car->transmission->value }}</li>
            <li><i class="icon-8"></i>{{ $car->seat_capacity }} Seats</li>
        </ul>
        <div class="single-featured-car-book-btn">
            <a href="{{ route('cars.show', $car) }}" class="btn-style-three">Rent now</a>
        </div>
    </div>
</div>
