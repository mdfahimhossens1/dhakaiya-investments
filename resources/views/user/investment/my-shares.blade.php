@extends('layouts.app')

@section('title', 'My Shares')

@section('content')

@include('partials.styles')

<div class="inv-page">

    {{-- Heading --}}
    <div class="inv-heading">
        <div>
            <h2>My Shares</h2>
            <p>View your holdings and resell shares.</p>
        </div>

        <span class="inv-badge"><i class="fas fa-chart-pie"></i> My Portfolio</span>
    </div>

    @if(session('success'))
        <div class="inv-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="inv-alert error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="inv-alert error">
            <strong>Please check the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    @php
        $profitLoss = bcsub((string) $summary['current_value_bdt'], (string) $summary['total_invested_bdt'], 2);
        $isProfit = bccomp($profitLoss, '0', 2) >= 0;
    @endphp

    {{-- Summary --}}
    <div class="inv-summary">

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-layer-group"></i></div>
            <div class="label">Total Shares</div>
            <h3>{{ number_format($summary['total_shares']) }}</h3>
            <small>{{ number_format($summary['locked_shares']) }} locked</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <div class="label">Active Holdings</div>
            <h3>{{ number_format($summary['active_records']) }}</h3>
            <small>{{ number_format($summary['completed_records']) }} fully sold</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-coins"></i></div>
            <div class="label">Total Invested (BDT)</div>
            <h3>{{ number_format((float) $summary['total_invested_bdt'], 2) }}</h3>
            <small>Across active holdings</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <div class="label">Current Value (BDT)</div>
            <h3>{{ number_format((float) $summary['current_value_bdt'], 2) }}</h3>
            <small class="{{ $isProfit ? 'pl-pos' : 'pl-neg' }}">
                {{ $isProfit ? '+' : '' }}{{ number_format((float) $profitLoss, 2) }} BDT
            </small>
        </div>

    </div>


    {{-- Holdings --}}
    <div class="inv-panel">

        <div class="inv-panel-header">
            <h4>My Share Holdings</h4>
            <p>All projects where you hold or have held shares.</p>
        </div>

        <form method="GET" action="{{ route('user.my-shares.index') }}" class="inv-filter">

            <select name="status">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>

            <button type="submit" class="inv-btn primary"><i class="fas fa-filter"></i> Apply Filter</button>

            <a href="{{ route('user.my-shares.index') }}" class="inv-btn outline">Reset</a>

        </form>

        <div class="inv-table-wrap">

            <table class="inv-table" style="min-width:950px;">

                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Shares</th>
                        <th>Locked</th>
                        <th>Sellable</th>
                        <th>Invested (BDT)</th>
                        <th>Current Value (BDT)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($shares as $share)

                        @php
                            $sellable = max(0, (int) $share->shares - (int) $share->locked_shares);
                            $isActive = $share->shares > 0;
                        @endphp

                        <tr>
                            <td>
                                @if($share->project)
                                    <a href="{{ route('user.project-details.show', $share->project->slug) }}"
                                       style="color:var(--text); font-weight:600; text-decoration:none;">
                                        {{ $share->project->name }}
                                    </a>
                                    <small>{{ $share->project->company->name ?? 'N/A' }}</small>
                                @else
                                    <span style="color:var(--muted);">Project removed</span>
                                @endif
                            </td>

                            <td><strong>{{ number_format($share->shares) }}</strong></td>
                            <td>{{ number_format($share->locked_shares) }}</td>
                            <td>{{ number_format($sellable) }}</td>
                            <td>{{ number_format((float) $share->invested_bdt, 2) }}</td>
                            <td>{{ number_format((float) $share->current_value, 2) }}</td>

                            <td>
                                <span class="st-badge {{ $isActive ? 'active' : 'completed' }}">
                                    {{ $isActive ? 'Active' : 'Completed' }}
                                </span>
                            </td>

                            <td>
                                @if($isActive && $sellable > 0 && $share->project && $walletOptions->count() > 0)
                                    <button type="button" class="inv-btn outline small"
                                            onclick="openSellModal(this)"
                                            data-action="{{ route('user.my-shares.sell', $share->project_id) }}"
                                            data-name="{{ $share->project->name }}"
                                            data-max="{{ $sellable }}">
                                        <i class="fas fa-hand-holding-usd"></i> Resell
                                    </button>
                                @else
                                    <span style="color:var(--muted);">—</span>
                                @endif
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8">
                                <div class="inv-empty">
                                    <div class="icon"><i class="fas fa-folder-open"></i></div>
                                    <h4>No Share Holdings Found</h4>
                                    <p>You don't own any shares yet. Browse the available projects to get started.</p>
                                    <a href="{{ route('user.available-projects.index') }}" class="inv-btn primary">
                                        <i class="fas fa-search"></i> Browse Available Projects
                                    </a>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($shares->hasPages())
            <div class="inv-pagination">{{ $shares->links() }}</div>
        @endif

    </div>


    {{-- Notice --}}
    <div class="inv-notice">
        <i class="fas fa-shield-alt"></i>
        <div>
            <h5>Important Information</h5>
            <p>
                Locked shares cannot be resold. Resale proceeds are credited to the wallet
                you select, at the current exchange rate. Current value is an estimate and
                may change.
            </p>
        </div>
    </div>

</div>


{{-- Resell Modal --}}
<div class="inv-modal" id="sellModal">
    <div class="inv-modal-box">

        <div class="inv-modal-head">
            <h3>Resell Shares</h3>
            <button type="button" class="inv-modal-close" onclick="closeSellModal()">&times;</button>
        </div>

        <form method="POST" id="sellForm" onsubmit="return confirm('Confirm resale?')">
            @csrf

            <div class="inv-modal-body">

                <p style="font-size:13px; color:var(--text); margin:0 0 16px;">
                    Project: <strong id="sellProjectName"></strong>
                </p>

                <div class="inv-field">
                    <label>Number of Shares</label>
                    <input type="number" name="shares" id="sellShares" min="1" required>
                    <small class="inv-help" id="sellMaxHelp"></small>
                </div>

                <div class="inv-field">
                    <label>Receive In (Wallet)</label>
                    <select name="currency" required>
                        @foreach($walletOptions as $w)
                            <option value="{{ $w['currency'] }}">
                                {{ $w['currency'] }} — Balance: {{ number_format((float) $w['balance'], 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="inv-modal-foot">
                <button type="button" class="inv-btn outline" onclick="closeSellModal()">Cancel</button>
                <button type="submit" class="inv-btn primary">Confirm Resale</button>
            </div>
        </form>

    </div>
</div>

<script>
    function openSellModal(btn) {
        const modal = document.getElementById('sellModal');
        const max = btn.dataset.max;

        document.getElementById('sellForm').action = btn.dataset.action;
        document.getElementById('sellProjectName').textContent = btn.dataset.name;

        const input = document.getElementById('sellShares');
        input.max = max;
        input.value = 1;

        document.getElementById('sellMaxHelp').textContent = 'Maximum sellable: ' + Number(max).toLocaleString();

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSellModal() {
        document.getElementById('sellModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.getElementById('sellModal').addEventListener('click', function (e) {
        if (e.target === this) closeSellModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSellModal();
    });
</script>

@endsection