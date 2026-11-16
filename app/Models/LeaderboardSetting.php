<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderboardSetting extends Model {
    protected $fillable = ['resell_leaderboard_enabled','monthly_leaderboard_enabled','top_performers_enabled','ranking_basis','reset_cycle','show_top_n','show_amount_publicly'];
    protected $casts = [
        'resell_leaderboard_enabled' => 'boolean',
        'monthly_leaderboard_enabled' => 'boolean',
        'top_performers_enabled' => 'boolean',
        'show_amount_publicly' => 'boolean',
    ];
    public static function current(): self { return static::first() ?? static::create(); }
}
