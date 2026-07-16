<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Passenger extends Model
{
    protected $fillable = [
        'booking_id',
        'type',
        'name',
        'discount_id',
        'school_name',
        'id_number',
        'seat_number',
        'seat_row',
        'seat_section',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }
}
