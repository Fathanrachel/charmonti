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
        // Drop PostgreSQL check constraints for status on orders, shippings, payments, complaints
        DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");
        DB::statement("ALTER TABLE shippings DROP CONSTRAINT IF EXISTS shippings_status_check");
        DB::statement("ALTER TABLE payments DROP CONSTRAINT IF EXISTS payments_payment_status_check");
        DB::statement("ALTER TABLE complaints DROP CONSTRAINT IF EXISTS complaints_status_check");

        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });

        Schema::table('shippings', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
