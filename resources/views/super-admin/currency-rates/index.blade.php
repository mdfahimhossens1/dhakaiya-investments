@extends('layouts.app')

@section('title', 'Exchange Rates')

@section('content')

@include('user.investment._styles')

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Exchange Rates</h2>
            <p>For each country's currency, enter how many <strong>BDT</strong> equal 1 unit (example: 1 USD = 122.50 BDT). Saved rates go live in the user panel immediately.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-coins"></i> {{ $updated ? 'Updated ' . \Illuminate\Support\Carbon::parse($updated)->diffForHumans() : 'No rate set yet' }}</span>
    </div>

    @if(session('success'))<div class="inv-panel" style="padding:13px 16px;background:#eaf8f0;color:#137547">✓ {{ session('success') }}</div>@endif
    @if($errors->any())<div class="inv-panel" style="padding:13px 16px;background:#fff0ef;color:#b42318">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif

    <div class="inv-summary">
        <div class="inv-stat"><div class="label">Currencies</div><h3>{{ $stats['total'] }}</h3></div>
        <div class="inv-stat"><div class="label">Rate set</div><h3 class="pl-pos">{{ $stats['set'] }}</h3></div>
        <div class="inv-stat"><div class="label">Not set (disabled)</div><h3 class="pl-neg">{{ $stats['missing'] }}</h3></div>
    </div>

    <div class="inv-panel">
        <form method="GET" action="{{ route('super-admin.currency-rates.index') }}" class="inv-filter">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search country or currency (e.g. India, USD)"
                   style="min-width:260px;padding:10px 13px;border:1px solid var(--border);border-radius:8px;font-size:13px">
            <button class="inv-btn primary">Search</button>
            <a href="{{ route('super-admin.currency-rates.index') }}" class="inv-btn outline">Reset</a>
        </form>

        <form method="POST" action="{{ route('super-admin.currency-rates.update') }}">
            @csrf @method('PUT')

            <div class="inv-table-wrap">
                <table class="inv-table" style="min-width:780px">
                    <thead><tr><th>Country</th><th>Currency</th><th>1 unit = ? BDT</th><th>Status</th><th>Updated</th></tr></thead>
                    <tbody>
                    @forelse($rates as $r)
                        <tr>
                            <td>
                                <span style="font-size:20px;margin-right:6px">{{ $r['flag'] }}</span>
                                {{ implode(', ', array_slice($r['countries'], 0, 3)) }}
                                @if(count($r['countries']) > 3)<small>+{{ count($r['countries']) - 3 }} more</small>@endif
                            </td>
                            <td><strong>{{ $r['code'] }}</strong><small>{{ $r['name'] }} ({{ $r['symbol'] }})</small></td>
                            <td>
                                <input type="number" step="0.000001" min="0" name="rates[{{ $r['code'] }}]" value="{{ old('rates.' . $r['code'], $r['rate']) }}"
                                       placeholder="e.g. 122.50"
                                       style="width:170px;padding:9px 11px;border:1px solid var(--border);border-radius:8px">
                            </td>
                            <td>
                                @if($r['rate'])
                                    <span style="background:#e7f6ef;color:#087f5b;padding:4px 10px;border-radius:15px;font-size:11px;font-weight:700">Active</span>
                                @else
                                    <span style="background:#feecec;color:#dc4545;padding:4px 10px;border-radius:15px;font-size:11px;font-weight:700">Not set</span>
                                @endif
                            </td>
                            <td>{{ $r['updated_at'] ? $r['updated_at']->format('d M Y, h:i A') : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="inv-empty"><p style="margin:0">No currency matches your search.</p></div></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding:16px 20px;display:flex;gap:12px;align-items:center;flex-wrap:wrap">
                <button type="submit" class="inv-btn primary">Save Rates (this page)</button>
                <small style="color:var(--muted)">Save before moving to another page. Leaving a box blank disables that currency.</small>
            </div>
        </form>

        @if($rates->hasPages())<div class="inv-pagination">{{ $rates->links() }}</div>@endif
    </div>

    <div class="inv-panel" style="padding:16px;font-size:12px;line-height:1.7">
        Rates are used for share purchase and resale in other currencies, daily profit and commission credit, wallet conversion and the USD equivalent shown in wallets. A user whose currency has no rate cannot buy shares and receives no profit until the rate is set. Update rates regularly from a reliable market source.
    </div>
</div>

@endsection