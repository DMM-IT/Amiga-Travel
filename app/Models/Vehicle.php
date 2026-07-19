<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'type',
        'name',
        'vehicle_id',
        'operator',
        'description',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    public function ferryRoutes(): HasMany
    {
        return $this->hasMany(FerryRoute::class);
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'ferry' => 'Ferry',
            'airline' => 'Airline',
            default => ucfirst($this->type),
        };
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->name} ({$this->vehicle_id})";
    }

    public static function ferries()
    {
        return static::where('type', 'ferry')->where('is_active', true);
    }

    public static function airlines()
    {
        return static::where('type', 'airline')->where('is_active', true);
    }
}
