@include('partials.agent-styles')

@php
    $localAgents = $localAgents
        ?? app(\App\Http\Controllers\User\LocalAgentController::class)->forDeposit(auth()->user());
@endphp

<div class="ag-panel" style="margin-bottom:20px;">
    <div class="ag-head" style="margin-bottom:10px;">
        <div>
            <h3><i class="fa-solid fa-location-dot"></i> Deposit via Local Agent</h3>
            <p>Pay a local agent and they will recharge your wallet. Give them your User ID: <strong>#{{ auth()->id() }}</strong></p>
        </div>
        <a href="{{ route('user.agents.index') }}" class="ag-btn ghost sm">Browse all agents</a>
    </div>

    @if($localAgents->count())
        <div class="ag-grid ag-g3">
            @foreach($localAgents as $a)
                @php $wa = preg_replace('/\D/', '', (string) ($a->whatsapp ?: $a->phone)); @endphp
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
                        @if($a->phone)<a class="ag-btn sm" href="tel:{{ $a->phone }}"><i class="fa-solid fa-phone"></i> Call</a>@endif
                        @if($wa)<a class="ag-btn ghost sm" target="_blank" rel="noopener" href="https://wa.me/{{ $wa }}"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>@endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="ag-empty" style="padding:20px;">
            <i class="fa-solid fa-user-slash"></i>
            No agent is available in your area right now.
        </div>
    @endif

    <div class="ag-notice warn" style="margin:14px 0 0;">
        <strong>Stay safe.</strong>
        Only give the agent your User ID, never your password or OTP. Check your wallet to confirm the money arrived.
    </div>
</div>