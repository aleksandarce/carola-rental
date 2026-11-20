<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Seeded here rather than in database/seeders — this is reference data
     * the app's own validation and booking form depend on structurally
     * (StoreBookingRequest, BookingFactory), so it must exist in every
     * environment the migrations run in, including tests via
     * RefreshDatabase, which doesn't run the optional demo seeders.
     */
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('label');
            $table->string('applies_to')->default('both');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        DB::table('locations')->insert([
            ['code' => 'skp', 'label' => 'Skopje Airport', 'applies_to' => 'both', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'ohd', 'label' => 'Ohrid Airport', 'applies_to' => 'both', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'sk_center', 'label' => 'Skopje Center', 'applies_to' => 'both', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'oh_center', 'label' => 'Ohrid Center', 'applies_to' => 'both', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'train_station', 'label' => 'Skopje Train Station', 'applies_to' => 'pickup', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'hq', 'label' => 'Carola HQ (Prilep)', 'applies_to' => 'return', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
