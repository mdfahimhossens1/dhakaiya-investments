<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentPaymentMethod extends Model
{
    public const METHODS = [
        'bkash'  => 'bKash',
        'nagad'  => 'Nagad',
        'rocket' => 'Rocket',
        'bank'   => 'Bank Transfer',
        'other'  => 'Other',
    ];

    protected $fillable = [
        'agent_id', 'method', 'account_name', 'account_number',
        'bank_name', 'note', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function label(): string
    {
        return self::METHODS[$this->method] ?? ucfirst($this->method);
    }

    /** Request e snapshot hishebe rakhar jonno */
    public function snapshot(): string
    {
        $parts = [$this->label(), $this->account_number, $this->account_name];
        if ($this->bank_name) $parts[] = $this->bank_name;

        return implode(' · ', $parts);
    }
}