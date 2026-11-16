<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use App\Support\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ReferralCommission;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'country',
    'phone',
    'email',
    'password',
    'status',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // User's profile review
    public function profileReview(): HasOne
    {
        return $this->hasOne(ProfileReview::class);
    }

    // Profile reviews performed by this admin
    public function performedProfileReviews(): HasMany
    {
        return $this->hasMany(ProfileReview::class, 'reviewed_by');
    }

    protected static function booted(): void
{
    static::creating(function (User $user) {
        if (empty($user->referral_code)) {
            do {
                $code = strtoupper(Str::random(8));
            } while (static::where('referral_code', $code)->exists());

            $user->referral_code = $code;
        }
    });

    static::created(function (User $user) {
        // রেজিস্ট্রেশনের সময়েই ইউজারের নিজের দেশের Wallet তৈরি হবে
        $user->wallets()->firstOrCreate(
            ['currency' => $user->walletCurrency()],
            ['balance' => 0]
        );
    });
}


public function walletCurrency(): string
{
    return Currency::forCountry($this->country)['code'];
}

public function wallets(): HasMany
{
    return $this->hasMany(Wallet::class);
}

public function wallet(): ?Wallet
{
    return $this->wallets()->where('currency', $this->walletCurrency())->first();
}

public function paymentAccounts(): HasMany
{
    return $this->hasMany(PaymentAccount::class);
}

public function walletTransactions(): HasMany
{
    return $this->hasMany(WalletTransaction::class);
}

public function referrer(): BelongsTo
{
    return $this->belongsTo(User::class, 'referred_by_id');
}

public function depositRequests(): HasMany
{
    return $this->hasMany(DepositRequest::class);
}

public function withdrawRequests(): HasMany
{
    return $this->hasMany(WithdrawRequest::class);
}

public function shareHoldings(): HasMany
{
    return $this->hasMany(ShareHolding::class);
}

public function shareOrders(): HasMany
{
    return $this->hasMany(ShareOrder::class);
}

public function dailyProfitLogs(): HasMany
{
    return $this->hasMany(DailyProfitLog::class);
}

public function userPosition(): HasOne
{
    return $this->hasOne(UserPosition::class);
}

public function shareLocks(): HasMany
{
    return $this->hasMany(ShareLock::class);
}

public function rewardLogs(): HasMany
{
    return $this->hasMany(RewardLog::class);
}

public function countryCode(): string
{
    return \App\Support\Currency::normalize($this->country);
}

/** নিজের দেশের কারেন্সি + USD */
public function walletCurrencies(): array
{
    $local = $this->walletCurrency();

    return $local === 'USD' ? ['USD'] : [$local, 'USD'];
}

public function referralCommissions(): HasMany
{
    return $this->hasMany(ReferralCommission::class, 'user_id');
}


}