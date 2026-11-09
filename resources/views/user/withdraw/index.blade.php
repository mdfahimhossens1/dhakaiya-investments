@extends('layouts.app')

@section('title', 'Withdraw')

@section('content')

<style>
    .withdraw-page {
        display: grid;
        gap: 22px;
    }

    .withdraw-hero {
        background: linear-gradient(135deg, #102b26, #087f5b);
        padding: 30px;
        color: #fff;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
    }

    .withdraw-hero h2 {
        font-size: 25px;
        font-weight: 700;
        margin: 0 0 9px;
    }

    .withdraw-hero p {
        color: #d5e9e1;
        font-size: 14px;
        margin: 0;
    }

    .withdraw-hero-icon {
        position: absolute;
        right: 35px;
        top: 20px;
        font-size: 75px;
        opacity: .15;
    }

    .withdraw-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .withdraw-card {
        background: #fff;
        border: 1px solid #e8edf0;
        border-radius: 13px;
        padding: 20px;
    }

    .withdraw-icon {
        width: 43px;
        height: 43px;
        border-radius: 10px;
        background: #e8f5ef;
        color: #087f5b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 15px;
    }

    .withdraw-card h3 {
        font-size: 22px;
        color: #1e293b;
        margin: 0 0 5px;
    }

    .withdraw-card p {
        font-size: 13px;
        color: #84919f;
        margin: 0;
    }

    .withdraw-section {
        background: #fff;
        border: 1px solid #e8edf0;
        border-radius: 13px;
        padding: 24px;
    }

    .withdraw-section h3 {
        font-size: 18px;
        color: #1e293b;
        margin: 0 0 7px;
        font-weight: 700;
    }

    .withdraw-section .section-description {
        color: #84919f;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .withdraw-methods {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .withdraw-method {
        border: 1px solid #e8edf0;
        background: #fafbfc;
        border-radius: 11px;
        padding: 20px;
        text-align: center;
    }

    .withdraw-method i {
        font-size: 27px;
        color: #087f5b;
        margin-bottom: 12px;
    }

    .withdraw-method h4 {
        color: #1e293b;
        font-size: 15px;
        margin: 0 0 6px;
    }

    .withdraw-method p {
        font-size: 12px;
        color: #84919f;
        margin: 0;
    }

    .withdraw-badge {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 30px;
        background: #fff4db;
        color: #946200;
        font-size: 11px;
        font-weight: 700;
        margin-top: 12px;
    }

    .withdraw-notice {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
        border-radius: 10px;
        padding: 16px;
        font-size: 13px;
        line-height: 1.7;
    }

    .withdraw-empty {
        text-align: center;
        padding: 35px 15px;
        background: #fafbfc;
        border: 1px dashed #dce4e8;
        border-radius: 10px;
    }

    .withdraw-empty i {
        font-size: 35px;
        color: #b8c3cc;
        margin-bottom: 12px;
    }

    .withdraw-empty h4 {
        color: #334155;
        font-size: 16px;
        margin: 0 0 7px;
    }

    .withdraw-empty p {
        color: #84919f;
        font-size: 13px;
        margin: 0;
    }

    @media(max-width: 1000px) {
        .withdraw-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 600px) {
        .withdraw-hero {
            padding: 23px;
        }

        .withdraw-hero h2 {
            font-size: 21px;
        }

        .withdraw-hero-icon {
            font-size: 55px;
            right: 15px;
        }

        .withdraw-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .withdraw-card {
            padding: 15px;
        }

        .withdraw-card h3 {
            font-size: 19px;
        }

        .withdraw-section {
            padding: 17px;
        }

        .withdraw-methods {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="withdraw-page">

    {{-- Hero --}}
    <div class="withdraw-hero">

        <div class="withdraw-hero-icon">
            <i class="fa-solid fa-money-bill-transfer"></i>
        </div>

        <h2>Withdraw</h2>

        <p>
            View withdrawal information and your account withdrawal status.
        </p>

    </div>

    {{-- Summary --}}
    <div class="withdraw-grid">

        <div class="withdraw-card">
            <div class="withdraw-icon">
                <i class="fa-solid fa-wallet"></i>
            </div>

            <h3>
                {{ number_format($withdrawalSummary['available_balance'], 2) }}
            </h3>

            <p>Available Balance</p>
        </div>

        <div class="withdraw-card">
            <div class="withdraw-icon">
                <i class="fa-solid fa-arrow-down-wide-short"></i>
            </div>

            <h3>
                {{ number_format($withdrawalSummary['minimum_withdrawal'], 2) }}
            </h3>

            <p>Minimum Withdrawal</p>
        </div>

        <div class="withdraw-card">
            <div class="withdraw-icon">
                <i class="fa-solid fa-clock"></i>
            </div>

            <h3>
                {{ $withdrawalSummary['pending_withdrawals'] }}
            </h3>

            <p>Pending Withdrawals</p>
        </div>

        <div class="withdraw-card">
            <div class="withdraw-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <h3>
                {{ $withdrawalSummary['completed_withdrawals'] }}
            </h3>

            <p>Completed Withdrawals</p>
        </div>

    </div>

    {{-- Withdrawal Methods --}}
    <div class="withdraw-section">

        <h3>
            <i class="fa-solid fa-building-columns"
               style="color:#087f5b;"></i>
            Withdrawal Methods
        </h3>

        <p class="section-description">
            Available withdrawal methods will be displayed here.
        </p>

        @if($withdrawalMethods->isEmpty())

            <div class="withdraw-methods">

                <div class="withdraw-method">
                    <i class="fa-solid fa-building-columns"></i>
                    <h4>Bank Transfer</h4>
                    <p>International bank transfer method.</p>
                    <span class="withdraw-badge">Not Configured</span>
                </div>

                <div class="withdraw-method">
                    <i class="fa-solid fa-credit-card"></i>
                    <h4>Digital Payment</h4>
                    <p>Supported digital payment methods.</p>
                    <span class="withdraw-badge">Not Configured</span>
                </div>

                <div class="withdraw-method">
                    <i class="fa-solid fa-wallet"></i>
                    <h4>Wallet Transfer</h4>
                    <p>Supported wallet withdrawal method.</p>
                    <span class="withdraw-badge">Not Configured</span>
                </div>

            </div>

        @endif

    </div>

    {{-- Withdrawal History --}}
    <div class="withdraw-section">

        <h3>
            <i class="fa-solid fa-clock-rotate-left"
               style="color:#087f5b;"></i>
            Recent Withdrawal Activity
        </h3>

        <p class="section-description">
            Your withdrawal activity summary.
        </p>

        <div class="withdraw-empty">

            <i class="fa-solid fa-receipt"></i>

            <h4>No Withdrawal Activity</h4>

            <p>
                Your withdrawal records will appear here when available.
            </p>

        </div>

    </div>

    {{-- Notice --}}
    <div class="withdraw-notice">

        <i class="fa-solid fa-circle-info"></i>

        <strong>Information:</strong>

        Withdrawal services are currently not configured.
        No withdrawal requests or money transfers can be made through this page.

    </div>

</div>

@endsection