@extends('layouts.auth')

@section('title', 'Login')

@push('styles')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">
@endpush

@section('content')

    <h2>Welcome Back!</h2>

    <p class="subtitle">Login to your account</p>

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" id="loginForm">

        @csrf

        <div class="form-group">
            <label>Phone Number</label>

            <input
                type="tel"
                id="phone"
                placeholder="Enter phone number"
                autocomplete="tel"
                required
                autofocus
            >

            <input
                type="hidden"
                name="phone"
                id="full_phone"
                value="{{ old('phone') }}"
            >
        </div>

        <div class="form-group">
            <label>Password</label>

            <div class="password-wrapper">

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
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

        <button type="submit" class="auth-button" id="loginButton">
            Login
        </button>

    </form>

    <div class="auth-footer">
        Don't have an account?
        <a href="{{ route('register') }}">Create Account</a>
    </div>

@endsection

@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const phoneInput = document.querySelector("#phone");
            const fullPhoneInput = document.querySelector("#full_phone");
            const loginForm = document.querySelector("#loginForm");
            const loginButton = document.querySelector("#loginButton");

            const oldPhone = @json(old('phone'));

            const iti = window.intlTelInput(phoneInput, {
                initialCountry: "bd",
                separateDialCode: true,
                nationalMode: true,
                allowDropdown: true,
                showFlags: true,
                countrySearch: true,

                preferredCountries: [
                    'bd',
                    'in',
                    'us',
                    'gb',
                    'sa',
                    'ae'
                ],

                loadUtils: () => import(
                    "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"
                )
            });

            // Restore previous phone number.
            if (oldPhone) {
                iti.setNumber(oldPhone);
            }

            loginForm.addEventListener("submit", async function (event) {

                event.preventDefault();

                loginButton.disabled = true;
                loginButton.textContent = "Please wait...";

                try {

                    // Wait until phone validation utilities are loaded.
                    await iti.promise;

                    if (!iti.isValidNumber()) {

                        alert("Please enter a valid phone number for the selected country.");

                        phoneInput.focus();

                        loginButton.disabled = false;
                        loginButton.textContent = "Login";

                        return;
                    }

                    // Convert phone number into E.164 format.
                    fullPhoneInput.value = iti.getNumber();

                    // Submit the login form.
                    loginForm.submit();

                } catch (error) {

                    console.error("Phone validation error:", error);

                    alert("Unable to validate phone number. Please try again.");

                    loginButton.disabled = false;
                    loginButton.textContent = "Login";
                }

            });

        });

        function togglePassword(id) {

            const input = document.getElementById(id);

            input.type = input.type === 'password'
                ? 'text'
                : 'password';
        }
    </script>

@endpush