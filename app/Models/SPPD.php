<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SPPD extends Model
{
    use HasFactory, Searchable;
    protected $table = 'sppd';
    protected $fillable = [
        'uuid',
        'nomor_sppd',
        'surat_perintah_id',
        'instance_id',
        'employee_giver_id',
        'employee_giver_instance_id',
        'employee_executor_id',
        'employee_executor_instance_id',
        'tingkat_biaya',
        'maksud_perjalanan',
        'alat_angkutan',
        'tempat_berangkat',
        'tempat_tujuan',
        'province_id',
        'regency_id',
        'lama_perjalanan',
        'tanggal_berangkat',
        'tanggal_pulang',
        'instance_pembebanan_id',
        'kode_sub_kegiatan',
        'uraian_sub_kegiatan',
        'anggaran_sub_kegiatan',
        'kode_rekening',
        'uraian_rekening',
        'anggaran_rekening',
        'keterangan_lain',
        'publication_date',
        'publication_place',
        'publication_employee_id',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];
    protected $searchable = [
        'nomor_sppd',
        'tempat_tujuan',
        'employeeExecutor.nama_lengkap',
        'employeeExecutor.nip',
        'suratPerintah.nomor_surat',
        'instance.name',
        'instancePembebanan.name',
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

    public function getNomorSuratAttribute()
    {
        return $this->nomor_sppd;
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
                'type' => 'sppd',
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

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    public function suratPerintah()
    {
        return $this->belongsTo(SuratPerintah::class, 'surat_perintah_id');
    }

    public function employeeGiver()
    {
        return $this->belongsTo(Employee::class, 'employee_giver_id');
    }

    public function employeeGiverInstance()
    {
        return $this->belongsTo(Instance::class, 'employee_giver_instance_id');
    }

    public function employeeExecutor()
    {
        return $this->belongsTo(Employee::class, 'employee_executor_id');
    }

    public function employeeExecutorInstance()
    {
        return $this->belongsTo(Instance::class, 'employee_executor_instance_id');
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

    public function statusLogs()
    {
        return $this->hasMany(StatusSuratLog::class, 'reference_id')->where('type', 'sppd')->orderBy('created_at', 'desc');
    }

    public function lastStatusLog()
    {
        return $this->hasOne(StatusSuratLog::class, 'reference_id')->where('type', 'sppd')->latestOfMany();
    }

    public function tteRecord()
    {
        return $this->hasOne(TTERecord::class, 'reference_id')->where('type', 'sppd');
    }

    public static function GetTingkatOptions($type = null)
    {
        $data = [
            [
                'value' => 'A',
                'label' => 'Tingkat A - Untuk Bupati, Wakil Bupati, dan Pimpinan DPRD',
            ],
            [
                'value' => 'B',
                'label' => 'Tingkat B - Untuk Pejabat Esselon II dan Anggota DPRD',
            ],
            [
                'value' => 'C',
                'label' => 'Tingkat C - Untuk Pejabat Esselon IIb',
            ],
            [
                'value' => 'D',
                'label' => 'Tingkat D - Untuk Pejabat Esselon III',
            ],
            [
                'value' => 'E',
                'label' => 'Tingkat E - Untuk Pejabat Esselon IV atau Golongan IV',
            ],
            [
                'value' => 'F',
                'label' => 'Tingkat F - Untuk Pejabat Golongan III dan II',
            ],
            [
                'value' => 'G',
                'label' => 'Tingkat G - Untuk Pejabat Golongan I',
            ],
        ];

        if ($type) {
            return collect($data)->where('value', $type)->first();
        }
        return $data;
    }
}
