@extends('layouts.app')

@section('title', 'Reward History')

@section('content')

@include('user.investment._styles')

<style>
    .chips { display:flex; flex-wrap:wrap; gap:6px; margin-top:4px; }
    .chip { background:#e8f5ef; color:var(--primary); padding:4px 9px; border-radius:20px; font-size:11px; font-weight:600; }
</style>

@php
    $catLabels = ['position' => 'Position', 'resell' => 'Resell', 'referral' => 'Referral'];
@endphp

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Reward History</h2>
            <p>Every reward credited to your wallet.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-history"></i> {{ number_format($summary['total']) }} reward(s)</span>
    </div>

    <div class="inv-summary">
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-gift"></i></div>
            <div class="label">Total Rewards</div>
            <h3>{{ number_format($summary['total']) }}</h3>
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
        </div>

        @foreach(['position', 'resell', 'referral'] as $cat)
            <div class="inv-stat">
                <div class="icon"><i class="fas fa-{{ ['position' => 'crown', 'resell' => 'exchange-alt', 'referral' => 'user-friends'][$cat] }}"></i></div>
                <div class="label">{{ $catLabels[$cat] }} Rewards</div>
                <h3>{{ number_format($summary['by_category'][$cat] ?? 0) }}</h3>
                <small>Received</small>
            </div>
        @endforeach
    </div>

    <div class="inv-panel">

        <form method="GET" action="{{ route('user.reward-history.index') }}" class="inv-filter">
            <select name="category">
                <option value="all" {{ $category === 'all' ? 'selected' : '' }}>All Sources</option>
                @foreach($catLabels as $key => $label)
                    <option value="{{ $key }}" {{ $category === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="inv-btn primary"><i class="fas fa-filter"></i> Apply Filter</button>
            <a href="{{ route('user.reward-history.index') }}" class="inv-btn outline">Reset</a>
        </form>

        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:650px;">
                <thead>
                    <tr><th>#</th><th>Source</th><th>Reward</th><th>Date</th><th>Status</th></tr>
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
                            <td><span class="st-badge approved">Credited</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="inv-empty">
                                    <div class="icon"><i class="fas fa-inbox"></i></div>
                                    <h4>No Reward Records Found</h4>
                                    <p>Rewards credited to your wallet will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rewards->hasPages())
            <div class="inv-pagination">{{ $rewards->links() }}</div>
        @endif
    </div>

</div>

@endsection