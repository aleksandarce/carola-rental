<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CarType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,inactive'],
            'type' => ['nullable', Rule::enum(CarType::class)],
            // Whitelisted against real columns — never pass a raw column
            // name from the client straight into orderBy().
            'sort' => ['nullable', 'in:name,daily_rate'],
            'direction' => ['nullable', 'in:asc,desc'],
        ]);

        $sort = $filters['sort'] ?? 'name';
        $direction = $filters['direction'] ?? 'asc';

        $cars = Car::query()
            ->search($filters['search'] ?? null)
            ->when($filters['status'] ?? null, fn ($q, $s) => $q->where('is_active', $s === 'active'))
            ->when($filters['type'] ?? null, fn ($q, $type) => $q->where('type', $type))
            ->orderBy($sort, $direction)
            ->paginate(15)
            ->withQueryString();

        return view('admin.cars.index', compact('cars', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.cars.create', ['car' => new Car]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');

        $data['slug'] = $this->uniqueSlug($request->validated('name'));
        $data['image_path'] = $request->file('image')?->store('cars', 'public');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        Car::create($data);

        return redirect()
            ->route('admin.cars.index')
            ->with('status', 'Car created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car): View
    {
        return view('admin.cars.edit', ['car' => $car]);
    }

    /**
     * Update the specified resource in storage.
     *
     * The slug is intentionally left untouched here — it's the car's public
     * URL, and regenerating it on every name edit would silently break
     * bookmarks and shared links.
     */
    public function update(UpdateCarRequest $request, Car $car): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $oldPath = $car->image_path;
            $data['image_path'] = $request->file('image')->store('cars', 'public');

            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        $car->update($data);

        return redirect()
            ->route('admin.cars.index')
            ->with('status', 'Car updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car): RedirectResponse
    {
        if ($car->bookings()->exists()) {
            return back()->with('error', 'Cannot delete a car that has bookings.');
        }

        $imagePath = $car->image_path;
        $car->delete();

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()
            ->route('admin.cars.index')
            ->with('status', 'Car deleted.');
    }

    /**
     * Generate a slug guaranteed to be unique among cars, excluding $car
     * itself when updating.
     */
    private function uniqueSlug(string $name, ?Car $car = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 2;

        while (
            Car::query()
                ->where('slug', $slug)
                ->when(
                    $car,
                    fn ($query) => $query->whereKeyNot($car->getKey())
                )
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
