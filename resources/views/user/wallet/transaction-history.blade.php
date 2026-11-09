@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')

<style>
    .transaction-page {
        padding: 5px 0 25px;
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
        margin: 0 0 5px;
    }

    .page-heading p {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .transaction-badge {
        background: #e8f5ef;
        color: #087f5b;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .transaction-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .summary-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
    }

    .summary-card .label {
        font-size: 12px;
        color: var(--muted);
        margin-bottom: 12px;
    }

    .summary-card h3 {
        font-size: 25px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    .summary-card small {
        display: block;
        margin-top: 8px;
        font-size: 11px;
        color: var(--muted);
    }

    .transaction-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .transaction-card-header {
        padding: 20px;
        border-bottom: 1px solid var(--border);
    }

    .transaction-card-header h4 {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 5px;
        color: var(--text);
    }

    .transaction-card-header p {
        font-size: 12px;
        color: var(--muted);
        margin: 0;
    }

    .filter-section {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        padding: 18px 20px;
        background: #fafcfc;
        border-bottom: 1px solid var(--border);
    }

    .filter-section select {
        min-width: 170px;
        padding: 10px 13px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: white;
        color: var(--text);
        font-size: 13px;
        outline: none;
    }

    .filter-btn {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .filter-btn:hover {
        background: var(--primary-dark);
    }

    .reset-btn {
        display: inline-flex;
        align-items: center;
        padding: 10px 16px;
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        text-decoration: none;
        font-size: 13px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .transaction-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 750px;
    }

    .transaction-table th {
        background: #f8fafb;
        color: var(--muted);
        font-size: 11px;
        font-weight: 600;
        padding: 15px 18px;
        text-align: left;
        white-space: nowrap;
    }

    .transaction-table td {
        padding: 16px 18px;
        border-top: 1px solid var(--border);
        font-size: 12px;
        color: var(--text);
    }

    .empty-state {
        text-align: center;
        padding: 55px 20px;
    }

    .empty-icon {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: #eef7f3;
        color: var(--primary);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 27px;
        margin: 0 auto 15px;
    }

    .empty-state h4 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 7px;
    }

    .empty-state p {
        color: var(--muted);
        font-size: 12px;
        max-width: 380px;
        margin: auto;
        line-height: 1.7;
    }

    .security-notice {
        background: #f0f7ff;
        border: 1px solid #dcecff;
        border-radius: 10px;
        padding: 16px 18px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .security-notice-icon {
        color: #2563eb;
        font-size: 18px;
    }

    .security-notice h5 {
        font-size: 13px;
        font-weight: 700;
        color: #1e40af;
        margin: 0 0 5px;
    }

    .security-notice p {
        font-size: 12px;
        color: #475569;
        margin: 0;
        line-height: 1.6;
    }

    @media(max-width: 1000px) {
        .transaction-summary {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 576px) {
        .transaction-summary {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .summary-card {
            padding: 15px;
        }

        .summary-card h3 {
            font-size: 21px;
        }

        .filter-section select,
        .filter-btn,
        .reset-btn {
            width: 100%;
        }
    }
</style>

<div class="transaction-page">

    {{-- Page Heading --}}
    <div class="page-heading">
        <div>
            <h2>Transaction History</h2>
            <p>View and monitor your account transaction records.</p>
        </div>

        <span class="transaction-badge">
            <i class="fas fa-history"></i>
            Transaction Records
        </span>
    </div>


    {{-- Summary Cards --}}
    <div class="transaction-summary">

        <div class="summary-card">
            <div class="label">Total Transactions</div>
            <h3>{{ number_format($summary['total_transactions']) }}</h3>
            <small>All transaction records</small>
        </div>

        <div class="summary-card">
            <div class="label">Total Deposits</div>
            <h3>{{ number_format($summary['total_deposits']) }}</h3>
            <small>Deposit transactions</small>
        </div>

        <div class="summary-card">
            <div class="label">Total Withdrawals</div>
            <h3>{{ number_format($summary['total_withdrawals']) }}</h3>
            <small>Withdrawal transactions</small>
        </div>

        <div class="summary-card">
            <div class="label">Other Transactions</div>
            <h3>{{ number_format($summary['other_transactions']) }}</h3>
            <small>Transfer and other records</small>
        </div>

    </div>


    {{-- Transaction History --}}
    <div class="transaction-card">

        <div class="transaction-card-header">
            <h4>All Transactions</h4>
            <p>Your complete transaction activity will appear here.</p>
        </div>


        {{-- Filters --}}
        <form method="GET"
              action="{{ route('user.transaction-history.index') }}"
              class="filter-section">

            <select name="currency">
                <option value="all" {{ $currency === 'all' ? 'selected' : '' }}>
                    All Currencies
                </option>

                <option value="BDT" {{ $currency === 'BDT' ? 'selected' : '' }}>
                    BDT Wallet
                </option>

                <option value="USD" {{ $currency === 'USD' ? 'selected' : '' }}>
                    USD Wallet
                </option>
            </select>


            <select name="type">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>
                    All Transaction Types
                </option>

                <option value="deposit" {{ $type === 'deposit' ? 'selected' : '' }}>
                    Deposit
                </option>

                <option value="withdrawal" {{ $type === 'withdrawal' ? 'selected' : '' }}>
                    Withdrawal
                </option>

                <option value="transfer" {{ $type === 'transfer' ? 'selected' : '' }}>
                    Transfer
                </option>

                <option value="other" {{ $type === 'other' ? 'selected' : '' }}>
                    Other
                </option>
            </select>


            <button type="submit" class="filter-btn">
                <i class="fas fa-filter"></i>
                Apply Filter
            </button>

            <a href="{{ route('user.transaction-history.index') }}"
               class="reset-btn">
                Reset
            </a>

        </form>


        {{-- Transaction Table --}}
        <div class="table-responsive">

            <table class="transaction-table">

                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Date & Time</th>
                        <th>Transaction Type</th>
                        <th>Wallet</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($transactions as $transaction)

                        <tr>
                            <td>{{ $transaction->transaction_id }}</td>

                            <td>
                                {{ $transaction->created_at->format('d M Y, h:i A') }}
                            </td>

                            <td>{{ ucfirst($transaction->type) }}</td>

                            <td>{{ $transaction->currency }}</td>

                            <td>
                                {{ number_format($transaction->amount, 2) }}
                            </td>

                            <td>{{ ucfirst($transaction->status) }}</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="fas fa-exchange-alt"></i>
                                    </div>

                                    <h4>No Transactions Found</h4>

                                    <p>
                                        You don't have any transaction records yet.
                                        Once the transaction system is activated
                                        and transactions are recorded, they will
                                        appear here.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Security Notice --}}
    <div class="security-notice">

        <div class="security-notice-icon">
            <i class="fas fa-shield-alt"></i>
        </div>

        <div>
            <h5>Transaction Security</h5>

            <p>
                Transaction records should be generated from verified
                server-side financial records. Never trust transaction
                amounts or statuses submitted directly from the browser.
            </p>
        </div>

    </div>

</div>

@endsection