<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\WithdrawRequest;
use App\Services\WalletService;
use App\Support\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WithdrawRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = WithdrawRequest::with(['user', 'reviewer', 'paymentMethod'])
            ->when(
                in_array($request->status, ['pending', 'approved', 'rejected'], true),
                fn ($q) => $q->where('status', $request->status)
            )
            ->when($request->filled('currency'), fn ($q) => $q->where('currency', strtoupper($request->currency)))
            ->when($request->filled('method'), fn ($q) => $q->where('method', $request->method))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;

                $q->where(function ($q) use ($s) {
                    $q->where('account_number', 'like', "%{$s}%")
                        ->orWhereHas('user', fn ($u) => $u
                            ->where('name', 'like', "%{$s}%")
                            ->orWhere('phone', 'like', "%{$s}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pendingCount = WithdrawRequest::pending()->count();

        $pendingByCurrency = WithdrawRequest::pending()
            ->selectRaw('currency, SUM(net_amount) as total')
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $paidTodayByCurrency = WithdrawRequest::where('status', 'approved')
            ->whereDate('reviewed_at', today())
            ->selectRaw('currency, SUM(net_amount) as total')
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $rejectedCount = WithdrawRequest::where('status', 'rejected')->count();

        $methods = PaymentMethod::orderBy('name')->get(['code', 'name']);
        $currencies = WithdrawRequest::select('currency')->distinct()->orderBy('currency')->pluck('currency');

        return view('super-admin.withdraw-requests.index', compact(
            'requests',
            'pendingCount',
            'pendingByCurrency',
            'paidTodayByCurrency',
            'rejectedCount',
            'methods',
            'currencies'
        ));
    }

    /** Admin ম্যানুয়ালি টাকা পাঠানোর পর Approve (Wallet আগেই কাটা, তাই ব্যালেন্স বদলায় না) */
   public function approve(Request $request, WithdrawRequest $withdrawRequest)
{
    $data = $request->validate([
        'payout_reference' => ['required', 'string', 'max:100'],
        'admin_note' => ['nullable', 'string', 'max:500'],
    ]);

    $updated = WithdrawRequest::whereKey($withdrawRequest->id)
        ->where('status', 'pending')
        ->update([
            'status' => 'approved',
            'payout_reference' => $data['payout_reference'],
            'admin_note' => $data['admin_note'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'updated_at' => now(),
        ]);

    if (! $updated) {
        return back()->with('error', 'এই অনুরোধ আগেই প্রসেস করা হয়েছে।');
    }

    $withdrawRequest->refresh();

    Notify::user(
        $withdrawRequest->user,
        'Withdraw Approved',
        'Your withdrawal of ' . Notify::money($withdrawRequest->net_amount, $withdrawRequest->currency)
            . ' has been paid. Reference: ' . $data['payout_reference'],
        'success'
    );

    return back()->with('success', 'Withdraw approved (Paid)।');
}

public function reject(Request $request, WithdrawRequest $withdrawRequest, WalletService $wallets)
{
    $data = $request->validate([
        'admin_note' => ['required', 'string', 'max:500'],
    ]);

    try {
        DB::transaction(function () use ($withdrawRequest, $data, $wallets) {
            $withdraw = WithdrawRequest::whereKey($withdrawRequest->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($withdraw->status !== 'pending') {
                throw new RuntimeException('এই অনুরোধ আগেই প্রসেস করা হয়েছে।');
            }

            $withdraw->update([
                'status' => 'rejected',
                'admin_note' => $data['admin_note'],
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            $wallets->credit(
                $withdraw->user,
                'withdraw_refund',
                $withdraw->amount,
                $withdraw,
                'Withdraw #' . $withdraw->id . ' rejected, refunded',
                [],
                $withdraw->currency
            );
        });
    } catch (RuntimeException $e) {
        return back()->with('error', $e->getMessage());
    }

    $withdrawRequest->refresh();

    Notify::user(
        $withdrawRequest->user,
        'Withdraw Rejected',
        'Your withdrawal of ' . Notify::money($withdrawRequest->amount, $withdrawRequest->currency)
            . ' was rejected and refunded to your wallet. Reason: ' . $data['admin_note'],
        'danger'
    );

    return back()->with('success', 'Withdraw rejected এবং টাকা ইউজারের Wallet-এ ফেরত গেছে।');
}
}