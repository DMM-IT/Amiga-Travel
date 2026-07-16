<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'schedule_id',
        'schedule_service',
        'schedule_departure_time',
        'schedule_arrival_time',
        'schedule_price',
        'status',
        'total_price',
        'client_email',
        'client_name',
        'has_vehicle',
        'vehicle_type',
        'vehicle_plate_number',
        'vehicle_price',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'schedule_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'has_vehicle' => 'boolean',
        'vehicle_price' => 'decimal:2',
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

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
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
}
