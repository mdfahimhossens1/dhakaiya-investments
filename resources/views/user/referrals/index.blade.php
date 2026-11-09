@extends('layouts.app')

@section('title', 'Referral System')

@section('content')

<style>
    .referral-wrapper {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .referral-hero {
        position: relative;
        overflow: hidden;
        padding: 30px;
        border-radius: 18px;
        background: linear-gradient(120deg, #075c46, #0b9870);
        color: #fff;
    }

    .referral-hero::after {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
        right: -55px;
        top: -90px;
    }

    .referral-hero-content {
        position: relative;
        z-index: 1;
        max-width: 650px;
    }

    .referral-hero h2 {
        font-size: 25px;
        font-weight: 700;
        margin: 0 0 10px;
        color: #fff;
    }

    .referral-hero p {
        font-size: 14px;
        line-height: 1.8;
        color: rgba(255,255,255,.85);
        margin: 0;
    }

    .referral-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .referral-stat-card,
    .referral-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 15px;
        padding: 22px;
    }

    .referral-stat-icon {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #e9f7f1;
        color: var(--primary);
        font-size: 19px;
        margin-bottom: 15px;
    }

    .referral-stat-card h3 {
        font-size: 26px;
        margin: 0 0 5px;
        color: var(--text);
    }

    .referral-stat-card p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .referral-panel-header {
        margin-bottom: 20px;
    }

    .referral-panel-header h3 {
        font-size: 17px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 6px;
    }

    .referral-panel-header p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .referral-link-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 11px;
        padding: 9px;
    }

    .referral-link-input {
        flex: 1;
        min-width: 0;
        border: none;
        outline: none;
        background: transparent;
        padding: 8px;
        color: var(--text);
        font-size: 13px;
    }

    .referral-copy-btn {
        border: none;
        background: var(--primary);
        color: #fff;
        padding: 11px 17px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        transition: .2s;
    }

    .referral-copy-btn:hover {
        background: var(--primary-dark);
    }

    .referral-code-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 17px;
        border: 1px dashed #a7d9c5;
        border-radius: 11px;
        background: #f0faf5;
        margin-top: 17px;
    }

    .referral-code-label {
        font-size: 12px;
        color: var(--muted);
        margin-bottom: 5px;
    }

    .referral-code-value {
        font-size: 19px;
        font-weight: 700;
        letter-spacing: 2px;
        color: var(--primary-dark);
    }

    .referral-code-copy {
        border: 1px solid #b7dfcf;
        background: #fff;
        color: var(--primary);
        border-radius: 8px;
        padding: 9px 13px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
    }

    .referral-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .referral-table {
        width: 100%;
        min-width: 600px;
        border-collapse: collapse;
    }

    .referral-table th {
        background: #f8fafc;
        color: var(--muted);
        text-align: left;
        padding: 14px;
        font-size: 12px;
        font-weight: 600;
        border-bottom: 1px solid var(--border);
    }

    .referral-table td {
        padding: 15px 14px;
        font-size: 13px;
        color: var(--text);
        border-bottom: 1px solid var(--border);
    }

    .referral-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .referral-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e9f7f1;
        color: var(--primary);
        font-weight: 700;
    }

    .referral-user-name {
        font-weight: 600;
        color: var(--text);
    }

    .referral-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        background: #e9f7f1;
        color: #087f5b;
        font-size: 11px;
        font-weight: 600;
    }

    .referral-empty {
        text-align: center;
        padding: 40px 20px;
    }

    .referral-empty-icon {
        width: 62px;
        height: 62px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        background: #f1f5f9;
        color: #94a3b8;
        font-size: 25px;
    }

    .referral-empty h4 {
        font-size: 16px;
        color: var(--text);
        margin-bottom: 7px;
    }

    .referral-empty p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .referral-guide {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .referral-guide-item {
        display: flex;
        gap: 13px;
        padding: 18px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 13px;
    }

    .guide-number {
        min-width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #e9f7f1;
        color: var(--primary);
        font-weight: 700;
    }

    .referral-guide-item h4 {
        font-size: 14px;
        margin: 0 0 5px;
        color: var(--text);
    }

    .referral-guide-item p {
        font-size: 12px;
        line-height: 1.6;
        color: var(--muted);
        margin: 0;
    }

    .referral-notice {
        padding: 16px 18px;
        border-radius: 12px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
        font-size: 13px;
        line-height: 1.7;
    }

    .copy-message {
        color: #087f5b;
        font-size: 12px;
        margin-top: 10px;
        display: none;
    }

    @media(max-width: 900px) {
        .referral-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .referral-guide {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 600px) {
        .referral-stats {
            grid-template-columns: 1fr;
        }

        .referral-hero {
            padding: 23px;
        }

        .referral-hero h2 {
            font-size: 21px;
        }

        .referral-panel,
        .referral-stat-card {
            padding: 17px;
        }

        .referral-link-box {
            flex-direction: column;
            align-items: stretch;
        }

        .referral-copy-btn {
            width: 100%;
        }

        .referral-code-box {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="referral-wrapper">

    {{-- Hero --}}
    <div class="referral-hero">
        <div class="referral-hero-content">
            <h2>Invite Friends & Grow Your Network</h2>

            <p>
                Share your personal referral link with others.
                You can track registrations made through your
                referral link from your account.
            </p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="referral-stats">

        <div class="referral-stat-card">
            <div class="referral-stat-icon">
                <i class="fa-solid fa-users"></i>
            </div>

            <h3>{{ number_format($summary['total_referrals']) }}</h3>
            <p>Total Referred Members</p>
        </div>

        <div class="referral-stat-card">
            <div class="referral-stat-icon">
                <i class="fa-solid fa-calendar-days"></i>
            </div>

            <h3>{{ number_format($summary['this_month']) }}</h3>
            <p>Joined This Month</p>
        </div>

        <div class="referral-stat-card">
            <div class="referral-stat-icon">
                <i class="fa-solid fa-link"></i>
            </div>

            <h3>{{ $user->referral_code ? 'Active' : 'Unavailable' }}</h3>
            <p>Referral Code Status</p>
        </div>

    </div>

    {{-- Referral Link --}}
    <div class="referral-panel">

        <div class="referral-panel-header">
            <h3><i class="fa-solid fa-share-nodes"></i> Your Referral Link</h3>

            <p>
                Copy your personal referral link and share it with others.
            </p>
        </div>

        @if($user->referral_code)

            <div class="referral-link-box">

                <input
                    type="text"
                    class="referral-link-input"
                    id="referralLink"
                    value="{{ $referralLink }}"
                    readonly
                >

                <button
                    type="button"
                    class="referral-copy-btn"
                    onclick="copyReferral('referralLink', 'linkMessage')"
                >
                    <i class="fa-solid fa-copy"></i>
                    Copy Link
                </button>

            </div>

            <div id="linkMessage" class="copy-message">
                Referral link copied successfully!
            </div>

            <div class="referral-code-box">

                <div>
                    <div class="referral-code-label">YOUR REFERRAL CODE</div>

                    <div class="referral-code-value">
                        {{ $user->referral_code }}
                    </div>
                </div>

                <button
                    type="button"
                    class="referral-code-copy"
                    onclick="copyReferral('referralCode', 'codeMessage')"
                >
                    <i class="fa-solid fa-copy"></i>
                    Copy Code
                </button>

                <input
                    type="hidden"
                    id="referralCode"
                    value="{{ $user->referral_code }}"
                >

            </div>

            <div id="codeMessage" class="copy-message">
                Referral code copied successfully!
            </div>

        @else

            <p>Your referral code is not available.</p>

        @endif

    </div>

    {{-- Referred Members --}}
    <div class="referral-panel">

        <div class="referral-panel-header">
            <h3>Referred Members</h3>

            <p>
                Members who registered using your referral code.
            </p>
        </div>

        <div class="referral-table-wrapper">

            <table class="referral-table">

                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Member</th>
                        <th>Phone</th>
                        <th>Registration Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($referrals as $index => $referral)

                        <tr>
                            <td>
                                {{ $referrals->firstItem() + $index }}
                            </td>

                            <td>
                                <div class="referral-user">

                                    <div class="referral-avatar">
                                        {{ strtoupper(substr($referral->name, 0, 1)) }}
                                    </div>

                                    <div class="referral-user-name">
                                        {{ $referral->name }}
                                    </div>

                                </div>
                            </td>

                            <td>
                                {{ $referral->phone ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $referral->created_at?->format('d M Y') ?? 'N/A' }}
                            </td>

                            <td>
                                <span class="referral-status">
                                    Registered
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5">

                                <div class="referral-empty">

                                    <div class="referral-empty-icon">
                                        <i class="fa-solid fa-user-group"></i>
                                    </div>

                                    <h4>No Referred Members Yet</h4>

                                    <p>
                                        Share your referral link to see
                                        registered members here.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($referrals->hasPages())
            <div style="margin-top: 20px;">
                {{ $referrals->links() }}
            </div>
        @endif

    </div>

    {{-- Referral Guide --}}
    <div>

        <div class="referral-panel-header">
            <h3>How Referral Works</h3>
            <p>Follow these simple steps to share your referral link.</p>
        </div>

        <div class="referral-guide">

            <div class="referral-guide-item">
                <div class="guide-number">01</div>

                <div>
                    <h4>Copy Your Link</h4>
                    <p>Copy your unique referral link from this page.</p>
                </div>
            </div>

            <div class="referral-guide-item">
                <div class="guide-number">02</div>

                <div>
                    <h4>Share With Others</h4>
                    <p>Share the link with people who may be interested in joining.</p>
                </div>
            </div>

            <div class="referral-guide-item">
                <div class="guide-number">03</div>

                <div>
                    <h4>Track Registrations</h4>
                    <p>View successful registrations in your referred members list.</p>
                </div>
            </div>

        </div>

    </div>

    {{-- Notice --}}
    <div class="referral-notice">
        <i class="fa-solid fa-circle-info"></i>

        <strong> Important Notice:</strong>

        Referral tracking currently records registrations only.
        Referral commissions, bonuses and financial incentives are
        not activated. Any future reward program will require
        approved policies and applicable legal compliance.
    </div>

</div>

<script>
    function copyReferral(inputId, messageId) {
        const input = document.getElementById(inputId);
        const message = document.getElementById(messageId);

        const value = input.value;

        function showMessage() {
            message.style.display = 'block';

            setTimeout(() => {
                message.style.display = 'none';
            }, 2500);
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(value)
                .then(showMessage)
                .catch(() => fallbackCopy(input, showMessage));
        } else {
            fallbackCopy(input, showMessage);
        }
    }

    function fallbackCopy(input, callback) {
        input.type = 'text';
        input.select();
        input.setSelectionRange(0, 99999);

        const copied = document.execCommand('copy');

        input.type = 'hidden';

        if (copied) {
            callback();
        }
    }
</script>

@endsection