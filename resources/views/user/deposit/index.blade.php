@extends('layouts.app')

@section('title', 'Deposit')

@section('content')

@include('user.wallet._styles')

@php $sym = \App\Support\Currency::symbol($currency); @endphp

<div class="wl-page">

    <div class="wl-hero">
        <div>
            <h2>📥 Deposit</h2>
            <p>Send money to our account, then submit the transaction details. Your wallet is credited after verification.</p>
        </div>
        <div class="wl-hero-actions">
            <a href="{{ route('user.deposit-history.index') }}">Deposit History</a>
            <a href="{{ route('user.wallet.index') }}" class="alt">My Wallet</a>
        </div>
    </div>

    @include('user.wallet._flash')

    @if(count($currencies) > 1)
        <div class="wl-tabs">
            @foreach($currencies as $c)
                <a href="{{ route('user.deposit', ['currency' => $c]) }}" class="{{ $c === $currency ? 'on' : '' }}">{{ $c }} Wallet</a>
            @endforeach
        </div>
    @endif

    @if($methods->isEmpty())
        <div class="wl-card">
            <div class="wl-empty">
                <div class="ic"><i class="fas fa-building-columns"></i></div>
                <h4>No deposit method for {{ $currency }}</h4>
                <p>No payment method is available for your country and this wallet yet. Please check back later or contact support.</p>
            </div>
        </div>
    @else

    <style>
        .dp-steps{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px}
        .dp-step{background:var(--white);border:1px solid var(--border);border-radius:11px;padding:14px;display:flex;gap:11px;align-items:center;font-size:12px;color:var(--text)}
        .dp-step b{min-width:28px;height:28px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px}
        .dp-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start}
        .dp-methods{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px}
        .dp-m{position:relative;display:block;cursor:pointer}
        .dp-m input{position:absolute;opacity:0}
        .dp-m span{display:flex;align-items:center;gap:8px;padding:12px;border:2px solid var(--border);border-radius:10px;font-size:13px;font-weight:700;color:var(--text);background:#fff;transition:.15s}
        .dp-m input:checked + span{border-color:var(--primary);background:#effaf5}
        .dp-send{margin-top:16px;padding:16px;background:#effaf5;border:1px solid #b7dfcf;border-radius:11px}
        .dp-send small{display:block;font-size:11px;color:#065f46;margin-bottom:6px;font-weight:700;letter-spacing:.4px}
        .dp-acc{font-size:16px;font-weight:700;color:#065f46;white-space:pre-wrap;overflow-wrap:anywhere;margin:0;font-family:inherit}
        .dp-copy{margin-top:10px;border:0;background:#087f5b;color:#fff;padding:7px 13px;border-radius:7px;font-size:11px;font-weight:700;cursor:pointer}
        .dp-ins{margin-top:12px;font-size:12px;line-height:1.7;color:#475569;white-space:pre-line}
        .dp-warn{margin-top:14px;padding:11px 13px;background:#fffbeb;border:1px solid #fde68a;border-radius:9px;font-size:11px;color:#78716c;line-height:1.6}
        @media(max-width:850px){.dp-grid{grid-template-columns:1fr}.dp-steps{grid-template-columns:1fr}}
    </style>

    <div class="dp-steps">
        <div class="dp-step"><b>1</b> Choose a method and copy the account</div>
        <div class="dp-step"><b>2</b> Send the money from your app/bank</div>
        <div class="dp-step"><b>3</b> Submit the Transaction ID below</div>
    </div>

    <form method="POST" action="{{ route('user.deposit.store') }}" enctype="multipart/form-data" class="wl-form">
        @csrf
        <input type="hidden" name="currency" value="{{ $currency }}">

        <div class="dp-grid">

            {{-- LEFT: method + where to send --}}
            <div class="wl-card">
                <div class="wl-card-h"><div><h3>1. Payment Method</h3><p>Choose how you will pay.</p></div></div>

                <div class="dp-methods">
                    @foreach($methods as $m)
                        <label class="dp-m">
                            <input type="radio" name="method" value="{{ $m->code }}"
                                   @checked(old('method', $methods->first()->code) === $m->code)
                                   onchange="dpShow('{{ $m->code }}')">
                            <span>{{ $m->icon ?: '💳' }} {{ $m->name }}</span>
                        </label>
                    @endforeach
                </div>

                @foreach($methods as $m)
                    <div class="dp-info" id="info-{{ $m->code }}" style="display:none">
                        <div class="dp-send">
                            <small>SEND {{ $currency }} TO</small>
                            <pre class="dp-acc" id="acc-{{ $m->code }}">{{ $m->deposit_account ?: 'Account not set. Please contact support.' }}</pre>
                            @if($m->deposit_account)
                                <button type="button" class="dp-copy" onclick="dpCopy('{{ $m->code }}', this)">Copy</button>
                            @endif
                        </div>
                        @if($m->deposit_instructions)
                            <div class="dp-ins">{{ $m->deposit_instructions }}</div>
                        @endif
                    </div>
                @endforeach

                <div class="dp-warn">
                    ⚠ Send only to the account shown above. Keep your payment receipt. Never share your PIN or OTP with anyone.
                </div>
            </div>

            {{-- RIGHT: transaction details --}}
            <div class="wl-card">
                <div class="wl-card-h"><div><h3>2. Transaction Details</h3><p>Fill in after you have sent the money.</p></div></div>

                <label>Amount ({{ $currency }}) — minimum {{ $sym }} {{ number_format((float) $minAmount, 2) }}</label>
                <input type="number" step="0.01" min="{{ $minAmount }}" name="amount" value="{{ old('amount') }}" required>

                <label>Your sender number / account</label>
                <input type="text" name="sender_number" value="{{ old('sender_number') }}" placeholder="The number/account you paid from" required>

                <label>Transaction ID</label>
                <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" placeholder="e.g. 9A7B3C2D1E" required>

                <label>Payment screenshot (optional)</label>
                <input type="file" name="screenshot" accept="image/*">

                <button type="submit" class="wl-btn primary">Submit Deposit Request</button>
            </div>

        </div>
    </form>

    <script>
    function dpShow(code) {
        document.querySelectorAll('.dp-info').forEach(function (e) { e.style.display = 'none'; });
        var el = document.getElementById('info-' + code);
        if (el) el.style.display = 'block';
    }
    function dpCopy(code, btn) {
        var t = document.getElementById('acc-' + code).textContent;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(t).then(function () {
                btn.textContent = 'Copied ✓';
                setTimeout(function () { btn.textContent = 'Copy'; }, 1500);
            });
        }
    }
    (function () {
        var c = document.querySelector('input[name=method]:checked');
        if (c) dpShow(c.value);
    })();
    </script>

    @endif

    <div class="wl-notice">
        <i class="fas fa-info-circle"></i>
        <div>
            <h4>How deposits work</h4>
            <p>Our team checks your Transaction ID against the received payment. After approval the amount appears in your {{ $currency }} wallet. Duplicate Transaction IDs are rejected automatically.</p>
        </div>
    </div>

</div>

@endsection