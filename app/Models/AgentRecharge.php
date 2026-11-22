<?php // app/Models/AgentRecharge.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentRecharge extends Model
{
    protected $fillable = ['agent_id', 'user_id', 'amount', 'wallet_currency', 'exchange_rate',
        'wallet_amount', 'status', 'request_token'];

    public function agent() { return $this->belongsTo(Agent::class); }
    public function user()  { return $this->belongsTo(User::class); }
}