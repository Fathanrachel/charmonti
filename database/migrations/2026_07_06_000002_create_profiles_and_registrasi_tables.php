<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address_line')->nullable();
            $table->integer('postal_code')->nullable();
            $table->enum('role', ['admin', 'owner', 'customer'])->default('customer');
            $table->timestamps();
        });

        Schema::create('registrasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('profiles')->onDelete('cascade');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['name', 'role']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->enum('role', ['admin', 'owner', 'customer'])->default('customer');
        });

        Schema::dropIfExists('registrasi');
        Schema::dropIfExists('profiles');
    }
};
