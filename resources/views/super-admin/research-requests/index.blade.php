@extends('layouts.app')
@section('title', $reports ? 'Research Reports' : 'Research Requests')
@section('content')

<div class="page-heading">
    <div>
        <h2>{{ $reports ? 'Research Reports' : 'Research Requests' }}</h2>
        <p>{{ $reports ? 'Reports submitted by agents.' : 'All research requests across agents.' }}</p>
    </div>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-panel">
    <form method="GET" class="ag-filter" style="margin-bottom:18px;">
        <div class="ag-field"><label>Search title</label><input type="text" name="q" value="{{ $q }}"></div>
        <div class="ag-field">
            <label>Agent</label>
            <select name="agent">
                <option value="">All agents</option>
                @foreach($agents as $a)<option value="{{ $a->id }}" @selected($agentId === $a->id)>{{ $a->user?->name }}</option>@endforeach
            </select>
        </div>
        @unless($reports)
            <div class="ag-field">
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    @foreach(\App\Models\ResearchRequest::STATUSES as $k => $v)<option value="{{ $k }}" @selected($status === $k)>{{ $v[0] }}</option>@endforeach
                </select>
            </div>
        @endunless
        <button class="ag-btn">Filter</button>
    </form>

    @if($requests->count())
        <div class="ag-table-wrap">
            <table class="ag-table">
                <thead>
                    <tr><th>#</th><th>Title</th><th>User</th><th>Agent</th><th>Quote</th><th>{{ $reports ? 'Submitted' : 'Status' }}</th><th></th></tr>
                </thead>
                <tbody>
                @foreach($requests as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($r->title, 45) }}<small>{{ $r->created_at->format('d M Y') }}</small></td>
                        <td>{{ $r->user?->name }}</td>
                        <td>{{ $r->agent?->user?->name }}</td>
                        <td>{{ $r->quoteText() ?? '—' }}</td>
                        <td>
                            @if($reports)
                                {{ $r->submission?->submitted_at?->format('d M Y, h:i A') ?? '—' }}
                            @else
                                <span class="ag-badge {{ $r->statusColor() }}">{{ $r->statusLabel() }}</span>
                            @endif
                        </td>
                        <td class="num"><a class="ag-btn ghost sm" href="{{ route('super-admin.research-requests.show', $r) }}">Open</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">{{ $requests->links() }}</div>
    @else
        <div class="ag-empty"><i class="fa-solid fa-file-lines"></i>Nothing found.</div>
    @endif
</div>
@endsection