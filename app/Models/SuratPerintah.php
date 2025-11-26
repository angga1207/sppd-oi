<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratPerintah extends Model
{
    use HasFactory, Searchable;
    protected $table = 'surat_perintah';
    protected $fillable = [
        'uuid',
        'klasifikasi_surat_id',
        'nomor_surat',
        'instance_id',
        'employee_giver_id',
        'employee_giver_instance_id',

        'dasar',
        'tujuan',
        'province_id',
        'regency_id',

        'alat_angkutan',
        'tempat_berangkat',
        'tempat_tujuan',
        'lama_perjalanan',
        'tanggal_berangkat',
        'tanggal_pulang',

        'publication_date',
        'publication_place',
        'publication_employee_id',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $searchable = [
        'nomor_surat',
        'employeeGiver.nama_lengkap',
        'employeeGiver.nip',
        'instance.name',
        'publicationEmployee.nama_lengkap',
        'publicationEmployee.nip',
    ];

    function generateUUID()
    {
        // 11 length A-Z az 0-9
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        $length = 11;
        do {
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
        } while ($this->_isExistsUUID($randomString));
        return $randomString;
    }

    function _isExistsUUID($uuid)
    {
        $check = SuratPerintah::where('uuid', '=', $uuid)->first();
        if ($check) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($data) {
            $data->uuid = $data->generateUUID();
            if ($data->tanggal_berangkat && $data->tanggal_pulang) {
                $data->lama_perjalanan = Carbon::parse($data->tanggal_berangkat)->diffInDays(Carbon::parse($data->tanggal_pulang)) + 1;
            }
            $data->created_by = auth()->id() ?? null;

            // Set default status
            if (empty($data->status)) {
                $data->status = 'draft';
            }
        });

        static::created(function ($data) {
            // Create initial status log
            StatusSuratLog::create([
                'type' => 'surat_perintah',
                'reference_id' => $data->id,
                'new_status' => $data->status,
                'keterangan' => 'Surat Perintah Perjalanan Dinas dibuat',
            ]);
        });

        static::updating(function ($data) {
            if ($data->tanggal_berangkat && $data->tanggal_pulang) {
                $data->lama_perjalanan = Carbon::parse($data->tanggal_berangkat)->diffInDays(Carbon::parse($data->tanggal_pulang)) + 1;
            }
        });
    }

    public function klasifikasiSurat()
    {
        return $this->belongsTo(KlasifikasiNomorSurat::class, 'klasifikasi_surat_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    public function sppds()
    {
        return $this->hasMany(Sppd::class, 'surat_perintah_id');
    }

    public function employeeGiver()
    {
        return $this->belongsTo(Employee::class, 'employee_giver_id');
    }

    public function employeeGiverInstance()
    {
        return $this->belongsTo(Instance::class, 'employee_giver_instance_id');
    }

    public function instance()
    {
        return $this->belongsTo(Instance::class, 'instance_id');
    }

    public function publicationEmployee()
    {
        return $this->belongsTo(Employee::class, 'publication_employee_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(StatusSuratLog::class, 'reference_id')->where('type', 'surat_perintah')->orderBy('created_at', 'desc');
    }

    public function lastStatusLog()
    {
        return $this->hasOne(StatusSuratLog::class, 'reference_id')->where('type', 'surat_perintah')->latestOfMany();
    }

    public function tteRecord()
    {
        return $this->hasOne(TTERecord::class, 'reference_id')->where('type', 'surat_perintah');
    }
}
