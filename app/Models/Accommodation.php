<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Accommodation extends Model
{
    protected $fillable = [
        'name',
        'destination',
        'description',
        'price',
        'images',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class)
            ->withPivot('price')
            ->withTimestamps();
    }

    /**
     * First image for use as a card cover / thumbnail.
     */
    public function getCoverImageAttribute(): ?string
    {
        return $this->images[0] ?? null;
    }
}
