@extends('layouts.auth')

@section('title', 'Login')

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

    <form method="POST" action="{{ route('login.store') }}">

        @csrf

        <div class="form-group">
            <label>Phone Number</label>

            <input
                type="tel"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="+88017XXXXXXXX"
                autocomplete="username"
                required
                autofocus
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

        <button type="submit" class="auth-button">
            Login
        </button>

    </form>

    <div class="auth-footer">
        Don't have an account?
        <a href="{{ route('register') }}">Create Account</a>
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