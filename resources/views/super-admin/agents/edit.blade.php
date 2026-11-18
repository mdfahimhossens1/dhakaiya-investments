@extends('layouts.app')
@section('title', 'Edit Agent')
@section('content')

<div class="page-heading">
    <div><h2>Edit Agent</h2><p>{{ $agent->user->name }}</p></div>
    <a href="{{ route('super-admin.agents.show', $agent) }}" class="ag-btn ghost">Back</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-panel">
    <form method="POST" action="{{ route('super-admin.agents.update', $agent) }}">
        @csrf @method('PUT')
        @include('super-admin.agents._form')
        <button class="ag-btn"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
    </form>
</div>
@endsection