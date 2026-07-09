<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Transaction extends Model
{
    protected $fillable = [
        'booking_id',
        'payment_status',
        'proof_of_payment',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function getProofUrlAttribute(): ?string
    {
        if (! $this->proof_of_payment) {
            return null;
        }

        return '/storage/'.$this->proof_of_payment;
    }

    public function deleteProof(): void
    {
        if (! $this->proof_of_payment) {
            return;
        }

        Storage::disk('public')->delete($this->proof_of_payment);

        $this->update([
            'proof_of_payment' => null,
        ]);
    }
}
