@extends('layouts.app')
@section('title', 'Agent Performance')
@section('content')

<div class="page-heading">
    <div><h2>Agent Performance</h2><p>Monitor how agents handle research requests.</p></div>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-panel">
    @if($agents->count())
        <div class="ag-table-wrap">
            <table class="ag-table">
                <thead>
                    <tr>
                        <th>Agent</th><th class="num">Total</th><th class="num">Waiting</th><th class="num">Active</th>
                        <th class="num">Completed</th><th class="num">Rejected</th><th class="num">Completion</th><th class="num">Avg days</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($agents as $a)
                    @php $rate = $a->requests_count ? round($a->completed_count / $a->requests_count * 100) : 0; @endphp
                    <tr>
                        <td><a href="{{ route('super-admin.agents.show', $a) }}">{{ $a->user?->name }}</a></td>
                        <td class="num">{{ $a->requests_count }}</td>
                        <td class="num">{{ $a->waiting_count }}</td>
                        <td class="num">{{ $a->active_count }}</td>
                        <td class="num"><strong>{{ $a->completed_count }}</strong></td>
                        <td class="num">{{ $a->rejected_count }}</td>
                        <td class="num">
                            {{ $rate }}%
                            <div style="height:6px;background:#e2e8f0;border-radius:6px;margin-top:5px;min-width:80px;"><span style="display:block;height:100%;width:{{ $rate }}%;background:var(--primary);border-radius:6px;"></span></div>
                        </td>
                        <td class="num">{{ $avgDays[$a->id] ?? '—' }}</td>
                        <td>
                            @if($a->is_active && $a->approval_status === 'approved')<span class="ag-badge green">Active</span>
                            @else<span class="ag-badge red">Inactive</span>@endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="ag-empty"><i class="fa-solid fa-chart-column"></i>No agents yet.</div>
    @endif
</div>
@endsection