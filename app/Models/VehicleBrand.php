<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleBrand extends Model
{
    protected $fillable = [
        'name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class)->orderBy('sort_order');
    }
}
