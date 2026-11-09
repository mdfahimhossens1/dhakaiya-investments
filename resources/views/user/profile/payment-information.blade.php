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

    <div class="payment-summary">

        <div class="summary-icon">💳</div>

        <div>
            <span>PAYMENT PROFILE</span>
            <h2>{{ $user->name }}</h2>
            <p>Member ID: #{{ $user->id }}</p>
        </div>

        <div class="summary-status">
            <span class="status-dot"></span>
            Not Configured
        </div>

    </div>

    <div class="payment-notice">
        <span>ℹ️</span>
        <div>
            <strong>Payment Information Notice</strong>
            <p>
                Payment account registration is not currently enabled.
                Do not share your bank account number, PIN, password,
                OTP or other confidential payment credentials here.
            </p>
        </div>
    </div>

    <div class="section-heading">
        <div>
            <h3>Supported Payment Methods</h3>
            <p>Available payment methods on the platform.</p>
        </div>

        <span class="method-count">
            {{ count($paymentMethods) }} Methods
        </span>
    </div>

    <div class="payment-method-grid">

        @foreach($paymentMethods as $method)

            <div class="payment-method-card">

                <div class="method-top">
                    <div class="method-icon">
                        {{ $method['icon'] }}
                    </div>

                    <span class="method-status">
                        {{ $method['status'] }}
                    </span>
                </div>

                <h4>{{ $method['name'] }}</h4>

                <p>{{ $method['description'] }}</p>

                <div class="method-footer">
                    <span>Account details</span>
                    <strong>Not Added</strong>
                </div>

            </div>

        @endforeach

    </div>

    <div class="security-card">

        <div class="security-icon">🔐</div>

        <div>
            <h3>Payment Security</h3>

            <p>
                Your payment credentials should only be collected through
                a properly secured and authorized payment-management system.
                Never share your PIN, password or OTP with anyone.
            </p>
        </div>

    </div>

    <div class="back-action">
        <a href="{{ route('user.personal-information.index') }}">
            ← Back to Personal Information
        </a>
    </div>

</div>

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