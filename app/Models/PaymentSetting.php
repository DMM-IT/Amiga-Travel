<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PaymentSetting extends Model
{
    protected $fillable = [
        'fee_per_person',
        'fee_per_accommodation',
        'qr_code_path',
        'proof_retention_days',
    ];

    protected $casts = [
        'fee_per_person' => 'decimal:2',
        'fee_per_accommodation' => 'decimal:2',
        'proof_retention_days' => 'integer',
    ];

    /**
     * There is always exactly one settings row. Fetch it (or create with
     * defaults), caching the result in Redis for 6 hours.
     *
     * Cache is invalidated any time the row is updated via bust().
     */
    public static function current(): self
    {
        return Cache::remember('payment_settings:current', now()->addHours(6), function () {
            return static::query()->firstOrCreate(['id' => 1], [
                'fee_per_person'        => 2000,
                'fee_per_accommodation' => 5000,
                'proof_retention_days'  => 30,
            ]);
        });
    }

    /**
     * Bust the PaymentSetting cache.
     * Call this after saving/updating the settings row.
     */
    public static function bust(): void
    {
        Cache::forget('payment_settings:current');
    }
}
