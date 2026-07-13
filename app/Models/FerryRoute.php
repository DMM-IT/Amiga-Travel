<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FerryRoute extends Model
{
    protected $fillable = [
        'origin',
        'destination',
        'is_active',
        'mode',
        'operator',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function getLabelAttribute(): string
    {
        $parts = ["{$this->origin} → {$this->destination}"];

        if (! empty($this->operator)) {
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
            ->orderBy('origin')
            ->pluck('origin')
            ->unique()
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
            ->orderBy('destination')
            ->pluck('destination')
            ->unique()
            ->values()
            ->all();
    }
}
