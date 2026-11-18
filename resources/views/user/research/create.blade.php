@extends('layouts.app')
@section('title', 'New Research Request')
@section('content')

<div class="page-heading">
    <div><h2>New Research Request</h2><p>To {{ $agent->user->name }}</p></div>
    <a href="{{ route('user.agents.messages', $agent) }}" class="ag-btn ghost">Back to chat</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">
    <div class="ag-notice">
        The agent will reply with a price and timeline. You pay only if you accept. This is a <strong>research service</strong>, not an investment deposit.
    </div>

    <div class="ag-panel">
        <form method="POST" action="{{ route('user.research.store', $agent) }}">
            @csrf
            <div class="ag-field"><label>Title *</label><input type="text" name="title" value="{{ old('title') }}" maxlength="150" required placeholder="e.g. Verify land ownership documents"></div>

            <div class="ag-field">
                <label>Area</label>
                <select name="agent_district_id">
                    <option value="">Not specific</option>
                    @foreach($agent->districts as $d)<option value="{{ $d->id }}" @selected((int) old('agent_district_id') === $d->id)>{{ $d->name }}, {{ $d->country->name }}</option>@endforeach
                </select>
            </div>

            <div class="ag-field">
                <label>What do you need researched? *</label>
                <textarea name="description" style="min-height:170px;" required minlength="20" maxlength="3000">{{ old('description') }}</textarea>
                <small>At least 20 characters. Be specific so the agent can give an accurate price.</small>
            </div>

            <button class="ag-btn"><i class="fa-solid fa-paper-plane"></i> Send Request</button>
        </form>
    </div>
</div>
@endsection