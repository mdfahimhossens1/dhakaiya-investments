<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    protected $fillable = ['currency', 'rate_to_bdt', 'updated_by'];

    protected function casts(): array
    {
        return ['rate_to_bdt' => 'decimal:6'];
    }

    private static ?array $memo = null;

    public static function rateFor(string $currency): ?string
    {
        self::$memo ??= self::pluck('rate_to_bdt', 'currency')->map(fn ($v) => (string) $v)->all();

        return self::$memo[strtoupper($currency)] ?? null;
    }

    public static function forget(): void
    {
        self::$memo = null;
    }
}