<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLoginHistory extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'login_type',
        'ip_address',
        'user_agent',
        'success',
        'description',
        'metadata',
    ];

    protected $casts = [
        'success' => 'boolean',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
