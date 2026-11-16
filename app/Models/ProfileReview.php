<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileReview extends Model
{
    protected $fillable = [
        'user_id',
        'reviewed_by',
        'status',
        'review_notes',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    // User whose profile is being reviewed
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Admin who reviewed the profile
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}