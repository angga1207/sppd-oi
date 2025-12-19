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
        Schema::create('sppd', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique();
            $table->string('nomor_sppd')
                // ->unique()
                ->nullable();
            $table->foreignId('surat_perintah_id')
                ->nullable()
                ->constrained('surat_perintah')
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
            $table->foreignId('employee_executor_id')
                ->nullable()
                ->constrained('employees')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('employee_executor_instance_id')
                ->nullable()
                ->constrained('instances')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('tingkat_biaya')->nullable();
            $table->text('maksud_perjalanan')->nullable();
            $table->string('alat_angkutan')->nullable();
            $table->string('tempat_berangkat')->nullable();
            $table->string('tempat_tujuan')->nullable();
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
            $table->integer('lama_perjalanan')->nullable();
            $table->date('tanggal_berangkat')->nullable();
            $table->date('tanggal_pulang')->nullable();

            $table->foreignId('instance_pembebanan_id')
                ->nullable()
                ->constrained('instances')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('kode_sub_kegiatan')->nullable();
            $table->string('uraian_sub_kegiatan')->nullable();
            $table->double('anggaran_sub_kegiatan')->nullable();
            $table->string('kode_rekening')->nullable();
            $table->string('uraian_rekening')->nullable();
            $table->double('anggaran_rekening')->nullable();

            $table->text('keterangan_lain')->nullable();

            $table->date('publication_date')->nullable();
            $table->string('publication_place')->nullable();
            $table->foreignId('publication_employee_id')
                ->nullable()
                ->constrained('employees')
                ->cascadeOnDelete()
                ->comment('Pegawai yang menandatangani sppd');
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
                'nomor_sppd',
                'surat_perintah_id',
                'instance_id',
                'employee_giver_id',
                'employee_giver_instance_id',
                'employee_executor_id',
                'employee_executor_instance_id',
                'instance_pembebanan_id',
                'status',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sppd');
    }
};
