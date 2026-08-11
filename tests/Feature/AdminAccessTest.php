<?php

use App\Models\User;

// Risk: public or normal authenticated users can access administrative actions.
test('admin routes redirect guests, forbid non-admins, and allow admins', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('login'));

    $user = User::factory()->create(['is_admin' => false]);
    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();

    $admin = User::factory()->create(['is_admin' => true]);
    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();
});
