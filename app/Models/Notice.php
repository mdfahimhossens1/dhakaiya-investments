<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'title',
    'slug',
    'content',
    'priority',
    'status',
    'is_pinned',
    'publish_date',
    'expiry_date',
])]
class Notice extends Model
{
    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'publish_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function scopePublished($query)
    {
        return $query
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('publish_date')
                    ->orWhereDate('publish_date', '<=', today());
            })
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                    ->orWhereDate('expiry_date', '>=', today());
            });
    }
}