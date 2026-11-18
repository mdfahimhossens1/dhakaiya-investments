<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class ResearchRequest extends Model
{
    /** status => [label, badge color] */
    public const STATUSES = [
        'pending'     => ['Pending', 'amber'],
        'quoted'      => ['Quote Sent', 'blue'],
        'accepted'    => ['Awaiting Payment', 'purple'],
        'in_progress' => ['In Progress', 'teal'],
        'completed'   => ['Completed', 'green'],
        'rejected'    => ['Rejected', 'red'],
        'cancelled'   => ['Cancelled', 'red'],
    ];

    public const CURRENCIES = ['BDT', 'USD', 'INR', 'AED', 'SAR', 'MYR', 'GBP', 'EUR'];

    protected $fillable = [
        'user_id', 'agent_id', 'agent_district_id', 'title', 'description', 'status',
        'quoted_price', 'quoted_currency', 'quoted_days', 'quote_note', 'quoted_at', 'accepted_at',
        'payment_method_id', 'payment_details', 'payment_reference',
        'payment_submitted_at', 'payment_confirmed_at', 'completed_at', 'closed_reason',
    ];

    protected function casts(): array
    {
        return [
            'quoted_price'         => 'decimal:2',
            'quoted_days'          => 'integer',
            'quoted_at'            => 'datetime',
            'accepted_at'          => 'datetime',
            'payment_submitted_at' => 'datetime',
            'payment_confirmed_at' => 'datetime',
            'completed_at'         => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(AgentDistrict::class, 'agent_district_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(AgentPaymentMethod::class, 'payment_method_id');
    }

    public function submission(): HasOne
    {
        return $this->hasOne(ResearchSubmission::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status][0] ?? ucfirst($this->status);
    }

    public function statusColor(): string
    {
        return self::STATUSES[$this->status][1] ?? 'gray';
    }

    public function quoteText(): ?string
    {
        return $this->quoted_price === null
            ? null
            : number_format((float) $this->quoted_price, 2) . ' ' . $this->quoted_currency;
    }

    /** Request er conversation e ekta line add kore */
    public function say(int $senderId, string $body): void
    {
        AgentMessage::create([
            'agent_id'            => $this->agent_id,
            'user_id'             => $this->user_id,
            'sender_id'           => $senderId,
            'research_request_id' => $this->id,
            'body'                => $body,
        ]);
    }

    /**
     * Race condition atkate: row lock kore status check, tarpor kaj.
     * Callback false return korle kichu hoy nai dhora hobe.
     */
    public static function guarded(int $id, array $from, callable $fn): bool
    {
        return DB::transaction(function () use ($id, $from, $fn) {
            $row = static::whereKey($id)->lockForUpdate()->first();

            if (! $row || ! in_array($row->status, $from, true)) {
                return false;
            }

            return $fn($row) !== false;
        });
    }
}