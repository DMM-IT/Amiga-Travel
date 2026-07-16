<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TransportClass extends Model
{
    protected $fillable = [
        'operator',
        'code',
        'name',
        'description',
        'price',
        'sort_order',
        'images',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_transport_class')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'schedule_transport_class')
            ->withPivot('additional_price')
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
