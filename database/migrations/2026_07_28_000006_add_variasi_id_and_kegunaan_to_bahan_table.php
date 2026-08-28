<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bahan', function (Blueprint $table) {
            $table->foreignId('variasi_id')
                ->nullable()
                ->after('id')
                ->constrained('variasis')
                ->nullOnDelete();

            $table->text('kegunaan')
                ->nullable()
                ->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bahan', function (Blueprint $table) {
            $table->dropForeign(['variasi_id']);
            $table->dropColumn(['variasi_id', 'kegunaan']);
        });
    }
};
