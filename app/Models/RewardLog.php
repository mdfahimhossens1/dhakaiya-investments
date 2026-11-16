<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewardLog extends Model
{
    protected $fillable = [
        'user_id', 'category', 'period_key', 'title', 'amount_bdt',
        'wallet_currency', 'exchange_rate', 'wallet_amount', 'meta',
    ];

    protected function casts(): array
    {
        return [
            'amount_bdt' => 'decimal:2',
            'exchange_rate' => 'decimal:6',
            'wallet_amount' => 'decimal:2',
            'meta' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}