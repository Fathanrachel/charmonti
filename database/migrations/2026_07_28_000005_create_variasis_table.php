<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('variasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_variasi');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Seed default variations
        DB::table('variasis')->insert([
            [
                'nama_variasi' => 'Tali Gelang',
                'deskripsi' => 'Variasi tali dasar perakitan gelang custom (contoh: Silver, Gold, Black).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_variasi' => 'Charm Manik Diamond / Kristal',
                'deskripsi' => 'Variasi manik aksesoris kilau berkilau bertema kristal dan permata.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_variasi' => 'Charm Manik Alphabet / Huruf',
                'deskripsi' => 'Variasi manik karakter huruf A-Z dan angka untuk inisial nama.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_variasi' => 'Charm Aksesoris & Liontin',
                'deskripsi' => 'Variasi liontin gantung dengan bentuk beragam seperti hati, bintang, dan hewan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variasis');
    }
};
