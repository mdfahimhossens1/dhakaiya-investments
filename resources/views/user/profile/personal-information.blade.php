@extends('layouts.app')

@section('page-title', 'Personal Information')

@section('content')

<div class="personal-page">

    {{-- Page Heading --}}
    <div class="page-heading">

        <div>
            <div class="breadcrumb">
                <a href="{{ route('user.dashboard') }}">Dashboard</a>
                <span>/</span>
                <span>My Profile</span>
                <span>/</span>
                <span>Personal Information</span>
            </div>

            <h1>Personal Information</h1>

            <p>Manage and update your personal account information.</p>
        </div>

        <div class="page-heading-icon">
            ♙
        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert-success">
            <span>✓</span>
            {{ session('success') }}
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert-error">
            <strong>Please correct the following errors:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="profile-layout">

        {{-- Left Profile Card --}}
        <div class="profile-card">

            <div class="profile-card-cover"></div>

            <div class="profile-card-content">

                <div class="profile-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <h3>{{ $user->name }}</h3>

                <p class="profile-role">
                    {{ ucwords(str_replace('_', ' ', $user->role)) }}
                </p>

                <div class="account-status">
                    <span></span>
                    Active Account
                </div>

                <div class="profile-divider"></div>

                <div class="profile-detail">
                    <span>Member Since</span>
                    <strong>{{ $user->created_at?->format('d M, Y') }}</strong>
                </div>

                <div class="profile-detail">
                    <span>Referral Code</span>
                    <strong>{{ $user->referral_code ?? 'N/A' }}</strong>
                </div>

                <div class="profile-detail">
                    <span>Country</span>
                    <strong>{{ $countries[$user->country] ?? $user->country ?? 'N/A' }}</strong>
                </div>

            </div>

        </div>


        {{-- Personal Information Form --}}
        <div class="form-panel">

            <div class="form-panel-heading">

                <div>
                    <h3>Basic Information</h3>
                    <p>Update your registered personal details.</p>
                </div>

                <div class="form-heading-icon">
                    ✎
                </div>

            </div>


            <form action="{{ route('user.personal-information.update') }}"
                  method="POST">

                @csrf
                @method('PUT')


                {{-- Full Name --}}
                <div class="form-group">

                    <label for="name">
                        Full Name <span>*</span>
                    </label>

                    <div class="input-wrapper">
                        <span class="input-icon">♙</span>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Enter your full name"
                            maxlength="100"
                            required
                        >
                    </div>

                    @error('name')
                        <small class="field-error">{{ $message }}</small>
                    @enderror

                </div>


                {{-- Phone --}}
                <div class="form-group">

                    <label for="phone">
                        Phone Number <span>*</span>
                    </label>

                    <div class="input-wrapper">
                        <span class="input-icon">☎</span>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            placeholder="+8801XXXXXXXXX"
                            maxlength="16"
                            required
                        >
                    </div>

                    <small class="field-help">
                        Use international format, for example +88017XXXXXXXX.
                    </small>

                    @error('phone')
                        <small class="field-error">{{ $message }}</small>
                    @enderror

                </div>


                {{-- Email --}}
                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <div class="input-wrapper">
                        <span class="input-icon">✉</span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            placeholder="Enter your email address"
                        >
                    </div>

                    @error('email')
                        <small class="field-error">{{ $message }}</small>
                    @enderror

                </div>


                {{-- Country --}}
                <div class="form-group">

                    <label for="country">
                        Country <span>*</span>
                    </label>

                    <div class="input-wrapper">
                        <span class="input-icon">◎</span>

                        <select id="country" name="country" required>

                            <option value="">Select Country</option>

                            @foreach($countries as $code => $countryName)

                                <option
                                    value="{{ $code }}"
                                    {{ old('country', $user->country) === $code ? 'selected' : '' }}
                                >
                                    {{ $countryName }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    @error('country')
                        <small class="field-error">{{ $message }}</small>
                    @enderror

                </div>


                {{-- Information Notice --}}
                <div class="form-notice">
                    <span>ⓘ</span>

                    <p>
                        Please ensure your personal information is accurate.
                        Your account security and communication may depend
                        on these details.
                    </p>
                </div>


                {{-- Form Actions --}}
                <div class="form-actions">

                    <a href="{{ route('user.account-summary') }}"
                       class="cancel-btn">
                        Cancel
                    </a>

                    <button type="submit" class="save-btn">
                        <span>✓</span>
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

    .personal-page {
        width: 100%;
    }

    /* Heading */

    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .breadcrumb {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        color: #94a3b8;
        font-size: 11px;
        margin-bottom: 9px;
    }

    .breadcrumb a {
        color: var(--primary);
        text-decoration: none;
    }

    .page-heading h1 {
        font-size: 23px;
        font-weight: 750;
        color: #1e293b;
        margin: 0;
    }

    .page-heading p {
        color: #84919f;
        font-size: 12px;
        margin-top: 6px;
    }

    .page-heading-icon {
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        border-radius: 11px;
        background: #dcfce7;
        color: var(--primary);
        font-size: 22px;
    }


    /* Alerts */

    .alert-success {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px;
        background: #ecfdf5;
        color: #15803d;
        border: 1px solid #bbf7d0;
        border-radius: 9px;
        font-size: 12px;
        margin-bottom: 20px;
    }

    .alert-error {
        padding: 14px 16px;
        background: #fff1f2;
        color: #be123c;
        border: 1px solid #fecdd3;
        border-radius: 9px;
        font-size: 12px;
        margin-bottom: 20px;
    }

    .alert-error ul {
        margin: 8px 0 0 18px;
        line-height: 1.8;
    }


    /* Layout */

    .profile-layout {
        display: grid;
        grid-template-columns: 300px minmax(0, 1fr);
        gap: 20px;
        align-items: start;
    }


    /* Profile Card */

    .profile-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .profile-card-cover {
        height: 100px;
        background: linear-gradient(120deg, #087f5b, #10b981);
    }

    .profile-card-content {
        padding: 0 22px 22px;
        text-align: center;
    }

    .profile-avatar {
        width: 82px;
        height: 82px;
        display: grid;
        place-items: center;
        margin: -41px auto 13px;
        position: relative;
        background: #dcfce7;
        border: 5px solid #fff;
        border-radius: 50%;
        color: #087f5b;
        font-size: 28px;
        font-weight: 750;
    }

    .profile-card-content h3 {
        color: #1e293b;
        font-size: 16px;
        font-weight: 750;
    }

    .profile-role {
        color: #94a3b8;
        font-size: 11px;
        margin-top: 5px;
    }

    .account-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        margin-top: 13px;
        background: #ecfdf5;
        color: #15803d;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .account-status span {
        width: 7px;
        height: 7px;
        background: #16a34a;
        border-radius: 50%;
    }

    .profile-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 22px 0 12px;
    }

    .profile-detail {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        text-align: left;
    }

    .profile-detail:last-child {
        border-bottom: 0;
    }

    .profile-detail span {
        color: #94a3b8;
        font-size: 10px;
    }

    .profile-detail strong {
        color: #334155;
        font-size: 12px;
        overflow-wrap: anywhere;
    }


    /* Form Panel */

    .form-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 25px;
    }

    .form-panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 19px;
        margin-bottom: 23px;
    }

    .form-panel-heading h3 {
        font-size: 15px;
        color: #1e293b;
        font-weight: 750;
    }

    .form-panel-heading p {
        color: #94a3b8;
        font-size: 11px;
        margin-top: 5px;
    }

    .form-heading-icon {
        width: 38px;
        height: 38px;
        display: grid;
        place-items: center;
        background: #ecfdf5;
        color: var(--primary);
        border-radius: 9px;
        font-size: 18px;
    }


    /* Form Fields */

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        color: #334155;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .form-group label span {
        color: #e11d48;
    }

    .input-wrapper {
        display: flex;
        align-items: center;
        gap: 11px;
        min-height: 46px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0 13px;
        transition: .2s;
    }

    .input-wrapper:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(8, 127, 91, .08);
    }

    .input-icon {
        color: #94a3b8;
        font-size: 15px;
        flex-shrink: 0;
    }

    .input-wrapper input,
    .input-wrapper select {
        width: 100%;
        min-width: 0;
        height: 44px;
        border: 0;
        outline: 0;
        background: transparent;
        color: #334155;
        font-size: 12px;
    }

    .input-wrapper input::placeholder {
        color: #b0bac6;
    }

    .input-wrapper select {
        cursor: pointer;
    }

    .field-help {
        display: block;
        color: #94a3b8;
        font-size: 10px;
        margin-top: 7px;
    }

    .field-error {
        display: block;
        color: #e11d48;
        font-size: 10px;
        margin-top: 7px;
    }


    /* Notice */

    .form-notice {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        padding: 14px;
        border-radius: 8px;
        margin-top: 25px;
    }

    .form-notice span {
        color: #0284c7;
        font-size: 15px;
    }

    .form-notice p {
        color: #475569;
        font-size: 10px;
        line-height: 1.7;
    }


    /* Buttons */

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .cancel-btn,
    .save-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        padding: 11px 17px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
    }

    .cancel-btn {
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #64748b;
    }

    .cancel-btn:hover {
        background: #f8fafc;
    }

    .save-btn {
        background: var(--primary);
        border: 1px solid var(--primary);
        color: #fff;
    }

    .save-btn:hover {
        background: var(--primary-dark);
    }


    /* Responsive */

    @media(max-width: 992px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }

        .profile-card {
            max-width: 100%;
        }
    }

    @media(max-width: 576px) {
        .page-heading h1 {
            font-size: 20px;
        }

        .form-panel {
            padding: 17px;
        }

        .profile-card-content {
            padding-left: 17px;
            padding-right: 17px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .cancel-btn,
        .save-btn {
            width: 100%;
        }
    }

</style>

@endpush