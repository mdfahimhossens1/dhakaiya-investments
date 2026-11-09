@extends('layouts.app')

@section('title', 'Withdrawal Methods')

@section('content')

<style>
    .withdrawal-methods-page {
        padding: 10px 0 30px;
    }

    .wm-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .wm-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 6px;
    }

    .wm-header p {
        color: var(--muted);
        margin: 0;
        font-size: 14px;
    }

    .wm-summary {
        background: linear-gradient(135deg, #087f5b, #065f46);
        color: #fff;
        border-radius: 14px;
        padding: 25px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .wm-summary h3 {
        font-size: 19px;
        font-weight: 700;
        margin: 0 0 8px;
    }

    .wm-summary p {
        margin: 0;
        font-size: 13px;
        opacity: .85;
    }

    .wm-summary-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        background: rgba(255,255,255,.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
    }

    .wm-section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 15px;
    }

    .wm-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .wm-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 13px;
        padding: 22px;
        transition: .2s;
    }

    .wm-card:hover {
        border-color: var(--primary);
        transform: translateY(-3px);
    }

    .wm-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 18px;
    }

    .wm-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: #e8f5ef;
        color: var(--primary);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 21px;
    }

    .wm-status {
        background: #f1f5f9;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        padding: 6px 10px;
        border-radius: 20px;
    }

    .wm-card h4 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 7px;
    }

    .wm-card p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
        line-height: 1.6;
    }

    .wm-card-footer {
        border-top: 1px solid var(--border);
        margin-top: 18px;
        padding-top: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .wm-card-footer span {
        color: var(--muted);
        font-size: 12px;
    }

    .wm-card-footer strong {
        color: var(--text);
        font-size: 12px;
    }

    .wm-notice {
        margin-top: 25px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 12px;
        padding: 17px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .wm-notice i {
        color: #2563eb;
        font-size: 19px;
        margin-top: 2px;
    }

    .wm-notice h5 {
        font-size: 14px;
        font-weight: 700;
        color: #1e40af;
        margin: 0 0 5px;
    }

    .wm-notice p {
        color: #475569;
        font-size: 12px;
        margin: 0;
        line-height: 1.7;
    }

    @media(max-width: 900px) {
        .wm-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media(max-width: 600px) {
        .wm-grid {
            grid-template-columns: 1fr;
        }

        .wm-header h2 {
            font-size: 21px;
        }

        .wm-summary {
            padding: 20px;
        }
    }
</style>

<div class="withdrawal-methods-page">

    {{-- Page Header --}}
    <div class="wm-header">
        <div>
            <h2>Withdrawal Methods</h2>
            <p>View the available withdrawal method categories.</p>
        </div>
    </div>

    {{-- Summary --}}
    <div class="wm-summary">
        <div>
            <h3>Withdrawal Method Overview</h3>
            <p>Review the available method categories and their current status.</p>
        </div>

        <div class="wm-summary-icon">
            <i class="fa-solid fa-money-bill-transfer"></i>
        </div>
    </div>

    {{-- Methods --}}
    <h3 class="wm-section-title">Available Method Categories</h3>

    <div class="wm-grid">

        @forelse($withdrawalMethods as $method)

            <div class="wm-card">

                <div class="wm-card-top">
                    <div class="wm-icon">
                        <i class="{{ $method['icon'] }}"></i>
                    </div>

                    <span class="wm-status">
                        {{ $method['status'] }}
                    </span>
                </div>

                <h4>{{ $method['name'] }}</h4>

                <p>{{ $method['description'] }}</p>

                <div class="wm-card-footer">
                    <span>Current Status</span>
                    <strong>{{ $method['status'] }}</strong>
                </div>

            </div>

        @empty

            <div class="wm-card">
                <h4>No Methods Available</h4>
                <p>There are currently no withdrawal method categories to display.</p>
            </div>

        @endforelse

    </div>

    {{-- Notice --}}
    <div class="wm-notice">
        <i class="fa-solid fa-circle-info"></i>

        <div>
            <h5>Important Information</h5>
            <p>
                This page currently provides informational details only.
                Withdrawal methods are not activated, and no payment,
                account registration, or withdrawal processing is available.
            </p>
        </div>
    </div>

</div>

@endsection