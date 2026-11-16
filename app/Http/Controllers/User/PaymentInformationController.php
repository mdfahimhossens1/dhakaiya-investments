<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentInformationController extends Controller
{
    private const MAX_ACCOUNTS = 10;

    public function index()
    {
        $user = auth()->user();
        $country = $user->countryCode();
        $walletCurrencies = $user->walletCurrencies();

        // শুধু এই ইউজারের দেশ ও কারেন্সিতে চলে এমন Active method
        $methods = PaymentMethod::active()->ordered()->get()
            ->filter(fn (PaymentMethod $m) => $m->availableFor($country, $walletCurrencies))
            ->values();

        $accounts = $user->paymentAccounts()
            ->with('paymentMethod')
            ->latest()
            ->get()
            ->groupBy('payment_method_id');

        $paymentMethods = $methods->map(function (PaymentMethod $m) use ($accounts, $walletCurrencies) {
            $list = $accounts->get($m->id, collect());
            $first = $list->first();

            return [
                'id' => $m->id,
                'name' => $m->name,
                'description' => $m->description,
                'icon' => $m->icon,
                'type' => $m->type,
                'currencies' => $m->currenciesFor($walletCurrencies),
                'fields' => $m->fieldDefinitions(),
                'can_deposit' => $m->deposit_enabled,
                'can_withdraw' => $m->withdraw_enabled,
                'status' => $list->isNotEmpty() ? 'Configured' : 'Not Added',
                'is_configured' => $list->isNotEmpty(),
                'account' => $first ? [
                    'currency' => $first->currency,
                    'masked' => $first->maskedDetails(),
                    'verified' => $first->is_verified,
                ] : null,
                'accounts' => $list->map(fn ($a) => [
                    'id' => $a->id,
                    'currency' => $a->currency,
                    'masked' => $a->maskedDetails(),
                    'verified' => $a->is_verified,
                    'is_default' => $a->is_default,
                ])->values()->all(),
            ];
        });

        $configuredCount = $paymentMethods->where('is_configured', true)->count();
        $totalCount = $paymentMethods->count();

        return view('user.profile.payment-information', compact(
            'user', 'paymentMethods', 'configuredCount', 'totalCount'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate(['payment_method_id' => ['required', 'integer']]);

        $method = PaymentMethod::active()->find($request->payment_method_id);

        if (! $method || ! $method->availableFor($user->countryCode(), $user->walletCurrencies())) {
            return back()->with('error', 'এই Payment method আপনার দেশে বা কারেন্সিতে চালু নেই।');
        }

        $allowed = $method->currenciesFor($user->walletCurrencies());

        $rules = ['currency' => ['required', Rule::in($allowed)]];

        foreach ($method->fieldDefinitions() as $f) {
            $rules[$f['key']] = [
                $f['required'] ? 'required' : 'nullable',
                'string',
                'max:' . ($f['key'] === 'account_number' ? 100 : 150),
            ];
        }

        $data = $request->validate($rules);

        if ($user->paymentAccounts()->count() >= self::MAX_ACCOUNTS) {
            return back()->with('error', 'সর্বোচ্চ ' . self::MAX_ACCOUNTS . 'টি অ্যাকাউন্ট যোগ করা যাবে।');
        }

        $duplicate = $user->paymentAccounts()
            ->where('payment_method_id', $method->id)
            ->where('currency', $data['currency'])
            ->where('account_number', trim($data['account_number']))
            ->exists();

        if ($duplicate) {
            return back()->withInput()->with('error', 'এই অ্যাকাউন্ট আগেই যোগ করা আছে।');
        }

        $columns = ['account_name', 'account_number', 'bank_name', 'branch_name'];
        $row = [];
        $extra = [];

        foreach ($method->fieldDefinitions() as $f) {
            $value = isset($data[$f['key']]) ? trim($data[$f['key']]) : null;

            if (in_array($f['key'], $columns, true)) {
                $row[$f['key']] = $value ?: null;
            } elseif ($value) {
                $extra[$f['key']] = $value;
            }
        }

        $hasDefault = $user->paymentAccounts()
            ->where('currency', $data['currency'])->where('is_default', true)->exists();

        $user->paymentAccounts()->create($row + [
            'payment_method_id' => $method->id,
            'method' => $method->code,
            'currency' => $data['currency'],
            'account_name' => $row['account_name'] ?? $user->name,
            'details' => $extra ?: null,
            'is_default' => ! $hasDefault,
            'is_verified' => false,
        ]);

        return back()->with('success', $method->name . ' অ্যাকাউন্ট যোগ হয়েছে।');
    }

    public function destroy(PaymentAccount $paymentAccount)
    {
        abort_unless($paymentAccount->user_id === auth()->id(), 403);

        $wasDefault = $paymentAccount->is_default;
        $currency = $paymentAccount->currency;

        $paymentAccount->delete();

        if ($wasDefault) {
            auth()->user()->paymentAccounts()
                ->where('currency', $currency)->oldest()->first()?->update(['is_default' => true]);
        }

        return back()->with('success', 'অ্যাকাউন্ট মুছে ফেলা হয়েছে।');
    }
}