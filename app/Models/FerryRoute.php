<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FerryRoute extends Model
{
    protected $fillable = [
        'origin',
        'destination',
        'is_active',
        'mode',
        'operator',
        'vehicle_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function getLabelAttribute(): string
    {
        $parts = ["{$this->origin} → {$this->destination}"];

        // Show vehicle name if available
        if ($this->vehicle) {
            $parts[] = $this->vehicle->full_name;
        } elseif (! empty($this->operator)) {
            $parts[] = $this->operator;
        }

        if (! empty($this->mode)) {
            $parts[] = ucfirst($this->mode);
        }

        return implode(' • ', $parts);
    }

    public static function activeOrigins(?string $mode = null): array
    {
        return static::query()
            ->where('is_active', true)
            ->when($mode, function ($query, $mode) {
                $query->where('mode', $mode);
            })
            ->select('origin')
            ->distinct()
            ->orderBy('origin')
            ->pluck('origin')
            ->values()
            ->all();
    }

    public static function activeDestinationsFor(string $origin, ?string $mode = null): array
    {
        return static::query()
            ->where('is_active', true)
            ->where('origin', $origin)
            ->when($mode, function ($query, $mode) {
                $query->where('mode', $mode);
            })
            ->select('destination')
            ->distinct()
            ->orderBy('destination')
            ->pluck('destination')
            ->values()
            ->all();
    }

    public static function activeOperatorsFor(?string $mode = null): array
    {
        return static::query()
            ->where('is_active', true)
            ->when($mode, function ($query, $mode) {
                $query->where('mode', $mode);
            })
            ->whereNotNull('operator')
            ->where('operator', '!=', '')
            ->select('operator')
            ->distinct()
            ->orderBy('operator')
            ->pluck('operator')
            ->values()
            ->all();
    }
}
