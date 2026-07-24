<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCancellationReplacementSchedule extends Model
{
    use HasFactory;

    protected $table = 'cancellation_replacements';

    protected $fillable = [
        'service_cancellation_id',
        'schedule_id',
        'replacement_date',
    ];

    protected $casts = [
        'replacement_date' => 'date',
    ];

    public function serviceCancellation(): BelongsTo
    {
        return $this->belongsTo(ServiceCancellation::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
