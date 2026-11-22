<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\AgentDeposit;
use App\Models\AgentRecharge;
use App\Models\AgentTransaction;
use App\Models\User;
use App\Support\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AgentBalanceService
{
    public function __construct(
        private WalletService $wallets,
        private ShareService $shares
    ) {
    }

    /** Approve হওয়া ডিপোজিট: এজেন্টের Recharge Balance বাড়ানো (caller-এর transaction-এর ভেতরে) */
    public function creditDeposit(AgentDeposit $deposit, string $amount): AgentTransaction
    {
        return $this->apply(
            $deposit->agent_id,
            'credit',
            'deposit',
            $amount,
            $deposit,
            'Deposit via ' . strtoupper($deposit->method) . ' (TrxID: ' . $deposit->transaction_id . ')'
        );
    }

    /**
     * ইউজারকে রিচার্জ: এজেন্টের ব্যালেন্স কমে, ইউজারের Wallet-এ যোগ হয়। সবই এক transaction-এ।
     *
     * @throws RuntimeException
     * @throws \Illuminate\Database\UniqueConstraintViolationException ($token আগেই ব্যবহৃত হলে)
     */
    public function recharge(Agent $agent, User $user, string $amount, string $token): AgentRecharge
    {
        $amount = number_format((float) $amount, 2, '.', '');

        if (bccomp($amount, '0', 2) <= 0) {
            throw new RuntimeException('Amount must be greater than zero.');
        }

        if ($user->id === $agent->user_id || $user->role !== 'user' || $user->status !== 'active') {
            throw new RuntimeException('এই ইউজারকে রিচার্জ করা যাবে না।');
        }

        [$currency, $walletAmount, $rate] = $this->toWallet($user, $amount);

        return DB::transaction(function () use ($agent, $user, $amount, $token, $currency, $walletAmount, $rate) {
            // এজেন্ট row লক করে অবস্থা যাচাই (অনুমোদিত, active কিনা)
            $locked = Agent::whereKey($agent->id)->lockForUpdate()->firstOrFail();

            if (! $locked->isOperational()) {
                throw new RuntimeException('আপনার এজেন্ট অ্যাকাউন্ট এখন সক্রিয় নয়।');
            }

            // token unique, তাই ডুপ্লিকেট হলে এখানেই exception হয়ে সব rollback
            $recharge = AgentRecharge::create([
                'agent_id' => $agent->id,
                'user_id' => $user->id,
                'amount' => $amount,
                'wallet_currency' => $currency,
                'exchange_rate' => $rate,
                'wallet_amount' => $walletAmount,
                'status' => 'completed',
                'request_token' => $token,
            ]);

            // ১) এজেন্টের Recharge Balance থেকে কাটা (কম থাকলে exception)
            $this->apply($agent->id, 'debit', 'recharge', $amount, $recharge, "Recharge to user #{$user->id}");

            // ২) ইউজারের Investment Wallet-এ যোগ (WalletService দিয়ে, Principal অংশে যায়)
            $this->wallets->credit(
                $user,
                'agent_recharge',
                $walletAmount,
                $recharge,
                'Wallet recharge by agent',
                ['agent_id' => $agent->id],
                $currency
            );

            return $recharge;
        });
    }

    /* ------------------------------------------------------------------ */

    private function apply(
        int $agentId,
        string $direction,
        string $type,
        string $amount,
        ?Model $source,
        ?string $description
    ): AgentTransaction {
        $amount = number_format((float) $amount, 2, '.', '');

        if (bccomp($amount, '0', 2) <= 0) {
            throw new RuntimeException('Amount must be greater than zero.');
        }

        return DB::transaction(function () use ($agentId, $direction, $type, $amount, $source, $description) {
            $agent = Agent::whereKey($agentId)->lockForUpdate()->firstOrFail();

            $current = (string) $agent->recharge_balance;

            if ($direction === 'debit') {
                if (bccomp($current, $amount, 2) < 0) {
                    throw new RuntimeException('আপনার Recharge Balance যথেষ্ট নয়।');
                }

                $new = bcsub($current, $amount, 2);
            } else {
                $new = bcadd($current, $amount, 2);
            }

            $agent->forceFill(['recharge_balance' => $new])->save();

            return AgentTransaction::create([
                'agent_id' => $agentId,
                'direction' => $direction,
                'type' => $type,
                'amount' => $amount,
                'balance_after' => $new,
                'source_type' => $source?->getMorphClass(),
                'source_id' => $source?->getKey(),
                'description' => $description,
            ]);
        });
    }

    /**
     * USD (এজেন্টের ব্যালেন্স) → ইউজারের Wallet কারেন্সি (Home currency)।
     * ⚠️ নিচের রেট-লজিক আপনার ReferralService::releaseDue()-এর ধরন ধরে লেখা।
     *    $home['rate'] = Wallet কারেন্সির ১ ইউনিট = কত BDT,
     *    Currency::rateFromBdt('USD') = ১ BDT = কত USD।
     *    আপনার আসল Currency/ShareService::home() এর রেটের দিক ভিন্ন হলে শুধু এই method ঠিক করুন।
     *
     * @return array{0:string,1:string,2:string} [currency, wallet_amount, usd→wallet rate]
     */
    private function toWallet(User $user, string $usd): array
    {
        $home = $this->shares->home($user);

        if (! $home) {
            throw new RuntimeException('এই ইউজারের Wallet কারেন্সির রেট পাওয়া যায়নি।');
        }

        $currency = $home['currency'];

        if ($currency === 'USD') {
            return ['USD', $usd, '1.000000'];
        }

        $usdPerBdt = (string) Currency::rateFromBdt('USD');

        if (bccomp($usdPerBdt, '0', 8) <= 0) {
            throw new RuntimeException('USD রেট পাওয়া যায়নি।');
        }

        $bdt = bcdiv($usd, $usdPerBdt, 6);
        $walletAmount = bcdiv($bdt, (string) $home['rate'], 2);

        if (bccomp($walletAmount, '0', 2) <= 0) {
            throw new RuntimeException('এই পরিমাণ রিচার্জ করা যাবে না, রূপান্তরের পর পরিমাণ খুব কম।');
        }

        return [$currency, $walletAmount, bcdiv($walletAmount, $usd, 6)];
    }
}