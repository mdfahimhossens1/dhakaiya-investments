@extends('layouts.app')

@section('title', 'Payment Information')

@section('content')

<div class="payment-page">

    <div class="page-heading">
        <div class="breadcrumb">
            <a href="{{ route('user.dashboard') }}">Dashboard</a>
            <span>/</span>
            <span>My Profile</span>
            <span>/</span>
            <strong>Payment Information</strong>
        </div>

        <h1>Payment Information</h1>
        <p>View the payment methods supported by the platform.</p>
    </div>

    @if(session('success'))<div class="pm-flash ok">✓ {{ session('success') }}</div>@endif
    @if(session('error'))<div class="pm-flash err">{{ session('error') }}</div>@endif
    @if($errors->any())
        <div class="pm-flash err">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <div class="payment-summary">
        <div class="summary-icon">💳</div>
        <div>
            <span>PAYMENT PROFILE</span>
            <h2>{{ $user->name }}</h2>
            <p>Member ID: #{{ $user->id }}</p>
        </div>
        <div class="summary-status">
            <span class="status-dot"></span>
            {{ $configuredCount > 0 ? 'Configured (' . $configuredCount . '/' . $totalCount . ')' : 'Not Configured' }}
        </div>
    </div>

    <div class="payment-notice">
        <span>ℹ️</span>
        <div>
            <strong>Payment Information Notice</strong>
            <p>
                Add the account where you want to receive withdrawals. Never share your
                PIN, password or OTP. The methods below are available for your country and wallet currency.
            </p>
        </div>
    </div>

    <div class="section-heading">
        <div>
            <h3>Supported Payment Methods</h3>
            <p>Available payment methods for your country.</p>
        </div>
        <span class="method-count">{{ $totalCount }} Methods</span>
    </div>

    <div class="payment-method-grid">
        @forelse($paymentMethods as $method)
            <div class="payment-method-card">
                <div class="method-top">
                    <div class="method-icon">{{ $method['icon'] ?: '💳' }}</div>
                    <span class="method-status">{{ $method['status'] }}</span>
                </div>

                <h4>{{ $method['name'] }}</h4>
                <p>{{ $method['description'] ?: implode(', ', $method['currencies']) }}</p>

                @forelse($method['accounts'] as $acc)
                    <div class="acc-row">
                        <div>
                            <strong>{{ $acc['currency'] }}</strong> · {{ $acc['masked'] }}
                            @if($acc['verified'])<span class="acc-ok">Verified</span>@endif
                            @if($acc['is_default'])<span class="acc-def">Default</span>@endif
                        </div>
                        <form method="POST" action="{{ route('user.payment-information.destroy', $acc['id']) }}"
                              onsubmit="return confirm('Remove this account?')">
                            @csrf @method('DELETE')
                            <button class="acc-del" title="Remove">✕</button>
                        </form>
                    </div>
                @empty
                    <div class="method-footer">
                        <span>Account details</span>
                        <strong>Not Added</strong>
                    </div>
                @endforelse

                <button type="button" class="acc-add"
                        data-id="{{ $method['id'] }}"
                        data-name="{{ $method['name'] }}"
                        data-currencies="{{ json_encode($method['currencies']) }}"
                        data-fields="{{ json_encode($method['fields']) }}"
                        onclick="openAddAccount(this)">
                    + Add Account
                </button>
            </div>
        @empty
            <div class="payment-method-card" style="grid-column:1/-1;text-align:center">
                <p style="margin:0">No payment method is available for your country yet.</p>
            </div>
        @endforelse
    </div>

    {{-- ADD ACCOUNT MODAL --}}
    <div class="acc-ov" id="accModal">
        <div class="acc-modal">
            <div class="acc-mh">
                <h3 id="accTitle">Add Account</h3>
                <button type="button" onclick="closeAddAccount()">&times;</button>
            </div>
            <form method="POST" action="{{ route('user.payment-information.store') }}" id="accForm">
                @csrf
                <input type="hidden" name="payment_method_id" value="">
                <div class="acc-mb">
                    <label>Currency</label>
                    <select name="currency" required></select>
                    <div id="accFields"></div>
                </div>
                <div class="acc-mf">
                    <button type="button" class="acc-cancel" onclick="closeAddAccount()">Cancel</button>
                    <button type="submit" class="acc-save">Save Account</button>
                </div>
            </form>
        </div>
    </div>

    <div class="back-action">
        <a href="{{ route('user.personal-information.index') }}">
            ← Back to Personal Information
        </a>
    </div>

</div>
<script>
function openAddAccount(btn) {
    const f = document.getElementById('accForm');
    f.querySelector('[name=payment_method_id]').value = btn.dataset.id;
    document.getElementById('accTitle').textContent = 'Add ' + btn.dataset.name + ' Account';

    const sel = f.querySelector('[name=currency]');
    sel.innerHTML = '';
    JSON.parse(btn.dataset.currencies).forEach(function (c) {
        const o = document.createElement('option');
        o.value = c; o.textContent = c;
        sel.appendChild(o);
    });

    const box = document.getElementById('accFields');
    box.innerHTML = '';
    JSON.parse(btn.dataset.fields).forEach(function (fd) {
        const label = document.createElement('label');
        label.textContent = fd.label + (fd.required ? ' *' : '');
        const input = document.createElement('input');
        input.type = 'text'; input.name = fd.key; input.required = !!fd.required;
        input.autocomplete = 'off'; input.maxLength = 150;
        box.appendChild(label); box.appendChild(input);
    });

    document.getElementById('accModal').classList.add('show');
}
function closeAddAccount() { document.getElementById('accModal').classList.remove('show'); }
document.getElementById('accModal').addEventListener('click', function (e) { if (e.target === this) closeAddAccount(); });
</script>
@endsection

@push('styles')
<style>
.payment-page {
    max-width: 1200px;
    margin: 0 auto;
}

.page-heading {
    margin-bottom: 25px;
}

.breadcrumb {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
    align-items: center;
    color: #94a3b8;
    font-size: 12px;
    margin-bottom: 12px;
}

.breadcrumb a {
    color: #087f5b;
    text-decoration: none;
}

.breadcrumb strong {
    color: #334155;
}

.page-heading h1 {
    color: #1e293b;
    font-size: 25px;
    margin: 0 0 7px;
}

.page-heading p {
    color: #84919f;
    font-size: 13px;
    margin: 0;
}

.payment-summary {
    display: flex;
    align-items: center;
    gap: 17px;
    padding: 25px;
    background: linear-gradient(120deg, #087f5b, #065f46);
    color: white;
    border-radius: 14px;
    margin-bottom: 20px;
}

.summary-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,.15);
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.payment-summary > div:nth-child(2) {
    flex: 1;
}

.payment-summary > div:nth-child(2) > span {
    font-size: 10px;
    letter-spacing: 1px;
    opacity: .75;
    font-weight: 700;
}

.payment-summary h2 {
    color: white;
    font-size: 21px;
    margin: 5px 0;
}

.payment-summary p {
    margin: 0;
    font-size: 12px;
    opacity: .8;
}

.summary-status {
    background: rgba(255,255,255,.15);
    padding: 9px 13px;
    border-radius: 30px;
    font-size: 11px;
    white-space: nowrap;
}

.status-dot {
    display: inline-block;
    width: 7px;
    height: 7px;
    background: #fcd34d;
    border-radius: 50%;
    margin-right: 5px;
}

.payment-notice {
    display: flex;
    gap: 13px;
    padding: 17px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 10px;
    margin-bottom: 28px;
}

.payment-notice > span {
    font-size: 19px;
}

.payment-notice strong {
    color: #92400e;
    font-size: 12px;
}

.payment-notice p {
    color: #78716c;
    font-size: 12px;
    line-height: 1.7;
    margin: 5px 0 0;
}

.section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 16px;
}

.section-heading h3 {
    color: #1e293b;
    font-size: 16px;
    margin: 0 0 5px;
}

.section-heading p {
    color: #94a3b8;
    font-size: 12px;
    margin: 0;
}

.method-count {
    color: #087f5b;
    background: #effaf5;
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

.payment-method-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 17px;
}

.payment-method-card {
    background: white;
    border: 1px solid #e8edf0;
    border-radius: 12px;
    padding: 21px;
    transition: .2s;
}

.payment-method-card:hover {
    border-color: #b7dfcf;
    box-shadow: 0 5px 18px rgba(15, 23, 42, .04);
}

.method-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 17px;
}

.method-icon {
    width: 43px;
    height: 43px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #effaf5;
    border-radius: 10px;
    font-size: 21px;
}

.method-status {
    background: #f1f5f9;
    color: #64748b;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
}

.payment-method-card h4 {
    color: #263445;
    font-size: 15px;
    margin: 0 0 6px;
}

.payment-method-card > p {
    color: #94a3b8;
    font-size: 12px;
    margin: 0 0 17px;
}

.method-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #edf0f2;
    padding-top: 13px;
    font-size: 11px;
}

.method-footer span {
    color: #94a3b8;
}

.method-footer strong {
    color: #64748b;
}

.security-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    background: #f8fafc;
    border: 1px solid #e8edf0;
    border-radius: 12px;
    padding: 20px;
    margin-top: 22px;
}

.security-icon {
    width: 42px;
    height: 42px;
    background: #e0f2fe;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.security-card h3 {
    color: #1e293b;
    font-size: 14px;
    margin: 0 0 7px;
}

.security-card p {
    color: #64748b;
    font-size: 12px;
    line-height: 1.7;
    margin: 0;
}

.back-action {
    margin-top: 22px;
}

.back-action a {
    color: #087f5b;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
}
.pm-flash{padding:13px 16px;border-radius:9px;margin-bottom:16px;font-size:13px}
.pm-flash.ok{background:#eaf8f0;color:#137547;border:1px solid #c8ecd7}
.pm-flash.err{background:#fff0ef;color:#b42318;border:1px solid #ffd5d1}
.acc-row{display:flex;justify-content:space-between;align-items:center;gap:8px;padding:10px 0;border-top:1px solid #edf0f2;font-size:12px;color:#334155}
.acc-row form{margin:0}
.acc-ok{background:#e9f8f0;color:#16814f;padding:2px 7px;border-radius:10px;font-size:10px;margin-left:6px}
.acc-def{background:#eef2ff;color:#4f46e5;padding:2px 7px;border-radius:10px;font-size:10px;margin-left:4px}
.acc-del{border:0;background:#fff0ef;color:#d34c43;width:26px;height:26px;border-radius:6px;cursor:pointer}
.acc-add{width:100%;margin-top:13px;padding:10px;border:1px dashed #b7dfcf;background:#effaf5;color:#087f5b;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer}
.acc-ov{position:fixed;inset:0;background:rgba(15,23,42,.6);z-index:99999;display:none;align-items:center;justify-content:center;padding:16px}
.acc-ov.show{display:flex}
.acc-modal{width:100%;max-width:460px;background:#fff;border-radius:14px;overflow:hidden}
.acc-mh{display:flex;justify-content:space-between;align-items:center;padding:17px 20px;border-bottom:1px solid #edf0f2}
.acc-mh h3{margin:0;font-size:16px;color:#1e293b}
.acc-mh button{border:0;background:#f1f5f9;width:32px;height:32px;border-radius:8px;font-size:20px;cursor:pointer}
.acc-mb{padding:18px 20px;max-height:65vh;overflow-y:auto}
.acc-mb label{display:block;margin:12px 0 6px;font-size:12px;font-weight:700;color:#344054}
.acc-mb input,.acc-mb select{width:100%;padding:10px 12px;border:1px solid #dce2ea;border-radius:8px;font-size:13px;box-sizing:border-box}
.acc-mf{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;background:#f9fafc;border-top:1px solid #edf0f2}
.acc-cancel,.acc-save{padding:10px 16px;border:0;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer}
.acc-cancel{background:#edf0f5;color:#475467}.acc-save{background:#087f5b;color:#fff}
@media(max-width: 650px) {
    .payment-summary {
        flex-wrap: wrap;
        padding: 19px;
    }

    .summary-status {
        margin-left: 77px;
    }

    .payment-method-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush