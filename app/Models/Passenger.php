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
        'return_seat_number',
        'return_seat_row',
        'return_seat_section',
        'promotional_ticket_id',
        'is_promo',
        'promo_price',
    ];

    protected $casts = [
        'is_promo'   => 'boolean',
        'promo_price' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function promotionalTicket(): BelongsTo
    {
        return $this->belongsTo(PromotionalTicket::class);
    }
}
