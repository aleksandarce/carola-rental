<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Nullable at the database level only because SQLite's ALTER TABLE
        // ADD COLUMN refuses a NOT NULL column without a non-null default,
        // and neither location has a sensible universal default. "Required"
        // is enforced where it actually matters: StoreBookingRequest.
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('pickup_location')->nullable()->after('insurance_daily_rate_snapshot');
            $table->string('return_location')->nullable()->after('pickup_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['pickup_location', 'return_location']);
        });
    }
};
