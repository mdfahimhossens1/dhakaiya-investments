<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareHolding extends Model
{
    protected $fillable = [
        'user_id', 'project_id', 'shares', 'locked_shares', 'invested_bdt', 'status',
    ];

    protected function casts(): array
    {
        return [
            'shares' => 'integer',
            'locked_shares' => 'integer',
            'invested_bdt' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /** রিসেল করা যাবে এমন শেয়ার (lock বাদে) */
    public function getSellableSharesAttribute(): int
    {
        return max(0, $this->shares - $this->locked_shares);
    }

    /** বর্তমান বাজার মূল্য (BDT) */
    public function getCurrentValueAttribute(): string
    {
        return bcmul((string) ($this->project?->share_price ?? 0), (string) $this->shares, 2);
    }
}