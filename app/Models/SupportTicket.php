<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    public const STATUSES = [
        'open' => 'Open', 'in_progress' => 'In Progress', 'answered' => 'Answered',
        'resolved' => 'Resolved', 'closed' => 'Closed',
    ];

    public const PRIORITIES = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'];

    public const CATEGORIES = [
        'general' => 'General', 'account' => 'Account & Profile', 'kyc' => 'KYC Verification',
        'deposit' => 'Deposit', 'withdrawal' => 'Withdrawal', 'shares' => 'Shares & Projects',
        'referral' => 'Referral & Rewards', 'technical' => 'Technical Issue', 'other' => 'Other',
    ];

    protected $fillable = ['ticket_no', 'user_id', 'subject', 'category', 'priority', 'status', 'assigned_to', 'last_reply_at', 'closed_at'];

    protected $casts = ['last_reply_at' => 'datetime', 'closed_at' => 'datetime'];

    protected static function booted(): void
    {
        static::created(function (self $ticket) {
            $ticket->updateQuietly(['ticket_no' => 'TKT-' . str_pad((string) $ticket->id, 6, '0', STR_PAD_LEFT)]);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(SupportTicketReply::class)->orderBy('created_at')->orderBy('id');
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }
}
