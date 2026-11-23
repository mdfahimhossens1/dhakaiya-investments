@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')

@include('user.wallet._styles')

<style>
    .mk-filter { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; margin-bottom: 20px; }
    .mk-filter label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; color: var(--muted); }
    .mk-filter select { padding: 10px 12px; border: 1px solid var(--border); border-radius: 9px; background: #fff; min-width: 180px; }
    .mk-btn { border: none; background: var(--primary); color: #fff; padding: 10px 18px; border-radius: 9px; font-weight: 700; cursor: pointer; font-size: 13px; }
    .mk-btn:disabled { opacity: .45; cursor: not-allowed; }
    .mk-btn.ghost { background: #fff; color: var(--primary); border: 1px solid var(--border); }

    .mk-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 18px; }

    .mk-card {
        background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 20px;
        display: flex; flex-direction: column; gap: 16px; transition: box-shadow .2s, transform .2s;
    }
    .mk-card:hover { box-shadow: 0 10px 30px rgba(15, 23, 42, .08); transform: translateY(-2px); }

    .mk-top { display: flex; align-items: center; gap: 12px; }
    .mk-avatar {
        width: 44px; height: 44px; flex-shrink: 0; border-radius: 12px;
        background: #e9f7f1; color: var(--primary);
        display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 18px;
    }
    .mk-top h4 { font-size: 15px; margin: 0 0 3px; color: var(--text); }
    .mk-top span { font-size: 12px; color: var(--muted); }

    .mk-price { padding: 14px; border-radius: 12px; background: #f8fafc; border: 1px solid var(--border); }
    .mk-price small { display: block; font-size: 11px; letter-spacing: .5px; color: var(--muted); text-transform: uppercase; }
    .mk-price strong { display: block; font-size: 26px; color: var(--text); margin: 4px 0 8px; }
    .mk-tag { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; }
    .mk-tag.good { background: #dcfce7; color: #166534; }
    .mk-tag.warn { background: #fef3c7; color: #92400e; }
    .mk-tag.same { background: #e2e8f0; color: #475569; }

    .mk-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .mk-meta span { display: block; font-size: 11px; color: var(--muted); margin-bottom: 3px; }
    .mk-meta strong { font-size: 13px; color: var(--text); }

    .mk-bar { height: 7px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
    .mk-bar i { display: block; height: 100%; background: linear-gradient(90deg, var(--primary), #34d399); }
    .mk-left { font-size: 12px; color: var(--muted); margin-top: 6px; }

    .mk-qty { display: flex; align-items: center; gap: 6px; }
    .mk-qty button {
        width: 38px; height: 38px; border: 1px solid var(--border); background: #fff; border-radius: 9px;
        font-size: 18px; cursor: pointer; color: var(--text);
    }
    .mk-qty input {
        flex: 1; min-width: 0; height: 38px; text-align: center; border: 1px solid var(--border);
        border-radius: 9px; font-size: 15px; font-weight: 700; color: var(--text);
    }
    .mk-qty .max { width: auto; padding: 0 12px; font-size: 12px; font-weight: 700; color: var(--primary); }

    .mk-pay { display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: var(--muted); }
    .mk-pay strong { font-size: 17px; color: var(--text); }
    .mk-warn { font-size: 12px; font-weight: 600; color: #b91c1c; min-height: 16px; }
    .mk-buy { width: 100%; padding: 13px; font-size: 14px; }
    .mk-own { text-align: center; padding: 12px; border-radius: 10px; background: #f1f5f9; color: var(--muted); font-size: 13px; font-weight: 600; }
</style>

<div class="wl-page">

    <div class="wl-head">
        <div>
            <h2>Marketplace</h2>
            <p>Buy shares that other members have posted for sale.</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('user.resell.index') }}" class="mk-btn ghost" style="text-decoration:none;"><i class="fas fa-bullhorn"></i> Resell Share</a>
            <a href="{{ route('user.resell.trades') }}" class="mk-btn ghost" style="text-decoration:none;"><i class="fas fa-handshake"></i> My Trades</a>
        </div>
    </div>

    @include('user.wallet._flash')

    <div class="wl-notice" style="margin-bottom:18px;">
        <i class="fas fa-wallet"></i>
        <div>
            <p>Your wallet: <strong>{{ $symbol }} {{ number_format((float) $balance, 2) }}</strong>. Shares are bought instantly from your wallet balance. <a href="{{ route('user.deposit') }}">Deposit money</a></p>
        </div>
    </div>

    <form method="GET" class="mk-filter">
        <div>
            <label>Project</label>
            <select name="project">
                <option value="">All projects</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" @selected($projectId === (int) $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Sort by</label>
            <select name="sort">
                <option value="new" @selected($sort === 'new')>Newest</option>
                <option value="low" @selected($sort === 'low')>Price: Low to High</option>
                <option value="high" @selected($sort === 'high')>Price: High to Low</option>
            </select>
        </div>
        <button class="mk-btn">Filter</button>
    </form>

    @if($listings->count())
        <div class="mk-grid">
            @foreach($listings as $l)
                @php
                    $mine = $l->seller_id === $user->id;
                    $unit = $l->unit_price_local !== null ? (float) $l->unit_price_local : null;
                    $comp = $l->company_price_local !== null ? (float) $l->company_price_local : null;
                    $diff = ($unit !== null && $comp !== null && $comp > 0) ? (($unit - $comp) / $comp) * 100 : null;
                    $leftPct = $l->shares > 0 ? round(($l->remaining_shares / $l->shares) * 100) : 0;
                @endphp

                <div class="mk-card"
                     data-buy
                     data-unit-bdt="{{ $l->unit_price_bdt }}"
                     data-rate="{{ $rate }}"
                     data-symbol="{{ $symbol }}"
                     data-balance="{{ $balance }}">

                    <div class="mk-top">
                        <div class="mk-avatar">{{ strtoupper(mb_substr($l->project?->name ?? 'S', 0, 1)) }}</div>
                        <div>
                            <h4>{{ $l->project?->name ?? 'Project' }}</h4>
                            <span>{{ $l->project?->company?->name ?? '—' }}</span>
                        </div>
                    </div>

                    <div class="mk-price">
                        <small>Price per share</small>
                        <strong>{{ $unit !== null ? $symbol . ' ' . number_format($unit, 2) : '—' }}</strong>
                        @if($diff === null)
                            <span class="mk-tag same">Company price not available</span>
                        @elseif($diff < -0.5)
                            <span class="mk-tag good">{{ number_format(abs($diff), 1) }}% below company price</span>
                        @elseif($diff > 0.5)
                            <span class="mk-tag warn">{{ number_format($diff, 1) }}% above company price</span>
                        @else
                            <span class="mk-tag same">Same as company price</span>
                        @endif
                    </div>

                    <div class="mk-meta">
                        <div><span>Company price</span><strong>{{ $comp !== null ? $symbol . ' ' . number_format($comp, 2) : '—' }}</strong></div>
                        <div><span>Seller</span><strong>{{ \Illuminate\Support\Str::mask($l->seller?->name ?? 'Member', '*', 2) }}</strong></div>
                        <div><span>Posted</span><strong>{{ $l->created_at->diffForHumans() }}</strong></div>
                        <div><span>Available</span><strong>{{ number_format($l->remaining_shares) }} shares</strong></div>
                    </div>

                    <div>
                        <div class="mk-bar"><i style="width: {{ $leftPct }}%;"></i></div>
                        <div class="mk-left">{{ number_format($l->remaining_shares) }} of {{ number_format($l->shares) }} shares left</div>
                    </div>

                    @if($mine)
                        <div class="mk-own"><i class="fas fa-user-check"></i> This is your post</div>
                    @elseif($unit === null)
                        <div class="mk-own">Exchange rate not set, cannot buy right now</div>
                    @else
                        <form method="POST" action="{{ route('user.marketplace.buy', $l) }}" style="display:flex;flex-direction:column;gap:12px;">
                            @csrf

                            <div class="mk-qty">
                                <button type="button" data-step="-1" aria-label="Decrease">−</button>
                                <input type="number" name="shares" min="1" max="{{ $l->remaining_shares }}" value="1" step="1" inputmode="numeric" required>
                                <button type="button" data-step="1" aria-label="Increase">+</button>
                                <button type="button" class="max" data-max>Max</button>
                            </div>

                            <div class="mk-pay"><span>You pay</span><strong data-total>—</strong></div>
                            <div class="mk-warn" data-warn></div>

                            <button class="mk-btn mk-buy" data-submit><i class="fas fa-shopping-cart"></i> Buy Now</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        @if($listings->hasPages())
            <div style="margin-top:22px;">{{ $listings->links() }}</div>
        @endif
    @else
        <div class="wl-card">
            <div class="wl-empty">
                <div class="ic"><i class="fas fa-store"></i></div>
                <h4>No shares for sale right now</h4>
                <p>Check back later, or post your own shares from Resell Share.</p>
            </div>
        </div>
    @endif

</div>

<script>
    document.querySelectorAll('[data-buy]').forEach(function (card) {
        const qty = card.querySelector('input[name=shares]');
        if (!qty) return;

        const out = card.querySelector('[data-total]');
        const warn = card.querySelector('[data-warn]');
        const btn = card.querySelector('[data-submit]');

        const unit = parseFloat(card.dataset.unitBdt);
        const rate = parseFloat(card.dataset.rate);
        const sym = card.dataset.symbol;
        const balance = parseFloat(card.dataset.balance) || 0;
        const max = parseInt(qty.max, 10) || 1;

        function update() {
            const q = parseInt(qty.value, 10) || 0;
            let message = '';
            let ok = true;

            if (q < 1) {
                ok = false;
                out.textContent = '—';
            } else if (q > max) {
                ok = false;
                out.textContent = '—';
                message = 'Only ' + max + ' share(s) available.';
            } else {
                // সার্ভারের মতোই উপরে রাউন্ড
                const pay = Math.ceil((unit * q / rate) * 100 - 1e-9) / 100;
                out.textContent = sym + ' ' + pay.toFixed(2);

                if (pay > balance) {
                    ok = false;
                    message = 'Not enough wallet balance.';
                }
            }

            warn.textContent = message;
            btn.disabled = !ok;
        }

        card.querySelectorAll('[data-step]').forEach(function (b) {
            b.addEventListener('click', function () {
                const next = (parseInt(qty.value, 10) || 0) + parseInt(b.dataset.step, 10);
                qty.value = Math.min(max, Math.max(1, next));
                update();
            });
        });

        card.querySelector('[data-max]').addEventListener('click', function () {
            qty.value = max;
            update();
        });

        qty.addEventListener('input', update);
        update();
    });
</script>

@endsection