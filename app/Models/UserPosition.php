<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPosition extends Model
{
    protected $fillable = ['user_id', 'position_rank_id', 'locked_value_bdt', 'achieved_at', 'reward_paid_at'];

    protected function casts(): array
    {
        return [
            'locked_value_bdt' => 'decimal:2',
            'achieved_at' => 'datetime',
            'reward_paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rank(): BelongsTo
    {
        return $this->belongsTo(PositionRank::class, 'position_rank_id');
    }
}