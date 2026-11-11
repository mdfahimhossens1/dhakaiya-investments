@extends('layouts.app')

@section('page-title', 'Contact Support')

@section('content')

<style>
    .contact-page {
        padding-bottom: 25px;
    }

    .contact-hero {
        background: linear-gradient(135deg, #102b26, #087f5b);
        padding: 28px;
        border-radius: 14px;
        color: #fff;
        margin-bottom: 25px;
    }

    .contact-hero h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .contact-hero p {
        color: rgba(255,255,255,.8);
        margin: 0;
        font-size: 14px;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 25px;
    }

    .contact-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 23px;
        text-align: center;
    }

    .contact-icon {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        background: #e8f5ef;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin: 0 auto 15px;
    }

    .contact-card h5 {
        color: var(--text);
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .contact-card p {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.6;
        min-height: 42px;
        margin-bottom: 15px;
    }

    .contact-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        background: #fff7ed;
        color: #c2410c;
        font-size: 11px;
        font-weight: 700;
    }

    .contact-section {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 22px;
    }

    .contact-section-header {
        padding: 20px 22px;
        border-bottom: 1px solid var(--border);
    }

    .contact-section-header h4 {
        color: var(--text);
        font-size: 17px;
        font-weight: 700;
        margin: 0;
    }

    .faq-list {
        padding: 5px 22px;
    }

    .faq-item {
        padding: 18px 0;
        border-bottom: 1px solid var(--border);
    }

    .faq-item:last-child {
        border-bottom: none;
    }

    .faq-question {
        color: var(--text);
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .faq-question i {
        color: var(--primary);
        margin-right: 8px;
    }

    .faq-answer {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.7;
        margin: 0;
        padding-left: 24px;
    }

    .contact-notice {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 10px;
        padding: 16px 18px;
        color: #1d4ed8;
        font-size: 13px;
        line-height: 1.6;
    }

    @media(max-width: 992px) {
        .contact-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 576px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }

        .contact-hero {
            padding: 22px;
        }

        .contact-hero h2 {
            font-size: 21px;
        }
    }
</style>

<div class="contact-page">

    {{-- Page Header --}}
    <div class="contact-hero">
        <h2>
            <i class="fa-solid fa-headset me-2"></i>
            Contact Support
        </h2>

        <p>
            Find assistance and get information about available support channels.
        </p>
    </div>

    {{-- Support Channels --}}
    <div class="contact-grid">

        @foreach($supportChannels as $channel)

            <div class="contact-card">

                <div class="contact-icon">
                    <i class="fa-solid {{ $channel['icon'] }}"></i>
                </div>

                <h5>{{ $channel['name'] }}</h5>

                <p>{{ $channel['description'] }}</p>

                <span class="contact-status">
                    {{ $channel['status'] }}
                </span>

            </div>

        @endforeach

    </div>

    {{-- FAQ Section --}}
    <div class="contact-section">

        <div class="contact-section-header">
            <h4>
                <i class="fa-solid fa-circle-question me-2"></i>
                Frequently Asked Questions
            </h4>
        </div>

        <div class="faq-list">

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fa-solid fa-circle-check"></i>
                    How can I contact the support team?
                </div>

                <p class="faq-answer">
                    You can use the official support channels when they become available.
                </p>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fa-solid fa-circle-check"></i>
                    How can I submit a support request?
                </div>

                <p class="faq-answer">
                    Visit the Submit Ticket page to review the support request interface.
                </p>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fa-solid fa-circle-check"></i>
                    Where can I track my support requests?
                </div>

                <p class="faq-answer">
                    Visit the My Tickets page to review your ticket history interface.
                </p>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fa-solid fa-circle-check"></i>
                    Where can I find platform updates?
                </div>

                <p class="faq-answer">
                    Check the Announcements and Important Notices sections.
                </p>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <i class="fa-solid fa-circle-check"></i>
                    Are support channels currently active?
                </div>

                <p class="faq-answer">
                    Support channels are currently under development and are not yet active.
                </p>
            </div>

        </div>

    </div>

    {{-- Notice --}}
    <div class="contact-notice">
        <i class="fa-solid fa-circle-info me-2"></i>

        <strong>Support Information:</strong>
        Contact channels and support services will be activated
        when officially configured by the platform administrator.
    </div>

</div>

@endsection