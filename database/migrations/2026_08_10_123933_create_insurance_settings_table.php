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
     * Seeded here (see create_locations_table for why) so the 3 rows exist
     * in every environment. The set of codes is intentionally fixed —
     * admins edit label/price/coverage per row, but never add or remove a
     * row; InsuranceOption (the enum) remains the single source of truth
     * for which 3 codes are legal.
     */
    public function up(): void
    {
        Schema::create('insurance_settings', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('label');
            $table->decimal('daily_rate', 10, 2);
            $table->text('coverage');
            $table->timestamps();
        });

        $now = now();

        DB::table('insurance_settings')->insert([
            [
                'code' => 'standard',
                'label' => 'Standard',
                'daily_rate' => 0,
                'coverage' => 'No extra charges — basic coverage only.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'performance',
                'label' => 'Performance',
                'daily_rate' => 9,
                'coverage' => 'Adds Collision Damage Waiver (CDW) and Loss Damage Waiver (LDW).',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'super',
                'label' => 'Super',
                'daily_rate' => 15,
                'coverage' => 'Everything in Performance, plus theft protection and supplemental liability.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_settings');
    }
};
