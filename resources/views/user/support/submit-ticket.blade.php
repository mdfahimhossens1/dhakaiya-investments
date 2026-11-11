@extends('layouts.app')

@section('page-title', 'Submit Ticket')

@section('content')

<style>
    .support-page {
        padding-bottom: 25px;
    }

    .support-hero {
        background: linear-gradient(135deg, #102b26, #087f5b);
        padding: 28px;
        border-radius: 14px;
        color: #fff;
        margin-bottom: 25px;
    }

    .support-hero h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .support-hero p {
        color: rgba(255,255,255,.8);
        margin: 0;
        font-size: 14px;
    }

    .support-form-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .support-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
    }

    .support-card-header h4 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: var(--text);
    }

    .support-card-body {
        padding: 25px;
    }

    .support-form-group {
        margin-bottom: 20px;
    }

    .support-form-group label {
        display: block;
        color: var(--text);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 9px;
    }

    .support-form-group label span {
        color: #ef4444;
    }

    .support-form-control {
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 14px;
        color: var(--text);
        background: #fff;
        outline: none;
        transition: .2s;
    }

    .support-form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(8,127,91,.08);
    }

    textarea.support-form-control {
        min-height: 150px;
        resize: vertical;
    }

    .support-submit-btn {
        border: none;
        background: var(--primary);
        color: #fff;
        padding: 12px 22px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: .2s;
    }

    .support-submit-btn:hover {
        background: var(--primary-dark);
    }

    .support-info {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 10px;
        padding: 16px;
        color: #1d4ed8;
        font-size: 13px;
        line-height: 1.6;
        margin-top: 20px;
    }

    .support-info i {
        margin-right: 7px;
    }

    @media(max-width: 768px) {
        .support-hero {
            padding: 22px;
        }

        .support-hero h2 {
            font-size: 21px;
        }

        .support-card-body {
            padding: 18px;
        }
    }
</style>

<div class="support-page">

    {{-- Page Header --}}
    <div class="support-hero">
        <h2>
            <i class="fa-solid fa-ticket me-2"></i>
            Submit Support Ticket
        </h2>

        <p>
            Need assistance? Submit a support request to describe your issue.
        </p>
    </div>

    {{-- Ticket Form --}}
    <div class="support-form-card">

        <div class="support-card-header">
            <h4>
                <i class="fa-solid fa-pen-to-square me-2"></i>
                Ticket Information
            </h4>
        </div>

        <div class="support-card-body">

            <form>

                {{-- Subject --}}
                <div class="support-form-group">
                    <label>
                        Ticket Subject <span>*</span>
                    </label>

                    <input
                        type="text"
                        class="support-form-control"
                        placeholder="Enter your issue subject"
                        disabled
                    >
                </div>

                {{-- Category --}}
                <div class="support-form-group">
                    <label>
                        Issue Category <span>*</span>
                    </label>

                    <select class="support-form-control" disabled>
                        <option value="">Select a category</option>
                        <option>Account Related</option>
                        <option>Technical Issue</option>
                        <option>General Inquiry</option>
                        <option>Other</option>
                    </select>
                </div>

                {{-- Priority --}}
                <div class="support-form-group">
                    <label>
                        Priority <span>*</span>
                    </label>

                    <select class="support-form-control" disabled>
                        <option value="">Select priority</option>
                        <option>Low</option>
                        <option>Medium</option>
                        <option>High</option>
                    </select>
                </div>

                {{-- Description --}}
                <div class="support-form-group">
                    <label>
                        Issue Description <span>*</span>
                    </label>

                    <textarea
                        class="support-form-control"
                        placeholder="Describe your issue in detail..."
                        disabled
                    ></textarea>
                </div>

                {{-- Attachment --}}
                <div class="support-form-group">
                    <label>
                        Attachment (Optional)
                    </label>

                    <input
                        type="file"
                        class="support-form-control"
                        disabled
                    >

                    <small class="text-muted mt-2 d-block">
                        Attachment submission is not currently available.
                    </small>
                </div>

                {{-- Submit --}}
                <button
                    type="button"
                    class="support-submit-btn"
                    disabled
                    style="opacity:.6; cursor:not-allowed;"
                >
                    <i class="fa-solid fa-paper-plane me-2"></i>
                    Submit Ticket
                </button>

            </form>

            <div class="support-info">
                <i class="fa-solid fa-circle-info"></i>

                <strong>Notice:</strong>
                Support ticket submission is currently unavailable.
                This page is a display-only interface.
            </div>

        </div>
    </div>

</div>

@endsection