@extends('layouts.app')

@section('title', 'My Rewards')

@section('content')

@include('user.investment._styles')

<style>
    .chips { display:flex; flex-wrap:wrap; gap:6px; margin-top:4px; }
    .chip { background:#e8f5ef; color:var(--primary); padding:4px 9px; border-radius:20px; font-size:11px; font-weight:600; }
</style>

@php
    $catLabels = ['position' => 'Position', 'resell' => 'Resell', 'referral' => 'Referral'];
    $unlocked = $categories->where('is_unlocked', true)->count();
@endphp

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>My Rewards</h2>
            <p>Rewards you have earned and the programs you can unlock.</p>
        </div>
        <a href="{{ route('user.reward-categories.index') }}" class="inv-btn outline"><i class="fas fa-gift"></i> Reward Categories</a>
    </div>

    <div class="inv-summary">
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-gift"></i></div>
            <div class="label">Rewards Received</div>
            <h3>{{ number_format($summary['total_rewards']) }}</h3>
            <small>All time</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-coins"></i></div>
            <div class="label">Total Earned</div>
            <div class="chips">
                @forelse($summary['by_currency'] as $cur => $total)
                    <span class="chip">{{ $cur }} {{ number_format((float) $total, 2) }}</span>
                @empty
                    <span style="color:var(--muted); font-size:12px;">—</span>
                @endforelse
            </div>
            <small>Credited to your wallet</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-unlock"></i></div>
            <div class="label">Unlocked Categories</div>
            <h3>{{ $unlocked }} / {{ $categories->count() }}</h3>
            <small>Based on your investment</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-layer-group"></i></div>
            <div class="label">By Source</div>
            <div class="chips">
                @forelse($summary['by_category'] as $cat => $count)
                    <span class="chip">{{ $catLabels[$cat] ?? ucfirst($cat) }}: {{ $count }}</span>
                @empty
                    <span style="color:var(--muted); font-size:12px;">—</span>
                @endforelse
            </div>
        </div>
    </div>

    <div class="inv-panel">
        <div class="inv-panel-header">
            <h4>Recent Rewards</h4>
            <p>Your latest 10 rewards. <a href="{{ route('user.reward-history.index') }}" style="color:var(--primary); font-weight:600;">View all →</a></p>
        </div>

        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:600px;">
                <thead>
                    <tr><th>#</th><th>Source</th><th>Reward</th><th>Date</th></tr>
                </thead>
                <tbody>
                    @forelse($rewards as $r)
                        @php $attrs = $r->getAttributes(); @endphp
                        <tr>
                            <td>#{{ $r->id }}</td>
                            <td>
                                {{ $catLabels[$r->category] ?? ucfirst((string) $r->category) }}
                                @if(!empty($attrs['description']))<small>{{ $attrs['description'] }}</small>@endif
                            </td>
                            <td class="pl-pos"><strong>+{{ number_format((float) $r->wallet_amount, 2) }} {{ $r->wallet_currency }}</strong></td>
                            <td>{{ $r->created_at->format('d M Y') }}<small>{{ $r->created_at->format('h:i A') }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="inv-empty">
                                    <div class="icon"><i class="fas fa-gift"></i></div>
                                    <h4>No Rewards Yet</h4>
                                    <p>Rewards you earn will appear here.</p>
                                    <a href="{{ route('user.reward-categories.index') }}" class="inv-btn primary">See How To Earn</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection