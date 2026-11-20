<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredCars = Car::query()
            ->active()
            ->where('is_featured', true)
            ->latest()
            ->limit(6)
            ->get();

        return view('home', compact('featuredCars'));
    }
}
