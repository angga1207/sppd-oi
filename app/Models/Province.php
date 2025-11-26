<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory, Searchable;
    protected $table = 'reg_provinces';
    protected $fillable = [
        'id',
        'name',
    ];

    protected $searchable = [
        'name',
        'regencies.name',
    ];

    public function regencies()
    {
        return $this->hasMany(Regency::class, 'province_id');
    }
}
