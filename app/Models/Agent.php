<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    protected $fillable = [
        'user_id', 'phone', 'whatsapp', 'bio', 'approval_status',
        'is_verified', 'verified_at', 'is_active', 'is_available',
    ];

    protected function casts(): array
    {
        return [
            'is_verified'  => 'boolean',
            'is_active'    => 'boolean',
            'is_available' => 'boolean',
            'verified_at'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(AgentLocation::class);
    }

    public function districts(): BelongsToMany
    {
        return $this->belongsToMany(AgentDistrict::class, 'agent_locations', 'agent_id', 'agent_district_id')
            ->withPivot('agent_country_id')
            ->withTimestamps();
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(AgentPaymentMethod::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(ResearchRequest::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(AgentMessage::class);
    }

    /** User ke dekhano list: approved + verified + active + available */
    public function scopeListed(Builder $q): Builder
    {
        return $q->where('approval_status', 'approved')
            ->where('is_verified', true)
            ->where('is_active', true)
            ->where('is_available', true);
    }

    /** Kaj korte parbe kina (message, quote, report) */
    public function isOperational(): bool
    {
        return $this->approval_status === 'approved' && $this->is_active;
    }
public function deposits()     { return $this->hasMany(AgentDeposit::class); }
public function recharges()    { return $this->hasMany(AgentRecharge::class); }
public function transactions() { return $this->hasMany(AgentTransaction::class); }
/** এজেন্টের Recharge Balance-এর কারেন্সি (না থাকলে তার Home currency) */
public function currency(): string
{
    return strtoupper($this->recharge_currency ?: ($this->user?->walletCurrency() ?: 'BDT'));
}

}