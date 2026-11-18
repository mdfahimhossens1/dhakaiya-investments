<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CurrencyRate;
use App\Support\Currency;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CurrencyRateController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request)
    {
        $q = mb_strtolower(trim((string) $request->query('q')));
        $saved = CurrencyRate::get()->keyBy('currency');

        $all = $this->rows()->map(function ($r) use ($saved) {
            $row = $saved->get($r['code']);
            $r['rate'] = $row ? rtrim(rtrim((string) $row->rate_to_bdt, '0'), '.') : null;
            $r['updated_at'] = $row?->updated_at;

            return $r;
        });

        $rows = $q === '' ? $all : $all->filter(fn ($r) => str_contains(
            mb_strtolower($r['code'] . ' ' . $r['name'] . ' ' . implode(' ', $r['countries'])), $q
        ))->values();

        $page = LengthAwarePaginator::resolveCurrentPage();

        $rates = new LengthAwarePaginator(
            $rows->forPage($page, self::PER_PAGE)->values(),
            $rows->count(),
            self::PER_PAGE,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $stats = [
            'total' => $all->count(),
            'set' => $all->whereNotNull('rate')->count(),
            'missing' => $all->whereNull('rate')->count(),
        ];

        return view('super-admin.currency-rates.index', [
            'rates' => $rates,
            'stats' => $stats,
            'q' => $request->query('q'),
            'updated' => CurrencyRate::max('updated_at'),
        ]);
    }

    public function update(Request $request)
    {
        $codes = $this->rows()->pluck('code')->all();

        $data = $request->validate([
            'rates' => ['required', 'array'],
            'rates.*' => ['nullable', 'numeric', 'gt:0', 'max:100000'],
        ]);

        foreach ($data['rates'] as $code => $rate) {
            $code = strtoupper((string) $code);

            if (! in_array($code, $codes, true)) {
                continue;
            }

            if ($rate === null || $rate === '') {
                CurrencyRate::where('currency', $code)->delete();   // খালি = ওই কারেন্সি বন্ধ
            } else {
                CurrencyRate::updateOrCreate(
                    ['currency' => $code],
                    ['rate_to_bdt' => $rate, 'updated_by' => auth()->id()]
                );
            }
        }

        CurrencyRate::forget();

        return back()->with('success', 'Exchange rates saved. They are now live in the user panel.');
    }

    /** কারেন্সি ধরে সারি; কোন কোন দেশ ওই কারেন্সি ব্যবহার করে সেটাও */
    private function rows(): Collection
    {
        $by = [];

        foreach (Currency::map() as $cc => $info) {
            if ($info['code'] === 'BDT') {
                continue;
            }

            $by[$info['code']] ??= [
                'code' => $info['code'],
                'name' => $info['name'],
                'symbol' => $info['symbol'],
                'flag' => $this->flag($cc),
                'countries' => [],
            ];

            $by[$info['code']]['countries'][] = $this->countryName($cc);
        }

        return collect($by)->sortBy(fn ($r) => $r['code'] === 'USD' ? '0' : $r['code'])->values();
    }

    private function countryName(string $cc): string
    {
        return class_exists(\Locale::class) ? (\Locale::getDisplayRegion('-' . $cc, 'en') ?: $cc) : $cc;
    }

    private function flag(string $cc): string
    {
        $flag = '';

        foreach (str_split(strtoupper($cc)) as $ch) {
            $flag .= mb_chr(127397 + ord($ch), 'UTF-8');
        }

        return $flag;
    }
}