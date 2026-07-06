<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'shipping_address']);
            $table->foreignId('profile_id')->nullable()->constrained('profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['profile_id']);
            $table->dropColumn(['profile_id']);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->text('shipping_address')->nullable();
        });
    }
};
