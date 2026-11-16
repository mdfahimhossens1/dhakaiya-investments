@extends('layouts.app')

@section('title', 'Deposit Requests')

@section('content')

@include('user.investment._styles')

@php
    $methodOptions = \App\Models\PaymentMethod::ordered()->pluck('name', 'code');
    $currencyOptions = \App\Models\DepositRequest::query()->distinct()->orderBy('currency')->pluck('currency');
@endphp

<style>
    .f-input { padding:10px 13px; border:1px solid var(--border); border-radius:8px; font-size:13px; outline:none; background:#fff; }
    .inv-field textarea { width:100%; padding:11px 12px; border:1px solid var(--border); border-radius:8px; font-size:13px; outline:none; box-sizing:border-box; resize:vertical; }
    .chips { display:flex; flex-wrap:wrap; gap:6px; margin-top:4px; }
    .chip { background:#e8f5ef; color:var(--primary); padding:4px 9px; border-radius:20px; font-size:11px; font-weight:600; }
    .row-actions { display:flex; gap:6px; flex-wrap:wrap; }
    .btn-ok { background:#dcfce7; color:#15803d; border-color:#bbf7d0; }
    .btn-no { background:#fee2e2; color:#b91c1c; border-color:#fecaca; }
    .modal-info { background:#f8fafb; border:1px solid var(--border); border-radius:8px; padding:12px 14px; font-size:12px; color:var(--text); line-height:1.8; margin-bottom:16px; }
</style>

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Deposit Requests</h2>
            <p>Review user deposits, approve to credit their wallet, or reject.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-arrow-down"></i> {{ $requests->total() }} request(s)</span>
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
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Stats --}}
    <div class="inv-summary">

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-clock"></i></div>
            <div class="label">Pending Requests</div>
            <h3>{{ number_format($pendingCount) }}</h3>
            <small>Awaiting review</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="label">Pending Amount</div>
            <div class="chips">
                @forelse($pendingByCurrency as $cur => $total)
                    <span class="chip">{{ $cur }} {{ number_format((float) $total, 2) }}</span>
                @empty
                    <span style="color:var(--muted); font-size:12px;">—</span>
                @endforelse
            </div>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <div class="label">Approved Today</div>
            <div class="chips">
                @forelse($approvedTodayByCurrency as $cur => $total)
                    <span class="chip">{{ $cur }} {{ number_format((float) $total, 2) }}</span>
                @empty
                    <span style="color:var(--muted); font-size:12px;">—</span>
                @endforelse
            </div>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <div class="label">Rejected (All Time)</div>
            <h3>{{ number_format($rejectedCount) }}</h3>
            <small>Rejected requests</small>
        </div>

    </div>

    {{-- Table --}}
    <div class="inv-panel">

        <form method="GET" action="{{ route('super-admin.deposit-requests.index') }}" class="inv-filter">
            <input type="text" name="search" value="{{ request('search') }}" class="f-input" style="min-width:230px;"
                   placeholder="TrxID, sender number, user name or phone...">

            <select name="status">
                <option value="">All Status</option>
                @foreach(['pending', 'approved', 'rejected'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>

            <select name="currency">
                <option value="">All Currencies</option>
                @foreach($currencyOptions as $c)
                    <option value="{{ $c }}" {{ strtoupper((string) request('currency')) === $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <select name="method">
                <option value="">All Methods</option>
                @foreach($methodOptions as $code => $name)
                    <option value="{{ $code }}" {{ request('method') === $code ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>

            <button type="submit" class="inv-btn primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('super-admin.deposit-requests.index') }}" class="inv-btn outline">Reset</a>
        </form>

        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:1150px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Sender / TrxID</th>
                        <th>Screenshot</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $r)
                        @php $isPending = $r->status === 'pending'; @endphp
                        <tr>
                            <td>#{{ $r->id }}</td>

                            <td>
                                <strong>{{ $r->user->name ?? '—' }}</strong>
                                <small>{{ $r->user->phone ?? '' }}</small>
                            </td>

                            <td>{{ strtoupper((string) $r->method) ?: '—' }}</td>

                            <td>
                                <strong>{{ number_format((float) $r->amount, 2) }} {{ $r->currency }}</strong>
                                @if($r->status === 'approved' && $r->approved_amount !== null)
                                    <small>Credited: {{ number_format((float) $r->approved_amount, 2) }}</small>
                                @endif
                            </td>

                            <td>
                                {{ $r->sender_number ?: '—' }}
                                <small>{{ $r->transaction_id ?: '' }}</small>
                            </td>

                            <td>
                                @if($r->screenshot)
                                    <a href="{{ route('super-admin.deposit-requests.screenshot', $r) }}" target="_blank"
                                       class="inv-btn outline small"><i class="fas fa-image"></i> View</a>
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                {{ $r->created_at->format('d M Y') }}
                                <small>{{ $r->created_at->format('h:i A') }}</small>
                            </td>

                            <td>
                                <span class="st-badge {{ $r->status }}">{{ ucfirst($r->status) }}</span>
                                @if(! $isPending)
                                    <small>
                                        by {{ $r->reviewer->name ?? '—' }}
                                        @if($r->reviewed_at) · {{ $r->reviewed_at->format('d M, h:i A') }} @endif
                                    </small>
                                    @if($r->admin_note)<small>“{{ $r->admin_note }}”</small>@endif
                                @endif
                            </td>

                            <td>
                                @if($isPending)
                                    <div class="row-actions">
                                        <button type="button" class="inv-btn small btn-ok"
                                                onclick="openApprove(this)"
                                                data-action="{{ route('super-admin.deposit-requests.approve', $r) }}"
                                                data-id="{{ $r->id }}"
                                                data-user="{{ $r->user->name ?? '—' }}"
                                                data-method="{{ strtoupper((string) $r->method) }}"
                                                data-trx="{{ $r->transaction_id }}"
                                                data-currency="{{ $r->currency }}"
                                                data-amount="{{ number_format((float) $r->amount, 2, '.', '') }}">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="inv-btn small btn-no"
                                                onclick="openReject(this)"
                                                data-action="{{ route('super-admin.deposit-requests.reject', $r) }}"
                                                data-id="{{ $r->id }}"
                                                data-user="{{ $r->user->name ?? '—' }}"
                                                data-amount="{{ number_format((float) $r->amount, 2) }} {{ $r->currency }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                @else
                                    <span style="color:var(--muted);">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="inv-empty">
                                    <div class="icon"><i class="fas fa-inbox"></i></div>
                                    <h4>No Deposit Requests Found</h4>
                                    <p>No deposit request matches your filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="inv-pagination">{{ $requests->links() }}</div>
        @endif
    </div>

</div>


{{-- Approve Modal --}}
<div class="inv-modal" id="approveModal">
    <div class="inv-modal-box">
        <div class="inv-modal-head">
            <h3>Approve Deposit</h3>
            <button type="button" class="inv-modal-close" onclick="closeModal('approveModal')">&times;</button>
        </div>

        <form method="POST" id="approveForm">
            @csrf
            @method('PATCH')

            <div class="inv-modal-body">
                <div class="modal-info" id="approveInfo"></div>

                <div class="inv-field">
                    <label>Amount to Credit (<span id="approveCurrency"></span>)</label>
                    <input type="number" name="approved_amount" id="approveAmount" step="0.01" min="0.01">
                    <small class="inv-help">Requested amount is pre-filled. Change it only if the amount actually received is different.</small>
                </div>

                <div class="inv-field">
                    <label>Admin Note (optional)</label>
                    <textarea name="admin_note" rows="3" maxlength="500"></textarea>
                </div>
            </div>

            <div class="inv-modal-foot">
                <button type="button" class="inv-btn outline" onclick="closeModal('approveModal')">Cancel</button>
                <button type="submit" class="inv-btn primary"><i class="fas fa-check"></i> Approve &amp; Credit Wallet</button>
            </div>
        </form>
    </div>
</div>

{{-- Reject Modal --}}
<div class="inv-modal" id="rejectModal">
    <div class="inv-modal-box">
        <div class="inv-modal-head">
            <h3>Reject Deposit</h3>
            <button type="button" class="inv-modal-close" onclick="closeModal('rejectModal')">&times;</button>
        </div>

        <form method="POST" id="rejectForm">
            @csrf
            @method('PATCH')

            <div class="inv-modal-body">
                <div class="modal-info" id="rejectInfo"></div>

                <div class="inv-field">
                    <label>Reason for Rejection *</label>
                    <textarea name="admin_note" rows="3" maxlength="500" required></textarea>
                </div>
            </div>

            <div class="inv-modal-foot">
                <button type="button" class="inv-btn outline" onclick="closeModal('rejectModal')">Cancel</button>
                <button type="submit" class="inv-btn primary" style="background:#dc2626;"><i class="fas fa-times"></i> Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = '';
    }

    function openApprove(btn) {
        const d = btn.dataset;

        document.getElementById('approveForm').action = d.action;
        document.getElementById('approveAmount').value = d.amount;
        document.getElementById('approveCurrency').textContent = d.currency;
        document.getElementById('approveInfo').innerHTML =
            'Request <strong>#' + d.id + '</strong> · ' + d.user + '<br>' +
            'Method: ' + (d.method || '—') + (d.trx ? ' · TrxID: ' + d.trx : '') + '<br>' +
            'Requested: <strong>' + d.amount + ' ' + d.currency + '</strong>';

        openModal('approveModal');
    }

    function openReject(btn) {
        const d = btn.dataset;

        document.getElementById('rejectForm').action = d.action;
        document.getElementById('rejectInfo').innerHTML =
            'Request <strong>#' + d.id + '</strong> · ' + d.user + '<br>Amount: <strong>' + d.amount + '</strong>';

        openModal('rejectModal');
    }

    document.querySelectorAll('.inv-modal').forEach(function (m) {
        m.addEventListener('click', function (e) { if (e.target === m) closeModal(m.id); });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') document.querySelectorAll('.inv-modal.active').forEach(m => closeModal(m.id));
    });
</script>

@endsection