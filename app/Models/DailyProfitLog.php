<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyProfitLog extends Model
{
    protected $fillable = [
        'share_holding_id', 'user_id', 'project_id', 'profit_date', 'shares',
        'share_price_bdt', 'profit_percent', 'profit_bdt',
        'wallet_currency', 'exchange_rate', 'wallet_amount',
    ];

    protected function casts(): array
    {
        return [
            'profit_date' => 'date',
            'shares' => 'integer',
            'share_price_bdt' => 'decimal:2',
            'profit_percent' => 'decimal:3',
            'profit_bdt' => 'decimal:2',
            'exchange_rate' => 'decimal:6',
            'wallet_amount' => 'decimal:2',
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

    public function holding(): BelongsTo
    {
        return $this->belongsTo(ShareHolding::class, 'share_holding_id');
    }
}