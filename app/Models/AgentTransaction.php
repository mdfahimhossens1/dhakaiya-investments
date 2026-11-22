<?php // app/Models/AgentTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentTransaction extends Model
{
    protected $fillable = ['agent_id', 'direction', 'type', 'amount', 'balance_after',
        'source_type', 'source_id', 'description'];

    public function agent()  { return $this->belongsTo(Agent::class); }
    public function source() { return $this->morphTo(); }
}