@extends('layouts.auth')

@section('title', 'Register')

@section('content')

    <h2>Create Account</h2>

    <p class="subtitle">Register your Dhakaiya Investment account</p>

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}">

        @csrf

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

        <div class="form-group">
            <label>Country</label>

            <select name="country" required>
                <option value="">Select Country</option>

                <option value="BD"
                    @selected(old('country') === 'BD')>
                    Bangladesh
                </option>

                <option value="IN"
                    @selected(old('country') === 'IN')>
                    India
                </option>

                <option value="US"
                    @selected(old('country') === 'US')>
                    United States
                </option>

                <option value="GB"
                    @selected(old('country') === 'GB')>
                    United Kingdom
                </option>

                <option value="SA"
                    @selected(old('country') === 'SA')>
                    Saudi Arabia
                </option>

                <option value="AE"
                    @selected(old('country') === 'AE')>
                    United Arab Emirates
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Phone Number</label>

            <input
                type="tel"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="+88017XXXXXXXX"
                autocomplete="tel"
                required
            >
        </div>

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

        <div class="form-group">
            <label>Referral Code (Optional)</label>

            <input
                type="text"
                name="referral_code_input"
                value="{{ old('referral_code_input', request('ref')) }}"
                placeholder="Enter referral code"
            >
        </div>

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

        <button type="submit" class="auth-button">
            Create Account
        </button>

    </form>

    <div class="auth-footer">
        Already have an account?
        <a href="{{ route('login') }}">Login</a>
    </div>

@endsection

@push('scripts')
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);

        input.type = input.type === 'password'
            ? 'text'
            : 'password';
    }
</script>
@endpush