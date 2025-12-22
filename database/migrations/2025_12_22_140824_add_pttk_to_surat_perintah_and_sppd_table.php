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
        Schema::table('surat_perintah', function (Blueprint $table) {
            $table->foreignId('pttk_id')
                ->nullable()
                ->constrained('employees')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        Schema::table('sppd', function (Blueprint $table) {
            $table->foreignId('pttk_id')
                ->nullable()
                ->constrained('employees')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_perintah', function (Blueprint $table) {
            $table->dropForeign(['pttk_id']);
            $table->dropColumn('pttk_id');
        });

        Schema::table('sppd', function (Blueprint $table) {
            $table->dropForeign(['pttk_id']);
            $table->dropColumn('pttk_id');
        });
    }
};
