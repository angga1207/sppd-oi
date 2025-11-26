<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory, Searchable;
    protected $table = 'reg_villages';
    protected $fillable = [
        'id',
        'district_id',
        'name',
    ];

    protected $searchable = [
        'name',
        'district.name',
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
