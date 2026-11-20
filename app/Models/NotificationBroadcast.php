<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationBroadcast extends Model
{
    public const TYPES = [
        'info'    => 'Info',
        'success' => 'Success',
        'warning' => 'Warning',
        'danger'  => 'Important',
    ];

    public const AUDIENCES = [
        'all_users'  => 'All Users',
        'all_agents' => 'All Agents',
        'all_admins' => 'All Admins',
        'everyone'   => 'Everyone',
        'specific'   => 'Specific User',
    ];

    protected $fillable = [
        'title',
        'message',
        'type',
        'audience',
        'target_user_id',
        'action_url',
        'recipients_count',
        'sent_by',
    ];

    protected $casts = [
        'recipients_count' => 'integer',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}