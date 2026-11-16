<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardCategory extends Model {
    protected $fillable = ['name','slug','description','reward_type','value_type','reward_value','min_investment','sort_order','status'];
    protected $casts = ['status' => 'boolean'];
}