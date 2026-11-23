@extends('layouts.app')
@section('title', 'Recharge Agents')
@section('content')

@php
    $searched   = $searched   ?? false;
    $countryId  = $countryId  ?? 0;
    $districtId = $districtId ?? 0;
    $agents     = $agents     ?? null;
    $geo        = $geo        ?? [];
@endphp

<div class="page-heading">
    <div><h2>Recharge Agents</h2><p>Find a local agent who can add money to your wallet.</p></div>
    <a href="{{ route('user.deposit') }}" class="ag-btn ghost"><i class="fa-solid fa-wallet"></i> Other Deposit Methods</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">

    <div class="ag-notice">
        <i class="fa-solid fa-circle-info"></i>
        <div>
            <strong>How it works:</strong>
            1) Contact an agent below. 2) Pay the agent using one of their payment methods.
            3) Give the agent your <strong>User ID: #{{ auth()->id() }}</strong> and they will recharge your wallet.
            Check your wallet to confirm the money arrived.
        </div>
    </div>

    <div class="ag-notice warn">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div>
            <strong>Stay safe.</strong> Only give an agent your User ID. Never share your password, PIN or OTP.
            An agent can only add money to your wallet; they cannot see or use your account.
        </div>
    </div>

    <div class="ag-panel">
        <form method="GET" class="ag-filter">
            <div class="ag-field"><label>Country</label>
                <select name="country" id="countrySel" required></select>
            </div>
            <div class="ag-field"><label>District / City</label>
                <select name="district" id="districtSel"></select>
            </div>
            <button class="ag-btn"><i class="fa-solid fa-magnifying-glass"></i> Find Agents</button>
        </form>
    </div>

    @if($searched)
        @if($agents->count())
            <div class="ag-grid ag-g3">
                @foreach($agents as $a)
                    @php
                        $wa = preg_replace('/\D/', '', (string) ($a->whatsapp ?: $a->phone));
                    @endphp
                    <div class="ag-card ag-agent-card">
                        <div class="ag-person">
                            <div class="ag-avatar">{{ strtoupper(mb_substr($a->user->name, 0, 1)) }}</div>
                            <div>
                                <strong>{{ $a->user->name }}</strong>
                                <span class="ag-badge blue">Verified</span>
                                @if($a->is_available)
                                    <span class="ag-badge teal">Available</span>
                                @else
                                    <span class="ag-badge gray">Busy</span>
                                @endif
                            </div>
                        </div>

                        <p><i class="fa-solid fa-location-dot"></i>
                            {{ $a->districts->take(4)->pluck('name')->implode(', ') }}
                            @if($a->districts->count() > 4) +{{ $a->districts->count() - 4 }} @endif
                            · {{ $a->districts->first()?->country?->name }}
                        </p>

                        @if($a->bio)<p>{{ \Illuminate\Support\Str::limit($a->bio, 110) }}</p>@endif

                        @if($a->paymentMethods->count())
                            <div style="margin:10px 0;font-size:12.5px;">
                                <strong>Pay via</strong>
                                @foreach($a->paymentMethods as $pm)
                                    @php
                                        $label = \App\Models\AgentPaymentMethod::METHODS[$pm->method] ?? $pm->method;
                                        if (is_array($label)) { $label = $label['label'] ?? $pm->method; }
                                    @endphp
                                    <div style="padding:6px 0;border-bottom:1px solid #f1f5f9;">
                                        {{ $label }}: <strong>{{ $pm->account_number }}</strong>
                                        <small style="display:block;color:var(--muted);">{{ $pm->account_name }}@if($pm->bank_name) · {{ $pm->bank_name }}@endif</small>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="ag-actions">
                            @if($a->phone)
                                <a class="ag-btn sm" href="tel:{{ $a->phone }}"><i class="fa-solid fa-phone"></i> Call</a>
                            @endif
                            @if($wa)
                                <a class="ag-btn ghost sm" target="_blank" rel="noopener" href="https://wa.me/{{ $wa }}"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div style="margin-top:16px;">{{ $agents->links() }}</div>
        @else
            <div class="ag-panel"><div class="ag-empty"><i class="fa-solid fa-user-slash"></i>No agents found in this area right now.</div></div>
        @endif
    @endif
</div>

<script>
    (function () {
        const geo = @json($geo);
        const selCountry = {{ (int) $countryId }}, selDistrict = {{ (int) $districtId }};
        const c = document.getElementById('countrySel'), d = document.getElementById('districtSel');

        c.innerHTML = '<option value="">Select country</option>' +
            geo.map(x => `<option value="${x.id}" ${x.id === selCountry ? 'selected' : ''}>${x.name}</option>`).join('');

        function fillDistricts() {
            const country = geo.find(x => x.id === parseInt(c.value));
            d.innerHTML = '<option value="">All districts</option>' +
                (country ? country.districts.map(x => `<option value="${x.id}" ${x.id === selDistrict ? 'selected' : ''}>${x.name}</option>`).join('') : '');
        }

        c.addEventListener('change', fillDistricts);
        fillDistricts();
    })();
</script>
@endsection