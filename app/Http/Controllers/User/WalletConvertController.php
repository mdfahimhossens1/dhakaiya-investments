<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Services\ShareService;
use App\Services\WalletService;
use App\Support\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RuntimeException;

class WalletConvertController extends Controller
{
    private function currencies($user): array
    {
        $local = $user->walletCurrency();

        return $local === 'USD' ? ['USD'] : [$local, 'USD'];
    }

    public function create(Request $request, ShareService $shares)
    {
        $user = $request->user();
        $currencies = $this->currencies($user);

        if (count($currencies) < 2) {
            return redirect()->route('user.wallet.index')
                ->with('error', 'আপনার একটাই Wallet আছে (USD), তাই কনভার্ট করার দরকার নেই।');
        }

        $wallets = collect($currencies)->mapWithKeys(fn ($c) => [
            $c => Wallet::firstOrCreate(['user_id' => $user->id, 'currency' => $c], ['balance' => 0, 'withdrawable_balance' => 0]),
        ]);

        [$a, $b] = $currencies;

        return view('user.wallet.convert', [
            'user' => $user,
            'wallets' => $wallets,
            'currencies' => $currencies,
            'rates' => [$a => $shares->rate($a), $b => $shares->rate($b)],
            'feePercent' => (string) config('exchange.convert_fee_percent', 0),
            'min' => config('exchange.convert_min', 1),
            'rateReady' => (bool) ($shares->rate($a) && $shares->rate($b)),
        ]);
    }

    public function store(Request $request, ShareService $shares, WalletService $wallets)
    {
        $user = $request->user();
        $currencies = $this->currencies($user);

        $data = $request->validate([
            'from' => ['required', Rule::in($currencies)],
            'to' => ['required', Rule::in($currencies), 'different:from'],
            'bucket' => ['required', Rule::in(['earnings', 'locked'])],
            'amount' => ['required', 'numeric', 'min:' . config('exchange.convert_min', 1), 'max:10000000', 'decimal:0,2'],
        ]);

        $amount = number_format((float) $data['amount'], 2, '.', '');
        $gross = $shares->convertAmount($amount, $data['from'], $data['to']);

        if ($gross === null) {
            return back()->withInput()->with('error', 'এই কারেন্সির exchange rate এখনো সেট করা হয়নি। Admin-এর সাথে যোগাযোগ করুন।');
        }

        $fee = bcdiv(bcmul($gross, (string) config('exchange.convert_fee_percent', 0), 4), '100', 2);
        $received = bcsub($gross, $fee, 2);

        if (bccomp($received, '0', 2) <= 0) {
            return back()->withInput()->with('error', 'এই পরিমাণ কনভার্ট করা যাবে না, অঙ্ক খুব কম।');
        }

        $rateText = $data['from'] . '→' . $data['to'];

        try {
            DB::transaction(function () use ($user, $data, $amount, $received, $fee, $wallets, $rateText) {
                // দুই Wallet একই ক্রমে lock (deadlock এড়াতে)
                Wallet::where('user_id', $user->id)->whereIn('currency', [$data['from'], $data['to']])
                    ->orderBy('id')->lockForUpdate()->get();

                $meta = ['bucket' => $data['bucket'], 'fee' => $fee, 'pair' => $rateText];

                $wallets->debit($user, 'convert_out', $amount, null,
                    "Converted to {$data['to']}", $meta, $data['from']);

                $wallets->credit($user, 'convert_in', $received, null,
                    "Converted from {$data['from']}", $meta, $data['to']);
            });
        } catch (RuntimeException $e) {
            $msg = $data['bucket'] === 'earnings'
                ? 'আপনার আয়ের (Withdrawable) ব্যালেন্স যথেষ্ট নেই।'
                : 'আপনার Locked ব্যালেন্স যথেষ্ট নেই।';

            return back()->withInput()->with('error', $msg);
        }

        return redirect()->route('user.wallet.index')->with(
            'success',
            "{$amount} {$data['from']} কনভার্ট হয়ে {$received} {$data['to']} আপনার Wallet-এ যোগ হয়েছে।"
        );
    }
}