<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DepositRequest;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DepositRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = DepositRequest::with(['user', 'reviewer'])
            ->when(
                in_array($request->status, ['pending', 'approved', 'rejected'], true),
                fn ($q) => $q->where('status', $request->status)
            )
            ->when($request->filled('currency'), fn ($q) => $q->where('currency', strtoupper($request->currency)))
            ->when($request->filled('method'), fn ($q) => $q->where('method', $request->method))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;

                $q->where(function ($q) use ($s) {
                    $q->where('transaction_id', 'like', "%{$s}%")
                        ->orWhere('sender_number', 'like', "%{$s}%")
                        ->orWhereHas('user', fn ($u) => $u
                            ->where('name', 'like', "%{$s}%")
                            ->orWhere('phone', 'like', "%{$s}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // কারেন্সি মিশিয়ে যোগ করা অর্থহীন, তাই কারেন্সি-ভিত্তিক
        $pendingCount = DepositRequest::pending()->count();

        $pendingByCurrency = DepositRequest::pending()
            ->selectRaw('currency, SUM(amount) as total')
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $approvedTodayByCurrency = DepositRequest::where('status', 'approved')
            ->whereDate('reviewed_at', today())
            ->selectRaw('currency, SUM(approved_amount) as total')
            ->groupBy('currency')
            ->pluck('total', 'currency');

        $rejectedCount = DepositRequest::where('status', 'rejected')->count();

        return view('super-admin.deposit-requests.index', compact(
            'requests',
            'pendingCount',
            'pendingByCurrency',
            'approvedTodayByCurrency',
            'rejectedCount'
        ));
    }

    public function approve(Request $request, DepositRequest $depositRequest, WalletService $wallets)
    {
        $data = $request->validate([
            'approved_amount' => ['nullable', 'numeric', 'gt:0', 'max:10000000', 'decimal:0,2'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::transaction(function () use ($depositRequest, $data, $wallets) {
                // ডবল approve ঠেকাতে row lock
                $deposit = DepositRequest::whereKey($depositRequest->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($deposit->status !== 'pending') {
                    throw new RuntimeException('এই অনুরোধ আগেই প্রসেস করা হয়েছে।');
                }

                $amount = $data['approved_amount'] ?? $deposit->amount;

                $deposit->update([
                    'status' => 'approved',
                    'approved_amount' => $amount,
                    'admin_note' => $data['admin_note'] ?? null,
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                ]);

                $wallets->credit(
                    $deposit->user,
                    'deposit',
                    $amount,
                    $deposit,
                    'Deposit via ' . strtoupper($deposit->method) . ' (TrxID: ' . $deposit->transaction_id . ')',
                    [],
                    $deposit->currency
                );
            });
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Deposit approved এবং Wallet-এ ব্যালেন্স যোগ হয়েছে।');
    }

    public function reject(Request $request, DepositRequest $depositRequest)
    {
        $data = $request->validate([
            'admin_note' => ['required', 'string', 'max:500'],
        ]);

        $updated = DepositRequest::whereKey($depositRequest->id)
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

        return back()->with('success', 'Deposit rejected.');
    }

    /** private স্ক্রিনশট দেখা (শুধু Super Admin) */
    public function screenshot(DepositRequest $depositRequest)
    {
        abort_unless(
            $depositRequest->screenshot
                && Storage::disk('local')->exists($depositRequest->screenshot),
            404
        );

        return Storage::disk('local')->response($depositRequest->screenshot);
    }
}