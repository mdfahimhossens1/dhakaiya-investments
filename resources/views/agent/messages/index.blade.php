@extends('layouts.app')
@section('title', 'Messages')
@section('content')

<div class="page-heading">
    <div><h2>Messages</h2><p>Conversations with users.</p></div>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-panel">
    @forelse($threads as $t)
        @php $u = $users[$t->user_id] ?? null; $m = $last[$t->last_id] ?? null; @endphp
        @if($u)
            <a href="{{ route('agent.messages.show', $u) }}" style="display:flex;justify-content:space-between;align-items:center;gap:12px;padding:14px 4px;border-bottom:1px solid #f1f5f9;text-decoration:none;">
                <div class="ag-person">
                    <div class="ag-avatar">{{ strtoupper(mb_substr($u->name, 0, 1)) }}</div>
                    <div>
                        <strong>{{ $u->name }}</strong>
                        <small>{{ \Illuminate\Support\Str::limit($m?->body, 70) }}</small>
                    </div>
                </div>
                <div style="text-align:right;">
                    <small style="color:var(--muted);">{{ $m?->created_at->diffForHumans() }}</small>
                    @if($t->unread)<br><span class="ag-badge green">{{ $t->unread }} new</span>@endif
                </div>
            </a>
        @endif
    @empty
        <div class="ag-empty"><i class="fa-regular fa-comments"></i>No conversations yet. Users will message you from the agent list.</div>
    @endforelse
</div>
@endsection