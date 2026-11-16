<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'description', 'method', 'route_name', 'url',
        'status_code', 'ip_address', 'user_agent', 'properties',
    ];

    protected $casts = ['properties' => 'array'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Manual logging, e.g. ActivityLog::record('auth.login', 'Logged in', [], $user->id) */
    public static function record(string $action, ?string $description = null, array $properties = [], ?int $userId = null): self
    {
        return static::create([
            'user_id'     => $userId ?? auth()->id(),
            'action'      => $action,
            'description' => $description,
            'url'         => request()->fullUrl(),
            'ip_address'  => request()->ip(),
            'user_agent'  => substr((string) request()->userAgent(), 0, 500),
            'properties'  => $properties ?: null,
        ]);
    }
}