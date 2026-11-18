@include('partials.agent-styles')

@php
    $localAgents = $localAgents
        ?? app(\App\Http\Controllers\User\LocalAgentController::class)->forDeposit(auth()->user());
@endphp

<div class="ag-panel" style="margin-bottom:20px;">
    <div class="ag-head" style="margin-bottom:10px;">
        <div>
            <h3><i class="fa-solid fa-location-dot"></i> Local Agents</h3>
            <p>Apnar ekaka-r agent-er sathe local research-er jonno jogajog korun.</p>
        </div>
        <a href="{{ route('user.agents.index') }}" class="ag-btn ghost sm">Browse all agents</a>
    </div>

    @if($localAgents->count())
        <div class="ag-grid ag-g3">
            @foreach($localAgents as $a)
                <div class="ag-card ag-agent-card">
                    <div class="ag-person">
                        <div class="ag-avatar">{{ strtoupper(mb_substr($a->user->name ?? 'A', 0, 1)) }}</div>
                        <div>
                            <strong>{{ $a->user->name ?? 'Agent' }}</strong>
                            <span class="ag-badge blue">Verified</span>
                            @if($a->is_available)<span class="ag-badge teal">Available</span>@endif
                        </div>
                    </div>
                    <p><i class="fa-solid fa-location-dot"></i>
                        {{ $a->districts->take(3)->pluck('name')->implode(', ') }}
                        @if($a->districts->count() > 3) +{{ $a->districts->count() - 3 }} @endif
                    </p>
                    <div class="ag-actions">
                        <a class="ag-btn sm" href="{{ route('user.agents.messages', $a) }}"><i class="fa-solid fa-comments"></i> Message</a>
                        @if($a->is_available)
                            <a class="ag-btn ghost sm" href="{{ route('user.research.create', $a) }}">Research Request</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="ag-empty" style="padding:20px;">
            <i class="fa-solid fa-user-slash"></i>
            Apnar deshe ekhon kono agent nei.
        </div>
    @endif

    <div class="ag-notice warn" style="margin:14px 0 0;">
        <strong>Agent payment gateway na.</strong>
        Agent-ke dewa research fee apnar wallet-e deposit hoy na. Wallet-e taka add korte upore-r Online Payment option-gulo bebohar korun.
    </div>
</div>