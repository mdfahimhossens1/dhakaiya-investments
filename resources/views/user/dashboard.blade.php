@extends('layouts.app')

@section('title', 'My Dashboard')
@section('page-title', 'My Dashboard')

@section('content')

@php
    $banners       = $banners       ?? collect();
    $notices       = $notices       ?? collect();
    $announcements = $announcements ?? collect();
    $portfolioLabels = $portfolioLabels ?? [];
    $portfolioValues = $portfolioValues ?? [];
@endphp

{{-- ===== Notice ticker ===== --}}
@if($notices->count())
    <div class="notice-bar">
        <span class="notice-tag">NOTICE</span>
        <div class="notice-track">
            <div class="notice-move">
                @foreach($notices as $n)
                    @php $nText = $n->message ?? $n->content ?? $n->body ?? $n->description ?? ''; @endphp
                    <span>
                        <strong>{{ $n->title ?? '' }}</strong>
                        @if($nText) — {{ \Illuminate\Support\Str::limit(strip_tags($nText), 160) }} @endif
                    </span>
                @endforeach
            </div>
        </div>
    </div>
@endif

{{-- ===== Banner slider ===== --}}
@if($banners->count())
    <div class="banner-slider" id="bannerSlider">
        @foreach($banners as $b)
            @php
                $img = $b->image_url
                    ?? (($b->image ?? $b->image_path ?? null) ? asset('storage/' . ($b->image ?? $b->image_path)) : null);
                $link = $b->link ?? $b->link_url ?? $b->url ?? null;
            @endphp
            <div class="banner-slide {{ $loop->first ? 'active' : '' }}">
                @if($link)<a href="{{ $link }}" target="_blank" rel="noopener">@endif
                    @if($img)
                        <img src="{{ $img }}" alt="{{ $b->title ?? 'Banner' }}">
                    @else
                        <div class="banner-text"><h3>{{ $b->title ?? '' }}</h3></div>
                    @endif
                @if($link)</a>@endif
            </div>
        @endforeach

        @if($banners->count() > 1)
            <div class="banner-dots">
                @foreach($banners as $b)<span class="{{ $loop->first ? 'active' : '' }}"></span>@endforeach
            </div>
        @endif
    </div>
@endif

{{-- ===== Welcome ===== --}}
<div class="welcome-banner">
    <div>
        <span class="welcome-label">MEMBER DASHBOARD</span>
        <h1>Hello, {{ auth()->user()->name }}!</h1>
        <p>Welcome to your Dhakaiya Investment account.</p>
    </div>
    <div class="welcome-icon">⌂</div>
</div>

{{-- ===== Profit cards ===== --}}
<div class="stats-grid stats-grid-4">

    <div class="stat-card">
        <div class="stat-top"><span>Credited Today</span><div class="stat-icon green">৳</div></div>
        <h2>৳ {{ number_format((float) $profit['credited_today'], 2) }}</h2>
        <p>
            @forelse($profit['credited_today_local'] as $cur => $t){{ \App\Support\Currency::symbol($cur) }} {{ number_format((float) $t, 2) }} {{ $cur }}@if(! $loop->last) · @endif
            @empty Nothing credited yet today @endforelse
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-top"><span>Earning Today</span><div class="stat-icon purple">◔</div></div>
        <h2>৳ {{ number_format((float) $accruing, 2) }}</h2>
        <p>
            Added to your wallet after midnight
            @if(bccomp($boost, '0', 2) > 0) · incl. +{{ rtrim(rtrim($boost, '0'), '.') }}% rank boost @endif
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-top"><span>This Month</span><div class="stat-icon blue">▦</div></div>
        <h2>৳ {{ number_format((float) $profit['this_month'], 2) }}</h2>
        <p>
            @if($profit['last_date'])
                Last: {{ \Illuminate\Support\Carbon::parse($profit['last_date'])->format('d M') }} · ৳ {{ number_format((float) $profit['last'], 2) }}
            @else Waiting for first profit @endif
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-top"><span>Total Profit Earned</span><div class="stat-icon green">◈</div></div>
        <h2>৳ {{ number_format((float) $profit['total'], 2) }}</h2>
        <p>
            @forelse($profit['by_currency'] as $cur => $t){{ \App\Support\Currency::symbol($cur) }} {{ number_format((float) $t, 2) }} {{ $cur }}@if(! $loop->last) · @endif
            @empty No profit received yet @endforelse
            · <a href="{{ route('user.profit-loss-history.index') }}">History →</a>
        </p>
    </div>

</div>

{{-- ===== Account cards ===== --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top"><span>Account Status</span><div class="stat-icon green">✓</div></div>
        <h2>Active</h2>
        <p>Your account is registered</p>
    </div>

    <div class="stat-card">
        <div class="stat-top"><span>My Referrals</span><div class="stat-icon blue">♙</div></div>
        <h2>{{ auth()->user()->referrals()->count() }}</h2>
        <p>People registered through your code</p>
    </div>

    <div class="stat-card">
        <div class="stat-top"><span>Membership</span><div class="stat-icon purple">♧</div></div>
        <h2>Member</h2>
        <p>Platform account</p>
    </div>
</div>

{{-- ===== Announcements ===== --}}
@if($announcements->count())
    <div class="dashboard-card" style="margin-bottom:22px;">
        <h3>Announcements</h3>
        <p>Latest updates from Dhakaiya Investment</p>

        <div class="ann-list">
            @foreach($announcements as $a)
                <div class="ann-item ann-{{ $a->type ?? 'info' }}">
                    <strong>{{ $a->title }}</strong>
                    <small>{{ $a->created_at?->format('d M Y') }}</small>
                    <p>{{ $a->message ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- ===== Charts ===== --}}
<div class="chart-grid">

    <div class="dashboard-card chart-card">
        <div class="chart-head">
            <div>
                <h3>Profit Growth</h3>
                <p>Monthly profit credited (BDT)</p>
            </div>
            <span class="chart-badge">{{ now()->year }}</span>
        </div>
        <div class="chart-box"><canvas id="growthChart"></canvas></div>
    </div>

    <div class="dashboard-card chart-card">
        <div class="chart-head">
            <div>
                <h3>Portfolio Distribution</h3>
                <p>Your shares by project (BDT)</p>
            </div>
        </div>

        @if(count($portfolioValues))
            <div class="chart-box chart-box-doughnut"><canvas id="portfolioChart"></canvas></div>
        @else
            <div class="chart-box chart-box-doughnut" style="color:#87948e;font-size:13px;">You have no shares yet.</div>
        @endif
    </div>

</div>

{{-- ===== Account + Referral ===== --}}
<div class="dashboard-grid">

    <div class="dashboard-card">
        <h3>My Account</h3>
        <p>Your personal account information</p>

        <div class="account-details user-details">
            <div><span>Full Name</span><strong>{{ auth()->user()->name }}</strong></div>
            <div><span>Phone Number</span><strong>{{ auth()->user()->phone }}</strong></div>
            <div><span>Email Address</span><strong>{{ auth()->user()->email ?? 'Not provided' }}</strong></div>
            <div><span>Country</span><strong>{{ auth()->user()->country }}</strong></div>
            <div><span>Registration Date</span><strong>{{ auth()->user()->created_at->format('d M Y') }}</strong></div>
        </div>
    </div>

    <div class="dashboard-card referral-card">
        <div class="referral-icon">♧</div>
        <h3>My Referral Code</h3>
        <p>Share your referral code with others.</p>

        <div class="referral-code" id="referralCode">{{ auth()->user()->referral_code }}</div>

        <button type="button" class="copy-btn" onclick="copyReferral()">Copy Referral Code</button>
        <span id="copyMessage"></span>
    </div>

</div>

<style>
    .stats-grid-4 { grid-template-columns: repeat(4, 1fr); margin-bottom: 22px; }
    @media (max-width: 1100px) { .stats-grid-4 { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px)  { .stats-grid-4 { grid-template-columns: 1fr; } }

    .chart-grid { display: grid; grid-template-columns: 1.7fr 1fr; gap: 22px; margin-bottom: 22px; }
    .chart-card { padding: 22px; }
    .chart-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 18px; }
    .chart-head h3 { margin: 0; font-size: 16px; font-weight: 800; color: #172b25; }
    .chart-head p { margin: 5px 0 0; font-size: 12px; color: #87948e; }
    .chart-badge { background: #e8f7ef; color: #087f5b; font-size: 11px; font-weight: 700; padding: 6px 12px; border-radius: 20px; white-space: nowrap; }
    .chart-box { position: relative; height: 300px; }
    .chart-box-doughnut { height: 300px; display: flex; align-items: center; justify-content: center; }
    @media (max-width: 991px) {
        .chart-grid { grid-template-columns: 1fr; }
        .chart-box, .chart-box-doughnut { height: 260px; }
    }

    /* Notice ticker */
    .notice-bar { display: flex; align-items: center; gap: 12px; background: #fff7e6; border: 1px solid #ffe2a8; border-radius: 12px; padding: 10px 14px; margin-bottom: 18px; overflow: hidden; }
    .notice-tag { background: #f59e0b; color: #fff; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 20px; flex-shrink: 0; }
    .notice-track { overflow: hidden; flex: 1; }
    .notice-move { display: inline-flex; gap: 60px; white-space: nowrap; animation: noticeScroll 28s linear infinite; font-size: 13px; color: #7a4b00; }
    .notice-move:hover { animation-play-state: paused; }
    @keyframes noticeScroll { from { transform: translateX(100%); } to { transform: translateX(-100%); } }

    /* Banner slider */
    .banner-slider { position: relative; border-radius: 16px; overflow: hidden; margin-bottom: 22px; background: #e8f7ef; }
    .banner-slide { display: none; }
    .banner-slide.active { display: block; }
    .banner-slide img { width: 100%; max-height: 260px; object-fit: cover; display: block; }
    .banner-text { padding: 28px; }
    .banner-text h3 { margin: 0; color: #172b25; }
    .banner-dots { position: absolute; bottom: 10px; left: 0; right: 0; display: flex; justify-content: center; gap: 6px; }
    .banner-dots span { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,.6); }
    .banner-dots span.active { background: #087f5b; }

    /* Announcements */
    .ann-list { margin-top: 14px; display: grid; gap: 12px; }
    .ann-item { border: 1px solid #eef2f0; border-radius: 12px; padding: 14px 16px; background: #fbfdfc; }
    .ann-item strong { color: #172b25; }
    .ann-item small { margin-left: 8px; color: #87948e; }
    .ann-item p { margin: 6px 0 0; font-size: 13px; color: #4b5d56; }
    .ann-info    { border-left: 4px solid #3b82f6; }
    .ann-success { border-left: 4px solid #087f5b; }
    .ann-warning { border-left: 4px solid #f59e0b; }
    .ann-danger  { border-left: 4px solid #ef4444; }
</style>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    function copyReferral() {
        const code = document.getElementById('referralCode').innerText.trim();

        navigator.clipboard.writeText(code).then(() => {
            document.getElementById('copyMessage').innerText = 'Copied successfully!';
        }).catch(() => {
            document.getElementById('copyMessage').innerText = 'Unable to copy. Please copy manually.';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {

        /* ===== Banner auto slide ===== */
        const slider = document.getElementById('bannerSlider');
        if (slider) {
            const slides = slider.querySelectorAll('.banner-slide');
            const dots = slider.querySelectorAll('.banner-dots span');
            let cur = 0;

            if (slides.length > 1) {
                setInterval(() => {
                    slides[cur].classList.remove('active');
                    dots[cur]?.classList.remove('active');
                    cur = (cur + 1) % slides.length;
                    slides[cur].classList.add('active');
                    dots[cur]?.classList.add('active');
                }, 5000);
            }
        }

        Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
        Chart.defaults.color = '#87948e';

        /* ===== Profit Growth (Line) ===== */
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        const gradient = growthCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(8, 127, 91, 0.25)');
        gradient.addColorStop(1, 'rgba(8, 127, 91, 0)');

        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: @json($growthLabels),
                datasets: [{
                    label: 'Profit',
                    data: @json($growthProfit),
                    borderColor: '#087f5b',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#087f5b',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#172b25', padding: 12, cornerRadius: 8,
                        callbacks: { label: ctx => ' Profit: ৳ ' + ctx.parsed.y.toLocaleString() }
                    }
                },
                scales: {
                    x: { grid: { display: false }, border: { display: false } },
                    y: {
                        beginAtZero: true,
                        border: { display: false },
                        grid: { color: '#eef2f0' },
                        ticks: { callback: v => v >= 1000 ? (v / 1000) + 'k' : v }
                    }
                }
            }
        });

        /* ===== Portfolio (Doughnut) ===== */
        const pf = document.getElementById('portfolioChart');
        if (pf) {
            new Chart(pf, {
                type: 'doughnut',
                data: {
                    labels: @json($portfolioLabels),
                    datasets: [{
                        data: @json($portfolioValues),
                        backgroundColor: ['#087f5b', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444', '#14b8a6'],
                        borderColor: '#ffffff',
                        borderWidth: 4,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, padding: 16 } },
                        tooltip: {
                            backgroundColor: '#172b25', padding: 12, cornerRadius: 8,
                            callbacks: { label: ctx => ' ' + ctx.label + ': ৳ ' + ctx.parsed.toLocaleString() }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush

@include('partials.dashboard-styles')