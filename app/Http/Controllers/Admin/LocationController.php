<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $locations = Location::query()->orderBy('label')->get();

        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.locations.create', ['location' => new Location]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['code'] = $this->uniqueCode($data['label']);
        $data['is_active'] = $request->boolean('is_active');

        Location::create($data);

        return redirect()
            ->route('admin.locations.index')
            ->with('status', 'Location created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location): View
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * The code is intentionally left untouched here — see
     * UpdateLocationRequest for why.
     */
    public function update(UpdateLocationRequest $request, Location $location): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $location->update($data);

        return redirect()
            ->route('admin.locations.index')
            ->with('status', 'Location updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location): RedirectResponse
    {
        if ($location->pickupBookings()->exists() || $location->returnBookings()->exists()) {
            return back()->with('error', 'Cannot delete a location used by existing bookings. Deactivate it instead.');
        }

        $location->delete();

        return redirect()
            ->route('admin.locations.index')
            ->with('status', 'Location deleted.');
    }

    /**
     * Generate a code guaranteed to be unique among locations.
     */
    private function uniqueCode(string $label): string
    {
        $base = Str::slug($label, '_');
        $code = $base;
        $counter = 2;

        while (Location::query()->where('code', $code)->exists()) {
            $code = "{$base}_{$counter}";
            $counter++;
        }

        return $code;
    }
}
