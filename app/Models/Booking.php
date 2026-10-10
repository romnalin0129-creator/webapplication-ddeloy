<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'user_id',
        'service_id',
        'booking_date',
        'booking_time',
        'customer_name',
        'phone',
        'notes',
        'duration_minutes',
        'price',
        'status',
        'slot_key',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'duration_minutes' => 'integer',
        'price' => 'decimal:2',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(MassageService::class, 'service_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
