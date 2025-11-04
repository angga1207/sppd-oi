<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPPD extends Model
{
    use HasFactory;
    protected $table = 'sppd';
    protected $fillable = [
        'nomor_sppd',
        'instance_id',
        'employee_giver_id',
        'employee_executor_id',
        'tingkat_biaya',
        'maksud_perjalanan',
        'alat_angkutan',
        'tempat_berangkat',
        'tempat_tujuan',
        'lama_perjalanan',
        'tanggal_berangkat',
        'tanggal_pulang',
        'instance_pembebanan_id',
        'kode_rekening',
        'uraian_rekening',
        'anggaran',
        'keterangan_lain',
        'publication_date',
        'publication_place',
        'publication_employee_id',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    public function employeeGiver()
    {
        return $this->belongsTo(Employee::class, 'employee_giver_id');
    }

    public function employeeExecutor()
    {
        return $this->belongsTo(Employee::class, 'employee_executor_id');
    }

    public function instance()
    {
        return $this->belongsTo(Instance::class, 'instance_id');
    }

    public function instancePembebanan()
    {
        return $this->belongsTo(Instance::class, 'instance_pembebanan_id');
    }

    public function publicationEmployee()
    {
        return $this->belongsTo(Employee::class, 'publication_employee_id');
    }
}
