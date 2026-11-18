<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgentDistrict extends Model
{
    protected $fillable = ['agent_country_id', 'name', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(AgentCountry::class, 'agent_country_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(AgentLocation::class, 'agent_district_id');
    }
}