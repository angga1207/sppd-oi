<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instance extends Model
{
    protected $fillable = [
        'id_eoffice',
        'name',
        'alias',
        'code',
        'logo',
        'status',
        'description',
        'address',
        'phone',
        'fax',
        'email',
        'website',
        'facebook',
        'instagram',
        'youtube',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function sppds(): HasMany
    {
        return $this->hasMany(Sppd::class, 'budget_department_id');
    }
}
