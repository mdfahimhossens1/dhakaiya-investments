@extends('layouts.app')
@section('title', 'Local Agents')
@section('content')

<div class="page-heading">
    <div><h2>Local Agents</h2><p>Find an agent in your area and request local research.</p></div>
    <a href="{{ route('user.research.index') }}" class="ag-btn ghost"><i class="fa-solid fa-file-lines"></i> My Research Requests</a>
</div>

@include('partials.agent-styles')
@include('partials.agent-flash')

<div class="ag-wrap">

<div class="ag-notice warn">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <strong>Agents are not a payment gateway.</strong>
    They provide local research services. Pay an agent only after you accept their quote on a research request.
    That fee is <u>not</u> an investment deposit and is never added to your wallet. To deposit, use the Deposit page.
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

@php
    $searched   = $searched   ?? false;
    $countryId  = $countryId  ?? 0;
    $districtId = $districtId ?? 0;
    $agents     = $agents     ?? null;
    $threads    = $threads    ?? collect();
    $threadAgents = $threadAgents ?? collect();
    $geo        = $geo        ?? [];
@endphp

    @if($searched)
        @if($agents->count())
            <div class="ag-grid ag-g3">
                @foreach($agents as $a)
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
                        <div class="ag-actions">
                            <a class="ag-btn sm" href="{{ route('user.agents.messages', $a) }}"><i class="fa-solid fa-comments"></i> Contact Agent</a>
                            @if($a->is_available)
                                <a class="ag-btn ghost sm" href="{{ route('user.research.create', $a) }}">Research Request</a>
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

    @if($threads->count())
        <div class="ag-panel">
            <div class="ag-head"><div><h3>Your Conversations</h3></div></div>
            @foreach($threads as $t)
                @php $ag = $threadAgents[$t->agent_id] ?? null; @endphp
                @if($ag)
                    <a href="{{ route('user.agents.messages', $ag) }}" style="display:flex;justify-content:space-between;align-items:center;padding:12px 4px;border-bottom:1px solid #f1f5f9;text-decoration:none;color:var(--text);">
                        <span class="ag-person"><span class="ag-avatar">{{ strtoupper(mb_substr($ag->user->name, 0, 1)) }}</span><strong>{{ $ag->user->name }}</strong></span>
                        @if($t->unread)<span class="ag-badge green">{{ $t->unread }} new</span>@endif
                    </a>
                @endif
            @endforeach
        </div>
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