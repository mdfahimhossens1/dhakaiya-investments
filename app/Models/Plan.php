<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'duration_days',
        'display_price',
        'features',
        'is_featured',
        'status',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'duration_days' => 'integer',
            'display_price' => 'decimal:2',
            'features' => 'array',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}