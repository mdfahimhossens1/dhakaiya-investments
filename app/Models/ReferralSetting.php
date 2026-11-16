<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralSetting extends Model {
    protected $fillable = ['is_enabled','commission_on','level1_percent','level2_percent','level3_percent','min_investment_to_qualify','max_commission_per_referral','hold_days','min_commission_withdraw'];
    protected $casts = ['is_enabled' => 'boolean'];
    public static function current(): self { return static::first() ?? static::create(); }
}
