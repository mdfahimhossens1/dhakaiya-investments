@extends('layouts.app')

@section('title', 'Income History')

@section('content')
<div class="income-page">

    {{-- ===== Page Header ===== --}}
    <div class="page-head">
        <div>
            <h1 class="page-title">Income History</h1>
            <p class="page-sub">Entirely your profit and income</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('user.dashboard') }}" class="btn btn-light">← Dashboard</a>
        </div>
    </div>

    {{-- ===== Stat Cards ===== --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon">৳</div>
            <div class="stat-body">
                <span class="stat-label">Total Income</span>
                <h3 class="stat-value">৳ {{ number_format((float) $totalIncome, 2) }}</h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">▦</div>
            <div class="stat-body">
                <span class="stat-label">This Month</span>
                <h3 class="stat-value">৳ {{ number_format((float) $thisMonth, 2) }}</h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">◷</div>
            <div class="stat-body">
                <span class="stat-label">Last Income ({{ $lastDate ?? '—' }})</span>
                <h3 class="stat-value">৳ {{ number_format((float) $lastIncome, 2) }}</h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">▤</div>
            <div class="stat-body">
                <span class="stat-label">Total Records</span>
                <h3 class="stat-value">{{ number_format($recordsCount) }}</h3>
            </div>
        </div>
    </div>

    {{-- ===== Currency Breakdown ===== --}}
    @if($byCurrency->count() > 1)
    <div class="currency-row">
        @foreach($byCurrency as $c)
            <div class="currency-chip">
                <span class="chip-code">{{ $c->wallet_currency }}</span>
                <span class="chip-amount">{{ number_format((float) $c->total, 2) }}</span>
                <span class="chip-bdt">≈ ৳ {{ number_format((float) $c->bdt, 2) }}</span>
            </div>
        @endforeach
    </div>
    @endif

    {{-- ===== Filters ===== --}}
    <div class="card">
        <form method="GET" action="{{ route('user.income-history.index') }}" class="filter-form">
            <div class="filter-field">
                <label>Search</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Currency / Note...">
            </div>

            <div class="filter-field">
                <label>From Date</label>
                <input type="date" name="from" value="{{ request('from') }}">
            </div>

            <div class="filter-field">
                <label>To Date</label>
                <input type="date" name="to" value="{{ request('to') }}">
            </div>

            <div class="filter-field">
                <label>Currency</label>
                <select name="currency">
                    <option value="">All Currencies</option>
                    @foreach($currencies as $cur)
                        <option value="{{ $cur }}" {{ request('currency') === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('user.income-history.index') }}" class="btn btn-light">Reset</a>
            </div>
        </form>
    </div>

    {{-- ===== Table ===== --}}
    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Income Records</h2>
            <span class="card-count">{{ $incomes->total() }} records</span>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Currency</th>
                        <th class="text-right">Wallet Amount</th>
                        <th class="text-right">Income (BDT)</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incomes as $i => $income)
                        <tr>
                            <td class="muted">{{ $incomes->firstItem() + $i }}</td>
                            <td>{{ \Carbon\Carbon::parse($income->profit_date)->format('d M, Y') }}</td>
                            <td><span class="badge">{{ $income->wallet_currency }}</span></td>
                            <td class="text-right">{{ number_format((float) $income->wallet_amount, 2) }}</td>
                            <td class="text-right income-amount">৳ {{ number_format((float) $income->profit_bdt, 2) }}</td>
                            <td class="muted">{{ $income->note ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">▤</div>
                                    <h4>No income record found</h4>
                                    <p></p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== Pagination ===== --}}
        @if($incomes->hasPages())
        <div class="pagination-wrap">
            {{ $incomes->links() }}
        </div>
        @endif
    </div>

</div>
@endsection


{{-- ===================== STYLES ===================== --}}
@push('styles')
<style>
    .income-page { padding: 4px 0 30px; }

    /* Header */
    .page-head {
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 16px; flex-wrap: wrap; margin-bottom: 22px;
    }
    .page-title { font-size: 26px; font-weight: 800; color: #172b25; margin: 0 0 4px; }
    .page-sub { color: #7c8b84; font-size: 14px; margin: 0; }

    /* Buttons */
    .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        height: 42px; padding: 0 18px; border-radius: 10px; font-size: 14px;
        font-weight: 700; text-decoration: none; cursor: pointer; border: 1px solid transparent;
        transition: all .2s;
    }
    .btn-primary { background: #087f5b; color: #fff; }
    .btn-primary:hover { background: #066a4c; }
    .btn-light { background: #fff; color: #475569; border-color: #e5ebe8; }
    .btn-light:hover { background: #f0fdf4; color: #087f5b; border-color: #087f5b; }

    /* Stat grid */
    .stat-grid {
        display: grid; gap: 16px; margin-bottom: 22px;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }
    .stat-card {
        display: flex; align-items: center; gap: 14px;
        background: #fff; border: 1px solid #edf1ef; border-radius: 14px;
        padding: 18px 20px; box-shadow: 0 2px 10px rgba(15,23,42,.03);
    }
    .stat-icon {
        width: 48px; height: 48px; flex-shrink: 0; border-radius: 12px;
        display: grid; place-items: center; font-size: 20px; font-weight: 800;
        background: #dcfce7; color: #087f5b;
    }
    .stat-label { font-size: 12px; font-weight: 700; color: #87948e; text-transform: uppercase; letter-spacing: .5px; }
    .stat-value { font-size: 20px; font-weight: 800; color: #172b25; margin: 4px 0 0; }

    /* Currency chips */
    .currency-row { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 22px; }
    .currency-chip {
        display: flex; align-items: center; gap: 10px;
        background: #fff; border: 1px solid #edf1ef; border-radius: 999px;
        padding: 8px 14px; font-size: 13px;
    }
    .chip-code { font-weight: 800; color: #087f5b; }
    .chip-amount { color: #172b25; font-weight: 700; }
    .chip-bdt { color: #87948e; font-size: 12px; }

    /* Card */
    .card {
        background: #fff; border: 1px solid #edf1ef; border-radius: 14px;
        padding: 20px; margin-bottom: 22px; box-shadow: 0 2px 10px rgba(15,23,42,.03);
    }
    .card-head {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; margin-bottom: 16px;
    }
    .card-title { font-size: 17px; font-weight: 800; color: #172b25; margin: 0; }
    .card-count { font-size: 12px; color: #87948e; font-weight: 600; }

    /* Filter form */
    .filter-form {
        display: grid; gap: 14px;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        align-items: end;
    }
    .filter-field { display: flex; flex-direction: column; gap: 6px; }
    .filter-field label {
        font-size: 12px; font-weight: 700; color: #475569;
        text-transform: uppercase; letter-spacing: .4px;
    }
    .filter-field input, .filter-field select {
        height: 42px; padding: 0 12px; border: 1px solid #e5ebe8;
        border-radius: 10px; font-size: 14px; color: #172b25; background: #fff;
        outline: none; transition: border-color .2s;
    }
    .filter-field input:focus, .filter-field select:focus { border-color: #087f5b; }
    .filter-actions { display: flex; gap: 10px; }

    /* Table */
    .table-wrap { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; min-width: 650px; }
    .data-table thead th {
        text-align: left; font-size: 12px; font-weight: 800; color: #87948e;
        text-transform: uppercase; letter-spacing: .5px;
        padding: 12px 14px; border-bottom: 2px solid #f0f2f4; background: #fafcfb;
    }
    .data-table tbody td {
        padding: 14px; font-size: 14px; color: #24352e;
        border-bottom: 1px solid #f3f6f4;
    }
    .data-table tbody tr:hover { background: #f7fcf8; }
    .text-right { text-align: right !important; }
    .muted { color: #87948e; }
    .income-amount { color: #087f5b; font-weight: 800; }
    .badge {
        display: inline-block; padding: 3px 10px; border-radius: 999px;
        font-size: 12px; font-weight: 700;
        background: #dcfce7; color: #087f5b;
    }

    /* Empty state */
    .empty-state { text-align: center; padding: 50px 20px; }
    .empty-icon {
        width: 64px; height: 64px; margin: 0 auto 14px; border-radius: 50%;
        background: #f0fdf4; color: #087f5b; display: grid; place-items: center;
        font-size: 26px;
    }
    .empty-state h4 { font-size: 16px; color: #172b25; margin: 0 0 6px; }
    .empty-state p { font-size: 13px; color: #87948e; margin: 0; }

    /* Pagination */
    .pagination-wrap {
        margin-top: 18px; display: flex; justify-content: flex-end;
        overflow-x: auto;
    }
    .pagination-wrap svg { width: 16px; height: 16px; }

    @media (max-width: 576px) {
        .page-title { font-size: 22px; }
        .stat-value { font-size: 18px; }
        .card { padding: 16px; }
    }
</style>
@endpush