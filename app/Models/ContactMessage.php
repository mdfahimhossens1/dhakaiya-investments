<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    public const STATUSES = ['new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'archived' => 'Archived'];

    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'subject', 'message', 'status', 'read_at',
        'admin_reply', 'replied_by', 'replied_at', 'ip_address',
    ];

    protected $casts = ['read_at' => 'datetime', 'replied_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function repliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }
}
