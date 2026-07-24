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
        Schema::create('city_expeditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->foreignId('expedition_id')->constrained('expeditions')->onDelete('cascade');
            $table->unsignedInteger('shipping_cost')->default(10000);
            $table->unsignedInteger('estimated_days')->default(3);
            $table->timestamps();

            $table->unique(['city_id', 'expedition_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_expeditions');
    }
};
