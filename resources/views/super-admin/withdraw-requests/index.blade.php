@extends('layouts.app')

@section('title', 'Withdraw Requests')

@section('content')

@include('user.investment._styles')

@php
    $methodOptions = \App\Models\PaymentMethod::ordered()->pluck('name', 'code');
    $currencyOptions = \App\Models\WithdrawRequest::query()->distinct()->orderBy('currency')->pluck('currency');
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
            <h2>Withdraw Requests</h2>
            <p>Send the money manually, then approve. Rejecting refunds the user's wallet.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-arrow-up"></i> {{ $requests->total() }} request(s)</span>
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
            <small>Awaiting payout</small>
        </div>

        <div class="inv-stat">
            <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
            <div class="label">Pending Payout (Net)</div>
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
            <div class="label">Paid Today (Net)</div>
            <div class="chips">
                @forelse($paidTodayByCurrency as $cur => $total)
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
            <small>Refunded to wallets</small>
        </div>

    </div>

    {{-- Table --}}
    <div class="inv-panel">

        <form method="GET" action="{{ route('super-admin.withdraw-requests.index') }}" class="inv-filter">
            <input type="text" name="search" value="{{ request('search') }}" class="f-input" style="min-width:230px;"
                   placeholder="Account number, user name or phone...">

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
            <a href="{{ route('super-admin.withdraw-requests.index') }}" class="inv-btn outline">Reset</a>
        </form>

        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:1250px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Send To</th>
                        <th>Amount</th>
                        <th>Fee</th>
                        <th>Net Payable</th>
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

                            <td>
                                <strong>{{ strtoupper((string) $r->method) ?: '—' }}</strong>
                                {{ $r->account_number ?: '—' }}
                                <small>
                                    {{ $r->account_name }}
                                    @if($r->bank_name) · {{ $r->bank_name }} @endif
                                    @if($r->branch_name) · {{ $r->branch_name }} @endif
                                </small>
                            </td>

                            <td>{{ number_format((float) $r->amount, 2) }} {{ $r->currency }}</td>
                            <td>{{ number_format((float) $r->fee, 2) }}</td>
                            <td><strong>{{ number_format((float) $r->net_amount, 2) }} {{ $r->currency }}</strong></td>

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
                                    @if($r->payout_reference)<small>Ref: {{ $r->payout_reference }}</small>@endif
                                    @if($r->admin_note)<small>“{{ $r->admin_note }}”</small>@endif
                                @endif
                            </td>

                            <td>
                                @if($isPending)
                                    <div class="row-actions">
                                        <button type="button" class="inv-btn small btn-ok"
                                                onclick="openApprove(this)"
                                                data-action="{{ route('super-admin.withdraw-requests.approve', $r) }}"
                                                data-id="{{ $r->id }}"
                                                data-user="{{ $r->user->name ?? '—' }}"
                                                data-method="{{ strtoupper((string) $r->method) }}"
                                                data-account="{{ $r->account_number }}"
                                                data-name="{{ $r->account_name }}"
                                                data-net="{{ number_format((float) $r->net_amount, 2) }} {{ $r->currency }}">
                                            <i class="fas fa-check"></i> Mark Paid
                                        </button>
                                        <button type="button" class="inv-btn small btn-no"
                                                onclick="openReject(this)"
                                                data-action="{{ route('super-admin.withdraw-requests.reject', $r) }}"
                                                data-id="{{ $r->id }}"
                                                data-user="{{ $r->user->name ?? '—' }}"
                                                data-amount="{{ number_format((float) $r->amount, 2) }} {{ $r->currency }}">
                                            <i class="fas fa-undo"></i> Reject
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
                                    <h4>No Withdraw Requests Found</h4>
                                    <p>No withdraw request matches your filters.</p>
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


{{-- Approve (Mark Paid) Modal --}}
<div class="inv-modal" id="approveModal">
    <div class="inv-modal-box">
        <div class="inv-modal-head">
            <h3>Approve Withdraw</h3>
            <button type="button" class="inv-modal-close" onclick="closeModal('approveModal')">&times;</button>
        </div>

        <form method="POST" id="approveForm">
            @csrf
            @method('PATCH')

            <div class="inv-modal-body">
                <div class="modal-info" id="approveInfo"></div>

                <div class="inv-field">
                    <label>Payout Reference / TrxID *</label>
                    <input type="text" name="payout_reference" maxlength="100" required placeholder="Transaction ID of the payment you sent">
                    <small class="inv-help">Approve only after you have sent the money. The user's wallet was already debited when they requested.</small>
                </div>

                <div class="inv-field">
                    <label>Admin Note (optional)</label>
                    <textarea name="admin_note" rows="3" maxlength="500"></textarea>
                </div>
            </div>

            <div class="inv-modal-foot">
                <button type="button" class="inv-btn outline" onclick="closeModal('approveModal')">Cancel</button>
                <button type="submit" class="inv-btn primary"><i class="fas fa-check"></i> Confirm Payout</button>
            </div>
        </form>
    </div>
</div>

{{-- Reject Modal --}}
<div class="inv-modal" id="rejectModal">
    <div class="inv-modal-box">
        <div class="inv-modal-head">
            <h3>Reject Withdraw</h3>
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
                    <small class="inv-help">The full requested amount will be refunded to the user's wallet.</small>
                </div>
            </div>

            <div class="inv-modal-foot">
                <button type="button" class="inv-btn outline" onclick="closeModal('rejectModal')">Cancel</button>
                <button type="submit" class="inv-btn primary" style="background:#dc2626;"><i class="fas fa-undo"></i> Reject &amp; Refund</button>
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
        document.getElementById('approveForm').reset();
        document.getElementById('approveInfo').innerHTML =
            'Request <strong>#' + d.id + '</strong> · ' + d.user + '<br>' +
            'Send <strong>' + d.net + '</strong> to ' + (d.method || '—') + ' <strong>' + (d.account || '—') + '</strong>' +
            (d.name ? ' (' + d.name + ')' : '');

        openModal('approveModal');
    }

    function openReject(btn) {
        const d = btn.dataset;

        document.getElementById('rejectForm').action = d.action;
        document.getElementById('rejectForm').reset();
        document.getElementById('rejectInfo').innerHTML =
            'Request <strong>#' + d.id + '</strong> · ' + d.user + '<br>Amount to refund: <strong>' + d.amount + '</strong>';

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