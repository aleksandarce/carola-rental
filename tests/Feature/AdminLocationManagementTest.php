<?php

use App\Enums\LocationScope;
use App\Models\Booking;
use App\Models\Location;
use App\Models\User;

// Risk: an admin-created location isn't usable, or a location in use gets deleted
// out from under existing bookings instead of being safely deactivated.
test('admin can create a location and cannot delete one referenced by a booking', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.locations.store'), [
            'label' => 'Bitola Center',
            'applies_to' => LocationScope::Both->value,
            'is_active' => true,
        ])
        ->assertRedirect(route('admin.locations.index'));

    $location = Location::where('label', 'Bitola Center')->sole();
    expect($location->code)->toBe('bitola_center');
    expect($location->applies_to)->toBe(LocationScope::Both);
    expect(Location::query()->active()->forPickup()->pluck('id'))->toContain($location->id);

    // Deactivating (not deleting) is how an in-use location is retired.
    $this->actingAs($admin)
        ->put(route('admin.locations.update', $location), [
            'label' => $location->label,
            'applies_to' => $location->applies_to->value,
            'is_active' => false,
        ])
        ->assertRedirect(route('admin.locations.index'));

    expect($location->refresh()->is_active)->toBeFalse();
    expect(Location::query()->active()->forPickup()->pluck('id'))->not->toContain($location->id);

    // A location referenced by an existing booking cannot be hard-deleted.
    Booking::factory()->create(['pickup_location' => $location->code]);

    $this->actingAs($admin)
        ->delete(route('admin.locations.destroy', $location))
        ->assertRedirect();

    expect(Location::find($location->id))->not->toBeNull();
});
