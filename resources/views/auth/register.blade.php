@extends('layouts.auth')

@section('title', 'Register')

@push('styles')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">

    <style>
        .iti {
            width: 100%;
        }

        .iti input {
            width: 100%;
        }

        .iti__country-list {
            max-width: 300px;
        }
    </style>
@endpush

@section('content')

    <h2>Create Account</h2>

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST"
          action="{{ route('register.store') }}"
          id="registerForm">

        @csrf

        {{-- Full Name --}}
        <div class="form-group">
            <label>Full Name</label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Enter your full name"
                autocomplete="name"
                required
            >
        </div>

        {{-- Country & Phone --}}
        <div class="form-group">
            <label>Country & Phone Number</label>

            <input
                type="tel"
                id="phone"
                placeholder="Enter phone number"
                autocomplete="tel-national"
                required
            >

            <input
                type="hidden"
                name="phone"
                id="full_phone"
            >

            <input
                type="hidden"
                name="country"
                id="country"
                value="{{ old('country', 'BD') }}"
            >
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label>Email (Optional)</label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="example@email.com"
                autocomplete="email"
            >
        </div>

        {{-- Referral Code --}}
        <div class="form-group">
            <label>Referral Code (Optional)</label>

            <input
                type="text"
                name="referral_code_input"
                value="{{ old('referral_code_input', request('ref')) }}"
                placeholder="Enter referral code"
            >
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label>Password</label>

            <div class="password-wrapper">
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Create a password"
                    autocomplete="new-password"
                    required
                >

                <button
                    type="button"
                    class="password-toggle"
                    onclick="togglePassword('password')"
                >
                    Show
                </button>
            </div>
        </div>

        {{-- Confirm Password --}}
        <div class="form-group">
            <label>Confirm Password</label>

            <div class="password-wrapper">
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    placeholder="Confirm your password"
                    autocomplete="new-password"
                    required
                >

                <button
                    type="button"
                    class="password-toggle"
                    onclick="togglePassword('password_confirmation')"
                >
                    Show
                </button>
            </div>
        </div>

        <button type="submit" class="auth-button" id="registerButton">
            Create Account
        </button>

    </form>

    <div class="auth-footer">
        Already have an account?
        <a href="{{ route('login') }}">Login</a>
    </div>

@endsection

@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const phoneInput = document.querySelector("#phone");
            const fullPhoneInput = document.querySelector("#full_phone");
            const countryInput = document.querySelector("#country");
            const registerForm = document.querySelector("#registerForm");
            const registerButton = document.querySelector("#registerButton");

            const oldPhone = @json(old('phone'));
            const oldCountry = @json(strtolower(old('country', 'bd')));

            // Initialize International Telephone Input
            const iti = window.intlTelInput(phoneInput, {

                initialCountry: oldCountry,

                separateDialCode: true,

                nationalMode: true,

                allowDropdown: true,

                countrySearch: true,

                showFlags: true,

                preferredCountries: [
                    'bd',
                    'in',
                    'us',
                    'gb',
                    'sa',
                    'ae'
                ],

                // Load phone validation utilities
                loadUtils: () => import(
                    "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"
                )
            });

            // Restore old phone number after validation error
            if (oldPhone) {
                iti.setNumber(oldPhone);
            }

            // Update country whenever selected country changes
            phoneInput.addEventListener("countrychange", function () {

                const countryData = iti.getSelectedCountryData();

                countryInput.value = countryData.iso2.toUpperCase();

            });

            // Set initial country
            const initialCountryData = iti.getSelectedCountryData();

            if (initialCountryData.iso2) {
                countryInput.value = initialCountryData.iso2.toUpperCase();
            }

            // Form submission
            registerForm.addEventListener("submit", async function (event) {

                event.preventDefault();

                const phoneValue = phoneInput.value.trim();

                if (!phoneValue) {
                    alert("Please enter your phone number.");
                    phoneInput.focus();
                    return;
                }

                // Wait until phone validation utilities are loaded
                try {
                    await iti.promise;
                } catch (error) {
                    console.error("Phone utilities failed to load:", error);

                    alert("Phone validation could not be loaded. Please refresh the page and try again.");
                    return;
                }

                // Validate selected country and phone number
                const countryData = iti.getSelectedCountryData();

                if (!countryData.iso2) {
                    alert("Please select a country.");
                    phoneInput.focus();
                    return;
                }

                if (!iti.isValidNumber()) {
                    alert("Please enter a valid phone number for the selected country.");
                    phoneInput.focus();
                    return;
                }

                // Get full international phone number
                const internationalNumber = iti.getNumber();

                // Ensure E.164 format
                if (!/^\+[1-9]\d{7,14}$/.test(internationalNumber)) {
                    alert("Invalid international phone number.");
                    phoneInput.focus();
                    return;
                }

                // Set hidden form values
                fullPhoneInput.value = internationalNumber;

                countryInput.value = countryData.iso2.toUpperCase();

                // Prevent multiple submissions
                registerButton.disabled = true;
                registerButton.textContent = "Creating Account...";

                // Submit form
                registerForm.submit();

            });

        });

        // Password visibility
        function togglePassword(id) {

            const input = document.getElementById(id);

            input.type = input.type === 'password'
                ? 'text'
                : 'password';

        }
    </script>

@endpush