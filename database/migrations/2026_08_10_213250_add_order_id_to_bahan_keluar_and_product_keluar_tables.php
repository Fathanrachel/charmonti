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
        Schema::table('bahan_keluar', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after('bahan_id')->constrained('orders')->nullOnDelete();
            $table->string('deskripsi')->nullable()->after('tanggal_keluar');
        });

        Schema::table('product_keluar', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after('product_id')->constrained('orders')->nullOnDelete();
            $table->string('deskripsi')->nullable()->after('tanggal_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bahan_keluar', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'deskripsi']);
        });

        Schema::table('product_keluar', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'deskripsi']);
        });
    }
};
