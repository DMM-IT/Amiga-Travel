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
        return "{$this->origin} → {$this->destination}";
    }

    public static function activeOrigins(): array
    {
        return static::query()
            ->where('is_active', true)
            ->orderBy('origin')
            ->pluck('origin')
            ->unique()
            ->values()
            ->all();
    }

    public static function activeDestinationsFor(string $origin): array
    {
        return static::query()
            ->where('is_active', true)
            ->where('origin', $origin)
            ->orderBy('destination')
            ->pluck('destination')
            ->values()
            ->all();
    }
}
