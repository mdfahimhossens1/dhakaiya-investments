@extends('layouts.app')

@section('title', 'Referral Link')

@section('content')

<div class="page-heading">
    <div>
        <h2>Referral Link</h2>
        <p>Share your referral link and earn when your referred members make a profit.</p>
    </div>
</div>

@include('partials.referral-styles')

<style>
    .rf-hero {
        position: relative; overflow: hidden;
        background: linear-gradient(135deg, #087f5b, #064e3b);
        color: #fff; border-radius: 18px; padding: 32px;
        display: flex; justify-content: space-between; align-items: center; gap: 24px; flex-wrap: wrap;
    }
    .rf-hero::after {
        content: ''; position: absolute; right: -60px; top: -60px;
        width: 220px; height: 220px; border-radius: 50%; background: rgba(255, 255, 255, .07);
    }
    .rf-hero h3 { font-size: 24px; margin: 0 0 10px; }
    .rf-hero p { font-size: 13.5px; opacity: .92; line-height: 1.7; margin: 0; max-width: 480px; }
    .rf-hero-stat {
        position: relative; z-index: 1; background: rgba(255, 255, 255, .14);
        border-radius: 14px; padding: 16px 26px; text-align: center;
    }
    .rf-hero-stat strong { display: block; font-size: 30px; line-height: 1.1; }
    .rf-hero-stat span { font-size: 12px; opacity: .9; }

    .rf-field { margin-top: 20px; }
    .rf-field:first-of-type { margin-top: 0; }
    .rf-field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 9px; color: var(--text); }
    .rf-input-group { display: flex; gap: 10px; }
    .rf-input {
        flex: 1; min-width: 0; padding: 13px 14px; border: 1px solid var(--border);
        border-radius: 10px; background: #f8fafc; color: var(--text); font-size: 13.5px;
    }
    .rf-btn {
        border: none; border-radius: 10px; background: var(--primary); color: #fff;
        padding: 0 22px; cursor: pointer; font-size: 13px; font-weight: 600; white-space: nowrap; transition: background .15s;
    }
    .rf-btn:hover { background: var(--primary-dark); }

    .rf-share { display: flex; flex-wrap: wrap; gap: 10px; }
    .rf-share a, .rf-share button {
        display: inline-flex; align-items: center; gap: 8px; padding: 11px 18px;
        border-radius: 10px; border: none; cursor: pointer; color: #fff;
        font-size: 13px; font-weight: 600; text-decoration: none;
    }
    .rf-share .wa { background: #25d366; }
    .rf-share .tg { background: #229ed9; }
    .rf-share .fb { background: #1877f2; }
    .rf-share .nv { background: #475569; }

    .rf-steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .rf-step { display: flex; gap: 14px; }
    .rf-step-no {
        width: 34px; height: 34px; flex-shrink: 0; border-radius: 50%;
        background: #e9f7f1; color: var(--primary);
        display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;
    }
    .rf-step h4 { font-size: 14px; margin: 0 0 4px; color: var(--text); }
    .rf-step p { font-size: 12.5px; color: var(--muted); margin: 0; line-height: 1.6; }

    @media (max-width: 750px) { .rf-steps { grid-template-columns: 1fr; } }
    @media (max-width: 600px) {
        .rf-input-group { flex-direction: column; }
        .rf-btn { padding: 13px; }
        .rf-hero { padding: 24px; }
    }
</style>

@php
    $shareText = 'Join me on ' . config('app.name') . ' using my referral link: ' . $referralLink;
    $rate = (float) $program['rate_percent'];
    $days = (int) $program['hold_days'];
@endphp

<div class="rf-wrap">

    {{-- Hero --}}
    <div class="rf-hero">
        <div style="position:relative;z-index:1;">
            <h3><i class="fa-solid fa-share-nodes"></i> Invite &amp; Earn</h3>
            <p>
                Share your personal link with friends and family.
                @if($enabled && $rate > 0)
                    You earn <strong>{{ $rate + 0 }}%</strong> commission on the profit your referred members make.
                @endif
            </p>
        </div>
        <div class="rf-hero-stat">
            <strong>{{ $totalReferrals }}</strong>
            <span>People referred</span>
        </div>
    </div>

    {{-- Code + Link --}}
    <div class="rf-panel">
        <div class="rf-panel-head">
            <div>
                <h3>Your Referral Information</h3>
                <p>Copy and share these details.</p>
            </div>
        </div>

        <div class="rf-field">
            <label>Referral Code</label>
            <div class="rf-input-group">
                <input type="text" id="referralCode" class="rf-input" value="{{ $user->referral_code }}" readonly>
                <button type="button" class="rf-btn" onclick="copyReferral('referralCode', this)">
                    <i class="fa-regular fa-copy"></i> Copy Code
                </button>
            </div>
        </div>

        <div class="rf-field">
            <label>Referral Link</label>
            <div class="rf-input-group">
                <input type="text" id="referralLink" class="rf-input" value="{{ $referralLink }}" readonly>
                <button type="button" class="rf-btn" onclick="copyReferral('referralLink', this)">
                    <i class="fa-regular fa-copy"></i> Copy Link
                </button>
            </div>
        </div>

        <div class="rf-field">
            <label>Share via</label>
            <div class="rf-share">
                <a class="wa" target="_blank" rel="noopener" href="https://wa.me/?text={{ urlencode($shareText) }}">
                    <i class="fa-brands fa-whatsapp"></i> WhatsApp
                </a>
                <a class="tg" target="_blank" rel="noopener"
                   href="https://t.me/share/url?url={{ urlencode($referralLink) }}&text={{ urlencode('Join me using my referral link') }}">
                    <i class="fa-brands fa-telegram"></i> Telegram
                </a>
                <a class="fb" target="_blank" rel="noopener"
                   href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($referralLink) }}">
                    <i class="fa-brands fa-facebook-f"></i> Facebook
                </a>
                <button type="button" class="nv" id="nativeShare" style="display:none;">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i> More
                </button>
            </div>
        </div>
    </div>

    @if($enabled)
        {{-- How it works --}}
        <div class="rf-panel">
            <div class="rf-panel-head">
                <div>
                    <h3>How it works</h3>
                    <p>You earn only when your referred member makes a real profit.</p>
                </div>
            </div>

            <div class="rf-steps">
                <div class="rf-step">
                    <div class="rf-step-no">1</div>
                    <div>
                        <h4>Share your link</h4>
                        <p>People who register through your link become your referrals.</p>
                    </div>
                </div>
                <div class="rf-step">
                    <div class="rf-step-no">2</div>
                    <div>
                        <h4>They earn a profit</h4>
                        <p>
                            When a referred member earns profit from their shares, you get {{ $rate > 0 ? ($rate + 0) . '%' : 'a share' }} of that profit.
                            Joining, buying shares, research or selling alone does not earn commission.
                            @if((float) $program['min_investment_bdt'] > 0)
                                You need at least ৳{{ number_format($program['min_investment_bdt'], 2) }} invested to qualify.
                            @endif
                        </p>
                    </div>
                </div>
                <div class="rf-step">
                    <div class="rf-step-no">3</div>
                    <div>
                        <h4>Get paid</h4>
                        <p>
                            @if($days > 0)
                                After {{ $days }} {{ \Illuminate\Support\Str::plural('day', $days) }}
                            @else
                                Right away
                            @endif
                            the commission is credited to your wallet.
                        </p>
                    </div>
                </div>
            </div>

            <div class="rf-chips">
                @if($rate > 0)
                    <span class="rf-chip"><i class="fa-solid fa-percent"></i> Commission: {{ $rate + 0 }}% of profit</span>
                @endif

                <span class="rf-chip"><i class="fa-solid fa-wallet"></i> Min payout: ৳{{ number_format($program['min_payout_bdt'], 2) }}</span>

                @if($program['max_per_referral_bdt'] !== null)
                    <span class="rf-chip"><i class="fa-solid fa-ranking-star"></i> Cap per referral: ৳{{ number_format($program['max_per_referral_bdt'], 2) }}</span>
                @endif
            </div>
        </div>

        <div class="rf-notice">
            <i class="fa-solid fa-circle-info"></i>
            <strong>Note:</strong>
            Commission is paid only on profit actually earned, never on the amount invested.
            If a profit is cancelled or reversed, the related commission is adjusted too.
            Rules may change over time and apply to future activity.
        </div>
    @else
        <div class="rf-notice warn">
            <i class="fa-solid fa-circle-pause"></i>
            <strong>Note:</strong>
            Referral commission is currently turned off. Registrations through your link are still tracked,
            but no new commission is created.
        </div>
    @endif

</div>

<script>
    function copyReferral(id, button) {
        const input = document.getElementById(id);
        const old = button.innerHTML;

        const done = () => {
            button.innerHTML = '<i class="fa-solid fa-check"></i> Copied';
            setTimeout(() => button.innerHTML = old, 1800);
        };

        const fallback = () => {
            input.select();
            document.execCommand('copy');
            done();
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(input.value).then(done).catch(fallback);
        } else {
            fallback();
        }
    }

    (function () {
        const btn = document.getElementById('nativeShare');
        if (btn && navigator.share) {
            btn.style.display = 'inline-flex';
            btn.addEventListener('click', () => {
                navigator.share({
                    title: @json(config('app.name')),
                    text: 'Join me using my referral link',
                    url: @json($referralLink),
                }).catch(() => {});
            });
        }
    })();
</script>

@endsection