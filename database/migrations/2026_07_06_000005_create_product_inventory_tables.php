<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('qty_masuk');
            $table->text('deskripsi')->nullable();
            $table->dateTime('tanggal_masuk');
            $table->timestamps();
        });

        Schema::create('product_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idproduct_masuk')->constrained('product_masuk')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('qty_keluar');
            $table->dateTime('tanggal_keluar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_keluar');
        Schema::dropIfExists('product_masuk');
    }
};
