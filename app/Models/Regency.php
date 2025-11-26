<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    use HasFactory, Searchable;
    protected $table = 'reg_regencies';
    protected $fillable = [
        'id',
        'province_id',
        'name',
    ];

    protected $searchable = [
        'name',
        'province.name',
        'districts.name',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'regency_id');
    }
}
