@extends('layouts.app')

@section('title', 'Deposit History')

@section('content')

<div class="deposit-page">

    {{-- Page Heading --}}
    <div class="page-heading">
        <div>
            <h2>Deposit History</h2>
            <p>View and track your wallet deposit records.</p>
        </div>

        <div class="breadcrumb">
            <a href="{{ route('user.dashboard') }}">Dashboard</a>
            <span>/</span>
            <a href="{{ route('user.wallet.index') }}">My Wallet</a>
            <span>/</span>
            <span>Deposit History</span>
        </div>
    </div>


    {{-- Summary --}}
    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-icon green">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div>
                <p>Total Deposits</p>
                <h3>{{ $summary['total_deposits'] }}</h3>
                <span>Recorded deposits</span>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon blue">
                <i class="fas fa-coins"></i>
            </div>
            <div>
                <p>BDT Deposits</p>
                <h3>Not Available</h3>
                <span>BDT wallet records</span>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon purple">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div>
                <p>USD Deposits</p>
                <h3>Not Available</h3>
                <span>USD wallet records</span>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon orange">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p>Pending Deposits</p>
                <h3>{{ $summary['pending_deposits'] }}</h3>
                <span>Awaiting processing</span>
            </div>
        </div>

    </div>


    {{-- History Card --}}
    <div class="content-card">

        <div class="card-heading">

            <div>
                <h3>Deposit Transactions</h3>
                <p>Your complete wallet deposit history.</p>
            </div>

            <span class="history-badge">
                <i class="fas fa-history"></i>
                History
            </span>

        </div>


        {{-- Filters --}}
        <form method="GET"
              action="{{ route('user.deposit-history.index') }}"
              class="filter-form">

            <div class="filter-group">

                <label for="currency">Filter by Currency</label>

                <select name="currency" id="currency">

                    <option value="all"
                        {{ $currency === 'all' ? 'selected' : '' }}>
                        All Currencies
                    </option>

                    <option value="BDT"
                        {{ $currency === 'BDT' ? 'selected' : '' }}>
                        BDT - Bangladeshi Taka
                    </option>

                    <option value="USD"
                        {{ $currency === 'USD' ? 'selected' : '' }}>
                        USD - US Dollar
                    </option>

                </select>

            </div>

            <button type="submit" class="filter-btn">
                <i class="fas fa-filter"></i>
                Apply Filter
            </button>

            <a href="{{ route('user.deposit-history.index') }}"
               class="reset-btn">
                Reset
            </a>

        </form>


        {{-- Table --}}
        <div class="table-responsive">

            <table class="deposit-table">

                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Date</th>
                        <th>Wallet</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($deposits as $deposit)

                        <tr>
                            <td>{{ $deposit->transaction_id }}</td>
                            <td>{{ $deposit->created_at->format('d M Y') }}</td>
                            <td>{{ $deposit->currency }}</td>
                            <td>{{ $deposit->payment_method }}</td>
                            <td>
                                {{ $deposit->currency === 'BDT' ? '৳' : '$' }}
                                {{ number_format($deposit->amount, 2) }}
                            </td>
                            <td>{{ ucfirst($deposit->status) }}</td>
                            <td>
                                <span class="detail-badge">View</span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="fas fa-receipt"></i>
                                    </div>

                                    <h4>No Deposit Records Found</h4>

                                    <p>
                                        Your deposit history will appear here
                                        when deposit services become available.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Information Notice --}}
    <div class="notice-card">

        <div class="notice-icon">
            <i class="fas fa-info-circle"></i>
        </div>

        <div>
            <h4>Deposit History Information</h4>

            <p>
                This page currently displays an empty history.
                Deposit records will be available after the platform's
                payment processing system is properly configured and
                authorized.
            </p>
        </div>

    </div>

</div>


<style>

.deposit-page {
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
    font-size: 17px;
}

.summary-icon.green {
    background: #e7f6ef;
    color: #087f5b;
}

.summary-icon.blue {
    background: #eaf2ff;
    color: #3478db;
}

.summary-icon.purple {
    background: #f2ebff;
    color: #8255c7;
}

.summary-icon.orange {
    background: #fff2e6;
    color: #e58b32;
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

.history-badge {
    background: #e7f6ef;
    color: #087f5b;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 11px;
}

.filter-form {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    flex-wrap: wrap;
    background: #f8fafc;
    padding: 16px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 7px;
    min-width: 240px;
}

.filter-group label {
    font-size: 11px;
    font-weight: 600;
    color: var(--text);
}

.filter-group select {
    height: 40px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 7px;
    background: white;
    color: var(--text);
    font-size: 12px;
    outline: none;
}

.filter-btn,
.reset-btn {
    height: 40px;
    padding: 0 16px;
    border: none;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    font-size: 12px;
    text-decoration: none;
    cursor: pointer;
}

.filter-btn {
    background: var(--primary);
    color: white;
}

.reset-btn {
    background: #e9eef2;
    color: #475569;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.deposit-table {
    width: 100%;
    min-width: 750px;
    border-collapse: collapse;
}

.deposit-table th,
.deposit-table td {
    padding: 14px;
    text-align: left;
    border-bottom: 1px solid var(--border);
    font-size: 12px;
}

.deposit-table th {
    background: #f8fafc;
    color: #64748b;
    font-weight: 600;
}

.empty-state {
    text-align: center;
    padding: 40px 15px;
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

.detail-badge {
    background: #e7f6ef;
    color: #087f5b;
    padding: 5px 10px;
    border-radius: 15px;
}

.notice-card {
    display: flex;
    gap: 14px;
    padding: 18px;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    border-radius: 11px;
    margin-bottom: 20px;
}

.notice-icon {
    color: #2563eb;
    font-size: 21px;
}

.notice-card h4 {
    font-size: 13px;
    color: #1e40af;
    margin: 0 0 6px;
}

.notice-card p {
    font-size: 11px;
    line-height: 1.7;
    color: #475569;
    margin: 0;
}

@media(max-width: 1100px) {
    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width: 600px) {
    .summary-grid {
        grid-template-columns: 1fr;
    }

    .content-card {
        padding: 16px;
    }

    .filter-group {
        width: 100%;
        min-width: 100%;
    }

    .filter-btn,
    .reset-btn {
        flex: 1;
    }
}

</style>

@endsection