<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'transaction_number',
        'origin',
        'destination',
        'departure_date',
        'return_date',
        'status',
        'total_price',
        'client_email',
        'client_name',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }

    public function accommodations(): BelongsToMany
    {
        return $this->belongsToMany(Accommodation::class)
            ->withPivot('price')
            ->withTimestamps();
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
