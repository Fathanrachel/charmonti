<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Rename tabel lama
        Schema::rename('shippings', 'shippings_old');

        // 2. Buat tabel baru dengan struktur sesuai ERD
        Schema::create('shippings', function (Blueprint $table) {
            $table->id('shipping_id');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->foreignId('expedition_id')->nullable()->constrained('expeditions')->onDelete('set null');
            $table->decimal('shipping_cost', 12, 2);
            $table->string('tracking_number')->nullable();
            $table->dateTime('estimated_arrival')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        // 3. Pindahkan data dari tabel lama ke tabel baru
        DB::statement('
            INSERT INTO shippings (shipping_id, order_id, payment_id, expedition_id, shipping_cost, tracking_number, estimated_arrival, status, created_at, updated_at)
            SELECT id, order_id, payment_id, expedition_id, shipping_cost, tracking_number, estimated_arrival, status, created_at, updated_at
            FROM shippings_old
        ');

        // 4. Update sequence agar auto-increment melanjutkan dari ID terakhir
        $maxId = DB::table('shippings')->max('shipping_id') ?? 0;
        DB::statement("SELECT setval(pg_get_serial_sequence('shippings', 'shipping_id'), " . max(1, $maxId) . ", true)");

        // 5. Hapus tabel lama
        Schema::dropIfExists('shippings_old');
    }

    public function down(): void
    {
        Schema::rename('shippings', 'shippings_new');

        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->decimal('shipping_cost', 12, 2);
            $table->string('tracking_number')->nullable();
            $table->dateTime('estimated_arrival')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->foreignId('expedition_id')->nullable()->constrained('expeditions')->onDelete('set null');
        });

        DB::statement('
            INSERT INTO shippings (id, order_id, shipping_cost, tracking_number, estimated_arrival, status, created_at, updated_at, payment_id, expedition_id)
            SELECT shipping_id, order_id, shipping_cost, tracking_number, estimated_arrival, status, created_at, updated_at, payment_id, expedition_id
            FROM shippings_new
        ');

        Schema::dropIfExists('shippings_new');
    }
};
