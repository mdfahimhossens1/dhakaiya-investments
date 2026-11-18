@extends('layouts.app')

@section('title', 'Convert Currency')

@section('content')

@include('user.wallet._styles')

@php [$a, $b] = $currencies; @endphp

<div class="wl-page">

    <div class="wl-head">
        <div><h2>Convert Currency</h2><p>Move money between your {{ $a }} and {{ $b }} wallets at the platform rate.</p></div>
        <div class="wl-crumb"><a href="{{ route('user.wallet.index') }}">My Wallet</a><span>/</span><strong>Convert</strong></div>
    </div>

    @include('user.wallet._flash')

    @if(! $rateReady)
        <div class="wl-flash err">Exchange rate is not available yet. Please try again later.</div>
    @else

    <div class="wl-stats">
        @foreach($wallets as $cur => $w)
            @php $sym = \App\Support\Currency::symbol($cur); @endphp
            <div class="wl-stat"><div class="ic"><i class="fas fa-wallet"></i></div>
                <div><p>{{ $cur }} Wallet</p><h3>{{ $sym }} {{ number_format((float) $w->balance, 2) }}</h3>
                    <small>Earnings {{ $sym }} {{ number_format((float) $w->withdrawable_balance, 2) }} · Locked {{ $sym }} {{ number_format((float) $w->principal_balance, 2) }}</small></div></div>
        @endforeach
        <div class="wl-stat"><div class="ic blue"><i class="fas fa-percent"></i></div>
            <div><p>Rate</p><h3>1 {{ $b }} = {{ rtrim(rtrim(bcdiv($rates[$b], $rates[$a], 6), '0'), '.') }} {{ $a }}</h3><small>Fee {{ $feePercent }}%</small></div></div>
    </div>

    <div class="wl-card">
        <form method="POST" action="{{ route('user.wallet.convert.store') }}" class="wl-form" style="max-width:520px">
            @csrf
            <label>From</label>
            <select name="from" id="cvFrom" onchange="cvSync()">
                @foreach($currencies as $c)<option value="{{ $c }}" @selected(old('from', $a) === $c)>{{ $c }} Wallet</option>@endforeach
            </select>

            <label>To</label>
            <select name="to" id="cvTo" onchange="cvSync(true)">
                @foreach($currencies as $c)<option value="{{ $c }}" @selected(old('to', $b) === $c)>{{ $c }} Wallet</option>@endforeach
            </select>

            <label>Money type to convert</label>
            <select name="bucket">
                <option value="earnings" @selected(old('bucket') === 'earnings')>Earnings (withdrawable) — stays withdrawable</option>
                <option value="locked" @selected(old('bucket') === 'locked')>Locked (deposit / resale) — stays locked</option>
            </select>

            <label>Amount</label>
            <input type="number" step="0.01" min="{{ $min }}" name="amount" id="cvAmt" value="{{ old('amount') }}" required oninput="cvCalc()">

            <div class="wl-preview" id="cvPrev"></div>
            <button type="submit" class="wl-btn primary">Convert</button>
        </form>
    </div>

    <script>
    var RATE = @json($rates), FEE = {{ (float) $feePercent }};
    function cvSync(fromTo) {
        var f = document.getElementById('cvFrom'), t = document.getElementById('cvTo');
        if (f.value === t.value) { if (fromTo) { f.value = f.value === '{{ $a }}' ? '{{ $b }}' : '{{ $a }}'; } else { t.value = t.value === '{{ $a }}' ? '{{ $b }}' : '{{ $a }}'; } }
        cvCalc();
    }
    function cvCalc() {
        var v = parseFloat(document.getElementById('cvAmt').value), box = document.getElementById('cvPrev');
        var f = document.getElementById('cvFrom').value, t = document.getElementById('cvTo').value;
        if (!v || v <= 0) { box.style.display = 'none'; return; }
        var gross = Math.floor(v * RATE[f] / RATE[t] * 100) / 100, fee = gross * FEE / 100;
        box.style.display = 'block';
        box.innerHTML = 'You will receive about <strong>' + (gross - fee).toFixed(2) + ' ' + t + '</strong> (fee ' + fee.toFixed(2) + ')';
    }
    cvSync();
    </script>
    @endif
</div>

@endsection