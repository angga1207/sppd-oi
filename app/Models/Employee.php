<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'semesta_id',
        'nama_lengkap',
        'nip',
        'jenis_pegawai',
        'instance_id',
        'id_skpd',
        'id_jabatan',
        'jabatan',
        'kepala_skpd',
        'foto_pegawai',
        'email',
        'no_hp',
        'eselon',
        'golongan',
        'pangkat',
        'ref_jabatan_baru',
    ];

    protected $casts = [
        'ref_jabatan_baru' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($data) {

        // });

        static::created(function ($data) {
            // connect to user table nip = user.username
            $user = User::where('username', $data->nip)->first();
            if ($user) {
                $user->employee_id = $data->id;
                $user->save();
            }
        });

        // static::updated(function ($data) {
        //     // connect to user table nip = user.username
        //     $user = User::where('username', $data->nip)->first();
        //     if ($user) {
        //         $user->employee_id = $data->id;
        //         $user->save();
        //     }
        // });
    }

    public static function getTingkatBiayaOptions()
    {
        return [
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
    }

    public function instance()
    {
        return $this->belongsTo(Instance::class);
    }

    public function sppd()
    {
        return $this->hasMany(SPPD::class, 'employee_executor_id');
    }
}
