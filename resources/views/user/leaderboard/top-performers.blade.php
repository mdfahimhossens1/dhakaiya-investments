@extends('layouts.app')

@section('title', 'Top Performers')

@section('content')

@include('user.investment._styles')

<style>
    .medal { font-size:18px; }
    tr.me td { background:#e8f5ef; }
</style>

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Top Performers</h2>
            <p>Members with the highest total earnings: share profit, referral commission and rewards.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-star"></i> All Time</span>
    </div>

    @if(! $enabled)

        <div class="inv-panel">
            <div class="inv-empty">
                <div class="icon"><i class="fas fa-lock"></i></div>
                <h4>Leaderboard Unavailable</h4>
                <p>This leaderboard is turned off right now. Please check back later.</p>
            </div>
        </div>

    @else

        <div class="inv-summary" style="grid-template-columns:repeat(2, 1fr);">
            <div class="inv-stat">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="label">Performers</div>
                <h3>{{ number_format($summary['total_performers']) }}</h3>
                <small>Members with earnings</small>
            </div>
            <div class="inv-stat">
                <div class="icon"><i class="fas fa-crown"></i></div>
                <div class="label">Your Position</div>
                <h3>{{ $summary['your_position'] ? '#' . $summary['your_position'] : '—' }}</h3>
                <small>{{ $summary['your_position'] ? 'Among top performers' : 'Not in the top 100 yet' }}</small>
            </div>
        </div>

        <div class="inv-panel">
            <div class="inv-panel-header">
                <h4>Top {{ $limit }} Performers</h4>
                <p>Names are partially hidden for privacy.</p>
            </div>

            <div class="inv-table-wrap">
                <table class="inv-table" style="min-width:600px;">
                    <thead>
                        <tr><th>Rank</th><th>Member</th><th>Total Earnings</th></tr>
                    </thead>
                    <tbody>
                        @forelse($performers as $row)
                            <tr class="{{ $row->is_me ? 'me' : '' }}">
                                <td>
                                    @if($row->rank === 1)<span class="medal">🥇</span>
                                    @elseif($row->rank === 2)<span class="medal">🥈</span>
                                    @elseif($row->rank === 3)<span class="medal">🥉</span>
                                    @else #{{ $row->rank }}
                                    @endif
                                </td>
                                <td><strong>{{ $row->name }}</strong></td>
                                <td>
                                    @if($showAmounts || $row->is_me)
                                        <strong>{{ number_format((float) $row->income_bdt, 2) }} BDT</strong>
                                        @if($currency !== 'BDT')
                                            <small>≈ {{ number_format((float) $row->income_local, 2) }} {{ $currency }}</small>
                                        @endif
                                        @if($row->is_me)
                                            <small>
                                                Profit {{ number_format((float) $row->profit_bdt, 2) }} ·
                                                Commission {{ number_format((float) $row->commission_bdt, 2) }} ·
                                                Rewards {{ number_format((float) $row->reward_bdt, 2) }}
                                            </small>
                                        @endif
                                    @else
                                        <span style="color:var(--muted);">Hidden</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="inv-empty">
                                        <div class="icon"><i class="fas fa-star"></i></div>
                                        <h4>No Performers Yet</h4>
                                        <p>Earnings will show here once members start earning.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @endif

</div>

@endsection