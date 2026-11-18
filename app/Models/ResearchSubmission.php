<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchSubmission extends Model
{
    protected $fillable = [
        'research_request_id', 'agent_id', 'title', 'report',
        'attachment_path', 'attachment_name', 'submitted_at',
    ];

    protected function casts(): array
    {
        return ['submitted_at' => 'datetime'];
    }

    public function researchRequest(): BelongsTo
    {
        return $this->belongsTo(ResearchRequest::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}