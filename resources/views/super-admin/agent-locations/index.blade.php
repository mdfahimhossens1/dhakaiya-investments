@extends('layouts.app')
@section('title', 'Countries & Districts')
@section('content')

<div class="page-heading">
    <div><h2>Countries &amp; Districts</h2><p>Define where agents can be assigned.</p></div>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">

    <div class="ag-grid ag-g2">
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Add Country</h3></div></div>
            <form method="POST" action="{{ route('super-admin.agent-locations.countries.store') }}">
                @csrf
                <div class="ag-row">
                    <div class="ag-field"><label>Country name *</label><input type="text" name="name" required></div>
                    <div class="ag-field"><label>Code</label><input type="text" name="code" maxlength="3" placeholder="BD"></div>
                </div>
                <button class="ag-btn"><i class="fa-solid fa-plus"></i> Add Country</button>
            </form>
        </div>

        <div class="ag-panel">
            <div class="ag-head"><div><h3>Add District / City</h3></div></div>
            <form method="POST" action="{{ route('super-admin.agent-locations.districts.store') }}">
                @csrf
                <div class="ag-row">
                    <div class="ag-field">
                        <label>Country *</label>
                        <select name="agent_country_id" required>
                            <option value="">Select</option>
                            @foreach($countries as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="ag-field"><label>District / City *</label><input type="text" name="name" required></div>
                </div>
                <button class="ag-btn"><i class="fa-solid fa-plus"></i> Add District</button>
            </form>
        </div>
    </div>

    @forelse($countries as $country)
        <div class="ag-panel">
            <form method="POST" action="{{ route('super-admin.agent-locations.countries.update', $country) }}" class="ag-filter" style="margin-bottom:16px;">
                @csrf @method('PUT')
                <div class="ag-field"><label>Country</label><input type="text" name="name" value="{{ $country->name }}" required></div>
                <div class="ag-field" style="max-width:100px;"><label>Code</label><input type="text" name="code" value="{{ $country->code }}" maxlength="3"></div>
                <label class="ag-check" style="padding-bottom:11px;"><input type="checkbox" name="is_active" value="1" @checked($country->is_active)> Active</label>
                <button class="ag-btn sm">Save</button>
                <button type="submit" form="delc-{{ $country->id }}" class="ag-btn danger sm">Delete</button>
            </form>
            <form id="delc-{{ $country->id }}" method="POST" action="{{ route('super-admin.agent-locations.countries.destroy', $country) }}" onsubmit="return confirm('Delete this country and its districts?')" hidden>
                @csrf @method('DELETE')
            </form>

            @if($country->districts->count())
                <div class="ag-table-wrap">
                    <table class="ag-table" style="min-width:560px;">
                        <thead><tr><th>District / City</th><th>Agents</th><th>Active</th><th></th></tr></thead>
                        <tbody>
                        @foreach($country->districts as $d)
                            <tr>
                                <td>
                                    <input form="ed-{{ $d->id }}" type="text" name="name" value="{{ $d->name }}" class="ag-input" required>
                                </td>
                                <td>{{ $d->locations_count }}</td>
                                <td><input form="ed-{{ $d->id }}" type="checkbox" name="is_active" value="1" @checked($d->is_active)></td>
                                <td class="num">
                                    <button form="ed-{{ $d->id }}" class="ag-btn ghost sm">Save</button>
                                    <button form="dd-{{ $d->id }}" class="ag-btn danger sm" onclick="return confirm('Delete this district?')">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @foreach($country->districts as $d)
                    <form id="ed-{{ $d->id }}" method="POST" action="{{ route('super-admin.agent-locations.districts.update', $d) }}" hidden>@csrf @method('PUT')</form>
                    <form id="dd-{{ $d->id }}" method="POST" action="{{ route('super-admin.agent-locations.districts.destroy', $d) }}" hidden>@csrf @method('DELETE')</form>
                @endforeach
            @else
                <div class="ag-empty" style="padding:20px;">No districts in {{ $country->name }} yet.</div>
            @endif
        </div>
    @empty
        <div class="ag-panel"><div class="ag-empty"><i class="fa-solid fa-earth-asia"></i>Add your first country to begin.</div></div>
    @endforelse
</div>
@endsection