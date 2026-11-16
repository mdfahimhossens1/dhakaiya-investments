<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PositionRank extends Model
{
    protected $fillable = [
        'name', 'level', 'min_total_investment', 'min_active_referrals',
        'min_resell_volume', 'bonus_amount', 'profit_boost_percent',
        'lock_period_days', 'badge_color', 'status',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'min_total_investment' => 'decimal:2',
            'min_active_referrals' => 'integer',
            'min_resell_volume' => 'decimal:2',
            'bonus_amount' => 'decimal:2',
            'profit_boost_percent' => 'decimal:2',
            'lock_period_days' => 'integer',
            'status' => 'boolean',
        ];
    }

    public function userPositions(): HasMany
    {
        return $this->hasMany(UserPosition::class, 'position_rank_id');
    }
}