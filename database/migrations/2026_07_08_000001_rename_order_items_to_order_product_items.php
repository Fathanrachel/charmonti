<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('order_items', 'order_product_items');
    }

    public function down(): void
    {
        Schema::rename('order_product_items', 'order_items');
    }
};
