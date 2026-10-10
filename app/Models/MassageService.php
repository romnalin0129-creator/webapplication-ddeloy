<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MassageService extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'name',
        'description',
        'duration_minutes',
        'price',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'service_id');
    }
}
