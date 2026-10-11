<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'category',
        'price',
        'icon',
        'tag',
        'color',
        'description',
        'is_bestseller',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_bestseller' => 'boolean',
        'is_active' => 'boolean',
    ];
}
