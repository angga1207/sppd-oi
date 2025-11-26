<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory, Searchable;
    protected $table = 'reg_districts';
    protected $fillable = [
        'id',
        'regency_id',
        'name',
    ];

    protected $searchable = [
        'name',
        'regency.name',
        'villages.name',
    ];

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    public function villages()
    {
        return $this->hasMany(Village::class, 'district_id');
    }
}
