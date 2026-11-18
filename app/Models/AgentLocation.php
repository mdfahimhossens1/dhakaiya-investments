<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentLocation extends Model
{
    protected $fillable = ['agent_id', 'agent_country_id', 'agent_district_id'];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(AgentCountry::class, 'agent_country_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(AgentDistrict::class, 'agent_district_id');
    }
}