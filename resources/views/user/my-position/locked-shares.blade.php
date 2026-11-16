@extends('layouts.app')

@section('title', 'Locked Shares')

@section('content')

<div class="mp-page">

    @include('user.my-position._styles')

    <div class="mp-hero">
        <i class="fa-solid fa-lock"></i>
        <h2>Locked Shares</h2>
        <p>Shares you locked to hold your position. Locked shares cannot be resold until you unlock them.</p>
    </div>

    <div class="mp-grid">
        <div class="mp-card"><h3>{{ number_format($summary['total_locked_shares']) }}</h3><p>Currently Locked Shares</p></div>
        <div class="mp-card"><h3>{{ $summary['total_records'] }}</h3><p>Total Lock Records</p></div>
        <div class="mp-card"><h3>{{ $summary['active_records'] }}</h3><p>Active Records</p></div>
    </div>

    @if($position && $position->rank)
        <div class="mp-warn">
            ⚠ Your position <strong>{{ $position->rank->name }}</strong> depends on these locked shares.
            Unlocking can lower your position and restarts the {{ $position->rank->lock_period_days }}-day bonus timer.
        </div>
    @endif

    @if($lockedHoldings->isNotEmpty())
    <div class="mp-section">
        <h3>Unlock Shares</h3>
        <p class="sub">Unlock before you want to resell.</p>
        <div class="mp-table-wrap">
            <table class="mp-table" style="min-width:520px">
                <thead><tr><th>Project</th><th>Locked</th><th>Unlock</th></tr></thead>
                <tbody>
                @foreach($lockedHoldings as $h)
                    <tr>
                        <td>{{ $h->project->name ?? 'Removed' }}</td>
                        <td>{{ number_format($h->locked_shares) }}</td>
                        <td>
                            <form method="POST" action="{{ route('user.my-position.unlock') }}" class="mp-form"
                                  onsubmit="return confirm('Unlocking may lower your position. Continue?')">
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $h->project_id }}">
                                <input type="number" name="shares" min="1" max="{{ $h->locked_shares }}" value="{{ $h->locked_shares }}">
                                <button class="mp-btn red">Unlock</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="mp-section">
        <h3>Locked Share Records</h3>
        <p class="sub">Every lock and release.</p>

        @if($lockedShareRecords->isEmpty())
            <div class="mp-empty"><i class="fa-solid fa-lock"></i><h4>No Locked Share Records</h4>
                <p>Lock shares from <a href="{{ route('user.my-position.index') }}">Current Position</a> to start.</p></div>
        @else
            <div class="mp-table-wrap">
                <table class="mp-table">
                    <thead><tr><th>#</th><th>Project</th><th>Quantity</th><th>Value (BDT)</th><th>Lock Date</th><th>Released</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($lockedShareRecords as $r)
                        <tr>
                            <td>{{ $lockedShareRecords->firstItem() + $loop->index }}</td>
                            <td>{{ $r->project->name ?? 'Removed' }}</td>
                            <td>{{ number_format($r->shares) }}</td>
                            <td>৳ {{ number_format((float) bcmul((string) ($r->project->share_price ?? 0), (string) $r->shares, 2), 2) }}</td>
                            <td>{{ $r->locked_at->format('d M Y') }}<small>{{ $r->locked_at->format('h:i A') }}</small></td>
                            <td>{{ $r->released_at?->format('d M Y') ?? '—' }}</td>
                            <td><span class="mp-chip {{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($lockedShareRecords->hasPages())<div class="mp-pg">{{ $lockedShareRecords->links() }}</div>@endif
        @endif
    </div>

</div>

@endsection