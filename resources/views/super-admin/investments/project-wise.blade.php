@extends('layouts.app')

@section('title', 'Project-wise Records')

@section('content')

@include('user.investment._styles')

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Project-wise Records</h2>
            <p>Holders, volume, profit paid and resale result for every project.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-folder-open"></i> {{ $projects->total() }} project(s)</span>
    </div>

    <div class="inv-panel">

        <form method="GET" action="{{ route('super-admin.investments.project-wise') }}" class="inv-filter">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search project name..."
                   style="min-width:220px; padding:10px 13px; border:1px solid var(--border); border-radius:8px; font-size:13px; outline:none;">
            <button type="submit" class="inv-btn primary"><i class="fas fa-search"></i> Search</button>
            <a href="{{ route('super-admin.investments.project-wise') }}" class="inv-btn outline">Reset</a>
        </form>

        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:1200px;">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Availability</th>
                        <th>Holders</th>
                        <th>Shares Held</th>
                        <th>Invested (BDT)</th>
                        <th>Current (BDT)</th>
                        <th>Bought</th>
                        <th>Sold</th>
                        <th>Daily Profit Paid (BDT)</th>
                        <th>Resale P/L (BDT)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $p)
                        @php $s = $p->stats; @endphp
                        <tr>
                            <td>
                                <strong>{{ $p->name }}</strong>
                                <small>{{ $p->company->name ?? 'N/A' }} · {{ ucfirst($p->status) }}</small>
                            </td>
                            <td>{{ number_format($p->available_shares) }} / {{ number_format($p->total_shares) }}</td>
                            <td>{{ number_format($s->holders) }}</td>
                            <td><strong>{{ number_format($s->held) }}</strong></td>
                            <td>{{ number_format($s->invested, 2) }}</td>
                            <td>{{ number_format($s->current, 2) }}</td>
                            <td>{{ number_format($s->bought) }}<small>{{ number_format($s->buy_volume, 2) }} BDT</small></td>
                            <td>{{ number_format($s->sold) }}</td>
                            <td>{{ number_format($s->profit_paid, 2) }}</td>
                            <td class="{{ $s->resale_pl >= 0 ? 'pl-pos' : 'pl-neg' }}">
                                {{ $s->resale_pl >= 0 ? '+' : '' }}{{ number_format($s->resale_pl, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="inv-empty">
                                    <div class="icon"><i class="fas fa-folder-open"></i></div>
                                    <h4>No Projects Found</h4>
                                    <p>No project matches your search.</p>
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