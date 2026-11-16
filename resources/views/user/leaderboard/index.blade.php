@extends('layouts.app')

@section('title', 'Resell Leaderboard')

@section('content')

@include('user.investment._styles')

<style>
    .medal { font-size:18px; }
    tr.me td { background:#e8f5ef; }
    .prize-grid { display:flex; flex-wrap:wrap; gap:10px; }
    .prize { background:#f8fafb; border:1px solid var(--border); border-radius:8px; padding:10px 14px; font-size:12px; color:var(--muted); }
    .prize strong { display:block; font-size:14px; color:var(--text); margin-top:3px; }
</style>

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Leaderboard</h2>
            <p>Top members ranked by <strong>{{ $basisLabel }}</strong>.</p>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <span class="inv-badge"><i class="fas fa-sync"></i> Resets: {{ $cycleLabel }}</span>
            @if($from)
                <span class="inv-badge" style="background:#f1f5f9; color:#475569;">{{ $from->format('d M') }} – {{ $to->format('d M Y') }}</span>
            @endif
        </div>
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
                <small>In this cycle</small>
            </div>
            <div class="inv-stat">
                <div class="icon"><i class="fas fa-crown"></i></div>
                <div class="label">Your Rank</div>
                <h3>{{ $summary['your_rank'] ? '#' . $summary['your_rank'] : '—' }}</h3>
                <small>{{ $summary['your_rank'] ? 'Keep going!' : 'Not in the top 100 yet' }}</small>
            </div>
            <div class="inv-stat">
                <div class="icon"><i class="fas fa-trophy"></i></div>
                <div class="label">Top {{ $basisLabel }}</div>
                <h3>
                    @if($summary['top_value'] === null) —
                    @elseif(! $showAmounts) Hidden
                    @elseif($isMoney) {{ number_format($summary['top_value'], 2) }}
                    @else {{ number_format($summary['top_value']) }}
                    @endif
                </h3>
                <small>{{ $isMoney ? 'BDT' : 'Referrals' }}</small>
            </div>
        </div>

        @if(!empty($prizes) && is_array($prizes))
            <div class="inv-panel">
                <div class="inv-panel-header"><h4>Prizes</h4><p>Rewards for the top ranks.</p></div>
                <div class="inv-panel-body">
                    <div class="prize-grid">
                        @foreach($prizes as $rank => $prize)
                            @if(is_scalar($prize))
                                <div class="prize">Rank {{ is_numeric($rank) ? $rank + (isset($prizes[0]) ? 1 : 0) : $rank }}<strong>{{ $prize }}</strong></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="inv-panel">
            <div class="inv-panel-header">
                <h4>Top {{ $limit }}</h4>
                <p>Names are partially hidden for privacy.</p>
            </div>

            <div class="inv-table-wrap">
                <table class="inv-table" style="min-width:600px;">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Member</th>
                            @if($isMoney && $basisLabel !== 'Investment Amount')<th>Trades</th>@endif
                            <th>{{ $basisLabel }}</th>
                        </tr>
                    </thead>
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
                                @if($isMoney && $basisLabel !== 'Investment Amount')<td>{{ number_format($row->trades) }}</td>@endif
                                <td>
                                    @if($showAmounts || $row->is_me)
                                        @if($isMoney)
                                            <strong>{{ number_format($row->value, 2) }} BDT</strong>
                                            @if($currency !== 'BDT' && $row->value_local !== null)
                                                <small>≈ {{ number_format((float) $row->value_local, 2) }} {{ $currency }}</small>
                                            @endif
                                        @else
                                            <strong>{{ number_format($row->value) }}</strong>
                                        @endif
                                    @else
                                        <span style="color:var(--muted);">Hidden</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="inv-empty">
                                        <div class="icon"><i class="fas fa-trophy"></i></div>
                                        <h4>No Rankings Yet</h4>
                                        <p>Be the first to appear on the leaderboard.</p>
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