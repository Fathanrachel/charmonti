<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE products DROP CONSTRAINT IF EXISTS products_category_check");
        DB::statement("ALTER TABLE products ADD CONSTRAINT products_category_check 
            CHECK (category IN ('charm', 'strap', 'gelang_jadi', 'cincin'))");
        
        DB::statement("UPDATE products SET category = 'charm' WHERE category = 'gelang_custom'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE products DROP CONSTRAINT IF EXISTS products_category_check");
    }
};