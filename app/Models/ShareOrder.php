<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareOrder extends Model
{
    protected $fillable = [
        'user_id', 'project_id', 'type', 'shares', 'unit_price_bdt', 'total_bdt',
        'fee_bdt', 'net_bdt', 'wallet_currency', 'exchange_rate', 'wallet_amount',
        'profit_loss_bdt', 'status',
    ];

    protected function casts(): array
    {
        return [
            'shares' => 'integer',
            'unit_price_bdt' => 'decimal:2',
            'total_bdt' => 'decimal:2',
            'fee_bdt' => 'decimal:2',
            'net_bdt' => 'decimal:2',
            'exchange_rate' => 'decimal:6',
            'wallet_amount' => 'decimal:2',
            'profit_loss_bdt' => 'decimal:2',
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
}