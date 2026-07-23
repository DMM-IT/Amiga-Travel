<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GraciaUserBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_points',
        'unconverted_spend_centavos',
    ];

    protected $casts = [
        'current_points' => 'integer',
        'unconverted_spend_centavos' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
