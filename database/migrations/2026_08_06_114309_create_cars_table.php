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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('brand');
            $table->string('type');
            $table->decimal('daily_rate', 10, 2);
            $table->unsignedTinyInteger('seat_capacity');
            $table->unsignedTinyInteger('doors');
            $table->unsignedTinyInteger('large_luggage')->default(0);
            $table->unsignedTinyInteger('small_luggage')->default(0);
            $table->string('fuel_type');
            $table->string('transmission');
            $table->string('mileage')->nullable();
            $table->string('engine_power')->nullable();
            $table->string('location');
            $table->string('short_description');
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
