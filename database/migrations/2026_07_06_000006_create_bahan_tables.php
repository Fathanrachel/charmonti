<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('bahan_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_id')->constrained('bahan')->onDelete('cascade');
            $table->string('nama_bahan');
            $table->integer('qty_masuk');
            $table->text('deskripsi')->nullable();
            $table->dateTime('tanggal_masuk');
            $table->timestamps();
        });

        Schema::create('bahan_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idbahan_masuk')->constrained('bahan_masuk')->onDelete('cascade');
            $table->foreignId('bahan_id')->constrained('bahan')->onDelete('cascade');
            $table->integer('sisa');
            $table->integer('qty_keluar');
            $table->dateTime('tanggal_keluar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_keluar');
        Schema::dropIfExists('bahan_masuk');
        Schema::dropIfExists('bahan');
    }
};
