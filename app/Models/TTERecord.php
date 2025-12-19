<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TTERecord extends Model
{
    use HasFactory;
    protected $table = 'tte_records';
    protected $fillable = [
        'type',
        'reference_id',
        'tte_data',
    ];

    public function reference()
    {
        if ($this->type === 'surat_perintah') {
            return $this->belongsTo(SuratPerintah::class, 'reference_id');
        } elseif ($this->type === 'sppd') {
            return $this->belongsTo(SPPD::class, 'reference_id');
        }
    }

    public static function createRecord($type, $referenceId, $tteData)
    {
        return self::create([
            'type' => $type,
            'reference_id' => $referenceId,
            'tte_data' => $tteData,
        ]);
    }
}
