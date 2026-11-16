@extends('layouts.app')

@section('title', 'Position History')

@section('content')

<div class="mp-page">

    @include('user.my-position._styles')

    <div class="mp-hero">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <h2>Position History</h2>
        <p>Review every promotion and demotion of your position.</p>
    </div>

    <div class="mp-grid">
        <div class="mp-card"><h3>{{ $summary['total_positions'] }}</h3><p>Times Promoted</p></div>
        <div class="mp-card"><h3>{{ $summary['current_level'] }}</h3><p>Current Level</p></div>
        <div class="mp-card"><h3>{{ $summary['previous_positions'] }}</h3><p>Previous Positions</p></div>
    </div>

    <div class="mp-section">
        <h3>Position History Records</h3>
        <p class="sub">Newest first.</p>

        @if($positionHistory->isEmpty())
            <div class="mp-empty"><i class="fa-solid fa-clock-rotate-left"></i><h4>No Position History Found</h4>
                <p>Your position changes will appear here.</p></div>
        @else
            <div class="mp-table-wrap">
                <table class="mp-table">
                    <thead><tr><th>#</th><th>Change</th><th>From</th><th>To</th><th>Locked Value</th><th>Date</th></tr></thead>
                    <tbody>
                    @foreach($positionHistory as $h)
                        <tr>
                            <td>{{ $positionHistory->firstItem() + $loop->index }}</td>
                            <td><span class="mp-chip {{ $h->action }}">{{ $h->action === 'promoted' ? '▲ Promoted' : '▼ Demoted' }}</span></td>
                            <td>{{ $h->fromRank?->name ?? '—' }}@if($h->fromRank)<small>Level {{ $h->fromRank->level }}</small>@endif</td>
                            <td>
                                @if($h->toRank)
                                    <span class="mp-badge" style="background:{{ $h->toRank->badge_color }}">{{ $h->toRank->name }}</span>
                                    <small>Level {{ $h->toRank->level }}</small>
                                @else No position @endif
                            </td>
                            <td>৳ {{ number_format((float) $h->locked_value_bdt, 2) }}</td>
                            <td>{{ $h->created_at->format('d M Y') }}<small>{{ $h->created_at->format('h:i A') }}</small></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($positionHistory->hasPages())<div class="mp-pg">{{ $positionHistory->links() }}</div>@endif
        @endif
    </div>

</div>

@endsection