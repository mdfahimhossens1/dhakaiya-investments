@extends('layouts.app')

@section('title', 'BDT Wallet')

@section('content')

<div class="bdt-wallet-page">

    {{-- Breadcrumb --}}
    <div class="page-heading">
        <div>
            <h2>BDT Wallet</h2>
            <p>Manage your Bangladeshi Taka wallet information.</p>
        </div>

        <div class="breadcrumb">
            <a href="{{ route('user.dashboard') }}">Dashboard</a>
            <span>/</span>
            <a href="{{ route('user.wallet.index') }}">My Wallet</a>
            <span>/</span>
            <span>BDT Wallet</span>
        </div>
    </div>


    {{-- Wallet Banner --}}
    <div class="wallet-banner">

        <div class="wallet-banner-left">

            <div class="wallet-icon">
                ৳
            </div>

            <div>
                <span class="wallet-label">MY DIGITAL WALLET</span>

                <h2>BDT Wallet</h2>

                <p>Bangladeshi Taka Account</p>
            </div>

        </div>

        <div class="wallet-banner-right">

            <span class="status-badge">
                <span class="status-dot"></span>
                {{ $wallet['status'] }}
            </span>

            <h3>৳ BDT</h3>

            <small>Currency Code</small>

        </div>

    </div>


    {{-- Wallet Summary --}}
    <div class="summary-grid">

        <div class="summary-card">

            <div class="summary-icon green">
                ৳
            </div>

            <div>
                <p>Available Balance</p>
                <h3>Not Available</h3>
                <span>Wallet not activated</span>
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-icon blue">
                <i class="fas fa-arrow-down"></i>
            </div>

            <div>
                <p>Total Deposits</p>
                <h3>Not Available</h3>
                <span>No transaction records</span>
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-icon orange">
                <i class="fas fa-arrow-up"></i>
            </div>

            <div>
                <p>Total Withdrawals</p>
                <h3>Not Available</h3>
                <span>No transaction records</span>
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-icon purple">
                <i class="fas fa-exchange-alt"></i>
            </div>

            <div>
                <p>Total Transactions</p>
                <h3>{{ $summary['total_transactions'] }}</h3>
                <span>Recorded transactions</span>
            </div>

        </div>

    </div>


    {{-- Wallet Information --}}
    <div class="content-card">

        <div class="card-heading">
            <div>
                <h3>Wallet Information</h3>
                <p>Your BDT wallet account details.</p>
            </div>

            <span class="info-icon">
                <i class="fas fa-wallet"></i>
            </span>
        </div>


        <div class="information-grid">

            <div class="information-item">
                <span>Wallet Name</span>
                <strong>BDT Wallet</strong>
            </div>

            <div class="information-item">
                <span>Currency</span>
                <strong>Bangladeshi Taka (BDT)</strong>
            </div>

            <div class="information-item">
                <span>Currency Symbol</span>
                <strong>৳</strong>
            </div>

            <div class="information-item">
                <span>Country</span>
                <strong>Bangladesh</strong>
            </div>

            <div class="information-item">
                <span>Account Holder</span>
                <strong>{{ $user->name }}</strong>
            </div>

            <div class="information-item">
                <span>Wallet Status</span>
                <strong class="inactive-text">
                    Not Activated
                </strong>
            </div>

        </div>

    </div>


    {{-- Wallet Actions --}}
    <div class="content-card">

        <div class="card-heading">
            <div>
                <h3>Wallet Services</h3>
                <p>Available services for your BDT wallet.</p>
            </div>
        </div>


        <div class="wallet-actions">

            <div class="action-card">

                <div class="action-icon deposit-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>

                <div>
                    <h4>Deposit Money</h4>
                    <p>Add funds to your BDT wallet.</p>
                </div>

                <span class="disabled-badge">Coming Soon</span>

            </div>


            <div class="action-card">

                <div class="action-icon withdraw-icon">
                    <i class="fas fa-wallet"></i>
                </div>

                <div>
                    <h4>Withdraw Money</h4>
                    <p>Withdraw funds from your wallet.</p>
                </div>

                <span class="disabled-badge">Coming Soon</span>

            </div>

        </div>

        <div class="service-notice">
            <i class="fas fa-info-circle"></i>
            Wallet services are currently unavailable.
        </div>

    </div>


    {{-- Recent Transactions --}}
    <div class="content-card">

        <div class="card-heading">

            <div>
                <h3>Recent Transactions</h3>
                <p>Your latest BDT wallet transactions.</p>
            </div>

            <span class="transaction-count">
                {{ count($transactions) }} Transactions
            </span>

        </div>


        @if(count($transactions) > 0)

            <div class="table-responsive">
                <table class="wallet-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction['id'] }}</td>
                                <td>{{ $transaction['type'] }}</td>
                                <td>৳{{ number_format($transaction['amount'], 2) }}</td>
                                <td>{{ $transaction['date'] }}</td>
                                <td>{{ $transaction['status'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    <i class="fas fa-receipt"></i>
                </div>

                <h4>No Transactions Found</h4>

                <p>
                    Your BDT wallet transaction history
                    will appear here when available.
                </p>

            </div>

        @endif

    </div>


    {{-- Security Notice --}}
    <div class="security-notice">

        <div class="security-icon">
            <i class="fas fa-shield-alt"></i>
        </div>

        <div>
            <h4>Wallet Security</h4>

            <p>
                Keep your account credentials secure.
                Never share your password or verification codes
                with anyone. Wallet services will become available
                after the platform's required setup and verification.
            </p>
        </div>

    </div>

</div>


<style>

.bdt-wallet-page {
    width: 100%;
}

.page-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 25px;
}

.page-heading h2 {
    font-size: 23px;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 6px;
}

.page-heading p {
    color: var(--muted);
    font-size: 13px;
    margin: 0;
}

.breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    font-size: 12px;
    color: var(--muted);
}

.breadcrumb a {
    color: var(--primary);
    text-decoration: none;
}

.wallet-banner {
    background: linear-gradient(120deg, #087f5b, #065f46);
    border-radius: 14px;
    padding: 28px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 22px;
}

.wallet-banner-left {
    display: flex;
    align-items: center;
    gap: 18px;
}

.wallet-icon {
    width: 65px;
    height: 65px;
    border-radius: 14px;
    background: rgba(255,255,255,.16);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 35px;
    font-weight: 700;
}

.wallet-label {
    font-size: 10px;
    letter-spacing: 1.5px;
    opacity: .8;
}

.wallet-banner h2 {
    font-size: 25px;
    margin: 5px 0;
    color: white;
}

.wallet-banner p {
    font-size: 13px;
    margin: 0;
    opacity: .8;
}

.wallet-banner-right {
    text-align: right;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255,255,255,.15);
    padding: 7px 12px;
    border-radius: 30px;
    font-size: 11px;
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #ffd166;
}

.wallet-banner-right h3 {
    font-size: 25px;
    margin: 12px 0 2px;
    color: white;
}

.wallet-banner-right small {
    opacity: .75;
    font-size: 11px;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 22px;
}

.summary-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 13px;
}

.summary-icon {
    min-width: 43px;
    width: 43px;
    height: 43px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.summary-icon.green {
    background: #e7f6ef;
    color: #087f5b;
}

.summary-icon.blue {
    background: #eaf2ff;
    color: #3478db;
}

.summary-icon.orange {
    background: #fff2e6;
    color: #e58b32;
}

.summary-icon.purple {
    background: #f2ebff;
    color: #8255c7;
}

.summary-card p {
    color: var(--muted);
    font-size: 11px;
    margin: 0 0 5px;
}

.summary-card h3 {
    font-size: 15px;
    margin: 0 0 4px;
    color: var(--text);
}

.summary-card span {
    color: var(--muted);
    font-size: 10px;
}

.content-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 22px;
    margin-bottom: 20px;
}

.card-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 22px;
}

.card-heading h3 {
    color: var(--text);
    font-size: 16px;
    margin: 0 0 5px;
}

.card-heading p {
    color: var(--muted);
    font-size: 12px;
    margin: 0;
}

.info-icon {
    width: 38px;
    height: 38px;
    border-radius: 9px;
    background: #e7f6ef;
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
}

.information-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.information-item {
    border-bottom: 1px solid var(--border);
    padding-bottom: 13px;
}

.information-item span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    margin-bottom: 7px;
}

.information-item strong {
    color: var(--text);
    font-size: 13px;
}

.inactive-text {
    color: #d97706 !important;
}

.wallet-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.action-card {
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 17px;
    display: flex;
    align-items: center;
    gap: 13px;
    flex-wrap: wrap;
}

.action-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}

.deposit-icon {
    background: #e7f6ef;
    color: #087f5b;
}

.withdraw-icon {
    background: #eaf2ff;
    color: #3478db;
}

.action-card h4 {
    font-size: 13px;
    margin: 0 0 5px;
    color: var(--text);
}

.action-card p {
    font-size: 11px;
    color: var(--muted);
    margin: 0;
}

.disabled-badge {
    margin-left: auto;
    font-size: 10px;
    padding: 6px 9px;
    border-radius: 20px;
    background: #fff4e5;
    color: #b7791f;
}

.service-notice {
    margin-top: 17px;
    padding: 12px;
    border-radius: 8px;
    background: #f5f8fa;
    color: #64748b;
    font-size: 11px;
}

.service-notice i {
    margin-right: 6px;
}

.transaction-count {
    padding: 7px 10px;
    border-radius: 20px;
    background: #f1f5f9;
    color: #64748b;
    font-size: 10px;
}

.empty-state {
    text-align: center;
    padding: 35px 15px;
}

.empty-icon {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
    margin: 0 auto 13px;
}

.empty-state h4 {
    font-size: 14px;
    color: var(--text);
    margin: 0 0 7px;
}

.empty-state p {
    color: var(--muted);
    font-size: 11px;
    margin: 0;
}

.wallet-table {
    width: 100%;
    border-collapse: collapse;
}

.wallet-table th,
.wallet-table td {
    padding: 13px;
    text-align: left;
    border-bottom: 1px solid var(--border);
    font-size: 12px;
}

.wallet-table th {
    background: #f8fafc;
    color: #64748b;
}

.security-notice {
    display: flex;
    gap: 14px;
    padding: 18px;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    border-radius: 11px;
    margin-bottom: 20px;
}

.security-icon {
    color: #2563eb;
    font-size: 21px;
}

.security-notice h4 {
    font-size: 13px;
    color: #1e40af;
    margin: 0 0 6px;
}

.security-notice p {
    font-size: 11px;
    line-height: 1.7;
    color: #475569;
    margin: 0;
}

@media(max-width: 1100px) {
    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .information-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width: 600px) {
    .wallet-banner {
        padding: 20px;
    }

    .wallet-banner-right {
        text-align: left;
    }

    .summary-grid {
        grid-template-columns: 1fr;
    }

    .information-grid {
        grid-template-columns: 1fr;
    }

    .wallet-actions {
        grid-template-columns: 1fr;
    }

    .content-card {
        padding: 16px;
    }

    .action-card {
        align-items: flex-start;
    }

    .disabled-badge {
        margin-left: 55px;
    }
}

</style>

@endsection