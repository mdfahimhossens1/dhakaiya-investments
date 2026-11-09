@extends('layouts.app')

@section('title', 'Referral Link')

@section('content')

<div class="page-heading">
    <div>
        <h2>Referral Link</h2>
        <p>Share your referral link with others.</p>
    </div>
</div>

<style>
    .referral-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .referral-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 25px;
    }

    .referral-hero {
        background: linear-gradient(135deg, #087f5b, #065f46);
        color: #fff;
        border-radius: 14px;
        padding: 30px;
    }

    .referral-hero h3 {
        font-size: 23px;
        margin: 0 0 10px;
    }

    .referral-hero p {
        font-size: 13px;
        opacity: .9;
        line-height: 1.7;
        margin: 0;
    }

    .referral-field {
        margin-top: 20px;
    }

    .referral-field label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 9px;
        color: var(--text);
    }

    .referral-input-group {
        display: flex;
        gap: 10px;
    }

    .referral-input {
        flex: 1;
        min-width: 0;
        padding: 13px;
        border: 1px solid var(--border);
        border-radius: 9px;
        background: #f8fafc;
        color: var(--text);
        font-size: 13px;
    }

    .referral-copy-btn {
        border: none;
        border-radius: 9px;
        background: var(--primary);
        color: #fff;
        padding: 0 20px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
    }

    .referral-copy-btn:hover {
        background: var(--primary-dark);
    }

    .referral-info {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
        padding: 16px;
        border-radius: 10px;
        font-size: 13px;
        line-height: 1.7;
    }

    @media(max-width: 600px) {
        .referral-input-group {
            flex-direction: column;
        }

        .referral-copy-btn {
            padding: 13px;
        }

        .referral-panel {
            padding: 18px;
        }
    }
</style>

<div class="referral-wrapper">

    <div class="referral-hero">
        <h3><i class="fa-solid fa-share-nodes"></i> Invite People</h3>
        <p>
            Share your unique referral link with others.
            Your referral code helps identify registrations made through your link.
        </p>
    </div>

    <div class="referral-panel">

        <h3 style="font-size:18px;margin:0 0 20px;color:var(--text);">
            Your Referral Information
        </h3>

        <div class="referral-field">
            <label>Your Referral Code</label>

            <div class="referral-input-group">
                <input type="text"
                       id="referralCode"
                       class="referral-input"
                       value="{{ $user->referral_code }}"
                       readonly>

                <button type="button"
                        class="referral-copy-btn"
                        onclick="copyReferral('referralCode', this)">
                    <i class="fa-regular fa-copy"></i> Copy Code
                </button>
            </div>
        </div>

        <div class="referral-field">
            <label>Your Referral Link</label>

            <div class="referral-input-group">
                <input type="text"
                       id="referralLink"
                       class="referral-input"
                       value="{{ $referralLink }}"
                       readonly>

                <button type="button"
                        class="referral-copy-btn"
                        onclick="copyReferral('referralLink', this)">
                    <i class="fa-regular fa-copy"></i> Copy Link
                </button>
            </div>
        </div>

    </div>

    <div class="referral-info">
        <i class="fa-solid fa-circle-info"></i>
        <strong>Information:</strong>
        Your referral code is used to identify members who register
        through your referral link. Sharing the link does not guarantee
        any commission or financial reward.
    </div>

</div>

<script>
    function copyReferral(id, button) {
        const input = document.getElementById(id);

        navigator.clipboard.writeText(input.value).then(() => {
            const oldText = button.innerHTML;

            button.innerHTML = '<i class="fa-solid fa-check"></i> Copied';

            setTimeout(() => {
                button.innerHTML = oldText;
            }, 1800);
        }).catch(() => {
            input.select();
            document.execCommand('copy');
        });
    }
</script>

@endsection