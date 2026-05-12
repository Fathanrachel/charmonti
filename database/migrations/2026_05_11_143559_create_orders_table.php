<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('order_date')->useCurrent();
            $table->enum('status', ['pending', 'diproses', 'selesai', 'batal'])->default('pending');
            $table->decimal('total_price', 12, 2);
            $table->enum('payment_method', ['transfer', 'QRIS', 'midtrans'])->nullable();
            $table->text('shipping_address');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
