<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareLock extends Model
{
    protected $fillable = [
        'user_id', 'share_holding_id', 'project_id', 'shares', 'status', 'locked_at', 'released_at',
    ];

    protected function casts(): array
    {
        return [
            'shares' => 'integer',
            'locked_at' => 'datetime',
            'released_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}