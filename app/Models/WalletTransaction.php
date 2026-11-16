<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id', 'user_id', 'direction', 'type', 'amount',
        'balance_after', 'currency', 'source_type', 'source_id',
        'description', 'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'meta' => 'array',
    ];

    public const INCOME_TYPES = ['daily_profit', 'referral_commission', 'reward'];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeIncome($query)
    {
        return $query->where('direction', 'credit')
            ->whereIn('type', self::INCOME_TYPES);
    }

    public const TYPE_LABELS = [
    'deposit' => 'Deposit',
    'withdraw' => 'Withdrawal',
    'withdraw_refund' => 'Withdrawal Refund',
    'share_buy' => 'Share Purchase',
    'share_sell' => 'Share Resale',
    'daily_profit' => 'Daily Profit',
    'referral_commission' => 'Referral Commission',
    'reward' => 'Reward',
    'adjustment' => 'Adjustment',
    ];

    public function typeLabel(): string
    {
        return self::TYPE_LABELS[$this->type] ?? ucwords(str_replace('_', ' ', (string) $this->type));
    }
}