<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Schedule extends Model
{
    protected $fillable = [
        'ferry_route_id',
        'service_name',
        'vehicle_name',
        'departure_time',
        'arrival_time',
        'duration_minutes',
        'price',
        'operating_days',
        'availability_label',
        'seat_rows',
        'seat_columns',
        'is_active',
    ];

    protected $casts = [
        'operating_days' => 'array',
        'price' => 'decimal:2',
        'seat_columns' => 'array',
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

    public function transportClasses(): BelongsToMany
    {
        return $this->belongsToMany(TransportClass::class, 'schedule_transport_class')
            ->withPivot('additional_price')
            ->withTimestamps();
    }

    public function scheduleAccommodations(): HasMany
    {
        return $this->hasMany(ScheduleAccommodation::class)->orderBy('sort_order');
    }

    public function activeScheduleAccommodations(): HasMany
    {
        return $this->scheduleAccommodations()->where('is_active', true);
    }

    public function getSeatColumnLettersAttribute(): array
    {
        return $this->seat_columns ?? ['A', 'B', 'C', 'D', 'E', 'F'];
    }

    public function getSeatRowCountAttribute(): int
    {
        return $this->seat_rows ?? 30;
    }

    public function getOccupiedSeatsForDate(string $date): array
    {
        return Passenger::query()
            ->whereNotNull('seat_number')
            ->whereHas('booking', function (Builder $query) use ($date) {
                $query->where('schedule_id', $this->id)
                    ->where('departure_date', $date)
                    ->where('status', '!=', 'cancelled');
            })
            ->pluck('seat_number')
            ->all();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForRouteAndDate(Builder $query, string $origin, string $destination, string $date, ?string $mode = null): Builder
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeekIso;

        return $query->active()
            ->whereHas('ferryRoute', function (Builder $routeQuery) use ($origin, $destination, $mode) {
                $routeQuery->where('origin', $origin)
                    ->where('destination', $destination)
                    ->where('is_active', true);

                if (! empty($mode)) {
                    $routeQuery->where('mode', $mode);
                }
            })
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

    public function getAirlineSeatingProfile(): ?array
    {
        $operator = $this->ferryRoute?->operator;
        $aircraftType = $this->vehicle_name;

        if (blank($operator) || blank($aircraftType)) {
            return null;
        }

        return config("airline_seating.operators.{$operator}.aircraft.{$aircraftType}");
    }

    protected function inferTransportClassCode(TransportClass $class): string
    {
        if (filled($class->code)) {
            return $class->code;
        }

        return match (strtolower($class->name)) {
            'premium flatbed' => 'premium-flatbed',
            'hot seats' => 'hot-seat',
            'standard plus' => 'standard-plus',
            'premium economy / comfort class' => 'premium-economy',
            'business class' => 'business',
            'economy class' => 'economy',
            'standard' => 'standard',
            'premium' => 'premium',
            default => str($class->name)->slug()->value(),
        };
    }

    protected function buildCabinLayouts(array $aircraftConfig): array
    {
        $operatorConfig = config('airline_seating.operators.' . $this->ferryRoute?->operator . '.classes', []);
        $currentRow = 1;
        $layouts = [];

        foreach ($aircraftConfig['class_order'] ?? [] as $classCode) {
            $classConfig = $operatorConfig[$classCode] ?? null;
            $seatCount = $aircraftConfig['seat_counts'][$classCode] ?? null;

            if (! $classConfig || ! $seatCount) {
                continue;
            }

            $rows = $this->buildSeatRows(
                $currentRow,
                (int) $seatCount,
                $classConfig['columns'] ?? ['A', 'B', 'C', 'D', 'E', 'F'],
            );

            $lastRow = count($rows) > 0 ? $rows[array_key_last($rows)]['label'] : $currentRow - 1;

            $layouts[$classCode] = [
                'code' => $classCode,
                'name' => $classConfig['name'],
                'seat_capacity' => (int) $seatCount,
                'row_start' => $currentRow,
                'row_end' => $lastRow,
                'seat_rows' => $rows,
            ];

            $currentRow = $lastRow + 1;
        }

        return $layouts;
    }

    protected function buildSeatRows(int $startRow, int $seatCount, array $columns): array
    {
        $rows = [];
        $remaining = $seatCount;
        $rowNumber = $startRow;
        $midpoint = (int) ceil(count($columns) / 2);
        $leftColumns = array_slice($columns, 0, $midpoint);
        $rightColumns = array_slice($columns, $midpoint);

        while ($remaining > 0) {
            $rowSeatCount = min(count($columns), $remaining);
            $rowColumns = array_slice($columns, 0, $rowSeatCount);

            $rows[] = [
                'label' => $rowNumber,
                'left' => collect($leftColumns)
                    ->filter(fn (string $column) => in_array($column, $rowColumns, true))
                    ->map(fn (string $column) => [
                        'id' => $rowNumber . $column,
                        'label' => $column,
                    ])
                    ->values()
                    ->all(),
                'right' => collect($rightColumns)
                    ->filter(fn (string $column) => in_array($column, $rowColumns, true))
                    ->map(fn (string $column) => [
                        'id' => $rowNumber . $column,
                        'label' => $column,
                    ])
                    ->values()
                    ->all(),
            ];

            $remaining -= $rowSeatCount;
            $rowNumber++;
        }

        return $rows;
    }

    public function toBookingArray(?string $departureDate = null): array
    {
        $mode = $this->ferryRoute?->mode ?? 'ferry';
        $occupiedSeats = $departureDate ? $this->getOccupiedSeatsForDate($departureDate) : [];
        $airlineSeatingProfile = $mode === 'airline' ? $this->getAirlineSeatingProfile() : null;
        $cabinLayouts = $airlineSeatingProfile ? $this->buildCabinLayouts($airlineSeatingProfile) : [];

        // Explicitly fetch accommodations and transport classes
        $activeAccommodations = $this->activeScheduleAccommodations()->get();
        $activeTransportClasses = $this->transportClasses()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return [
            'id' => $this->id,
            'departure' => $this->formatted_departure,
            'arrival' => $this->formatted_arrival,
            'duration' => $this->duration_label,
            'price' => floatval($this->price),
            'service' => $this->service_name,
            'vehicle_name' => $this->vehicle_name,
            'availability' => $this->availability_label ?? 'Available',
            'mode' => $mode,
            'operator' => $this->ferryRoute?->operator,
            'accommodations' => $activeAccommodations
                ->map(fn (ScheduleAccommodation $accommodation) => [
                    'id' => $accommodation->id,
                    'name' => $accommodation->name,
                    'description' => $accommodation->description,
                    'price' => floatval($accommodation->price),
                    'has_bed' => $accommodation->has_bed,
                ])
                ->values()
                ->all(),
            'transport_classes' => $activeTransportClasses
                ->map(function (TransportClass $class) use ($cabinLayouts) {
                    $classCode = $this->inferTransportClassCode($class);
                    $cabinLayout = $cabinLayouts[$classCode] ?? null;

                    return [
                        'id' => $class->id,
                        'code' => $classCode,
                        'name' => $class->name,
                        'description' => $class->description,
                        'price' => floatval($class->price),
                        'cover_image' => $class->cover_image ? asset('storage/' . $class->cover_image) : null,
                        'seat_capacity' => $cabinLayout['seat_capacity'] ?? null,
                        'row_start' => $cabinLayout['row_start'] ?? null,
                        'row_end' => $cabinLayout['row_end'] ?? null,
                        'seat_rows' => $cabinLayout['seat_rows'] ?? [],
                    ];
                })
                ->values()
                ->all(),
            'seat_rows' => $this->seat_row_count,
            'seat_columns' => $this->seat_column_letters,
            'occupied_seats' => $occupiedSeats,
            'aircraft_capacity' => $airlineSeatingProfile['capacity'] ?? null,
        ];
    }
}
