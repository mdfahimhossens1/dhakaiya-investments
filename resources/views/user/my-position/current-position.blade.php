@extends('layouts.app')

@section('title', 'Current Position')

@section('content')

<div class="mp-page">

    @include('user.my-position._styles')

    <div class="mp-hero" @if($rank) style="background:linear-gradient(135deg,#102b26,{{ $rank->badge_color }})" @endif>
        <i class="fa-solid fa-chess-king"></i>
        <h2>Current Position</h2>
        <p>
            @if($rank)
                You are <strong>{{ $rank->name }}</strong> (Level {{ $rank->level }}). Keep your shares locked to earn your bonus.
            @else
                Lock shares and meet the requirements below to earn your first position.
            @endif
        </p>
    </div>

    <div class="mp-grid">
        <div class="mp-card">
            <div class="mp-card-icon"><i class="fa-solid fa-ranking-star"></i></div>
            <h3>{{ $currentPosition['rank'] }}</h3><p>Current Rank</p>
        </div>
        <div class="mp-card">
            <div class="mp-card-icon"><i class="fa-solid fa-layer-group"></i></div>
            <h3>{{ $summary['current_level'] }}</h3><p>Current Level</p>
        </div>
        <div class="mp-card">
            <div class="mp-card-icon"><i class="fa-solid fa-lock"></i></div>
            <h3>৳ {{ number_format((float) $metrics['locked_value_bdt'], 2) }}</h3><p>Locked Share Value</p>
            @if($currency !== 'BDT' && $lockedValueLocal !== null)<small>≈ {{ $symbol }} {{ number_format((float) $lockedValueLocal, 2) }}</small>@endif
        </div>
        <div class="mp-card">
            <div class="mp-card-icon"><i class="fa-solid fa-trophy"></i></div>
            <h3>{{ $summary['total_achievements'] }}</h3><p>Bonuses Earned</p>
        </div>
    </div>

    <div class="mp-section">
        <h3><i class="fa-solid fa-chess-king" style="color:#087f5b"></i> Position Details</h3>
        <p class="sub">Your current rank and bonus status.</p>
        <div class="mp-details">
            <div class="mp-detail"><span class="l">Current Position</span>
                @if($rank)<span class="mp-badge" style="background:{{ $rank->badge_color }}">{{ $rank->name }}</span>@else<strong>Not Assigned</strong>@endif
            </div>
            <div class="mp-detail"><span class="l">Position Level</span><strong>{{ $currentPosition['level'] }}</strong></div>
            <div class="mp-detail"><span class="l">Position Status</span>
                <span class="mp-status {{ $rank ? 'on' : '' }}">{{ $currentPosition['status'] }}</span></div>
            <div class="mp-detail"><span class="l">Assigned Date</span>
                <strong>{{ $currentPosition['assigned_date']?->format('d M Y, h:i A') ?? 'Not Assigned' }}</strong></div>
            <div class="mp-detail"><span class="l">Active Referrals</span><strong>{{ $metrics['active_referrals'] }}</strong></div>
            <div class="mp-detail"><span class="l">Total Resell Volume</span><strong>৳ {{ number_format((float) $metrics['resell_volume_bdt'], 2) }}</strong></div>
            @if($rank)
                <div class="mp-detail"><span class="l">Profit Boost</span><strong>+{{ rtrim(rtrim((string) $rank->profit_boost_percent, '0'), '.') ?: '0' }}% on daily profit</strong></div>
                <div class="mp-detail"><span class="l">Rank Bonus</span>
                    <strong>
                        ৳ {{ number_format((float) $rank->bonus_amount, 2) }}
                        @if($currency !== 'BDT' && $rank->bonus_local !== null)(≈ {{ $symbol }} {{ number_format((float) $rank->bonus_local, 2) }})@endif
                    </strong>
                    <small style="display:block;margin-top:7px;color:#84919f;font-size:12px">
                        @if($rewardPaid) ✓ Already received
                        @elseif($eligibleAt && $eligibleAt->isFuture()) Eligible on {{ $eligibleAt->format('d M Y') }} ({{ now()->diffInDays($eligibleAt) + 1 }} day(s) left)
                        @else Will be credited in the next daily run
                        @endif
                    </small>
                </div>
            @endif
        </div>
    </div>

    @if($next)
    <div class="mp-section">
        <h3>Next Rank: <span class="mp-badge" style="background:{{ $next->badge_color }}">{{ $next->name }}</span></h3>
        <p class="sub">Overall progress {{ $overall }}%. You must meet all requirements.</p>

        @foreach($requirements as $r)
            <div class="mp-req">
                <div class="mp-req-top">
                    <span>{{ $r['label'] }}</span>
                    <span>
                        @if($r['type'] === 'money')
                            ৳ {{ number_format((float) $r['current'], 2) }} / ৳ {{ number_format((float) $r['required'], 2) }}
                        @else
                            {{ (int) $r['current'] }} / {{ (int) $r['required'] }}
                        @endif
                        ({{ $r['percent'] }}%)
                    </span>
                </div>
                <div class="mp-bar"><i style="width:{{ $r['percent'] }}%"></i></div>
            </div>
        @endforeach
    </div>
    @elseif($rank)
    <div class="mp-notice">🎉 You have reached the highest available rank.</div>
    @endif

    <div class="mp-section">
        <h3>Lock Your Shares</h3>
        <p class="sub">Locked shares count towards your position. Unlocking lowers your position and restarts the bonus timer.</p>

        @if($holdings->isEmpty())
            <div class="mp-empty"><i class="fa-solid fa-chart-pie"></i><h4>No shares yet</h4>
                <p>Buy shares first from <a href="{{ route('user.available-projects.index') }}">Available Projects</a>.</p></div>
        @else
            <div class="mp-table-wrap">
                <table class="mp-table">
                    <thead><tr><th>Project</th><th>Owned</th><th>Locked</th><th>Free</th><th>Lock / Unlock</th></tr></thead>
                    <tbody>
                    @foreach($holdings as $h)
                        @php $free = $h->shares - $h->locked_shares; @endphp
                        <tr>
                            <td>{{ $h->project->name ?? 'Removed' }}</td>
                            <td>{{ number_format($h->shares) }}</td>
                            <td>{{ number_format($h->locked_shares) }}</td>
                            <td>{{ number_format($free) }}</td>
                            <td>
                                <div class="mp-form">
                                    @if($free > 0)
                                    <form method="POST" action="{{ route('user.my-position.lock') }}" class="mp-form">
                                        @csrf
                                        <input type="hidden" name="project_id" value="{{ $h->project_id }}">
                                        <input type="number" name="shares" min="1" max="{{ $free }}" value="{{ $free }}">
                                        <button class="mp-btn">Lock</button>
                                    </form>
                                    @endif
                                    @if($h->locked_shares > 0)
                                    <form method="POST" action="{{ route('user.my-position.unlock') }}" class="mp-form"
                                          onsubmit="return confirm('Unlocking may lower your position. Continue?')">
                                        @csrf
                                        <input type="hidden" name="project_id" value="{{ $h->project_id }}">
                                        <input type="number" name="shares" min="1" max="{{ $h->locked_shares }}" value="{{ $h->locked_shares }}">
                                        <button class="mp-btn red">Unlock</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mp-section">
        <h3>All Positions</h3>
        <p class="sub">Requirements and rewards for every rank.</p>

        @if($ranks->isEmpty())
            <div class="mp-empty"><i class="fa-solid fa-ranking-star"></i><h4>No ranks configured</h4><p>Ranks will appear here once the platform adds them.</p></div>
        @else
        <div class="mp-ladder">
            @foreach($ranks as $r)
                <div class="mp-rank {{ $r->is_current ? 'cur' : '' }}">
                    <span class="mp-badge" style="background:{{ $r->badge_color }}">Level {{ $r->level }}</span>
                    <h4>{{ $r->name }} @if($r->is_current)✓@endif</h4>
                    <ul>
                        <li>Locked shares: <b>৳ {{ number_format((float) $r->min_total_investment, 2) }}</b>
                            @if($currency !== 'BDT' && $r->min_local !== null)(≈ {{ $symbol }} {{ number_format((float) $r->min_local, 2) }})@endif</li>
                        <li>Active referrals: <b>{{ $r->min_active_referrals }}</b></li>
                        <li>Resell volume: <b>৳ {{ number_format((float) $r->min_resell_volume, 2) }}</b></li>
                        <li>Hold for: <b>{{ $r->lock_period_days }} day(s)</b></li>
                        <li>Bonus: <b>৳ {{ number_format((float) $r->bonus_amount, 2) }}</b>
                            @if($currency !== 'BDT' && $r->bonus_local !== null)(≈ {{ $symbol }} {{ number_format((float) $r->bonus_local, 2) }})@endif</li>
                        <li>Profit boost: <b>+{{ rtrim(rtrim((string) $r->profit_boost_percent, '0'), '.') ?: '0' }}%</b></li>
                    </ul>
                </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="mp-notice">
        <i class="fa-solid fa-circle-info"></i>
        Positions are recalculated every night. Rank amounts are set in BDT and shown in your wallet currency when an exchange rate is available. Your bonus is credited to your {{ $currency }} wallet.
    </div>

</div>

@endsection