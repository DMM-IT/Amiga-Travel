<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherRedemption extends Model
{
    protected $fillable = [
        'voucher_id',
        'booking_id',
        'user_id',
        'normalized_email',
        'voucher_code_snapshot',
        'discount_amount',
        'base_amount',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'base_amount' => 'decimal:2',
    ];

    // Relationships
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
