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
        // Drop PostgreSQL check constraint for profile role
        DB::statement("ALTER TABLE profiles DROP CONSTRAINT IF EXISTS profiles_role_check");
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");

        Schema::table('profiles', function (Blueprint $table) {
            $table->string('role')->default('customer')->change();
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
