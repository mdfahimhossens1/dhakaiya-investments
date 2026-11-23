@extends('layouts.app')

@section('title', 'Resell Share')

@section('content')

@include('user.wallet._styles')

<style>
    .mk-form { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    .mk-field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 7px; color: var(--text); }
    .mk-field input, .mk-field select {
        width: 100%; padding: 12px 14px; border: 1px solid var(--border); border-radius: 10px;
        background: #fff; font-size: 14px; color: var(--text);
    }
    .mk-field input[readonly] { background: #f8fafc; font-weight: 700; }
    .mk-field small { display: block; margin-top: 6px; font-size: 12px; color: var(--muted); }
    .mk-field .mk-error { color: #b91c1c; font-weight: 600; min-height: 16px; }
    .mk-btn { border: none; background: var(--primary); color: #fff; padding: 12px 24px; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 14px; }
    .mk-btn:disabled { opacity: .5; cursor: not-allowed; }
    .mk-btn.sm { padding: 7px 14px; font-size: 12px; }
    .mk-btn.ghost { background: #fff; color: var(--primary); border: 1px solid var(--border); }
    @media (max-width: 700px) { .mk-form { grid-template-columns: 1fr; } }
</style>

@php
    $statusClass = ['active' => 'approved', 'sold' => 'approved', 'cancelled' => 'rejected'];
@endphp

<div class="wl-page">

    <div class="wl-head">
        <div>
            <h2>Resell Share</h2>
            <p>Post your shares for sale to other members at a price you choose.</p>
        </div>
        <div class="wl-crumb">
            <a href="{{ route('user.marketplace.index') }}">Marketplace</a><span>/</span><strong>Resell Share</strong>
        </div>
    </div>

    @include('user.wallet._flash')

    <div class="wl-stats">
        <div class="wl-stat">
            <div class="ic"><i class="fas fa-store"></i></div>
            <div><p>Active Posts</p><h3>{{ $summary['active_listings'] }}</h3><small>Visible in marketplace</small></div>
        </div>
        <div class="wl-stat">
            <div class="ic blue"><i class="fas fa-layer-group"></i></div>
            <div><p>Shares Listed</p><h3>{{ number_format($summary['listed_shares']) }}</h3><small>Waiting for buyers</small></div>
        </div>
        <div class="wl-stat">
            <div class="ic orange"><i class="fas fa-handshake"></i></div>
            <div><p>Trades Sold</p><h3>{{ $summary['trades_sold'] }}</h3><small>Completed sales</small></div>
        </div>
        <div class="wl-stat">
            <div class="ic purple"><i class="fas fa-coins"></i></div>
            <div><p>Total Received</p><h3>{{ $symbol }} {{ number_format((float) $summary['total_received'], 2) }}</h3><small>After commission</small></div>
        </div>
    </div>

    {{-- Post for Sale --}}
    <div class="wl-card">
        <div class="wl-card-h">
            <div><h3>Post for Sale</h3><p>Prices are in {{ $currency }}. Commission: {{ (float) $feePercent + 0 }}% is deducted from the amount you receive.</p></div>
        </div>

        @if($holdings->isEmpty())
            <div class="wl-empty">
                <div class="ic"><i class="fas fa-box-open"></i></div>
                <h4>No shares available to resell</h4>
                <p>You either have no shares, or all of them are locked for your position or already posted for sale. Check <a href="{{ route('user.my-shares.index') }}">My Shares</a>.</p>
            </div>
        @else
            @if($errors->any())
                <div class="wl-notice" style="margin-bottom:14px;"><i class="fas fa-exclamation-circle"></i><div><p>{{ $errors->first() }}</p></div></div>
            @endif

            {{-- novalidate: ব্রাউজারের নিজস্ব মেসেজের বদলে নিচের পরিষ্কার মেসেজ দেখানো হয়; সার্ভারে যাচাই আগের মতোই আছে --}}
            <form method="POST" action="{{ route('user.resell.store') }}" id="resellForm" novalidate>
                @csrf
                <div class="mk-form">
                    <div class="mk-field">
                        <label>Project</label>
                        <select name="project_id" id="rsProject" required>
                            @foreach($holdings as $h)
                                <option value="{{ $h->project_id }}"
                                        data-sellable="{{ $h->sellable }}"
                                        data-total="{{ $h->shares }}"
                                        data-locked="{{ $h->locked_shares }}"
                                        data-listed="{{ (int) $h->listed_shares }}"
                                        data-company="{{ $h->company_price_local }}"
                                        @selected((int) old('project_id', $selectedProject) === (int) $h->project_id)>
                                    {{ $h->project->name }}
                                </option>
                            @endforeach
                        </select>
                        <small id="rsCompany"></small>
                    </div>

                    <div class="mk-field">
                        <label>Available Shares</label>
                        <input type="text" id="rsAvail" readonly>
                        <small id="rsBreakdown"></small>
                    </div>

                    <div class="mk-field">
                        <label>Selling Shares</label>
                        <input type="number" name="shares" id="rsQty" step="1" inputmode="numeric" value="{{ old('shares') }}" placeholder="e.g. 40" required>
                        <small class="mk-error" id="rsQtyError"></small>
                    </div>

                    <div class="mk-field">
                        <label>Selling Rate ({{ $symbol }} per share)</label>
                        <input type="number" name="price" id="rsPrice" step="0.01" inputmode="decimal" value="{{ old('price') }}" placeholder="e.g. 1500" required>
                        <small>You can set any price, higher or lower than the company price.</small>
                    </div>

                    <div class="mk-field">
                        <label>Total Amount</label>
                        <input type="text" id="rsTotal" readonly>
                    </div>

                    <div class="mk-field">
                        <label>You Receive (after {{ (float) $feePercent + 0 }}% commission)</label>
                        <input type="text" id="rsNet" readonly>
                    </div>
                </div>

                <div style="margin-top:18px;"><button class="mk-btn" id="rsSubmit"><i class="fas fa-bullhorn"></i> Post for Sale</button></div>
            </form>
        @endif
    </div>

    {{-- My posts --}}
    <div class="wl-card">
        <div class="wl-card-h">
            <div><h3>My Posts</h3><p>Posts you made on the marketplace.</p></div>
            <a href="{{ route('user.resell.trades') }}" class="wl-pill">Trade History</a>
        </div>

        <div class="wl-table-wrap">
            <table class="wl-table">
                <thead>
                    <tr><th>Date</th><th>Project</th><th>Posted</th><th>Remaining</th><th>Rate</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($myListings as $l)
                        <tr>
                            <td>{{ $l->created_at->format('d M Y') }}<small>{{ $l->created_at->format('h:i A') }}</small></td>
                            <td>{{ $l->project?->name ?? '—' }}</td>
                            <td>{{ number_format($l->shares) }}</td>
                            <td>{{ number_format($l->remaining_shares) }}</td>
                            <td>{{ \App\Support\Currency::symbol($l->list_currency) }} {{ number_format((float) $l->list_unit_price, 2) }}</td>
                            <td><span class="wl-badge {{ $statusClass[$l->status] ?? '' }}">{{ ucfirst($l->status) }}</span></td>
                            <td>
                                @if($l->status === 'active')
                                    <form method="POST" action="{{ route('user.resell.cancel', $l) }}" onsubmit="return confirm('Cancel this post?');">
                                        @csrf @method('DELETE')
                                        <button class="mk-btn ghost sm">Cancel</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">
                            <div class="wl-empty">
                                <div class="ic"><i class="fas fa-store"></i></div>
                                <h4>No posts yet</h4>
                                <p>Post some of your shares to see them here.</p>
                            </div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($myListings->hasPages())
            <div style="margin-top:16px;">{{ $myListings->links() }}</div>
        @endif
    </div>

</div>

<script>
    (function () {
        const sel = document.getElementById('rsProject');
        if (!sel) return;

        const avail = document.getElementById('rsAvail');
        const breakdown = document.getElementById('rsBreakdown');
        const qty = document.getElementById('rsQty');
        const qtyError = document.getElementById('rsQtyError');
        const price = document.getElementById('rsPrice');
        const total = document.getElementById('rsTotal');
        const net = document.getElementById('rsNet');
        const company = document.getElementById('rsCompany');
        const submit = document.getElementById('rsSubmit');
        const sym = @json($symbol);
        const FEE = {{ (float) $feePercent }};

        const fmt = n => sym + ' ' + n.toFixed(2);

        function update() {
            const o = sel.options[sel.selectedIndex];
            const a = o ? parseInt(o.dataset.sellable || '0', 10) : 0;

            avail.value = a;
            breakdown.textContent = o
                ? 'You own ' + o.dataset.total + ' · locked ' + o.dataset.locked + ' · already posted ' + o.dataset.listed
                : '';

            company.textContent = o && o.dataset.company
                ? 'Company price: ' + sym + ' ' + o.dataset.company + ' per share'
                : '';

            const q = parseInt(qty.value, 10) || 0;
            const p = parseFloat(price.value) || 0;

            let error = '';
            if (qty.value !== '' && q < 1) {
                error = 'Selling shares must be at least 1.';
            } else if (q > a) {
                error = 'You can sell at most ' + a + ' share(s) from this project.';
            }
            qtyError.textContent = error;

            const t = q * p;
            total.value = fmt(t);
            net.value = fmt(t - t * FEE / 100);

            submit.disabled = error !== '' || q < 1 || p <= 0;
        }

        [sel, qty, price].forEach(e => e.addEventListener('input', update));
        update();
    })();
</script>

@endsection