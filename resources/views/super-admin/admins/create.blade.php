@extends('layouts.app')

@section('title', 'Add New Admin')
@section('page-title', 'Add New Admin')

@section('content')

<div class="welcome-banner">
    <div>
        <span class="welcome-label">SUPER ADMIN PANEL</span>
        <h1>Add New Administrator</h1>
        <p>Create a new administrator account for your platform.</p>
    </div>

    <div class="welcome-icon">♙</div>
</div>

<div class="dashboard-card admin-form-card">

    <div class="card-heading">
        <div>
            <h3>Administrator Information</h3>
            <p>Enter the details below to create a new admin account.</p>
        </div>

        <a href="{{ route('super-admin.admins.index') }}" class="back-btn">
            ← Back to Admins
        </a>
    </div>

    @if ($errors->any())
        <div class="form-alert">
            <strong>Please check the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('super-admin.admins.store') }}"
        method="POST"
        id="adminCreateForm"
    >
        @csrf

        {{-- Personal Information --}}
        <div class="form-section">

            <h4>Personal Information</h4>

            <div class="form-grid">

                <div class="form-group">
                    <label for="name">Full Name <span>*</span></label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter full name"
                        required
                    >
                </div>

                {{-- Country & Phone --}}
                <div class="form-group">

                    <label for="phoneInput">
                        Country & Phone Number <span>*</span>
                    </label>

                    <input
                        type="tel"
                        id="phoneInput"
                        placeholder="Enter phone number"
                        autocomplete="tel"
                        required
                    >

                    <input
                        type="hidden"
                        name="phone"
                        id="phone"
                        value="{{ old('phone') }}"
                    >

                    <input
                        type="hidden"
                        name="country"
                        id="country"
                        value="{{ old('country', 'BD') }}"
                    >

                    <small>
                        Select your country and enter your phone number.
                    </small>

                    <div id="phoneError" class="phone-error"></div>

                </div>

                <div class="form-group full-width">
                    <label for="email">Email Address</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter email address"
                    >

                    <small>Email address is optional.</small>
                </div>

            </div>

        </div>

        {{-- Account Security --}}
        <div class="form-section">

            <h4>Account Security</h4>

            <div class="form-grid">

                <div class="form-group">
                    <label for="password">
                        Password <span>*</span>
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter password"
                        minlength="8"
                        required
                    >

                    <small>Minimum 8 characters.</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        Confirm Password <span>*</span>
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm password"
                        minlength="8"
                        required
                    >
                </div>

            </div>

        </div>

        {{-- Form Actions --}}
        <div class="admin-form-footer">

            <a
                href="{{ route('super-admin.admins.index') }}"
                class="cancel-btn"
            >
                Cancel
            </a>

            <button type="submit" class="submit-btn">
                + Create Administrator
            </button>

        </div>

    </form>

</div>

@endsection

@include('partials.dashboard-styles')

{{-- International Telephone Input --}}
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css"
>

<style>

    .admin-form-card {
        padding: 28px;
    }

    .back-btn {
        text-decoration: none;
        color: #c49a45;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
    }

    .back-btn:hover {
        color: #a77f30;
    }

    .form-section {
        padding: 25px 0;
        border-bottom: 1px solid #edf0f4;
    }

    .form-section h4 {
        margin: 0 0 22px;
        color: #172033;
        font-size: 17px;
        font-weight: 700;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 9px;
        min-width: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        color: #344054;
        font-size: 14px;
        font-weight: 600;
    }

    .form-group label span {
        color: #dc3545;
    }

    .form-group input:not([type="hidden"]) {
        width: 100%;
        min-height: 46px;
        padding: 11px 14px;
        border: 1px solid #dfe3ea;
        border-radius: 8px;
        background: #fff;
        color: #172033;
        font-size: 14px;
        outline: none;
        transition: .2s ease;
        box-sizing: border-box;
    }

    .form-group input:focus {
        border-color: #c49a45;
        box-shadow: 0 0 0 3px rgba(196, 154, 69, .12);
    }

    .form-group small {
        color: #8a94a6;
        font-size: 12px;
    }

    /* International Phone Input */

    .iti {
        width: 100%;
    }

    .iti input {
        width: 100% !important;
        min-height: 46px !important;
        padding-top: 11px !important;
        padding-bottom: 11px !important;
        border: 1px solid #dfe3ea !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        outline: none !important;
        box-sizing: border-box !important;
    }

    .iti input:focus {
        border-color: #c49a45 !important;
        box-shadow: 0 0 0 3px rgba(196, 154, 69, .12) !important;
    }

    .iti__dropdown-content {
        z-index: 9999 !important;
    }

    .phone-error {
        display: none;
        color: #dc3545;
        font-size: 12px;
    }

    .phone-error.show {
        display: block;
    }

    .form-alert {
        padding: 16px 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        background: #fff1f0;
        color: #bd3535;
        font-size: 14px;
    }

    .form-alert ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .admin-form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 25px;
    }

    .cancel-btn,
    .submit-btn {
        min-height: 44px;
        padding: 11px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .cancel-btn {
        display: inline-flex;
        align-items: center;
        color: #475467;
        background: #f1f3f6;
    }

    .cancel-btn:hover {
        background: #e5e8ed;
    }

    .submit-btn {
        border: none;
        background: #c49a45;
        color: #fff;
    }

    .submit-btn:hover {
        background: #a77f30;
        transform: translateY(-1px);
    }

    @media(max-width: 650px) {

        .admin-form-card {
            padding: 18px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .admin-form-footer {
            flex-direction: column-reverse;
        }

        .cancel-btn,
        .submit-btn {
            width: 100%;
            justify-content: center;
            text-align: center;
        }

    }

</style>

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', async function () {

    const phoneInput = document.querySelector('#phoneInput');
    const phoneField = document.querySelector('#phone');
    const countryField = document.querySelector('#country');
    const form = document.querySelector('#adminCreateForm');
    const phoneError = document.querySelector('#phoneError');

    const iti = window.intlTelInput(phoneInput, {

        initialCountry: @json(strtolower(old('country', 'BD'))),

        separateDialCode: true,

        nationalMode: true,

        formatOnDisplay: true,

        autoPlaceholder: 'aggressive',

        loadUtils: () => import(
            'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js'
        ),

    });

    await iti.promise;

    // Restore old phone number after validation error
    const oldPhone = @json(old('phone'));

    if (oldPhone) {
        iti.setNumber(oldPhone);
    }

    // Update selected country
    function updateCountry() {

        const countryData = iti.getSelectedCountryData();

        countryField.value = countryData.iso2
            ? countryData.iso2.toUpperCase()
            : '';

    }

    updateCountry();

    phoneInput.addEventListener('countrychange', updateCountry);

    // Form Submit
    form.addEventListener('submit', function (event) {

        phoneError.classList.remove('show');
        phoneError.textContent = '';

        const phone = phoneInput.value.trim();

        if (!phone) {

            event.preventDefault();

            phoneError.textContent = 'Please enter a phone number.';
            phoneError.classList.add('show');

            phoneInput.focus();

            return;
        }

        if (!iti.isValidNumber()) {

            event.preventDefault();

            phoneError.textContent = 'Please enter a valid phone number for the selected country.';
            phoneError.classList.add('show');

            phoneInput.focus();

            return;
        }

        phoneField.value = iti.getNumber();

        updateCountry();

    });

});

</script>