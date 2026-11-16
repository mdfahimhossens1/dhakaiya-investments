<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAccount extends Model
{
    protected $fillable = [
        'user_id', 'payment_method_id', 'method', 'currency', 'account_name',
        'account_number', 'bank_name', 'branch_name', 'details',
        'is_default', 'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
            'is_default' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    /** শেষ ৪ অক্ষর ছাড়া বাকি ঢাকা */
    public function maskedDetails(): string
    {
        $n = (string) $this->account_number;
        $len = mb_strlen($n);

        if ($len <= 4) {
            return str_repeat('*', max($len, 4));
        }

        return str_repeat('*', min(8, $len - 4)) . mb_substr($n, -4);
    }
}