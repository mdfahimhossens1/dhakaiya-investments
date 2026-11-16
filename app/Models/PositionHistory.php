<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PositionHistory extends Model
{
    protected $fillable = ['user_id', 'from_rank_id', 'to_rank_id', 'action', 'locked_value_bdt'];

    protected function casts(): array
    {
        return ['locked_value_bdt' => 'decimal:2'];
    }

    public function fromRank(): BelongsTo
    {
        return $this->belongsTo(PositionRank::class, 'from_rank_id');
    }

    public function toRank(): BelongsTo
    {
        return $this->belongsTo(PositionRank::class, 'to_rank_id');
    }
}