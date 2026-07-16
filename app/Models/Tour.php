<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    protected $fillable = [
        'name',
        'origin',
        'destination',
        'mode',
        'duration_days',
    ];

    protected $casts = [
        'duration_days' => 'integer',
    ];

    public function dates(): HasMany
    {
        return $this->hasMany(TourDate::class);
    }
}
