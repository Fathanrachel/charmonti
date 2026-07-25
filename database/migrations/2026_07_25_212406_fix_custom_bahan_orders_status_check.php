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
        // Drop PostgreSQL check constraint for status on custom_bahan_orders
        DB::statement("ALTER TABLE custom_bahan_orders DROP CONSTRAINT IF EXISTS custom_orders_status_check");
        DB::statement("ALTER TABLE custom_bahan_orders DROP CONSTRAINT IF EXISTS custom_bahan_orders_status_check");

        Schema::table('custom_bahan_orders', function (Blueprint $table) {
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
