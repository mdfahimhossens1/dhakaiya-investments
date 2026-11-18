@extends('layouts.app')
@section('title', 'My Research Requests')
@section('content')

<div class="page-heading">
    <div><h2>My Research Requests</h2><p>Track your requests and reports.</p></div>
    <a href="{{ route('user.agents.index') }}" class="ag-btn"><i class="fa-solid fa-plus"></i> New Request</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-panel">
    <div class="ag-tabs" style="margin-bottom:18px;">
        <a class="ag-tab {{ ! $status ? 'active' : '' }}" href="{{ route('user.research.index') }}">All</a>
        @foreach(\App\Models\ResearchRequest::STATUSES as $k => $v)
            <a class="ag-tab {{ $status === $k ? 'active' : '' }}" href="{{ route('user.research.index', ['status' => $k]) }}">{{ $v[0] }}</a>
        @endforeach
    </div>

    @if($requests->count())
        <div class="ag-table-wrap">
            <table class="ag-table">
                <thead><tr><th>#</th><th>Title</th><th>Agent</th><th>Quote</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($requests as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($r->title, 45) }}<small>{{ $r->created_at->format('d M Y') }}</small></td>
                        <td>{{ $r->agent?->user?->name }}</td>
                        <td>{{ $r->quoteText() ?? '—' }}</td>
                        <td><span class="ag-badge {{ $r->statusColor() }}">{{ $r->statusLabel() }}</span></td>
                        <td class="num"><a class="ag-btn ghost sm" href="{{ route('user.research.show', $r) }}">Open</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">{{ $requests->links() }}</div>
    @else
        <div class="ag-empty"><i class="fa-solid fa-file-lines"></i>No research requests yet. <a href="{{ route('user.agents.index') }}">Find an agent</a> to start.</div>
    @endif
</div>
@endsection