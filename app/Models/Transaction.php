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
        'confirmation_url',
        'confirmation_pdf',
        'rebooking_fee',
        'rebooking_proof_of_payment',
        'verified_by_user_id',
        'verified_at',
    ];

    protected $casts = [
        'rebooking_fee' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    public function getProofUrlAttribute(): ?string
    {
        if (! $this->proof_of_payment) {
            return null;
        }

        $path = '/storage/'.$this->proof_of_payment;

        if (app()->runningInConsole() || ! request()->hasHeader('Host')) {
            return $path;
        }

        return request()->getSchemeAndHttpHost().$path;
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
