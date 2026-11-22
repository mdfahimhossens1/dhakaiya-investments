<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AgentDeposit;
use App\Services\AgentBalanceService;
use App\Support\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AgentDepositController extends Controller
{
    public function index(Request $request)
    {
        $deposits = AgentDeposit::with(['agent.user:id,name,phone', 'reviewer:id,name'])
            ->when(
                in_array($request->status, ['pending', 'approved', 'rejected'], true),
                fn ($q) => $q->where('status', $request->status)
            )
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;

                $q->where(function ($q) use ($s) {
                    $q->where('transaction_id', 'like', "%{$s}%")
                        ->orWhereHas('agent.user', fn ($u) => $u
                            ->where('name', 'like', "%{$s}%")
                            ->orWhere('phone', 'like', "%{$s}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'pending_count' => AgentDeposit::pending()->count(),
            'pending_total' => (string) AgentDeposit::pending()->sum('amount'),
            'approved_today' => (string) AgentDeposit::where('status', 'approved')
                ->whereDate('reviewed_at', today())->sum('approved_amount'),
            'rejected_count' => AgentDeposit::where('status', 'rejected')->count(),
        ];

        return view('super-admin.agent-deposits.index', compact('deposits', 'stats'));
    }

    public function approve(Request $request, AgentDeposit $agentDeposit, AgentBalanceService $balances)
    {
        $data = $request->validate([
            // অনুরোধের চেয়ে বেশি approve করা যাবে না (টাইপো আটকাতে)
            'approved_amount' => ['nullable', 'numeric', 'gt:0', 'max:' . $agentDeposit->amount, 'decimal:0,2'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::transaction(function () use ($agentDeposit, $data, $balances) {
                $deposit = AgentDeposit::whereKey($agentDeposit->id)->lockForUpdate()->firstOrFail();

                if ($deposit->status !== 'pending') {
                    throw new RuntimeException('এই অনুরোধ আগেই প্রসেস করা হয়েছে।');
                }

                $amount = number_format((float) ($data['approved_amount'] ?? $deposit->amount), 2, '.', '');

                $deposit->update([
                    'status' => 'approved',
                    'approved_amount' => $amount,
                    'admin_note' => $data['admin_note'] ?? null,
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                ]);

                $balances->creditDeposit($deposit, $amount);
            });
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $agentDeposit->refresh()->load('agent.user');

        if ($agentDeposit->agent?->user) {
            Notify::user(
                $agentDeposit->agent->user,
                'Deposit Approved',
                'Your deposit of $' . number_format($agentDeposit->approved_amount, 2)
                    . ' has been approved and added to your recharge balance.',
                'success'
            );
        }

        return back()->with('success', 'Deposit approved এবং এজেন্টের Recharge Balance-এ যোগ হয়েছে।');
    }

    public function reject(Request $request, AgentDeposit $agentDeposit)
    {
        $data = $request->validate([
            'admin_note' => ['required', 'string', 'max:500'],
        ]);

        $updated = AgentDeposit::whereKey($agentDeposit->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'admin_note' => $data['admin_note'],
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'updated_at' => now(),
            ]);

        if (! $updated) {
            return back()->with('error', 'এই অনুরোধ আগেই প্রসেস করা হয়েছে।');
        }

        $agentDeposit->refresh()->load('agent.user');

        if ($agentDeposit->agent?->user) {
            Notify::user(
                $agentDeposit->agent->user,
                'Deposit Rejected',
                'Your deposit of $' . number_format($agentDeposit->amount, 2)
                    . ' was rejected. Reason: ' . $data['admin_note'],
                'danger'
            );
        }

        return back()->with('success', 'Deposit rejected.');
    }
}