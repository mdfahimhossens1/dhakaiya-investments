<?php // app/Models/AgentDeposit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentDeposit extends Model
{
    protected $fillable = ['agent_id', 'amount', 'approved_amount', 'method', 'transaction_id',
        'sender_info', 'status', 'admin_note', 'reviewed_by', 'reviewed_at'];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function agent()    { return $this->belongsTo(Agent::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function scopePending($q) { return $q->where('status', 'pending'); }
}