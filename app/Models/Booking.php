<?php

namespace App\Models;

use App\Mail\RebookingVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Mail;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_number',
        'origin',
        'destination',
        'departure_date',
        'return_date',
        'schedule_id',
        'schedule_service',
        'schedule_departure_time',
        'schedule_arrival_time',
        'schedule_price',
        'schedule_accommodation_id',
        'schedule_accommodation_name',
        'schedule_accommodation_price',
        'status',
        'total_price',
        'client_email',
        'client_name',
        'has_vehicle',
        'vehicle_type',
        'vehicle_plate_number',
        'vehicle_price',
        'driver_name',
        'driver_birthday',
        'tour_id',
        'tour_date_id',
        'tour_inclusions',
        'cancellation_fee',
        'refund_amount',
        'refund_destination',
        'cancellation_window_expires_at',
        'is_rebooked',
        'rebooking_status',
        'rebooking_departure_date',
        'rebooking_return_date',
        'verified_by_user_id',
        'verified_at',
        'promotional_ticket_id',
        'voucher_id',
        'voucher_code',
        'voucher_discount_amount',
        'subtotal_before_voucher',
        'terms_accepted_at',
        'terms_version',
        'terms_accepted_ip',
        'terms_accepted_user_agent',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'rebooking_departure_date' => 'date',
        'rebooking_return_date' => 'date',
        'schedule_price' => 'decimal:2',
        'schedule_accommodation_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'has_vehicle' => 'boolean',
        'vehicle_price' => 'decimal:2',
        'driver_birthday' => 'date',
        'tour_inclusions' => 'array',
        'cancellation_fee' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'cancellation_window_expires_at' => 'datetime',
        'is_rebooked' => 'boolean',
        'voucher_discount_amount' => 'decimal:2',
        'subtotal_before_voucher' => 'decimal:2',
        'terms_accepted_at' => 'datetime',
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

    public function transportClasses(): BelongsToMany
    {
        return $this->belongsToMany(TransportClass::class, 'booking_transport_class')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function scheduleAccommodation(): BelongsTo
    {
        return $this->belongsTo(ScheduleAccommodation::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }


    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promotionalTicket(): BelongsTo
    {
        return $this->belongsTo(PromotionalTicket::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function voucherRedemption(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(VoucherRedemption::class);
    }

    public function getScheduleSummaryAttribute(): ?string
    {
        if (! $this->schedule_service) {
            return null;
        }

        $times = collect([$this->schedule_departure_time, $this->schedule_arrival_time])
            ->filter()
            ->implode(' → ');

        return trim("{$this->schedule_service}" . ($times ? " ({$times})" : ''));
    }

    public function canCancelOrRebook(): bool
    {
        return $this->departure_date->isFuture() || $this->departure_date->isToday();
    }

    public function getCancellationFeeAmount(): float
    {
        return $this->total_price * 0.5;
    }

    public function getRefundAmount(): float
    {
        return $this->total_price * 0.5;
    }

    public function getRebookingFeeAmount(): float
    {
        return $this->total_price * 0.3;
    }

    public function verifyRebooking(?string $ticketUrl = null, ?string $receiptPath = null, ?string $receiptDisk = null): void
    {
        if (! $this->rebooking_departure_date || ! $this->rebooking_status) {
            return;
        }

        $staffId = $this->verified_by_user_id ?? \Illuminate\Support\Facades\Auth::id();
        $now = $this->verified_at ?? now();

        $this->update([
            'departure_date' => $this->rebooking_departure_date,
            'return_date' => $this->rebooking_return_date,
            'status' => 'confirmed',
            'is_rebooked' => true,
            'rebooking_status' => 'verified',
            'verified_by_user_id' => $staffId,
            'verified_at' => $now,
        ]);

        app(\App\Services\GraciaPointsService::class)->awardPointsForBooking($this, \App\Models\User::find($staffId));

        if ($this->transaction) {
            $this->transaction->update([
                'payment_status' => 'paid',
                'verified_by_user_id' => $staffId,
                'verified_at' => $now,
            ]);
        }

        Mail::to($this->client_email)->send(new RebookingVerification($this, $ticketUrl, $receiptPath, $receiptDisk));
    }
}
