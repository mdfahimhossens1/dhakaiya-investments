<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationBroadcast extends Model
{
    public const TYPES = ['info' => 'Information', 'success' => 'Success', 'warning' => 'Warning', 'important' => 'Important'];

    public const AUDIENCES = [
        'all_users'  => 'All investors (users)',
        'all_admins' => 'All admins',
        'everyone'   => 'Everyone',
        'specific'   => 'One specific user',
    ];

    protected $fillable = ['title', 'message', 'type', 'audience', 'target_user_id', 'action_url', 'recipients_count', 'sent_by'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
