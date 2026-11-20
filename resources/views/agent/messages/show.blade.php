@extends('layouts.app')
@section('title', 'Chat with ' . $user->name)
@section('content')

<div class="agent-page">
<div class="page-heading">
    <div><h2>{{ $user->name }}</h2><p>Conversation with user.</p></div>
    <a href="{{ route('agent.messages.index') }}" class="ag-btn ghost">Back</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-two">
    <div class="ag-panel">
        @include('partials.agent-chat', [
            'messages'  => $messages,
            'meId'      => auth()->id(),
            'sendRoute' => route('agent.messages.store', $user),
            'canSend'   => $agent->isOperational(),
        ])
    </div>

    <div class="ag-panel">
        <div class="ag-head"><div><h3>Their Requests</h3></div></div>
        @forelse($requests as $r)
            <p style="margin:0 0 12px;font-size:13px;">
                <a href="{{ route('agent.requests.show', $r) }}"><strong>#{{ $r->id }}</strong> {{ \Illuminate\Support\Str::limit($r->title, 30) }}</a><br>
                <span class="ag-badge {{ $r->statusColor() }}">{{ $r->statusLabel() }}</span>
            </p>
        @empty
            <div class="ag-empty" style="padding:20px;">No requests yet.</div>
        @endforelse
    </div>
</div>
</div>
@endsection