@extends('layouts.app')

@section('title', 'Project Details')

@section('content')

@include('partials.styles')

<style>
    .pd-grid { display:grid; grid-template-columns: 1fr 360px; gap:20px; align-items:start; }
    .pd-banner { min-height:190px; background:linear-gradient(135deg,#102b26,#087f5b); color:#fff; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:30px 20px; position:relative; }
    .pd-banner.has-image { padding:0; min-height:0; height:230px; }
    .pd-banner img { width:100%; height:100%; object-fit:cover; display:block; }
    .pd-banner i { font-size:42px; margin-bottom:14px; opacity:.9; }
    .pd-banner h3 { font-size:21px; font-weight:700; margin:0 0 6px; }
    .pd-banner p { font-size:12px; margin:0; opacity:.8; }
    .pd-content { padding:25px; }
    .pd-content h4 { color:var(--text); font-size:16px; font-weight:700; margin:20px 0 10px; }
    .pd-content p.desc { color:var(--muted); font-size:13px; line-height:1.8; white-space:pre-line; margin:0; }
    .pd-info { display:grid; grid-template-columns:repeat(2,1fr); gap:14px; margin-top:20px; }
    .pd-info-item { border:1px solid var(--border); border-radius:9px; padding:14px; background:#fafcfc; }
    .pd-info-item span { display:block; color:var(--muted); font-size:11px; margin-bottom:7px; }
    .pd-info-item strong { color:var(--text); font-size:13px; font-weight:600; }
    .pd-progress { height:8px; background:#e2e8f0; border-radius:20px; overflow:hidden; margin-top:8px; }
    .pd-progress div { height:100%; background:var(--primary); }

    .buy-total { background:#f8fafb; border:1px solid var(--border); border-radius:8px; padding:12px 14px; font-size:12px; color:var(--muted); margin-bottom:16px; }
    .buy-total strong { display:block; font-size:18px; color:var(--text); margin-top:4px; }
    .buy-warn { color:#dc2626; font-size:11px; margin-top:6px; display:none; }
    .holding-box { margin-top:16px; padding:14px; background:#e8f5ef; border-radius:9px; font-size:12px; color:var(--text); line-height:1.8; }

    @media(max-width:900px) { .pd-grid { grid-template-columns:1fr; } }
    @media(max-width:576px) { .pd-info { grid-template-columns:1fr; } .pd-content { padding:18px; } }
</style>

<div class="inv-page">

    {{-- Heading --}}
    <div class="inv-heading">
        <div>
            <h2>Project Details</h2>
            <p>View detailed information about a project.</p>
        </div>

        <a href="{{ route('user.available-projects.index') }}" class="inv-btn outline">
            <i class="fas fa-arrow-left"></i>
            Available Projects
        </a>
    </div>

    @if(session('success'))
        <div class="inv-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="inv-alert error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="inv-alert error">
            <strong>Please check the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    @if($project)

        @php
            $available = (int) $project->available_shares;
            $total = max(1, (int) $project->total_shares);
            $availablePercent = min(100, round(($available / $total) * 100));
            $categoryName = $project->projectCategory->name ?? ucwords(str_replace('_', ' ', $project->category));
        @endphp

        <div class="pd-grid">

            {{-- LEFT: project info --}}
            <div>
                <div class="inv-panel">

                    <div class="pd-banner {{ $project->image ? 'has-image' : '' }}">
                        @if($project->image)
                            <img src="{{ Storage::disk('public')->url($project->image) }}" alt="{{ $project->name }}">
                        @else
                            <i class="fas fa-building"></i>
                            <h3>{{ $project->name }}</h3>
                            <p>{{ $project->company->name ?? 'Project Information' }}</p>
                        @endif
                    </div>

                    <div class="pd-content">

                        <span class="st-badge {{ $project->status }}">{{ ucfirst($project->status) }}</span>

                        <h3 style="margin:14px 0 4px; color:var(--text); font-size:20px;">{{ $project->name }}</h3>
                        <div style="color:var(--muted); font-size:12px;">{{ $project->company->name ?? 'N/A' }}</div>

                        <h4>About This Project</h4>
                        <p class="desc">{{ $project->description ?: 'No description available.' }}</p>

                        <div class="pd-info">

                            <div class="pd-info-item">
                                <span>Category</span>
                                <strong>{{ $categoryName }}</strong>
                            </div>

                            <div class="pd-info-item">
                                <span>Share Price (BDT)</span>
                                <strong>{{ number_format((float) $project->share_price, 2) }}</strong>
                            </div>

                            <div class="pd-info-item">
                                <span>Daily Profit</span>
                                <strong>{{ rtrim(rtrim(number_format((float) $project->daily_profit_percent, 2), '0'), '.') }}%</strong>
                            </div>

                            <div class="pd-info-item">
                                <span>Minimum Buy</span>
                                <strong>{{ number_format($project->min_buy_shares) }} share(s)</strong>
                            </div>

                            <div class="pd-info-item">
                                <span>Start Date</span>
                                <strong>{{ $project->start_date?->format('d M Y') ?? 'Not specified' }}</strong>
                            </div>

                            <div class="pd-info-item">
                                <span>End Date</span>
                                <strong>{{ $project->end_date?->format('d M Y') ?? 'Not specified' }}</strong>
                            </div>

                            <div class="pd-info-item" style="grid-column:1/-1;">
                                <span>Availability</span>
                                <strong>{{ number_format($available) }} / {{ number_format($project->total_shares) }} shares available</strong>
                                <div class="pd-progress"><div style="width: {{ $availablePercent }}%"></div></div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            {{-- RIGHT: buy box --}}
            <div>
                <div class="inv-panel">

                    <div class="inv-panel-header">
                        <h4>Buy Shares</h4>
                        <p>Pay from one of your wallets.</p>
                    </div>

                    <div class="inv-panel-body">

                        @if(!$canBuy)

                            <div class="inv-alert error" style="margin:0;">
                                This project is not open for purchase right now.
                            </div>

                        @elseif(count($walletOptions) === 0)

                            <div class="inv-alert error" style="margin:0;">
                                You don't have a wallet that can be used for this purchase yet.
                            </div>

                            <a href="{{ route('user.deposit') }}" class="inv-btn primary" style="width:100%; margin-top:14px;">
                                <i class="fas fa-wallet"></i> Go to Deposit
                            </a>

                        @else

                            <form method="POST"
                                  action="{{ route('user.project-details.buy', $project->slug) }}"
                                  onsubmit="return confirm('Confirm this purchase?')">
                                @csrf

                                <div class="inv-field">
                                    <label>Pay With (Wallet)</label>
                                    <select name="currency" id="buyCurrency" required>
                                        @foreach($walletOptions as $w)
                                            <option value="{{ $w['currency'] }}"
                                                    data-unit="{{ $w['unit_price'] }}"
                                                    data-balance="{{ $w['balance'] }}"
                                                    {{ old('currency') === $w['currency'] ? 'selected' : '' }}>
                                                {{ $w['currency'] }} — Balance: {{ number_format((float) $w['balance'], 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="inv-field">
                                    <label>Number of Shares</label>
                                    <input type="number" name="shares" id="buyShares"
                                           min="{{ $project->min_buy_shares }}"
                                           max="{{ $available }}"
                                           value="{{ old('shares', $project->min_buy_shares) }}" required>
                                    <small class="inv-help">
                                        Min {{ number_format($project->min_buy_shares) }} · Max {{ number_format($available) }}
                                    </small>
                                </div>

                                <div class="buy-total">
                                    Unit price: <span id="buyUnit">—</span>
                                    <strong id="buyTotal">—</strong>
                                    <div class="buy-warn" id="buyWarn">Insufficient wallet balance.</div>
                                </div>

                                <button type="submit" class="inv-btn primary" id="buyBtn" style="width:100%;">
                                    <i class="fas fa-shopping-cart"></i> Buy Now
                                </button>
                            </form>

                        @endif


                        @if($holding && $holding->shares > 0)
                            <div class="holding-box">
                                <strong>Your holding in this project</strong><br>
                                Shares: {{ number_format($holding->shares) }}
                                @if($holding->locked_shares > 0)
                                    ({{ number_format($holding->locked_shares) }} locked)
                                @endif
                                <br>
                                Invested: {{ number_format((float) $holding->invested_bdt, 2) }} BDT<br>
                                <a href="{{ route('user.my-shares.index') }}" style="color:var(--primary); font-weight:600;">View in My Shares →</a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

    @else

        {{-- Empty State --}}
        <div class="inv-panel">
            <div class="inv-empty">

                <div class="icon"><i class="fas fa-folder-open"></i></div>

                <h4>Project Details Not Available</h4>

                <p>
                    @if(isset($slug))
                        The requested project was not found or is not published.
                    @else
                        Select a published project from the Available Projects section to view its details.
                    @endif
                </p>

                <a href="{{ route('user.available-projects.index') }}" class="inv-btn primary">
                    <i class="fas fa-search"></i> Browse Available Projects
                </a>

            </div>
        </div>

    @endif


    {{-- Notice --}}
    <div class="inv-notice">
        <i class="fas fa-shield-alt"></i>
        <div>
            <h5>Important Information</h5>
            <p>
                Share prices and profit percentages shown here are project listing information
                and do not guarantee future returns. Please review the details carefully
                before buying.
            </p>
        </div>
    </div>

</div>


@if($project && $canBuy && count($walletOptions) > 0)
<script>
    (function () {
        const sel = document.getElementById('buyCurrency');
        const qty = document.getElementById('buyShares');
        const unitEl = document.getElementById('buyUnit');
        const totalEl = document.getElementById('buyTotal');
        const warn = document.getElementById('buyWarn');
        const btn = document.getElementById('buyBtn');

        function fmt(n) {
            return n.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        function calc() {
            const opt = sel.options[sel.selectedIndex];
            const unit = parseFloat(opt.dataset.unit) || 0;
            const balance = parseFloat(opt.dataset.balance) || 0;
            const q = parseInt(qty.value, 10) || 0;
            const total = unit * q;

            unitEl.textContent = fmt(unit) + ' ' + sel.value;
            totalEl.textContent = fmt(total) + ' ' + sel.value;

            const insufficient = total > balance;
            warn.style.display = insufficient ? 'block' : 'none';
            btn.disabled = insufficient || q < 1;
        }

        sel.addEventListener('change', calc);
        qty.addEventListener('input', calc);
        calc();
    })();
</script>
@endif

@endsection