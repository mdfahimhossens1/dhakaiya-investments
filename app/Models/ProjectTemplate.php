<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'layout_type',
        'preview_image',
        'description',
        'settings',
        'is_default',
        'status',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_default' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}