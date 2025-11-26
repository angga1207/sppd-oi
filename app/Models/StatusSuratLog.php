<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusSuratLog extends Model
{
    use HasFactory;
    protected $table = 'status_surat_logs';
    protected $fillable = [
        'type',
        'reference_id',
        'old_status',
        'new_status',
        'keterangan',
    ];

    public function reference()
    {
        if ($this->type === 'surat_perintah') {
            return $this->belongsTo(SuratPerintah::class, 'reference_id');
        } elseif ($this->type === 'sppd') {
            return $this->belongsTo(Sppd::class, 'reference_id');
        }
    }

    public static function logStatusChange($type, $referenceId, $oldStatus = null, $newStatus, $keterangan = null)
    {
        return self::create([
            'type' => $type,
            'reference_id' => $referenceId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'keterangan' => $keterangan,
        ]);
    }
}
