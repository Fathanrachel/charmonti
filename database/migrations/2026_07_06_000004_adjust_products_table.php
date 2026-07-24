<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'name')) {
                $table->renameColumn('name', 'product_name');
            }
            if (Schema::hasColumn('products', 'stock')) {
                $table->renameColumn('stock', 'sisa');
            } else {
                $table->integer('sisa')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'sisa')) {
                $table->renameColumn('sisa', 'stock');
            }
            if (Schema::hasColumn('products', 'product_name')) {
                $table->renameColumn('product_name', 'name');
            }
        });
    }
};
