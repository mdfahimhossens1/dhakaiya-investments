@extends('layouts.app')

@section('title', 'KYC Verification')

@section('content')

<div class="kyc-page">

    <div class="page-heading">
        <div class="breadcrumb">
            <a href="{{ route('user.dashboard') }}">Dashboard</a>
            <span>/</span>
            <span>My Profile</span>
            <span>/</span>
            <strong>KYC Verification</strong>
        </div>

        <h1>KYC Verification</h1>
        <p>View your identity verification status and requirements.</p>
    </div>

    {{-- KYC Status Overview --}}

    <div class="kyc-status-card">

        <div class="status-content">

            <div class="status-icon">🛡️</div>

            <div>
                <span class="status-label">CURRENT VERIFICATION STATUS</span>

                <h2>{{ $kyc['status_label'] }}</h2>

                <p>
                    Your identity verification has not been submitted yet.
                </p>
            </div>

        </div>

        <div class="status-badge">
            <span></span>
            Not Submitted
        </div>

    </div>

    {{-- Notice --}}

    <div class="kyc-notice">
        <div class="notice-icon">ℹ️</div>

        <div>
            <strong>KYC Submission Currently Unavailable</strong>

            <p>
                Identity document submission is not enabled yet.
                The platform will enable KYC submission after the required
                security, privacy and document-storage arrangements are ready.
            </p>
        </div>
    </div>

    {{-- Profile Information --}}

    <div class="kyc-card">

        <div class="card-heading">
            <div>
                <h3>Registered Member Information</h3>
                <p>Your existing account information.</p>
            </div>

            <a href="{{ route('user.personal-information.index') }}">
                Edit Profile →
            </a>
        </div>

        <div class="profile-information-grid">

            <div class="profile-info">
                <label>Full Name</label>
                <strong>{{ $user->name }}</strong>
            </div>

            <div class="profile-info">
                <label>Phone Number</label>
                <strong>{{ $user->phone ?? 'Not Provided' }}</strong>
            </div>

            <div class="profile-info">
                <label>Email Address</label>
                <strong>{{ $user->email ?? 'Not Provided' }}</strong>
            </div>

            <div class="profile-info">
                <label>Registered Country</label>
                <strong>{{ $user->country ?? 'Not Provided' }}</strong>
            </div>

        </div>

    </div>

    {{-- Verification Process --}}

    <div class="kyc-card process-card">

        <div class="card-heading">
            <div>
                <h3>Verification Process</h3>
                <p>Understand the steps involved in KYC verification.</p>
            </div>
        </div>

        <div class="verification-steps">

            @foreach($steps as $step)

                <div class="verification-step">

                    <div class="step-number">
                        {{ $step['number'] }}
                    </div>

                    <div class="step-content">
                        <h4>{{ $step['title'] }}</h4>
                        <p>{{ $step['description'] }}</p>
                    </div>

                    <span class="step-status">
                        Pending
                    </span>

                </div>

            @endforeach

        </div>

    </div>

    {{-- Document Requirements --}}

    <div class="kyc-card">

        <div class="card-heading">
            <div>
                <h3>Identity Document Requirements</h3>
                <p>Documents that may be required during future verification.</p>
            </div>
        </div>

        <div class="document-grid">

            <div class="document-item">
                <div class="document-icon">🪪</div>

                <div>
                    <h4>National Identity Card</h4>
                    <p>For eligible users in supported countries.</p>
                </div>

                <span>Planned</span>
            </div>

            <div class="document-item">
                <div class="document-icon">🛂</div>

                <div>
                    <h4>Passport</h4>
                    <p>Alternative identity document where applicable.</p>
                </div>

                <span>Planned</span>
            </div>

            <div class="document-item">
                <div class="document-icon">📷</div>

                <div>
                    <h4>Identity Verification</h4>
                    <p>Additional verification may be required.</p>
                </div>

                <span>Planned</span>
            </div>

        </div>

    </div>

    {{-- Security Notice --}}

    <div class="security-notice">

        <div class="security-icon">🔐</div>

        <div>
            <h3>Your Privacy Matters</h3>

            <p>
                Never send your identity documents, passwords, PINs or OTPs
                through unofficial channels. Submit sensitive information
                only through an officially enabled and secured verification system.
            </p>
        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
.kyc-page {
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

.kyc-status-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    background: linear-gradient(120deg, #087f5b, #065f46);
    border-radius: 14px;
    padding: 26px;
    color: white;
    margin-bottom: 20px;
}

.status-content {
    display: flex;
    align-items: center;
    gap: 17px;
}

.status-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.15);
    border-radius: 13px;
    font-size: 29px;
    flex-shrink: 0;
}

.status-label {
    font-size: 10px;
    letter-spacing: 1px;
    opacity: .75;
    font-weight: 700;
}

.status-content h2 {
    color: white;
    font-size: 22px;
    margin: 5px 0;
}

.status-content p {
    font-size: 12px;
    opacity: .85;
    margin: 0;
}

.status-badge {
    padding: 9px 13px;
    background: rgba(255,255,255,.15);
    border-radius: 30px;
    font-size: 11px;
    white-space: nowrap;
}

.status-badge span {
    display: inline-block;
    width: 7px;
    height: 7px;
    background: #fcd34d;
    border-radius: 50%;
    margin-right: 5px;
}

.kyc-notice {
    display: flex;
    gap: 13px;
    padding: 17px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 10px;
    margin-bottom: 22px;
}

.notice-icon {
    font-size: 19px;
}

.kyc-notice strong {
    color: #92400e;
    font-size: 12px;
}

.kyc-notice p {
    color: #78716c;
    font-size: 12px;
    line-height: 1.7;
    margin: 6px 0 0;
}

.kyc-card {
    background: white;
    border: 1px solid #e8edf0;
    border-radius: 13px;
    padding: 24px;
    margin-bottom: 20px;
}

.card-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border-bottom: 1px solid #edf0f2;
    padding-bottom: 17px;
    margin-bottom: 20px;
}

.card-heading h3 {
    color: #1e293b;
    font-size: 16px;
    margin: 0 0 5px;
}

.card-heading p {
    color: #94a3b8;
    font-size: 12px;
    margin: 0;
}

.card-heading a {
    color: #087f5b;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
}

.profile-information-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.profile-info {
    padding: 13px;
    background: #f8fafc;
    border: 1px solid #edf0f2;
    border-radius: 9px;
}

.profile-info label {
    display: block;
    color: #94a3b8;
    font-size: 11px;
    margin-bottom: 7px;
}

.profile-info strong {
    color: #334155;
    font-size: 13px;
    overflow-wrap: anywhere;
}

.verification-steps {
    display: flex;
    flex-direction: column;
}

.verification-step {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #edf0f2;
}

.verification-step:last-child {
    border-bottom: none;
}

.step-number {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #effaf5;
    color: #087f5b;
    font-weight: 700;
    font-size: 13px;
    border-radius: 50%;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
    min-width: 0;
}

.step-content h4 {
    color: #334155;
    font-size: 13px;
    margin: 0 0 5px;
}

.step-content p {
    color: #94a3b8;
    font-size: 11px;
    line-height: 1.6;
    margin: 0;
}

.step-status {
    color: #94a3b8;
    background: #f1f5f9;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 10px;
    white-space: nowrap;
}

.document-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 13px;
}

.document-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    border: 1px solid #edf0f2;
    border-radius: 10px;
    min-width: 0;
}

.document-icon {
    width: 42px;
    height: 42px;
    background: #effaf5;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.document-item > div:nth-child(2) {
    flex: 1;
    min-width: 0;
}

.document-item h4 {
    color: #334155;
    font-size: 12px;
    margin: 0 0 5px;
}

.document-item p {
    color: #94a3b8;
    font-size: 10px;
    line-height: 1.5;
    margin: 0;
}

.document-item > span {
    color: #64748b;
    background: #f1f5f9;
    padding: 5px 8px;
    border-radius: 20px;
    font-size: 9px;
    white-space: nowrap;
}

.security-notice {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    background: #f8fafc;
    border: 1px solid #e8edf0;
    border-radius: 12px;
    padding: 20px;
}

.security-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e0f2fe;
    border-radius: 10px;
    font-size: 20px;
    flex-shrink: 0;
}

.security-notice h3 {
    color: #1e293b;
    font-size: 14px;
    margin: 0 0 7px;
}

.security-notice p {
    color: #64748b;
    font-size: 12px;
    line-height: 1.7;
    margin: 0;
}

@media(max-width: 650px) {
    .kyc-status-card {
        align-items: flex-start;
        flex-direction: column;
        padding: 20px;
    }

    .profile-information-grid,
    .document-grid {
        grid-template-columns: 1fr;
    }

    .kyc-card {
        padding: 17px;
    }

    .verification-step {
        align-items: flex-start;
    }

    .step-status {
        margin-top: 5px;
    }

    .document-item {
        flex-wrap: wrap;
    }
}
</style>
@endpush