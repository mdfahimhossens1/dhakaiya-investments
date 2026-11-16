@extends('layouts.app')

@section('title', 'Reward Categories')

@section('content')

@include('user.investment._styles')

<style>
    .rc-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(290px, 1fr)); gap:18px; }
    .rc-card { background:var(--white); border:1px solid var(--border); border-radius:13px; padding:22px; display:flex; flex-direction:column; gap:12px; }
    .rc-card.locked { opacity:.85; }
    .rc-top { display:flex; justify-content:space-between; align-items:flex-start; gap:10px; }
    .rc-icon { width:46px; height:46px; border-radius:12px; background:#e8f5ef; color:var(--primary); display:flex; align-items:center; justify-content:center; font-size:20px; }
    .rc-card h4 { margin:0; font-size:16px; font-weight:700; color:var(--text); }
    .rc-card p { margin:0; font-size:12px; color:var(--muted); line-height:1.7; }
    .rc-meta { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .rc-meta div { background:#f8fafb; border:1px solid var(--border); border-radius:8px; padding:10px; font-size:11px; color:var(--muted); }
    .rc-meta strong { display:block; font-size:14px; color:var(--text); margin-top:4px; }
    .bar { height:7px; background:#e2e8f0; border-radius:20px; overflow:hidden; }
    .bar div { height:100%; background:var(--primary); }
</style>

@php
    $typeLabels = ['cash' => 'Cash', 'bonus_share' => 'Bonus Share', 'gift' => 'Gift', 'badge' => 'Badge'];
    $typeIcons = ['cash' => 'fa-money-bill-wave', 'bonus_share' => 'fa-layer-group', 'gift' => 'fa-gift', 'badge' => 'fa-award'];
@endphp

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Reward Categories</h2>
            <p>The reward programs available on the platform and what it takes to unlock them.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-coins"></i> Your investment: {{ number_format($invested, 2) }} BDT</span>
    </div>

    @if($categories->isEmpty())

        <div class="inv-panel">
            <div class="inv-empty">
                <div class="icon"><i class="fas fa-gift"></i></div>
                <h4>No Reward Categories Yet</h4>
                <p>Reward categories will appear here once they are published.</p>
            </div>
        </div>

    @else

        <div class="rc-grid">
            @foreach($categories as $c)
                @php $val = rtrim(rtrim(number_format((float) $c->reward_value, 2), '0'), '.'); @endphp

                <div class="rc-card {{ $c->is_unlocked ? '' : 'locked' }}">
                    <div class="rc-top">
                        <div class="rc-icon"><i class="fas {{ $typeIcons[$c->reward_type] ?? 'fa-gift' }}"></i></div>
                        <span class="st-badge {{ $c->is_unlocked ? 'approved' : 'pending' }}">
                            {{ $c->is_unlocked ? 'Unlocked' : 'Locked' }}
                        </span>
                    </div>

                    <div>
                        <h4>{{ $c->name }}</h4>
                        @if($c->description)<p>{{ $c->description }}</p>@endif
                    </div>

                    <div class="rc-meta">
                        <div>
                            Reward
                            <strong>{{ $c->value_type === 'percent' ? $val . '%' : $val }}</strong>
                            {{ $typeLabels[$c->reward_type] ?? ucfirst($c->reward_type) }}
                        </div>
                        <div>
                            Min. Investment
                            <strong>{{ number_format((float) $c->min_investment, 2) }}</strong>
                            BDT
                        </div>
                    </div>

                    @if($c->is_unlocked)
                        <div style="font-size:12px; color:#15803d; font-weight:600;"><i class="fas fa-check-circle"></i> You meet the requirement</div>
                    @else
                        <div>
                            <div style="display:flex; justify-content:space-between; font-size:11px; color:var(--muted); margin-bottom:6px;">
                                <span>{{ $c->progress }}% complete</span>
                                <span>{{ number_format($c->remaining, 2) }} BDT more</span>
                            </div>
                            <div class="bar"><div style="width: {{ $c->progress }}%"></div></div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

    @endif

    <div class="inv-notice">
        <i class="fas fa-info-circle"></i>
        <div>
            <h5>About Rewards</h5>
            <p>
                Progress is based on the total amount you currently have invested in shares.
                Rewards you have already received are listed in
                <a href="{{ route('user.reward-history.index') }}" style="color:var(--primary); font-weight:600;">Reward History</a>.
            </p>
        </div>
    </div>

</div>

@endsection