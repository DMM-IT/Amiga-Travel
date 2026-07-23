<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GraciaPointLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_id',
        'gracia_earning_rule_id',
        'points',
        'entry_type',
        'qualifying_spend_centavos',
        'reason',
        'admin_id',
        'idempotency_key',
    ];

    protected $casts = [
        'points' => 'integer',
        'qualifying_spend_centavos' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(GraciaEarningRule::class, 'gracia_earning_rule_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
