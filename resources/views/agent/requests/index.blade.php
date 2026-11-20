@extends('layouts.app')
@section('title', 'Research Requests')
@section('content')

<div class="agent-page">
<div class="page-heading">
    <div><h2>Research Requests</h2><p>Requests from users in your areas.</p></div>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-panel">
    <div class="ag-tabs" style="margin-bottom:18px;">
        @foreach(['all'=>'All','pending'=>'Pending','quoted'=>'Quote Sent','accepted'=>'Awaiting Payment','in_progress'=>'In Progress','completed'=>'Completed','history'=>'History'] as $k => $label)
            <a class="ag-tab {{ $status === $k ? 'active' : '' }}" href="{{ route('agent.requests.index', ['status' => $k]) }}">{{ $label }}</a>
        @endforeach
    </div>

    @if($requests->count())
        <div class="ag-table-wrap">
            <table class="ag-table">
                <thead><tr><th>#</th><th>Title</th><th>User</th><th>Quote</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($requests as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($r->title, 50) }}<small>{{ $r->created_at->format('d M Y, h:i A') }}</small></td>
                        <td>{{ $r->user?->name }}</td>
                        <td>{{ $r->quoteText() ?? '—' }}</td>
                        <td><span class="ag-badge {{ $r->statusColor() }}">{{ $r->statusLabel() }}</span></td>
                        <td class="num"><a class="ag-btn ghost sm" href="{{ route('agent.requests.show', $r) }}">Open</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">{{ $requests->links() }}</div>
    @else
        <div class="ag-empty"><i class="fa-solid fa-inbox"></i>No requests in this view.</div>
    @endif
</div>
</div>
@endsection