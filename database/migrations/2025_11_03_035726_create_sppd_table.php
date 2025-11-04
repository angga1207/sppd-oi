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
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('semesta_id');
            $table->string('nama_lengkap');
            $table->string('nip');
            $table->string('jenis_pegawai');
            $table->foreignId('instance_id')
                ->nullable()
                ->constrained('instances')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->integer('id_skpd')->nullable();
            $table->integer('id_jabatan')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('kepala_skpd')->nullable();
            $table->text('foto_pegawai')->nullable();
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('golongan')->nullable();
            $table->string('pangkat')->nullable();
            $table->json('ref_jabatan_baru')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'semesta_id',
                'nip',
                'instance_id',
                'id_skpd',
                'id_jabatan',
                'jabatan',
            ]);
        });

        Schema::create('sppd', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nomor_sppd')->unique();
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
            $table->foreignId('employee_executor_id')
                ->nullable()
                ->constrained('employees')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('tingkat_biaya')->nullable();
            $table->text('maksud_perjalanan')->nullable();
            $table->string('alat_angkutan')->nullable();
            $table->string('tempat_berangkat')->nullable();
            $table->string('tempat_tujuan')->nullable();
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
            $table->string('kode_rekening')->nullable();
            $table->string('uraian_rekening')->nullable();
            $table->double('anggaran')->nullable();

            $table->text('keterangan_lain')->nullable();

            $table->date('publication_date')->nullable();
            $table->string('publication_place')->nullable();
            $table->foreignId('publication_employee_id')
                ->nullable()
                ->constrained('employees')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->enum('status', ['draft', 'approved', 'rejected', 'completed'])->default('draft');
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
                'instance_id',
                'employee_giver_id',
                'employee_executor_id',
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
