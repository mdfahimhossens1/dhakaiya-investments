@extends('layouts.app')

@section('title', 'Maturity Information')

@section('content')

@include('user.investment._styles')

<style>
    .st-badge.running { background:#dbeafe; color:#1d4ed8; }
    .st-badge.matured { background:#dcfce7; color:#15803d; }
    .st-badge.no_date { background:#f1f5f9; color:#475569; }
</style>

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Maturity Information</h2>
            <p>Project end dates and the investors still holding shares.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-hourglass-half"></i> All Projects</span>
    </div>

    <div class="inv-summary">
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-layer-group"></i></div>
            <div class="label">Total Projects</div>
            <h3>{{ number_format($summary['total']) }}</h3>
            <small>All share products</small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="label">Running</div>
            <h3>{{ number_format($summary['running']) }}</h3>
            <small>End date in the future</small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-flag-checkered"></i></div>
            <div class="label">Matured</div>
            <h3>{{ number_format($summary['matured']) }}</h3>
            <small>End date reached</small>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-calendar-times"></i></div>
            <div class="label">No End Date</div>
            <h3>{{ number_format($summary['no_date']) }}</h3>
            <small>Open-ended</small>
        </div>
    </div>

    <div class="inv-panel">

        <form method="GET" action="{{ route('super-admin.investments.maturity') }}" class="inv-filter">
            <select name="state">
                <option value="all" {{ $state === 'all' ? 'selected' : '' }}>All</option>
                <option value="running" {{ $state === 'running' ? 'selected' : '' }}>Running</option>
                <option value="matured" {{ $state === 'matured' ? 'selected' : '' }}>Matured</option>
                <option value="no_date" {{ $state === 'no_date' ? 'selected' : '' }}>No End Date</option>
            </select>
            <button type="submit" class="inv-btn primary"><i class="fas fa-filter"></i> Apply Filter</button>
            <a href="{{ route('super-admin.investments.maturity') }}" class="inv-btn outline">Reset</a>
        </form>

        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:950px;">
                <thead>
                    <tr>
                        <th>Project</th><th>Start Date</th><th>End Date</th><th>Time Left</th>
                        <th>Holders</th><th>Shares Held</th><th>Invested (BDT)</th><th>State</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $p)
                        @php $m = $p->maturity; @endphp
                        <tr>
                            <td>
                                <strong>{{ $p->name }}</strong>
                                <small>{{ $p->company->name ?? 'N/A' }} · {{ ucfirst($p->status) }}</small>
                            </td>
                            <td>{{ $p->start_date?->format('d M Y') ?? '—' }}</td>
                            <td>{{ $p->end_date?->format('d M Y') ?? '—' }}</td>
                            <td>
                                @if($m->state === 'running')
                                    {{ number_format($m->days_left) }} day(s)
                                @elseif($m->state === 'matured')
                                    Ended {{ number_format(abs($m->days_left)) }} day(s) ago
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ number_format($m->holders) }}</td>
                            <td>{{ number_format($m->shares) }}</td>
                            <td>{{ number_format($m->invested, 2) }}</td>
                            <td>
                                <span class="st-badge {{ $m->state }}">
                                    {{ ['running' => 'Running', 'matured' => 'Matured', 'no_date' => 'No End Date'][$m->state] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="inv-empty">
                                    <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                                    <h4>No Projects Found</h4>
                                    <p>No project matches this filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="inv-pagination">{{ $projects->links() }}</div>
        @endif
    </div>

</div>

@endsection