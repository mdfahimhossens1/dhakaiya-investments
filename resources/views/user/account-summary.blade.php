@extends('layouts.app')

@section('page-title', 'Account Summary')

@section('content')

<div class="account-summary-page">

    {{-- Page Heading --}}
    <div class="page-heading">
        <div>
            <div class="breadcrumb">
                <a href="{{ route('user.dashboard') }}">Dashboard</a>
                <span>/</span>
                <span>Account Summary</span>
            </div>

            <h1>Account Summary</h1>
            <p>View your account information and referral activity.</p>
        </div>

        <div class="page-date">
            <span>📅</span>
            {{ now()->format('d M, Y') }}
        </div>
    </div>


    {{-- Summary Cards --}}
    <div class="summary-cards">

        <div class="summary-card">
            <div class="summary-card-top">
                <div class="summary-icon green">✓</div>
                <span class="card-label">Account Status</span>
            </div>

            <h2>Active</h2>
            <p>Your account is registered</p>
        </div>


        <div class="summary-card">
            <div class="summary-card-top">
                <div class="summary-icon blue">♙</div>
                <span class="card-label">Total Referrals</span>
            </div>

            <h2>{{ number_format($totalReferrals) }}</h2>
            <p>People registered through your code</p>
        </div>


        <div class="summary-card">
            <div class="summary-card-top">
                <div class="summary-icon purple">◈</div>
                <span class="card-label">Account Type</span>
            </div>

            <h2>{{ ucwords(str_replace('_', ' ', $user->role)) }}</h2>
            <p>Your platform account role</p>
        </div>


        <div class="summary-card">
            <div class="summary-card-top">
                <div class="summary-icon orange">◷</div>
                <span class="card-label">Member Since</span>
            </div>

            <h2>{{ $user->created_at?->format('d M, Y') ?? 'N/A' }}</h2>
            <p>Your registration date</p>
        </div>

    </div>


    {{-- Main Content --}}
    <div class="account-content-grid">

        {{-- Personal Information --}}
        <div class="account-panel">

            <div class="panel-heading">
                <div>
                    <h3>Personal Information</h3>
                    <p>Your registered account details</p>
                </div>

                <div class="panel-heading-icon">♙</div>
            </div>

            <div class="information-list">

                <div class="information-item">
                    <div class="information-label">Full Name</div>
                    <div class="information-value">
                        {{ $user->name }}
                    </div>
                </div>

                <div class="information-item">
                    <div class="information-label">Phone Number</div>
                    <div class="information-value">
                        {{ $user->phone ?? 'Not provided' }}
                    </div>
                </div>

                <div class="information-item">
                    <div class="information-label">Email Address</div>
                    <div class="information-value">
                        {{ $user->email ?? 'Not provided' }}
                    </div>
                </div>

                <div class="information-item">
                    <div class="information-label">Country</div>
                    <div class="information-value">
                        {{ $user->country ?? 'Not provided' }}
                    </div>
                </div>

                <div class="information-item">
                    <div class="information-label">Account Role</div>
                    <div class="information-value">
                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                    </div>
                </div>

                <div class="information-item">
                    <div class="information-label">Registration Date</div>
                    <div class="information-value">
                        {{ $user->created_at?->format('d M, Y') ?? 'N/A' }}
                    </div>
                </div>

            </div>

            <div class="panel-footer">
                <a href="{{ route('user.personal-information.index') }}" class="outline-btn">
                    Edit Profile <span>→</span>
                </a>
            </div>

        </div>


        {{-- Referral Information --}}
        <div class="account-panel">

            <div class="panel-heading">
                <div>
                    <h3>Referral Information</h3>
                    <p>Your referral account overview</p>
                </div>

                <div class="panel-heading-icon referral-icon">♧</div>
            </div>

            <div class="referral-highlight">

                <span class="referral-label">Your Referral Code</span>

                <div class="referral-code-box">
                    <strong id="referralCode">
                        {{ $user->referral_code ?? 'Not available' }}
                    </strong>

                    <button type="button"
                            onclick="copyReferralCode()"
                            class="copy-btn">
                        Copy
                    </button>
                </div>

                <small>Share your referral code with others.</small>

            </div>


            <div class="referral-stat">
                <div>
                    <span>Total Referrals</span>
                    <strong>{{ number_format($totalReferrals) }}</strong>
                </div>

                <div class="referral-stat-icon">♙</div>
            </div>


            <div class="referral-stat">
                <div>
                    <span>Joined Through</span>
                    <strong>
                        {{ $user->referredBy?->name ?? 'Direct Registration' }}
                    </strong>
                </div>

                <div class="referral-stat-icon">↗</div>
            </div>

        </div>

    </div>


    {{-- Recent Referrals --}}
    <div class="account-panel recent-referrals-panel">

        <div class="panel-heading">
            <div>
                <h3>Recent Joined Members</h3>
                <p>Users who registered using your referral code</p>
            </div>

            <span class="total-badge">
                {{ number_format($totalReferrals) }} Members
            </span>
        </div>


        @if($recentReferrals->count() > 0)

            <div class="table-responsive">

                <table class="account-table">

                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Phone Number</th>
                            <th>Country</th>
                            <th>Joined Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($recentReferrals as $referral)

                            <tr>
                                <td>
                                    <div class="member-cell">

                                        <div class="member-avatar">
                                            {{ strtoupper(substr($referral->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <strong>{{ $referral->name }}</strong>
                                            <small>Member</small>
                                        </div>

                                    </div>
                                </td>

                                <td>{{ $referral->phone ?? 'N/A' }}</td>

                                <td>{{ $referral->country ?? 'N/A' }}</td>

                                <td>
                                    {{ $referral->created_at?->format('d M, Y') }}
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state">
                <div class="empty-icon">♧</div>
                <h4>No Referrals Yet</h4>
                <p>
                    You haven't referred anyone yet.
                    Share your referral code to invite new members.
                </p>
            </div>

        @endif

    </div>

</div>

@endsection


@push('styles')

<style>

    .account-summary-page {
        width: 100%;
    }

    /* Page Heading */

    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .breadcrumb {
        display: flex;
        gap: 9px;
        font-size: 11px;
        color: #94a3b8;
        margin-bottom: 9px;
    }

    .breadcrumb a {
        color: var(--primary);
        text-decoration: none;
    }

    .page-heading h1 {
        font-size: 23px;
        font-weight: 750;
        color: #1e293b;
        margin: 0;
    }

    .page-heading p {
        color: #84919f;
        font-size: 12px;
        margin-top: 6px;
    }

    .page-date {
        background: #fff;
        border: 1px solid var(--border);
        padding: 10px 14px;
        border-radius: 8px;
        color: #64748b;
        font-size: 11px;
        display: flex;
        align-items: center;
        gap: 8px;
    }


    /* Summary Cards */

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }

    .summary-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        transition: .2s ease;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,.04);
    }

    .summary-card-top {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 17px;
    }

    .summary-icon {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        display: grid;
        place-items: center;
        font-size: 17px;
        font-weight: 700;
    }

    .summary-icon.green {
        background: #dcfce7;
        color: #15803d;
    }

    .summary-icon.blue {
        background: #dbeafe;
        color: #2563eb;
    }

    .summary-icon.purple {
        background: #f3e8ff;
        color: #9333ea;
    }

    .summary-icon.orange {
        background: #ffedd5;
        color: #ea580c;
    }

    .card-label {
        color: #84919f;
        font-size: 11px;
        font-weight: 600;
    }

    .summary-card h2 {
        color: #1e293b;
        font-size: 21px;
        font-weight: 750;
        margin: 0 0 7px;
        overflow-wrap: anywhere;
    }

    .summary-card p {
        color: #94a3b8;
        font-size: 11px;
        margin: 0;
    }


    /* Content Grid */

    .account-content-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 20px;
        margin-bottom: 22px;
    }

    .account-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 22px;
        min-width: 0;
    }

    .panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .panel-heading h3 {
        font-size: 14px;
        font-weight: 750;
        color: #1e293b;
        margin: 0 0 5px;
    }

    .panel-heading p {
        font-size: 11px;
        color: #94a3b8;
        margin: 0;
    }

    .panel-heading-icon {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        display: grid;
        place-items: center;
        background: #ecfdf5;
        color: var(--primary);
        font-size: 18px;
    }

    .referral-icon {
        background: #eff6ff;
        color: #2563eb;
    }


    /* Personal Information */

    .information-list {
        display: flex;
        flex-direction: column;
    }

    .information-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 13px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .information-item:first-child {
        padding-top: 0;
    }

    .information-item:last-child {
        border-bottom: 0;
    }

    .information-label {
        color: #94a3b8;
        font-size: 11px;
    }

    .information-value {
        color: #334155;
        font-size: 11px;
        font-weight: 650;
        text-align: right;
        overflow-wrap: anywhere;
    }

    .panel-footer {
        border-top: 1px solid #f1f5f9;
        padding-top: 16px;
        margin-top: 10px;
    }

    .outline-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #d1e7df;
        color: var(--primary);
        text-decoration: none;
        padding: 9px 13px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 650;
        transition: .2s;
    }

    .outline-btn:hover {
        background: #ecfdf5;
    }


    /* Referral */

    .referral-highlight {
        background: #f0fdf4;
        border: 1px dashed #bbf7d0;
        border-radius: 10px;
        padding: 17px;
        margin-bottom: 18px;
    }

    .referral-label {
        display: block;
        color: #64748b;
        font-size: 11px;
        margin-bottom: 10px;
    }

    .referral-code-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        background: #fff;
        border: 1px solid #dcfce7;
        padding: 10px 12px;
        border-radius: 7px;
    }

    .referral-code-box strong {
        color: #087f5b;
        font-size: 16px;
        letter-spacing: 1px;
        overflow-wrap: anywhere;
    }

    .copy-btn {
        background: var(--primary);
        color: #fff;
        border: 0;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 10px;
        font-weight: 650;
        cursor: pointer;
        flex-shrink: 0;
    }

    .copy-btn:hover {
        background: var(--primary-dark);
    }

    .referral-highlight small {
        display: block;
        margin-top: 9px;
        color: #94a3b8;
        font-size: 10px;
    }

    .referral-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
        gap: 12px;
    }

    .referral-stat:last-child {
        border-bottom: 0;
    }

    .referral-stat div:first-child {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .referral-stat span {
        color: #94a3b8;
        font-size: 11px;
    }

    .referral-stat strong {
        color: #334155;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .referral-stat-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: #f1f5f9;
        color: #64748b;
        font-size: 16px;
        flex-shrink: 0;
    }


    /* Recent Referrals */

    .recent-referrals-panel {
        margin-bottom: 20px;
    }

    .total-badge {
        background: #ecfdf5;
        color: #087f5b;
        padding: 7px 11px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .account-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 550px;
    }

    .account-table th {
        background: #f8fafc;
        color: #94a3b8;
        text-align: left;
        font-size: 10px;
        font-weight: 700;
        padding: 13px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .account-table td {
        padding: 14px 13px;
        border-bottom: 1px solid #f1f5f9;
        color: #64748b;
        font-size: 11px;
    }

    .account-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .member-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .member-avatar {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        background: #dcfce7;
        color: #087f5b;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .member-cell div:last-child {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .member-cell strong {
        color: #334155;
        font-size: 11px;
    }

    .member-cell small {
        color: #94a3b8;
        font-size: 10px;
    }


    /* Empty State */

    .empty-state {
        text-align: center;
        padding: 35px 15px;
    }

    .empty-icon {
        width: 55px;
        height: 55px;
        display: grid;
        place-items: center;
        margin: 0 auto 13px;
        border-radius: 50%;
        background: #f0fdf4;
        color: #087f5b;
        font-size: 23px;
    }

    .empty-state h4 {
        color: #334155;
        font-size: 13px;
        margin-bottom: 7px;
    }

    .empty-state p {
        color: #94a3b8;
        font-size: 11px;
        line-height: 1.7;
        max-width: 300px;
        margin: auto;
    }


    /* Responsive */

    @media(max-width: 1100px) {
        .summary-cards {
            grid-template-columns: repeat(2, 1fr);
        }

        .account-content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 576px) {
        .page-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .page-heading h1 {
            font-size: 20px;
        }

        .summary-cards {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .summary-card {
            padding: 14px;
        }

        .summary-card h2 {
            font-size: 16px;
        }

        .summary-card-top {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 12px;
        }

        .account-panel {
            padding: 16px;
        }

        .information-item {
            align-items: flex-start;
        }

        .information-value {
            max-width: 60%;
        }

        .panel-heading {
            align-items: flex-start;
        }

        .referral-code-box strong {
            font-size: 13px;
        }
    }

</style>

@endpush


@push('scripts')

<script>
    function copyReferralCode() {
        const code = document.getElementById('referralCode').innerText.trim();

        if (code === 'Not available') {
            alert('Referral code is not available.');
            return;
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(code)
                .then(() => alert('Referral code copied successfully!'))
                .catch(() => fallbackCopy(code));
        } else {
            fallbackCopy(code);
        }
    }

    function fallbackCopy(text) {
        const input = document.createElement('textarea');

        input.value = text;
        input.style.position = 'fixed';
        input.style.opacity = '0';

        document.body.appendChild(input);
        input.select();

        try {
            document.execCommand('copy');
            alert('Referral code copied successfully!');
        } catch (error) {
            alert('Unable to copy referral code.');
        }

        document.body.removeChild(input);
    }
</script>

@endpush