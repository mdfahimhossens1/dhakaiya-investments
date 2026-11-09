@extends('layouts.app')

@section('title', 'Wallet Overview')

@section('content')

<div class="wallet-page">

    {{-- Page Heading --}}

    <div class="page-heading">

        <div class="breadcrumb">
            <a href="{{ route('user.dashboard') }}">Dashboard</a>
            <span>/</span>
            <strong>My Wallet</strong>
        </div>

        <h1>Wallet Overview</h1>

        <p>
            Manage and view your wallet information.
        </p>

    </div>


    {{-- Wallet Status Banner --}}

    <div class="wallet-banner">

        <div class="banner-content">

            <div class="banner-icon">
                💳
            </div>

            <div>

                <span class="banner-label">
                    WALLET ACCOUNT
                </span>

                <h2>My Wallet</h2>

                <p>
                    Your wallet services are currently not activated.
                </p>

            </div>

        </div>

        <div class="banner-status">
            <span></span>
            Not Activated
        </div>

    </div>


    {{-- Summary Cards --}}

    <div class="wallet-summary-grid">

        <div class="summary-card">

            <div class="summary-card-top">
                <div class="summary-icon green">💼</div>
                <span>Wallets</span>
            </div>

            <h3>{{ $summary['total_wallets'] }}</h3>

            <p>Available wallet types</p>

        </div>


        <div class="summary-card">

            <div class="summary-card-top">
                <div class="summary-icon blue">✓</div>
                <span>Active Wallets</span>
            </div>

            <h3>{{ $summary['active_wallets'] }}</h3>

            <p>Currently activated wallets</p>

        </div>


        <div class="summary-card">

            <div class="summary-card-top">
                <div class="summary-icon orange">📥</div>
                <span>Total Deposits</span>
            </div>

            <h3>Unavailable</h3>

            <p>Transaction data not available</p>

        </div>


        <div class="summary-card">

            <div class="summary-card-top">
                <div class="summary-icon purple">📤</div>
                <span>Total Withdrawals</span>
            </div>

            <h3>Unavailable</h3>

            <p>Transaction data not available</p>

        </div>

    </div>


    {{-- Wallet Accounts --}}

    <div class="section-heading">

        <div>
            <h3>My Wallet Accounts</h3>

            <p>
                View your available wallet types and their activation status.
            </p>
        </div>

        <span class="section-badge">
            {{ count($wallets) }} Wallets
        </span>

    </div>


    <div class="wallet-grid">

        @foreach($wallets as $wallet)

            <div class="wallet-card">

                <div class="wallet-card-header">

                    <div class="wallet-currency-icon">
                        {{ $wallet['icon'] }}
                    </div>

                    <span class="wallet-inactive-badge">
                        {{ $wallet['status'] }}
                    </span>

                </div>


                <div class="wallet-card-body">

                    <h3>{{ $wallet['name'] }}</h3>

                    <p>{{ $wallet['description'] }}</p>

                    <div class="wallet-balance-label">
                        AVAILABLE BALANCE
                    </div>

                    <div class="wallet-balance">
                        <span>{{ $wallet['symbol'] }}</span>
                        <strong>— — —</strong>
                    </div>

                    <div class="wallet-card-footer">

                        <span>Wallet Status</span>

                        <strong>
                            Not Activated
                        </strong>

                    </div>

                </div>

            </div>

        @endforeach

    </div>


    {{-- Transaction Summary --}}

    <div class="transaction-card">

        <div class="transaction-heading">

            <div>
                <h3>Transaction Summary</h3>

                <p>
                    Overview of your wallet transactions.
                </p>
            </div>

            <span class="pending-badge">
                Not Available
            </span>

        </div>


        <div class="transaction-empty">

            <div class="empty-icon">
                📊
            </div>

            <h4>Transaction Data Not Available</h4>

            <p>
                Your transaction history will appear here
                when the wallet system is activated.
            </p>

        </div>

    </div>


    {{-- Wallet Notice --}}

    <div class="wallet-notice">

        <div class="notice-icon">
            🔐
        </div>

        <div>

            <h4>Wallet Security Notice</h4>

            <p>
                Never share your account password, PIN, OTP or other
                confidential payment credentials with anyone.
                Wallet deposits and withdrawals will be enabled only
                after the required security and compliance arrangements.
            </p>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

.wallet-page {
    max-width: 1200px;
    margin: 0 auto;
}


/* Page Heading */

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


/* Wallet Banner */

.wallet-banner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    background: linear-gradient(120deg, #087f5b, #065f46);
    border-radius: 14px;
    padding: 27px;
    color: white;
    margin-bottom: 23px;
}

.banner-content {
    display: flex;
    align-items: center;
    gap: 17px;
}

.banner-icon {
    width: 62px;
    height: 62px;
    background: rgba(255,255,255,.15);
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 29px;
    flex-shrink: 0;
}

.banner-label {
    font-size: 10px;
    letter-spacing: 1px;
    opacity: .75;
    font-weight: 700;
}

.banner-content h2 {
    color: white;
    font-size: 22px;
    margin: 5px 0;
}

.banner-content p {
    color: rgba(255,255,255,.85);
    font-size: 12px;
    margin: 0;
}

.banner-status {
    background: rgba(255,255,255,.15);
    padding: 9px 13px;
    border-radius: 30px;
    font-size: 11px;
    white-space: nowrap;
}

.banner-status span {
    display: inline-block;
    width: 7px;
    height: 7px;
    background: #fcd34d;
    border-radius: 50%;
    margin-right: 5px;
}


/* Summary Cards */

.wallet-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 30px;
}

.summary-card {
    background: white;
    border: 1px solid #e8edf0;
    border-radius: 12px;
    padding: 19px;
}

.summary-card-top {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 17px;
}

.summary-card-top > span {
    color: #64748b;
    font-size: 11px;
}

.summary-icon {
    width: 37px;
    height: 37px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    font-size: 16px;
}

.summary-icon.green {
    background: #dcfce7;
}

.summary-icon.blue {
    background: #dbeafe;
}

.summary-icon.orange {
    background: #ffedd5;
}

.summary-icon.purple {
    background: #f3e8ff;
}

.summary-card h3 {
    color: #1e293b;
    font-size: 21px;
    margin: 0 0 6px;
}

.summary-card p {
    color: #94a3b8;
    font-size: 11px;
    margin: 0;
}


/* Section Heading */

.section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 17px;
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

.section-badge {
    background: #effaf5;
    color: #087f5b;
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}


/* Wallet Cards */

.wallet-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 25px;
}

.wallet-card {
    background: white;
    border: 1px solid #e8edf0;
    border-radius: 13px;
    padding: 22px;
    transition: .2s;
}

.wallet-card:hover {
    border-color: #b7dfcf;
    box-shadow: 0 5px 18px rgba(15,23,42,.04);
}

.wallet-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 21px;
}

.wallet-currency-icon {
    width: 48px;
    height: 48px;
    background: #effaf5;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 25px;
}

.wallet-inactive-badge {
    color: #64748b;
    background: #f1f5f9;
    padding: 7px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
}

.wallet-card-body h3 {
    color: #263445;
    font-size: 16px;
    margin: 0 0 5px;
}

.wallet-card-body > p {
    color: #94a3b8;
    font-size: 11px;
    margin: 0 0 21px;
}

.wallet-balance-label {
    color: #94a3b8;
    font-size: 10px;
    letter-spacing: .7px;
    font-weight: 700;
    margin-bottom: 8px;
}

.wallet-balance {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #1e293b;
    margin-bottom: 22px;
}

.wallet-balance span {
    font-size: 19px;
    font-weight: 600;
}

.wallet-balance strong {
    font-size: 20px;
    letter-spacing: 2px;
}

.wallet-card-footer {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    border-top: 1px solid #edf0f2;
    padding-top: 14px;
    font-size: 11px;
}

.wallet-card-footer span {
    color: #94a3b8;
}

.wallet-card-footer strong {
    color: #64748b;
}


/* Transaction Card */

.transaction-card {
    background: white;
    border: 1px solid #e8edf0;
    border-radius: 13px;
    padding: 23px;
    margin-bottom: 20px;
}

.transaction-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    border-bottom: 1px solid #edf0f2;
    padding-bottom: 17px;
}

.transaction-heading h3 {
    color: #1e293b;
    font-size: 15px;
    margin: 0 0 5px;
}

.transaction-heading p {
    color: #94a3b8;
    font-size: 11px;
    margin: 0;
}

.pending-badge {
    background: #f1f5f9;
    color: #64748b;
    padding: 7px 10px;
    border-radius: 20px;
    font-size: 10px;
}

.transaction-empty {
    text-align: center;
    padding: 35px 15px;
}

.empty-icon {
    font-size: 35px;
    margin-bottom: 12px;
}

.transaction-empty h4 {
    color: #334155;
    font-size: 14px;
    margin: 0 0 7px;
}

.transaction-empty p {
    color: #94a3b8;
    font-size: 11px;
    line-height: 1.7;
    max-width: 400px;
    margin: 0 auto;
}


/* Notice */

.wallet-notice {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 19px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 11px;
}

.notice-icon {
    font-size: 20px;
}

.wallet-notice h4 {
    color: #92400e;
    font-size: 12px;
    margin: 0 0 6px;
}

.wallet-notice p {
    color: #78716c;
    font-size: 11px;
    line-height: 1.7;
    margin: 0;
}


/* Responsive */

@media(max-width: 950px) {

    .wallet-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media(max-width: 650px) {

    .wallet-banner {
        flex-direction: column;
        align-items: flex-start;
        padding: 20px;
    }

    .wallet-grid {
        grid-template-columns: 1fr;
    }

    .wallet-summary-grid {
        gap: 11px;
    }

    .summary-card {
        padding: 14px;
    }

    .summary-card h3 {
        font-size: 18px;
    }

    .transaction-card {
        padding: 17px;
    }

}

</style>

@endpush