<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralCommission extends Model
{
    protected $fillable = [
        'user_id', 'source_user_id', 'level', 'type', 'period_key',
        'base_bdt', 'percent', 'commission_bdt', 'status', 'release_at',
        'paid_at', 'wallet_currency', 'exchange_rate', 'wallet_amount',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'base_bdt' => 'decimal:2',
            'percent' => 'decimal:3',
            'commission_bdt' => 'decimal:2',
            'exchange_rate' => 'decimal:6',
            'wallet_amount' => 'decimal:2',
            'release_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sourceUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'source_user_id');
    }
}