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
        Schema::create('tte_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['surat_perintah', 'sppd']);
            $table->foreignId('reference_id');
            $table->json('tte_data');
            $table->timestamps();

            $table->index(['type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tte_records');
    }
};
