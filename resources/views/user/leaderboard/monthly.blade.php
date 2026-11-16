@extends('layouts.app')

@section('title', 'Monthly Leaderboard')

@section('content')

@include('user.investment._styles')

<style>
    .medal { font-size:18px; }
    tr.me td { background:#e8f5ef; }
</style>

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Monthly Leaderboard</h2>
            <p>Members who referred the most new users in {{ $month }}.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-calendar-alt"></i> {{ $month }}</span>
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

        <div class="inv-summary" style="grid-template-columns:repeat(3, 1fr);">
            <div class="inv-stat">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="label">Participants</div>
                <h3>{{ number_format($summary['total_participants']) }}</h3>
                <small>With at least 1 referral</small>
            </div>
            <div class="inv-stat">
                <div class="icon"><i class="fas fa-crown"></i></div>
                <div class="label">Your Rank</div>
                <h3>{{ $summary['your_rank'] ? '#' . $summary['your_rank'] : '—' }}</h3>
                <small>This month</small>
            </div>
            <div class="inv-stat">
                <div class="icon"><i class="fas fa-user-plus"></i></div>
                <div class="label">Your Referrals</div>
                <h3>{{ number_format($summary['monthly_referrals']) }}</h3>
                <small>
                    <a href="{{ route('user.referrals.link') }}" style="color:var(--primary); font-weight:600;">Get your referral link →</a>
                </small>
            </div>
        </div>

        <div class="inv-panel">
            <div class="inv-panel-header">
                <h4>Top {{ $limit }} Referrers</h4>
                <p>Names are partially hidden for privacy.</p>
            </div>

            <div class="inv-table-wrap">
                <table class="inv-table" style="min-width:500px;">
                    <thead><tr><th>Rank</th><th>Member</th><th>Referrals</th></tr></thead>
                    <tbody>
                        @forelse($leaderboard as $row)
                            <tr class="{{ $row->is_me ? 'me' : '' }}">
                                <td>
                                    @if($row->rank === 1)<span class="medal">🥇</span>
                                    @elseif($row->rank === 2)<span class="medal">🥈</span>
                                    @elseif($row->rank === 3)<span class="medal">🥉</span>
                                    @else #{{ $row->rank }}
                                    @endif
                                </td>
                                <td><strong>{{ $row->name }}</strong></td>
                                <td><strong>{{ number_format($row->referrals) }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="inv-empty">
                                        <div class="icon"><i class="fas fa-user-friends"></i></div>
                                        <h4>No Referrals This Month</h4>
                                        <p>Refer a friend to appear here.</p>
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