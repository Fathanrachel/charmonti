<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expeditions', function (Blueprint $table) {
            $table->id();
            $table->string('name_expedition');
            $table->timestamps();
        });

        Schema::table('shippings', function (Blueprint $table) {
            $table->dropColumn('courier');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->foreignId('expedition_id')->nullable()->constrained('expeditions')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('shippings', function (Blueprint $table) {
            $table->dropForeign(['expedition_id']);
            $table->dropForeign(['payment_id']);
            $table->dropColumn(['expedition_id', 'payment_id']);
            $table->string('courier')->nullable();
        });

        Schema::dropIfExists('expeditions');
    }
};
