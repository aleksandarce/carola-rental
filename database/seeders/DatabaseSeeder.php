<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * The admin account itself is guaranteed by the
     * seed_admin_user_into_users_table migration, not here — it needs to
     * exist after `migrate --force` alone, without depending on this
     * optional demo-content seeder ever being run.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->count(5)->create();

        $this->call([
            CarSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
