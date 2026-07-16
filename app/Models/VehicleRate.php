<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleRate extends Model
{
    protected $fillable = [
        'name',
        'price',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];
}
