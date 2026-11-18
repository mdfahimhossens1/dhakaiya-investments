@extends('layouts.app')
@section('title', 'Chat with ' . $agent->user->name)
@section('content')

<div class="page-heading">
    <div><h2>{{ $agent->user->name }}</h2><p>Local agent · {{ $agent->districts->pluck('name')->take(3)->implode(', ') }}</p></div>
    <div class="ag-actions">
        @if($agent->is_verified && $agent->is_available)
            <a href="{{ route('user.research.create', $agent) }}" class="ag-btn"><i class="fa-solid fa-file-circle-plus"></i> Send Research Request</a>
        @endif
        <a href="{{ route('user.agents.index') }}" class="ag-btn ghost">Back</a>
    </div>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-two">
    <div class="ag-panel">
        @include('partials.agent-chat', [
            'messages'  => $messages,
            'meId'      => auth()->id(),
            'sendRoute' => route('user.agents.messages.send', $agent),
            'canSend'   => true,
        ])
    </div>

    <div>
        <div class="ag-notice warn" style="margin-bottom:16px;">
            <i class="fa-solid fa-shield-halved"></i>
            Agent-ke kono taka pathaben na jotokkhon na ekta research request-e quote accept koren. Wallet deposit shudhu platform-er Deposit page theke hoy.
        </div>

        <div class="ag-panel">
            <div class="ag-head"><div><h3>Your Requests</h3></div></div>
            @forelse($requests as $r)
                <p style="margin:0 0 12px;font-size:13px;">
                    <a href="{{ route('user.research.show', $r) }}"><strong>#{{ $r->id }}</strong> {{ \Illuminate\Support\Str::limit($r->title, 30) }}</a><br>
                    <span class="ag-badge {{ $r->statusColor() }}">{{ $r->statusLabel() }}</span>
                </p>
            @empty
                <div class="ag-empty" style="padding:20px;">No requests with this agent yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection