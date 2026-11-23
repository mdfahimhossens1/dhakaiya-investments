<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\AgentDeposit;
use App\Models\AgentRecharge;
use App\Models\AgentTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AgentBalanceService
{
    public function __construct(private WalletService $wallets)
    {
    }

    /** Approve হওয়া ডিপোজিট: ব্যালেন্সের পুরো মূল্য এজেন্টের Recharge Balance-এ যোগ (caller-এর transaction-এর ভেতরে) */
    public function creditDeposit(AgentDeposit $deposit, string $amount): AgentTransaction
    {
        $note = 'Balance purchase via ' . strtoupper($deposit->method) . ' (TrxID: ' . $deposit->transaction_id . ')';

        if ((float) $deposit->discount_percent > 0) {
            $note .= ' · ' . ((float) $deposit->discount_percent + 0) . '% discount';
        }

        return $this->apply($deposit->agent_id, 'credit', 'deposit', $amount, $deposit, $note);
    }

    /**
     * ইউজারকে রিচার্জ: এজেন্টের ব্যালেন্স কমে, ইউজারের Wallet-এ যোগ হয়। সবই এক transaction-এ।
     * এজেন্ট ও ইউজারের কারেন্সি একই হতে হবে, তাই কোনো কনভার্সন নেই।
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

        return DB::transaction(function () use ($agent, $user, $amount, $token) {
            $locked = Agent::with('user')->whereKey($agent->id)->lockForUpdate()->firstOrFail();

            if (! $locked->isOperational()) {
                throw new RuntimeException('আপনার এজেন্ট অ্যাকাউন্ট এখন সক্রিয় নয়।');
            }

            $currency = $this->currencyOf($locked);
            $userCurrency = strtoupper($user->walletCurrency());

            if ($userCurrency !== $currency) {
                throw new RuntimeException(
                    "এই ইউজারের Wallet কারেন্সি {$userCurrency}, কিন্তু আপনার Recharge Balance {$currency}-এ। শুধু একই কারেন্সির ইউজারকে রিচার্জ করা যায়।"
                );
            }

            // token unique, তাই ডুপ্লিকেট হলে এখানেই exception হয়ে সব rollback
            $recharge = AgentRecharge::create([
                'agent_id' => $agent->id,
                'user_id' => $user->id,
                'amount' => $amount,
                'wallet_currency' => $currency,
                'exchange_rate' => '1.000000',
                'wallet_amount' => $amount,
                'status' => 'completed',
                'request_token' => $token,
            ]);

            // ১) এজেন্টের Recharge Balance থেকে কাটা (কম থাকলে exception)
            $this->apply($agent->id, 'debit', 'recharge', $amount, $recharge, "Recharge to user #{$user->id}");

            // ২) ইউজারের Wallet-এ যোগ (WalletService দিয়ে, Principal অংশে যায়)
            $this->wallets->credit(
                $user,
                'agent_recharge',
                $amount,
                $recharge,
                'Wallet recharge by agent',
                ['agent_id' => $agent->id],
                $currency
            );

            return $recharge;
        });
    }

    /* ------------------------------------------------------------------ */

    private function currencyOf(Agent $agent): string
    {
        return strtoupper($agent->recharge_currency ?: $agent->user->walletCurrency());
    }

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
            $agent = Agent::with('user')->whereKey($agentId)->lockForUpdate()->firstOrFail();

            $current = (string) $agent->recharge_balance;

            if ($direction === 'debit') {
                if (bccomp($current, $amount, 2) < 0) {
                    throw new RuntimeException('আপনার Recharge Balance যথেষ্ট নয়।');
                }

                $new = bcsub($current, $amount, 2);
            } else {
                $new = bcadd($current, $amount, 2);
            }

            // কারেন্সি প্রথমবার ব্যালেন্স বদলানোর সময় স্থায়ীভাবে ঠিক হয়ে যায়
            $agent->forceFill([
                'recharge_balance' => $new,
                'recharge_currency' => $this->currencyOf($agent),
            ])->save();

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
}