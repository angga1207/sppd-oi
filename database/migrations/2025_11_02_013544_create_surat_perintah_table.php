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
        Schema::create('surat_perintah', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique();
            $table->string('nomor_surat')
                // ->unique()
                ->nullable();

            $table->foreignId('klasifikasi_surat_id')
                ->nullable()
                ->constrained('klasifikasi')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('instance_id')
                ->nullable()
                ->constrained('instances')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('employee_giver_id')
                ->nullable()
                ->constrained('employees')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('employee_giver_instance_id')
                ->nullable()
                ->constrained('instances')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->text('dasar')->nullable();
            $table->text('tujuan')->nullable();

            $table->foreignId('province_id')
                ->nullable();
            // ->constrained('reg_provinces')
            // ->cascadeOnDelete()
            // ->cascadeOnUpdate();
            $table->foreignId('regency_id')
                ->nullable();
            // ->constrained('reg_regencies')
            // ->cascadeOnDelete()
            // ->cascadeOnUpdate();

            $table->string('alat_angkutan')->nullable();
            $table->string('tempat_berangkat')->nullable();
            $table->string('tempat_tujuan')->nullable();
            $table->integer('lama_perjalanan')->nullable();
            $table->date('tanggal_berangkat')->nullable();
            $table->date('tanggal_pulang')->nullable();


            $table->date('publication_date')->nullable();
            $table->string('publication_place')->nullable();
            $table->foreignId('publication_employee_id')
                ->nullable()
                ->constrained('employees')
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->comment('Pegawai yang menandatangani surat perintah');
            $table->text('file_word')->nullable();
            $table->text('file_pdf')->nullable();
            $table->text('file_pdf_signed')->nullable();
            $table->timestamp('tanggal_tte')->nullable();

            $table->enum('status', ['draft', 'sent', 'approved', 'rejected', 'completed'])->default('draft');
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'klasifikasi_surat_id',
                'nomor_surat',
                'instance_id',
                'province_id',
                'regency_id',
                'employee_giver_id',
                'employee_giver_instance_id',
                'publication_date',
                'approved_by',
                'approved_at',
                'status',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_perintah');
    }
};
