<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DepositRequest;
use App\Models\PaymentAccount;
use App\Models\PaymentMethod;
use App\Models\WithdrawRequest;
use App\Support\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $methods = PaymentMethod::withCount('accounts')
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(fn ($w) => $w->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%"));
            })
            ->when(in_array($request->status, ['active', 'inactive'], true), fn ($q) => $q->where('status', $request->status))
            ->when(array_key_exists((string) $request->type, PaymentMethod::TYPES), fn ($q) => $q->where('type', $request->type))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => PaymentMethod::count(),
            'active' => PaymentMethod::where('status', 'active')->count(),
            'inactive' => PaymentMethod::where('status', 'inactive')->count(),
            'accounts' => PaymentAccount::count(),
        ];

        return view('super-admin.payment-methods.index', [
            'methods' => $methods,
            'stats' => $stats,
            'types' => PaymentMethod::TYPES,
            'countryOptions' => $this->countryOptions(),
            'currencyOptions' => $this->currencyCodes(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->prepare($request);
        $data['code'] = $this->uniqueCode($data['name']);

        PaymentMethod::create($data);

        return redirect()->route('super-admin.payment-methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        // code বদলানো যাবে না, কারণ পুরোনো Deposit/Withdraw রেকর্ডে এটা জমা আছে
        $paymentMethod->update($this->prepare($request));

        return redirect()->route('super-admin.payment-methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function toggle(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'status' => $paymentMethod->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $used = $paymentMethod->accounts()->exists()
            || DepositRequest::where('payment_method_id', $paymentMethod->id)->exists()
            || WithdrawRequest::where('payment_method_id', $paymentMethod->id)->exists();

        if ($used) {
            return back()->with('error', 'এই method ব্যবহার হয়েছে, তাই মোছা যাবে না। বদলে Inactive করে দিন।');
        }

        $paymentMethod->delete();

        return back()->with('success', 'Payment method deleted.');
    }

    /* ------------------------------------------------------------------ */

    private function prepare(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(array_keys(PaymentMethod::TYPES))],
            'icon' => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string', 'max:500'],
            'countries' => ['nullable', 'array'],
            'countries.*' => [Rule::in(array_keys(Currency::map()))],
            'supported_currencies' => ['nullable', 'array'],
            'supported_currencies.*' => [Rule::in($this->currencyCodes())],
            'deposit_account' => ['nullable', 'string', 'max:1000'],
            'deposit_instructions' => ['nullable', 'string', 'max:2000'],
            'withdraw_fee_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['countries'] = $this->clean($data['countries'] ?? []);
        $data['supported_currencies'] = $this->clean($data['supported_currencies'] ?? []);
        $data['deposit_enabled'] = $request->boolean('deposit_enabled');
        $data['withdraw_enabled'] = $request->boolean('withdraw_enabled');
        $data['withdraw_fee_percent'] = $data['withdraw_fee_percent'] ?? 0;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    private function clean(array $values): ?array
    {
        $values = array_values(array_unique(array_filter($values)));

        return $values ?: null;
    }

    private function uniqueCode(string $name): string
    {
        $base = Str::slug($name) ?: 'method';
        $code = $base;
        $i = 1;

        while (PaymentMethod::where('code', $code)->exists()) {
            $code = $base . '-' . $i++;
        }

        return $code;
    }

    private function currencyCodes(): array
    {
        return collect(Currency::map())->pluck('code')->push('USD')->unique()->sort()->values()->all();
    }

    private function countryOptions(): array
    {
        return collect(array_keys(Currency::map()))->mapWithKeys(function ($code) {
            $name = class_exists(\Locale::class) ? \Locale::getDisplayRegion('-' . $code, 'en') : $code;

            return [$code => $name . ' (' . $code . ')'];
        })->sort()->all();
    }
}