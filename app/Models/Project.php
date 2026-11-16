<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'company_id',
        'project_category_id',
        'project_template_id',
        'name',
        'slug',
        'image',
        'category',
        'description',
        'total_shares',
        'available_shares',
        'share_price',
        'daily_profit_percent',
        'min_buy_shares',
        'status',
        'template',
        'start_date',
        'end_date',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'total_shares' => 'integer',
            'available_shares' => 'integer',
            'share_price' => 'decimal:2',
            'daily_profit_percent' => 'decimal:3',
            'min_buy_shares' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function projectCategory(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class);
    }

    public function projectTemplate(): BelongsTo
    {
        return $this->belongsTo(ProjectTemplate::class);
    }

    /** বিক্রি হওয়া শেয়ার */
    public function getSoldSharesAttribute(): int
    {
        return max(0, $this->total_shares - $this->available_shares);
    }

    /** এখন কেনা যাবে কিনা (Step 4-এ কাজে লাগবে) */
    public function isOpenForPurchase(): bool
    {
        return $this->is_active
            && $this->status === 'active'
            && $this->available_shares > 0;
    }
}