<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('custom_orders', 'custom_bahan_orders');

        Schema::table('custom_bahan_orders', function (Blueprint $table) {
            $table->dropColumn(['ukuran', 'tambahan_aksesoris']);
        });

        Schema::create('custom_bahan_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_order_id')->constrained('custom_bahan_orders')->onDelete('cascade');
            $table->foreignId('bahan_id')->constrained('bahan')->onDelete('cascade');
            $table->integer('qty');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_bahan_order_items');

        Schema::table('custom_bahan_orders', function (Blueprint $table) {
            $table->string('ukuran')->nullable();
            $table->string('tambahan_aksesoris')->nullable();
        });

        Schema::rename('custom_bahan_orders', 'custom_orders');
    }
};
