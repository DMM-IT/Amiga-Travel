<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'percentage',
    ];

    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }
}
