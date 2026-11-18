<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'currency', 'balance', 'withdrawable_balance'];

    protected $casts = [
        'balance' => 'decimal:2',
        'withdrawable_balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /** Deposit ও রিসেলের টাকা: শুধু শেয়ার কেনায় লাগে, তোলা যায় না */
    public function getPrincipalBalanceAttribute(): string
    {
        return bcsub((string) $this->balance, (string) $this->withdrawable_balance, 2);
    }
}