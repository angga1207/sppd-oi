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
        Schema::create('status_surat_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['surat_perintah', 'sppd']);
            $table->foreignId('reference_id');
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_surat_logs');
    }
};
