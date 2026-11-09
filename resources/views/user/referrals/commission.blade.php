@extends('layouts.app')

@section('title', 'Referral Commission')

@section('content')

<div class="page-heading">
    <div>
        <h2>Referral Commission</h2>
        <p>View referral commission information and records.</p>
    </div>
</div>

<style>
    .commission-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .commission-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .commission-card,
    .commission-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 22px;
    }

    .commission-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: #e9f7f1;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        margin-bottom: 15px;
    }

    .commission-card h3 {
        font-size: 25px;
        color: var(--text);
        margin: 0 0 5px;
    }

    .commission-card p {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .commission-panel h3 {
        font-size: 18px;
        color: var(--text);
        margin: 0 0 6px;
    }

    .commission-panel > p {
        color: var(--muted);
        font-size: 13px;
        margin-bottom: 20px;
    }

    .commission-empty {
        text-align: center;
        padding: 45px 20px;
    }

    .commission-empty i {
        font-size: 35px;
        color: #94a3b8;
        margin-bottom: 15px;
    }

    .commission-empty h4 {
        font-size: 17px;
        color: var(--text);
        margin-bottom: 8px;
    }

    .commission-empty p {
        max-width: 450px;
        margin: auto;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.7;
    }

    .commission-notice {
        padding: 16px 18px;
        border-radius: 12px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
        font-size: 13px;
        line-height: 1.7;
    }

    @media(max-width: 750px) {
        .commission-stats {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 550px) {
        .commission-card,
        .commission-panel {
            padding: 17px;
        }
    }
</style>

<div class="commission-wrapper">

    <div class="commission-stats">

        <div class="commission-card">
            <div class="commission-icon">
                <i class="fa-solid fa-coins"></i>
            </div>

            <h3>৳{{ number_format($summary['total_commission'], 2) }}</h3>
            <p>Total Commission</p>
        </div>

        <div class="commission-card">
            <div class="commission-icon">
                <i class="fa-solid fa-clock"></i>
            </div>

            <h3>৳{{ number_format($summary['pending_commission'], 2) }}</h3>
            <p>Pending Commission</p>
        </div>

        <div class="commission-card">
            <div class="commission-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <h3>৳{{ number_format($summary['paid_commission'], 2) }}</h3>
            <p>Paid Commission</p>
        </div>

    </div>

    <div class="commission-panel">

        <h3>Commission History</h3>

        <p>Referral commission records and their status.</p>

        <div class="commission-empty">

            <i class="fa-solid fa-file-invoice-dollar"></i>

            <h4>Commission System Not Configured</h4>

            <p>
                Referral commission rules and approval procedures
                have not been configured. No commission has been
                calculated or credited to your account.
            </p>

        </div>

    </div>

    <div class="commission-notice">

        <i class="fa-solid fa-circle-info"></i>

        <strong>Important Notice:</strong>

        Commission information is currently inactive.
        Any future commission feature requires approved business
        rules, applicable legal compliance and a verified payment process.

    </div>

</div>

@endsection