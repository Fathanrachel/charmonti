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
        Schema::create('complaint_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed default complaint categories
        DB::table('complaint_categories')->insert([
            [
                'name' => 'Barang Rusak / Cacat',
                'description' => 'Produk diterima dalam kondisi mengalami kerusakan fisik atau cacat produksi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ukuran / Warna Tidak Sesuai',
                'description' => 'Spesifikasi ukuran atau warna produk tidak sesuai dengan pesanan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jumlah Barang Kurang',
                'description' => 'Jumlah produk atau perhiasan custom yang diterima kurang dari yang dipesan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Keterlambatan Pengiriman',
                'description' => 'Pengiriman barang melebihi estimasi waktu yang ditentukan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lain-lain',
                'description' => 'Kendala atau pertanyaan lain seputar transaksi pesanan.',
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
        Schema::dropIfExists('complaint_categories');
    }
};
