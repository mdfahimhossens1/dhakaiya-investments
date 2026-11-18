@extends('layouts.app')
@section('title', 'My Profile')
@section('content')

<div class="page-heading">
    <div><h2>My Profile</h2><p>Contact details, assigned areas and payment methods.</p></div>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">

    <div class="ag-grid ag-g2">
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Profile</h3><p>Email: {{ $agent->user->email }}</p></div></div>
            <form method="POST" action="{{ route('agent.profile.update') }}">
                @csrf @method('PUT')
                <div class="ag-field"><label>Name *</label><input type="text" name="name" value="{{ old('name', $agent->user->name) }}" required></div>
                <div class="ag-row">
                    <div class="ag-field"><label>Phone *</label><input type="text" name="phone" value="{{ old('phone', $agent->phone) }}" required></div>
                    <div class="ag-field"><label>WhatsApp</label><input type="text" name="whatsapp" value="{{ old('whatsapp', $agent->whatsapp) }}"></div>
                </div>
                <div class="ag-field"><label>Bio</label><textarea name="bio" maxlength="1000">{{ old('bio', $agent->bio) }}</textarea></div>
                <button class="ag-btn">Save Profile</button>
            </form>
        </div>

        <div class="ag-panel">
            <div class="ag-head">
                <div><h3>Availability &amp; Areas</h3><p>Areas are assigned by the admin.</p></div>
                <form method="POST" action="{{ route('agent.availability') }}">@csrf @method('PATCH')
                    <button class="ag-btn sm {{ $agent->is_available ? 'ghost' : '' }}">{{ $agent->is_available ? 'Go unavailable' : 'Go available' }}</button>
                </form>
            </div>
            <p>
                <span class="ag-badge {{ $agent->is_verified ? 'blue' : 'gray' }}">{{ $agent->is_verified ? 'Verified' : 'Not verified' }}</span>
                <span class="ag-badge {{ $agent->is_available ? 'teal' : 'gray' }}">{{ $agent->is_available ? 'Available' : 'Unavailable' }}</span>
            </p>
            @forelse($agent->districts->groupBy(fn ($d) => $d->country->name) as $country => $list)
                <p style="margin:0 0 8px;font-size:13px;"><strong>{{ $country }}:</strong> {{ $list->pluck('name')->implode(', ') }}</p>
            @empty
                <div class="ag-empty" style="padding:20px;">No areas assigned yet.</div>
            @endforelse
        </div>
    </div>

    <div class="ag-panel">
        <div class="ag-head">
            <div><h3>Payment Methods</h3><p>Shown to a user only after they accept your quote. Use these for the <strong>research fee</strong> only.</p></div>
        </div>

        @if($agent->paymentMethods->count())
            <div class="ag-table-wrap" style="margin-bottom:20px;">
                <table class="ag-table" style="min-width:560px;">
                    <thead><tr><th>Method</th><th>Account</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @foreach($agent->paymentMethods as $pm)
                        <tr>
                            <td>{{ $pm->label() }}@if($pm->bank_name)<small>{{ $pm->bank_name }}</small>@endif</td>
                            <td>{{ $pm->account_number }}<small>{{ $pm->account_name }}</small></td>
                            <td><span class="ag-badge {{ $pm->is_active ? 'green' : 'gray' }}">{{ $pm->is_active ? 'Active' : 'Off' }}</span></td>
                            <td class="num">
                                <form method="POST" action="{{ route('agent.payment-methods.toggle', $pm) }}" style="display:inline;">@csrf @method('PATCH')
                                    <button class="ag-btn ghost sm">{{ $pm->is_active ? 'Turn off' : 'Turn on' }}</button>
                                </form>
                                <form method="POST" action="{{ route('agent.payment-methods.destroy', $pm) }}" style="display:inline;" onsubmit="return confirm('Remove this method?')">@csrf @method('DELETE')
                                    <button class="ag-btn danger sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <form method="POST" action="{{ route('agent.payment-methods.store') }}">
            @csrf
            <div class="ag-row3">
                <div class="ag-field">
                    <label>Method *</label>
                    <select name="method" required>
                        @foreach(\App\Models\AgentPaymentMethod::METHODS as $k => $label)<option value="{{ $k }}" @selected(old('method') === $k)>{{ $label }}</option>@endforeach
                    </select>
                </div>
                <div class="ag-field"><label>Account name *</label><input type="text" name="account_name" value="{{ old('account_name') }}" required></div>
                <div class="ag-field"><label>Account number *</label><input type="text" name="account_number" value="{{ old('account_number') }}" required></div>
            </div>
            <div class="ag-row">
                <div class="ag-field"><label>Bank name (for bank transfer)</label><input type="text" name="bank_name" value="{{ old('bank_name') }}"></div>
                <div class="ag-field"><label>Note</label><input type="text" name="note" value="{{ old('note') }}" maxlength="255"></div>
            </div>
            <button class="ag-btn"><i class="fa-solid fa-plus"></i> Add Payment Method</button>
        </form>
    </div>
</div>
@endsection