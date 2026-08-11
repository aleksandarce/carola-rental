<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Guarantees a working admin account exists after `migrate --force`
     * alone — platforms like Laravel Cloud run migrations automatically on
     * every deploy but not database seeders, so without this there'd be no
     * way to log into the admin panel on a fresh deploy until someone
     * manually ran `db:seed`. Cars/bookings/extra test users stay in
     * DatabaseSeeder — they're optional demo content, not something the
     * app needs to be usable.
     *
     * updateOrInsert() so this is also safe to run against a database that
     * already has this account (e.g. from an earlier DatabaseSeeder run).
     */
    public function up(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make(config('app.admin_password')),
                'is_admin' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', 'admin@example.com')->delete();
    }
};
