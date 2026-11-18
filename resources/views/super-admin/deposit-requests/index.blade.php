@extends('layouts.app')

@section('title', 'Deposit Requests')

@section('content')

@include('user.investment._styles')

<style>
    .rq-badge{display:inline-block;padding:4px 10px;border-radius:15px;font-size:11px;font-weight:700}
    .rq-badge.pending{background:#fff4db;color:#946200}
    .rq-badge.approved{background:#e7f6ef;color:#087f5b}
    .rq-badge.rejected{background:#feecec;color:#dc4545}
    .rq-act{display:flex;gap:6px;flex-wrap:wrap}
    .rq-b{border:0;padding:7px 12px;border-radius:7px;font-size:11px;font-weight:700;cursor:pointer}
    .rq-b.ok{background:#087f5b;color:#fff}.rq-b.no{background:#fff0ef;color:#d34c43}
    .rq-link{color:var(--primary);font-weight:700;font-size:11px;text-decoration:none}
    .rq-flash{padding:13px 16px;border-radius:9px;margin-bottom:16px;font-size:13px}
    .rq-flash.ok{background:#eaf8f0;color:#137547;border:1px solid #c8ecd7}
    .rq-flash.err{background:#fff0ef;color:#b42318;border:1px solid #ffd5d1}
    .rq-ov{position:fixed;inset:0;background:rgba(15,23,42,.6);z-index:99999;display:none;align-items:center;justify-content:center;padding:16px}
    .rq-ov.show{display:flex}
    .rq-modal{width:100%;max-width:440px;background:#fff;border-radius:14px;overflow:hidden}
    .rq-mh{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #edf0f2}
    .rq-mh h3{margin:0;font-size:16px}
    .rq-mh button{border:0;background:#f1f5f9;width:32px;height:32px;border-radius:8px;font-size:20px;cursor:pointer}
    .rq-mb{padding:16px 20px}
    .rq-mb p{margin:0 0 6px;font-size:12px;color:#64748b}
    .rq-mb label{display:block;margin:12px 0 6px;font-size:12px;font-weight:700}
    .rq-mb input,.rq-mb textarea{width:100%;padding:10px 12px;border:1px solid #dce2ea;border-radius:8px;font-size:13px;box-sizing:border-box;font-family:inherit}
    .rq-mf{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;background:#f9fafc;border-top:1px solid #edf0f2}
    .rq-chips{display:flex;flex-wrap:wrap;gap:6px;margin-top:6px}
    .rq-chip{background:#e8f5ef;color:var(--primary);padding:3px 9px;border-radius:15px;font-size:11px;font-weight:600}
</style>

<div class="inv-page">

    <div class="inv-heading">
        <div>
            <h2>Deposit Requests</h2>
            <p>Verify the payment, then approve. Approved money goes to the user's locked (deposit) balance.</p>
        </div>
        <span class="inv-badge"><i class="fas fa-inbox"></i> {{ $requests->total() }} request(s)</span>
    </div>

    @if(session('success'))<div class="rq-flash ok">✓ {{ session('success') }}</div>@endif
    @if(session('error'))<div class="rq-flash err">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="rq-flash err">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif

    <div class="inv-summary">
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-clock"></i></div>
            <div class="label">Pending</div>
            <h3>{{ number_format($pendingCount) }}</h3>
            <div class="rq-chips">
                @foreach($pendingByCurrency as $cur => $t)<span class="rq-chip">{{ $cur }} {{ number_format((float) $t, 2) }}</span>@endforeach
            </div>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <div class="label">Approved Today</div>
            <div class="rq-chips">
                @forelse($approvedTodayByCurrency as $cur => $t)<span class="rq-chip">{{ $cur }} {{ number_format((float) $t, 2) }}</span>
                @empty <small>Nothing today</small> @endforelse
            </div>
        </div>
        <div class="inv-stat">
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <div class="label">Rejected (All time)</div>
            <h3>{{ number_format($rejectedCount) }}</h3>
        </div>
    </div>

    <div class="inv-panel">

        <form method="GET" action="{{ route('super-admin.deposit-requests.index') }}" class="inv-filter">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="TrxID / sender / user name / phone"
                   style="min-width:230px;padding:10px 13px;border:1px solid var(--border);border-radius:8px;font-size:13px">
            <select name="status">
                <option value="">All Status</option>
                @foreach(['pending','approved','rejected'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="currency">
                <option value="">All Currencies</option>
                @foreach($currencies as $c)<option value="{{ $c }}" @selected(strtoupper((string) request('currency')) === $c)>{{ $c }}</option>@endforeach
            </select>
            <select name="method">
                <option value="">All Methods</option>
                @foreach($methods as $m)<option value="{{ $m->code }}" @selected(request('method') === $m->code)>{{ $m->name }}</option>@endforeach
            </select>
            <button type="submit" class="inv-btn primary"><i class="fas fa-filter"></i> Apply</button>
            <a href="{{ route('super-admin.deposit-requests.index') }}" class="inv-btn outline">Reset</a>
        </form>

        <div class="inv-table-wrap">
            <table class="inv-table" style="min-width:1100px">
                <thead>
                    <tr>
                        <th>#</th><th>User</th><th>Method</th><th>Amount</th><th>Sender</th>
                        <th>Transaction ID</th><th>Proof</th><th>Date</th><th>Status</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($requests as $r)
                    <tr>
                        <td>#{{ $r->id }}</td>
                        <td>{{ $r->user->name ?? 'Deleted' }}<small>{{ $r->user->phone ?? '' }}</small></td>
                        <td>{{ $r->paymentMethod?->name ?? strtoupper($r->method) }}</td>
                        <td>
                            <strong>{{ $r->currency }} {{ number_format((float) $r->amount, 2) }}</strong>
                            @if($r->status === 'approved' && (float) $r->approved_amount !== (float) $r->amount)
                                <small>Credited: {{ number_format((float) $r->approved_amount, 2) }}</small>
                            @endif
                        </td>
                        <td>{{ $r->sender_number }}</td>
                        <td>{{ $r->transaction_id }}</td>
                        <td>
                            @if($r->screenshot)
                                <a class="rq-link" href="{{ route('super-admin.deposit-requests.screenshot', $r) }}" target="_blank">View</a>
                            @else — @endif
                        </td>
                        <td>{{ $r->created_at->format('d M Y') }}<small>{{ $r->created_at->format('h:i A') }}</small></td>
                        <td>
                            <span class="rq-badge {{ $r->status }}">{{ ucfirst($r->status) }}</span>
                            @if($r->reviewed_at)<small>{{ $r->reviewer->name ?? '' }} · {{ $r->reviewed_at->format('d M, h:i A') }}</small>@endif
                            @if($r->admin_note)<small>{{ \Illuminate\Support\Str::limit($r->admin_note, 40) }}</small>@endif
                        </td>
                        <td>
                            @if($r->status === 'pending')
                                <div class="rq-act">
                                    <button type="button" class="rq-b ok"
                                        data-url="{{ route('super-admin.deposit-requests.approve', $r) }}"
                                        data-amount="{{ $r->amount }}" data-cur="{{ $r->currency }}"
                                        data-info="#{{ $r->id }} · {{ $r->user->name ?? '' }} · TrxID {{ $r->transaction_id }}"
                                        onclick="rqApprove(this)">Approve</button>
                                    <button type="button" class="rq-b no"
                                        data-url="{{ route('super-admin.deposit-requests.reject', $r) }}"
                                        data-info="#{{ $r->id }} · {{ $r->user->name ?? '' }} · TrxID {{ $r->transaction_id }}"
                                        onclick="rqReject(this)">Reject</button>
                                </div>
                            @else — @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10">
                        <div class="inv-empty"><div class="icon"><i class="fas fa-inbox"></i></div>
                            <h4>No Deposit Requests</h4><p>No request matches your filters.</p></div>
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())<div class="inv-pagination">{{ $requests->links() }}</div>@endif
    </div>
</div>

{{-- APPROVE --}}
<div class="rq-ov" id="apvModal"><div class="rq-modal">
    <div class="rq-mh"><h3>Approve Deposit</h3><button type="button" onclick="rqClose('apvModal')">&times;</button></div>
    <form method="POST" id="apvForm">@csrf @method('PATCH')
        <div class="rq-mb">
            <p id="apvInfo"></p>
            <label>Amount to credit (<span id="apvCur"></span>)</label>
            <input type="number" step="0.01" min="0.01" name="approved_amount" id="apvAmount" required>
            <p style="margin-top:6px">Change it only if the money you actually received is different.</p>
            <label>Note (optional)</label>
            <textarea name="admin_note" rows="2" maxlength="500"></textarea>
        </div>
        <div class="rq-mf">
            <button type="button" class="rq-b no" onclick="rqClose('apvModal')">Cancel</button>
            <button type="submit" class="rq-b ok">Confirm Approve</button>
        </div>
    </form>
</div></div>

{{-- REJECT --}}
<div class="rq-ov" id="rejModal"><div class="rq-modal">
    <div class="rq-mh"><h3>Reject Deposit</h3><button type="button" onclick="rqClose('rejModal')">&times;</button></div>
    <form method="POST" id="rejForm">@csrf @method('PATCH')
        <div class="rq-mb">
            <p id="rejInfo"></p>
            <label>Reason (shown to the user) *</label>
            <textarea name="admin_note" rows="3" maxlength="500" required></textarea>
        </div>
        <div class="rq-mf">
            <button type="button" class="rq-b" onclick="rqClose('rejModal')">Cancel</button>
            <button type="submit" class="rq-b no">Confirm Reject</button>
        </div>
    </form>
</div></div>

<script>
function rqClose(id){document.getElementById(id).classList.remove('show')}
function rqApprove(b){
    document.getElementById('apvForm').action=b.dataset.url;
    document.getElementById('apvInfo').textContent=b.dataset.info;
    document.getElementById('apvCur').textContent=b.dataset.cur;
    document.getElementById('apvAmount').value=b.dataset.amount;
    document.getElementById('apvModal').classList.add('show');
}
function rqReject(b){
    document.getElementById('rejForm').action=b.dataset.url;
    document.getElementById('rejInfo').textContent=b.dataset.info;
    document.getElementById('rejModal').classList.add('show');
}
document.querySelectorAll('.rq-ov').forEach(function(o){o.addEventListener('click',function(e){if(e.target===o)o.classList.remove('show')})});
document.addEventListener('keydown',function(e){if(e.key==='Escape')document.querySelectorAll('.rq-ov.show').forEach(function(o){o.classList.remove('show')})});
</script>

@endsection