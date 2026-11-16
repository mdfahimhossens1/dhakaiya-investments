<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    public const TYPES = [
        'mobile' => 'Mobile Wallet',
        'bank' => 'Bank Transfer',
        'crypto' => 'Crypto Wallet',
        'other' => 'Other',
    ];

    protected $fillable = [
        'name', 'code', 'type', 'icon', 'description', 'countries',
        'supported_currencies', 'deposit_enabled', 'withdraw_enabled',
        'deposit_account', 'deposit_instructions', 'withdraw_fee_percent',
        'status', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'countries' => 'array',
            'supported_currencies' => 'array',
            'deposit_enabled' => 'boolean',
            'withdraw_enabled' => 'boolean',
            'withdraw_fee_percent' => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(PaymentAccount::class);
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('name');
    }

    public function availableInCountry(string $country): bool
    {
        return empty($this->countries) || in_array($country, $this->countries, true);
    }

    public function supportsCurrency(string $currency): bool
    {
        return empty($this->supported_currencies) || in_array($currency, $this->supported_currencies, true);
    }

    /** ইউজারের Wallet কারেন্সির মধ্যে যেগুলো এই method চালায় */
    public function currenciesFor(array $userCurrencies): array
    {
        return array_values(array_filter($userCurrencies, fn ($c) => $this->supportsCurrency($c)));
    }

    public function availableFor(string $country, array $userCurrencies): bool
    {
        return $this->availableInCountry($country) && count($this->currenciesFor($userCurrencies)) > 0;
    }

    /** type অনুযায়ী ইউজারকে কী কী তথ্য দিতে হবে */
    public function fieldDefinitions(): array
    {
        return match ($this->type) {
            'bank' => [
                ['key' => 'account_name', 'label' => 'Account Holder Name', 'required' => true],
                ['key' => 'bank_name', 'label' => 'Bank Name', 'required' => true],
                ['key' => 'branch_name', 'label' => 'Branch', 'required' => false],
                ['key' => 'account_number', 'label' => 'Account Number / IBAN', 'required' => true],
                ['key' => 'swift_code', 'label' => 'SWIFT / Routing Code', 'required' => false],
            ],
            'crypto' => [
                ['key' => 'account_number', 'label' => 'Wallet Address', 'required' => true],
                ['key' => 'network', 'label' => 'Network (e.g. TRC20)', 'required' => true],
            ],
            'mobile' => [
                ['key' => 'account_number', 'label' => 'Mobile / Wallet Number', 'required' => true],
            ],
            default => [
                ['key' => 'account_number', 'label' => 'Account Details', 'required' => true],
            ],
        };
    }
}