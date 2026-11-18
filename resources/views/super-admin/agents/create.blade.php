@extends('layouts.app')
@section('title', 'New Agent')
@section('content')

<div class="page-heading">
    <div><h2>New Agent</h2><p>Create an agent account and assign areas.</p></div>
    <a href="{{ route('super-admin.agents.index') }}" class="ag-btn ghost">Back</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-panel">
    <form method="POST" action="{{ route('super-admin.agents.store') }}">
        @csrf
        @include('super-admin.agents._form')
        <button class="ag-btn"><i class="fa-solid fa-floppy-disk"></i> Create Agent</button>
    </form>
</div>
@endsection