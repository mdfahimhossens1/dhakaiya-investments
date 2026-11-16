<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DepositRequest;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WithdrawRequest;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RuntimeException;

class WalletController extends Controller
{
    /**
     * Country to Currency Configuration
     *
     * Local currency is selected according to user's registration country.
     */
    private function getCountryCurrency(string $country): array
    {
        $currencies = [

            'BD' => ['code' => 'BDT', 'name' => 'Bangladeshi Taka', 'symbol' => '৳'],
            'IN' => ['code' => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹'],
            'US' => ['code' => 'USD', 'name' => 'United States Dollar', 'symbol' => '$'],
            'GB' => ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£'],
            'CA' => ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$'],
            'AU' => ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$'],
            'NZ' => ['code' => 'NZD', 'name' => 'New Zealand Dollar', 'symbol' => 'NZ$'],
            'SG' => ['code' => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => 'S$'],
            'MY' => ['code' => 'MYR', 'name' => 'Malaysian Ringgit', 'symbol' => 'RM'],
            'PK' => ['code' => 'PKR', 'name' => 'Pakistani Rupee', 'symbol' => '₨'],
            'LK' => ['code' => 'LKR', 'name' => 'Sri Lankan Rupee', 'symbol' => 'Rs'],
            'NP' => ['code' => 'NPR', 'name' => 'Nepalese Rupee', 'symbol' => 'रू'],

            'SA' => ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => '﷼'],
            'AE' => ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ'],
            'QA' => ['code' => 'QAR', 'name' => 'Qatari Riyal', 'symbol' => 'ر.ق'],
            'KW' => ['code' => 'KWD', 'name' => 'Kuwaiti Dinar', 'symbol' => 'د.ك'],
            'BH' => ['code' => 'BHD', 'name' => 'Bahraini Dinar', 'symbol' => '.د.ب'],
            'OM' => ['code' => 'OMR', 'name' => 'Omani Rial', 'symbol' => 'ر.ع.'],
            'JO' => ['code' => 'JOD', 'name' => 'Jordanian Dinar', 'symbol' => 'د.ا'],
            'EG' => ['code' => 'EGP', 'name' => 'Egyptian Pound', 'symbol' => 'E£'],

            'DE' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'FR' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'IT' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'ES' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'NL' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'BE' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'PT' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'IE' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'AT' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'GR' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],

            'CH' => ['code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF'],
            'SE' => ['code' => 'SEK', 'name' => 'Swedish Krona', 'symbol' => 'kr'],
            'NO' => ['code' => 'NOK', 'name' => 'Norwegian Krone', 'symbol' => 'kr'],
            'DK' => ['code' => 'DKK', 'name' => 'Danish Krone', 'symbol' => 'kr'],
            'PL' => ['code' => 'PLN', 'name' => 'Polish Zloty', 'symbol' => 'zł'],
            'CZ' => ['code' => 'CZK', 'name' => 'Czech Koruna', 'symbol' => 'Kč'],
            'TR' => ['code' => 'TRY', 'name' => 'Turkish Lira', 'symbol' => '₺'],
            'RU' => ['code' => 'RUB', 'name' => 'Russian Ruble', 'symbol' => '₽'],
            'UA' => ['code' => 'UAH', 'name' => 'Ukrainian Hryvnia', 'symbol' => '₴'],

            'JP' => ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥'],
            'CN' => ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥'],
            'HK' => ['code' => 'HKD', 'name' => 'Hong Kong Dollar', 'symbol' => 'HK$'],
            'KR' => ['code' => 'KRW', 'name' => 'South Korean Won', 'symbol' => '₩'],
            'TH' => ['code' => 'THB', 'name' => 'Thai Baht', 'symbol' => '฿'],
            'ID' => ['code' => 'IDR', 'name' => 'Indonesian Rupiah', 'symbol' => 'Rp'],
            'PH' => ['code' => 'PHP', 'name' => 'Philippine Peso', 'symbol' => '₱'],
            'VN' => ['code' => 'VND', 'name' => 'Vietnamese Dong', 'symbol' => '₫'],

            'BR' => ['code' => 'BRL', 'name' => 'Brazilian Real', 'symbol' => 'R$'],
            'MX' => ['code' => 'MXN', 'name' => 'Mexican Peso', 'symbol' => 'Mex$'],
            'ZA' => ['code' => 'ZAR', 'name' => 'South African Rand', 'symbol' => 'R'],
            'NG' => ['code' => 'NGN', 'name' => 'Nigerian Naira', 'symbol' => '₦'],
            'KE' => ['code' => 'KES', 'name' => 'Kenyan Shilling', 'symbol' => 'KSh'],
            'GH' => ['code' => 'GHS', 'name' => 'Ghanaian Cedi', 'symbol' => '₵'],
        ];

        return $currencies[$country] ?? [
            'code' => 'USD',
            'name' => 'United States Dollar',
            'symbol' => '$',
        ];
    }
    /* ================================================================== */
    /*  Deposit                                                           */
    /* ================================================================== */

    public function deposit(Request $request)
    {
        $user = auth()->user();

        $currencies = $this->walletCurrencies($user);
        $currency = $this->resolveCurrency($request, $currencies);

        return view('user.deposit.index', [
            'user' => $user,
            'currencies' => $currencies,
            'currency' => $currency,
            'methods' => $this->methodsFor($user, $currency, 'deposit'),   // PaymentMethod collection
            'minAmount' => $this->minAmount('deposit', $currency),
        ]);
    }

    public function storeDeposit(Request $request)
    {
        $user = $request->user();

        $currencies = $this->walletCurrencies($user);
        $currency = strtoupper((string) $request->input('currency', $currencies[0]));
        $methods = in_array($currency, $currencies, true)
            ? $this->methodsFor($user, $currency, 'deposit')
            : collect();
        $min = $this->minAmount('deposit', $currency);

        $request->merge([
            'transaction_id' => strtoupper(trim((string) $request->input('transaction_id'))),
            'sender_number' => preg_replace('/[^\d+]/', '', (string) $request->input('sender_number')),
        ]);

        $method = $request->input('method');

        $data = $request->validate([
            'currency' => ['required', Rule::in($currencies)],
            'method' => ['required', Rule::in($methods->pluck('code')->all())],
            'amount' => ['required', 'numeric', "min:$min", 'max:10000000', 'decimal:0,2'],
            'sender_number' => ['required', 'string', 'min:6', 'max:30'],
            'transaction_id' => [
                'required', 'string', 'min:5', 'max:60',
                Rule::unique('deposit_requests', 'transaction_id')
                    ->where(fn ($q) => $q->where('method', $method)),
            ],
            'screenshot' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ], [
            'transaction_id.unique' => 'এই Transaction ID আগেই জমা দেওয়া হয়েছে।',
        ]);

        $pending = $user->depositRequests()->where('status', 'pending')->count();

        if ($pending >= config('payment.deposit.max_pending', 5)) {
            return back()->withInput()->with(
                'error',
                'আপনার অনেকগুলো Deposit অনুরোধ অপেক্ষমাণ আছে। Admin যাচাই করা পর্যন্ত অপেক্ষা করুন।'
            );
        }

        $pm = $methods->firstWhere('code', $data['method']);

        $path = $request->hasFile('screenshot')
            ? $request->file('screenshot')->store('deposit-proofs', 'local')
            : null;

        try {
            DepositRequest::create([
                'user_id' => $user->id,
                'payment_method_id' => $pm->id,
                'method' => $pm->code,
                'currency' => $currency,
                'amount' => $data['amount'],
                'sender_number' => $data['sender_number'],
                'transaction_id' => $data['transaction_id'],
                'screenshot' => $path,
                'status' => 'pending',
            ]);
        } catch (\Throwable $e) {
            if ($path) {
                Storage::disk('local')->delete($path);
            }

            throw $e;
        }

        return redirect()
            ->route('user.deposit-history.index')
            ->with('success', 'Deposit অনুরোধ জমা হয়েছে। Admin যাচাই করলে ব্যালেন্স যোগ হবে।');
    }

    /* ================================================================== */
    /*  Withdraw                                                          */
    /* ================================================================== */

    public function withdraw(Request $request)
    {
        $user = auth()->user();

        $currencies = $this->walletCurrencies($user);
        $currency = $this->resolveCurrency($request, $currencies);

        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id, 'currency' => $currency],
            ['balance' => 0]
        );

        // এই কারেন্সির অ্যাকাউন্ট, যার method এখনো Active ও Withdraw চালু
        $withdrawalMethods = $user->paymentAccounts()
            ->with('paymentMethod')
            ->where('currency', $currency)
            ->latest()
            ->get()
            ->filter(fn ($a) => $a->paymentMethod
                && $a->paymentMethod->status === 'active'
                && $a->paymentMethod->withdraw_enabled)
            ->values();

        $withdrawalSummary = [
            'available_balance' => $wallet->balance,
            'minimum_withdrawal' => $this->minAmount('withdraw', $currency),
            'pending_withdrawals' => $user->withdrawRequests()
                ->where('currency', $currency)->where('status', 'pending')->sum('amount'),
            'completed_withdrawals' => $user->withdrawRequests()
                ->where('currency', $currency)->where('status', 'approved')->sum('net_amount'),
        ];

        return view('user.withdraw.index', [
            'user' => $user,
            'withdrawalSummary' => $withdrawalSummary,
            'withdrawalMethods' => $withdrawalMethods,   // প্রতিটি: ->paymentMethod->name, ->maskedDetails(), ->id
            'currencies' => $currencies,
            'currency' => $currency,
            'wallet' => $wallet,
        ]);
    }

    public function storeWithdraw(Request $request, WalletService $wallets)
    {
        $user = $request->user();

        $currencies = $this->walletCurrencies($user);
        $currency = strtoupper((string) $request->input('currency', $currencies[0]));
        $min = $this->minAmount('withdraw', $currency);

        $data = $request->validate([
            'currency' => ['required', Rule::in($currencies)],
            'payment_account_id' => [
                'required',
                Rule::exists('payment_accounts', 'id')->where('user_id', $user->id),
            ],
            'amount' => ['required', 'numeric', "min:$min", 'max:10000000', 'decimal:0,2'],
        ]);

        $account = $user->paymentAccounts()->with('paymentMethod')->findOrFail($data['payment_account_id']);
        $pm = $account->paymentMethod;

        if (! $pm || $pm->status !== 'active' || ! $pm->withdraw_enabled || $account->currency !== $currency) {
            return back()->withInput()->withErrors([
                'payment_account_id' => 'এই অ্যাকাউন্টে ' . $currency . ' Withdraw করা যাবে না।',
            ]);
        }

        $pending = $user->withdrawRequests()->where('status', 'pending')->count();

        if ($pending >= config('payment.withdraw.max_pending', 3)) {
            return back()->withInput()->with('error', 'আপনার অনেকগুলো Withdraw অনুরোধ অপেক্ষমাণ আছে।');
        }

        $amount = number_format((float) $data['amount'], 2, '.', '');
        $feePercent = (string) ($pm->withdraw_fee_percent ?? 0);
        $fee = bcdiv(bcmul($amount, $feePercent, 4), '100', 2);
        $net = bcsub($amount, $fee, 2);

        if (bccomp($net, '0', 2) <= 0) {
            return back()->withInput()->with('error', 'ফি কাটার পর কিছু অবশিষ্ট থাকে না।');
        }

        try {
            DB::transaction(function () use ($user, $account, $pm, $currency, $amount, $fee, $net, $wallets) {
                $withdraw = WithdrawRequest::create([
                    'user_id' => $user->id,
                    'payment_account_id' => $account->id,
                    'payment_method_id' => $pm->id,
                    'method' => $pm->code,
                    'account_name' => $account->account_name,
                    'account_number' => $account->account_number,
                    'bank_name' => $account->bank_name,
                    'branch_name' => $account->branch_name,
                    'details' => $account->details,
                    'currency' => $currency,
                    'amount' => $amount,
                    'fee' => $fee,
                    'net_amount' => $net,
                    'status' => 'pending',
                ]);

                $wallets->debit(
                    $user, 'withdraw', $amount, $withdraw,
                    'Withdraw request #' . $withdraw->id, [], $currency
                );
            });
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', 'Wallet-এ পর্যাপ্ত ব্যালেন্স নেই।');
        }

        return redirect()
            ->route('user.withdrawal-history.index')
            ->with('success', 'Withdraw অনুরোধ জমা হয়েছে। Admin টাকা পাঠালে Approved দেখাবে।');
    }

    /* ================================================================== */
    /*  Withdrawal Methods                                                */
    /* ================================================================== */

    public function withdrawalMethods()
    {
        $user = auth()->user();

        $accounts = $user->paymentAccounts()->with('paymentMethod')->latest()->get();
        $currencies = $this->walletCurrencies($user);
        $country = $user->countryCode();

        $icons = [
            'bank' => 'fa-solid fa-building-columns',
            'mobile' => 'fa-solid fa-mobile-screen-button',
            'crypto' => 'fa-brands fa-bitcoin',
            'other' => 'fa-solid fa-wallet',
        ];

        $withdrawalMethods = PaymentMethod::active()->ordered()->get()
            ->filter(fn ($m) => $m->withdraw_enabled && $m->availableFor($country, $currencies))
            ->map(function ($m) use ($accounts, $icons) {
                $mine = $accounts->where('payment_method_id', $m->id)->values();

                return [
                    'name' => $m->name,
                    'description' => $m->description ?: implode(', ', $m->supported_currencies ?? ['All currencies']),
                    'icon' => $icons[$m->type] ?? $icons['other'],
                    'emoji' => $m->icon,
                    'status' => $mine->isNotEmpty() ? 'Activated' : 'Not Activated',
                    'accounts' => $mine,
                ];
            })
            ->values()
            ->all();

        return view('user.withdrawal-methods.index', compact('user', 'withdrawalMethods', 'accounts'));
    }

    /* ================================================================== */
    /*  Helpers                                                           */
    /* ================================================================== */

    /** নিজের দেশের কারেন্সি + USD (index() এর নিয়মের সাথে এক) */
    private function walletCurrencies(User $user): array
    {
        $local = $this->getCountryCurrency(strtoupper($user->country ?? 'BD'))['code'];

        return $local === 'USD' ? ['USD'] : [$local, 'USD'];
    }

    private function resolveCurrency(Request $request, array $currencies): string
    {
        $currency = strtoupper((string) $request->query('currency', $currencies[0]));

        return in_array($currency, $currencies, true) ? $currency : $currencies[0];
    }

    /** DB থেকে: ইউজারের দেশ + কারেন্সিতে চলে এমন Active method */
    private function methodsFor(User $user, string $currency, string $for)
    {
        $country = $user->countryCode();

        return PaymentMethod::active()->ordered()->get()
            ->filter(fn ($m) => $m->availableInCountry($country)
                && $m->supportsCurrency($currency)
                && ($for === 'deposit' ? $m->deposit_enabled : $m->withdraw_enabled))
            ->values();
    }

    private function minAmount(string $kind, string $currency): float|int
    {
        return config("payment.$kind.min.$currency", config("payment.$kind.min.default", 1));
    }
    /**
     * Generate country flag emoji from ISO country code.
     */
    private function getCountryFlag(string $country): string
    {
        $country = strtoupper($country);

        if (!preg_match('/^[A-Z]{2}$/', $country)) {
            return '🌍';
        }

        $flag = '';

        foreach (str_split($country) as $character) {
            $flag .= mb_chr(
                127397 + ord($character),
                'UTF-8'
            );
        }

        return $flag;
    }

    /* ================================================================== */
    /*  Wallet Overview                                                   */
    /* ================================================================== */

    public function index()
    {
        $user = auth()->user();

        $country = strtoupper($user->country ?? 'BD');

        $localCurrency = $this->getCountryCurrency($country);

        $localWallet = [
            'name' => $localCurrency['code'] . ' Wallet',
            'currency' => $localCurrency['code'],
            'symbol' => $localCurrency['symbol'],
            'description' => $localCurrency['name'] . ' Wallet',
            'status' => 'Not Activated',
            'icon' => $this->getCountryFlag($country),
        ];

        $wallets = [$localWallet];

        // Add USD only when the local currency is not already USD.
        if ($localCurrency['code'] !== 'USD') {
            $wallets[] = [
                'name' => 'USD Wallet',
                'currency' => 'USD',
                'symbol' => '$',
                'description' => 'United States Dollar Wallet',
                'status' => 'Not Activated',
                'icon' => '🇺🇸',
            ];
        }

        // প্রতিটি Wallet-এ আসল ব্যালেন্স ও হিসাব যোগ করা
        foreach ($wallets as $i => $w) {
            $row = Wallet::firstOrCreate(
                ['user_id' => $user->id, 'currency' => $w['currency']],
                ['balance' => 0]
            );

            $cur = $w['currency'];

            $wallets[$i]['status'] = 'Active';
            $wallets[$i]['balance'] = $row->balance;
            $wallets[$i]['total_deposit'] = $user->walletTransactions()
                ->where('currency', $cur)->where('type', 'deposit')->sum('amount');
            $wallets[$i]['total_withdraw'] = $user->withdrawRequests()
                ->where('currency', $cur)->where('status', 'approved')->sum('net_amount');
            $wallets[$i]['pending_withdraw'] = $user->withdrawRequests()
                ->where('currency', $cur)->where('status', 'pending')->sum('amount');
            $wallets[$i]['total_income'] = $user->walletTransactions()
                ->where('currency', $cur)->income()->sum('amount');
        }

        $summary = [
            'total_wallets' => count($wallets),
            'active_wallets' => count($wallets),
            'total_deposits' => $user->depositRequests()->where('status', 'approved')->count(),
            'total_withdrawals' => $user->withdrawRequests()->where('status', 'approved')->count(),
            'total_transactions' => $user->walletTransactions()->count(),
        ];

        $recentTransactions = $user->walletTransactions()->latest()->limit(10)->get();

        return view('user.wallet.index', compact(
            'user',
            'wallets',
            'summary',
            'recentTransactions'
        ));
    }
}