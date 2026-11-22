<?php

namespace App\Http\Controllers\Agent;

use App\Models\ShareHolding;
use App\Models\User;
use App\Services\AgentBalanceService;
use App\Support\Notify;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;

class RechargeController extends BaseAgentController
{
    /** Public User Search + Recharge ফর্ম (একই পেজ) */
    public function form(Request $request)
    {
        $agent = $this->agent();

        $searched = false;
        $found = null;
        $shares = '0';

        if ($request->filled('user_id')) {
            $request->validate(['user_id' => ['required', 'integer', 'min:1']]);

            $searched = true;

            // শুধু সাধারণ (public) active ইউজার; এজেন্ট নিজে নয়
            $found = User::where('id', $request->integer('user_id'))
                ->where('role', 'user')
                ->where('status', 'active')
                ->where('id', '!=', $agent->user_id)
                ->first(['id', 'name']);

            if ($found) {
                $shares = (string) ShareHolding::where('user_id', $found->id)->sum('shares');
            }
        }

        return view('agent.recharge.form', [
            'agent' => $agent,
            'searched' => $searched,
            'found' => $found,
            'shares' => $shares,
            'token' => (string) Str::uuid(),
        ]);
    }

    public function store(Request $request, AgentBalanceService $balances)
    {
        $agent = $this->agent();

        $data = $request->validate([
            'user_id' => ['required', 'integer', 'min:1'],
            'amount' => ['required', 'numeric', 'gt:0', 'max:100000', 'decimal:0,2'],
            'token' => ['required', 'uuid'],
        ]);

        $user = User::where('id', $data['user_id'])
            ->where('role', 'user')
            ->where('status', 'active')
            ->first();

        if (! $user) {
            return back()->with('error', 'ইউজার পাওয়া যায়নি।');
        }

        try {
            $recharge = $balances->recharge($agent, $user, (string) $data['amount'], $data['token']);
        } catch (UniqueConstraintViolationException) {
            return redirect()->route('agent.recharge.history')
                ->with('success', 'এই রিচার্জ আগেই সম্পন্ন হয়েছে।');
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        // Transaction commit হওয়ার পর notification
        Notify::user(
            $user,
            'Wallet Recharged',
            'Your wallet has been recharged with '
                . Notify::money($recharge->wallet_amount, $recharge->wallet_currency)
                . ' by an authorized agent.',
            'success'
        );

        return redirect()->route('agent.recharge.history')
            ->with('success', "রিচার্জ সফল: {$user->name} (#{$user->id}) — \${$recharge->amount}");
    }

    public function history(Request $request)
    {
        $agent = $this->agent();

        $request->validate([
            'user_id' => ['nullable', 'integer'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $base = fn () => $agent->recharges()
            ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', $request->integer('user_id')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->query('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->query('to')));

        $recharges = $base()->with('user:id,name')->latest()->paginate(20)->withQueryString();

        $summary = [
            'count' => $base()->where('status', 'completed')->count(),
            'total' => (string) $base()->where('status', 'completed')->sum('amount'),
        ];

        return view('agent.recharge.history', compact('agent', 'recharges', 'summary'));
    }
}