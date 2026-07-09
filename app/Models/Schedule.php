<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'ferry_route_id',
        'service_name',
        'departure_time',
        'arrival_time',
        'duration_minutes',
        'price',
        'operating_days',
        'availability_label',
        'is_active',
    ];

    protected $casts = [
        'operating_days' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function ferryRoute(): BelongsTo
    {
        return $this->belongsTo(FerryRoute::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForRouteAndDate(Builder $query, string $origin, string $destination, string $date): Builder
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeekIso;

        return $query->active()
            ->whereHas('ferryRoute', fn (Builder $routeQuery) => $routeQuery
                ->where('origin', $origin)
                ->where('destination', $destination)
                ->where('is_active', true))
            ->whereJsonContains('operating_days', $dayOfWeek)
            ->orderBy('departure_time');
    }

    public function getFormattedDepartureAttribute(): string
    {
        return Carbon::parse($this->departure_time)->format('H:i');
    }

    public function getFormattedArrivalAttribute(): string
    {
        return Carbon::parse($this->arrival_time)->format('H:i');
    }

    public function getDurationLabelAttribute(): string
    {
        if ($this->duration_minutes) {
            $hours = intdiv($this->duration_minutes, 60);
            $minutes = $this->duration_minutes % 60;

            if ($hours > 0 && $minutes > 0) {
                return "{$hours}h {$minutes}m";
            }

            if ($hours > 0) {
                return "{$hours}h";
            }

            return "{$minutes}m";
        }

        $departure = Carbon::parse($this->departure_time);
        $arrival = Carbon::parse($this->arrival_time);

        if ($arrival->lessThan($departure)) {
            $arrival->addDay();
        }

        $totalMinutes = $departure->diffInMinutes($arrival);
        $hours = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;

        return "{$hours}h {$minutes}m";
    }

    public function toBookingArray(): array
    {
        return [
            'id' => $this->id,
            'departure' => $this->formatted_departure,
            'arrival' => $this->formatted_arrival,
            'duration' => $this->duration_label,
            'price' => floatval($this->price),
            'service' => $this->service_name,
            'availability' => $this->availability_label ?? 'Available',
        ];
    }
}
