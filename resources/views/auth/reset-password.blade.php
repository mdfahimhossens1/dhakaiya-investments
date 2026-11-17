@extends('layouts.auth')

@section('title', 'Reset Password')

@push('styles')

<style>     .reset-card {         width: 100%;         max-width: 430px;         margin: 0 auto;     }      .reset-icon {         width: 65px;         height: 65px;         margin: 0 auto 22px;         display: flex;         align-items: center;         justify-content: center;         border-radius: 18px;         background: rgba(214, 173, 98, .12);         border: 1px solid rgba(214, 173, 98, .3);         color: #d6ad62;         font-size: 27px;     }      .reset-card h2 {         text-align: center;         margin-bottom: 10px;     }      .reset-card .subtitle {         text-align: center;         line-height: 1.7;         margin-bottom: 28px;     }      .reset-error {         padding: 13px 15px;         margin-bottom: 20px;         border-radius: 10px;         background: rgba(239, 68, 68, .10);         border: 1px solid rgba(239, 68, 68, .25);         color: #f87171;         font-size: 13px;         line-height: 1.6;     }      .reset-back {         text-align: center;         margin-top: 23px;         font-size: 13px;     }      .reset-back a {         color: #d6ad62;         font-weight: 600;         text-decoration: none;     }      .reset-back a\:hover {         color: #f0d49c;         text-decoration: underline;     } </style>

@endpush

@section('content')

<div class="reset-card">

<div class="reset-icon">
    <i class="fa-solid fa-shield-halved"></i>
</div>

<h2>Reset Password</h2>

<p class="subtitle">
    Create a new password for your account.
    Make sure it is secure and easy for you to remember.
</p>

@if ($errors->any())
    <div class="reset-error">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('password.update') }}">

    @csrf

    <input
        type="hidden"
        name="token"
        value="{{ $request->route('token') }}"
    >

    <div class="form-group">

        <label for="email">Email Address</label>

        <input
            type="email"
            name="email"
            id="email"
            value="{{ old('email', $request->email) }}"
            placeholder="Your registered email"
            autocomplete="email"
            required
            readonly
        >

    </div>

    <div class="form-group">

        <label for="password">New Password</label>

        <div class="password-wrapper">

            <input
                type="password"
                name="password"
                id="password"
                placeholder="Enter new password"
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

        <label for="password_confirmation">Confirm New Password</label>

        <div class="password-wrapper">

            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                placeholder="Confirm your new password"
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
        <i class="fa-solid fa-key"></i>
        Reset Password
    </button>

</form>

<div class="reset-back">
    <a href="{{ route('login') }}">
        <i class="fa-solid fa-arrow-left"></i>
        Back to Login
    </a>
</div>
</div>

@endsection

@push('scripts')

<script>

function togglePassword(id) {

const input = document.getElementById(id);
input.type = input.type === 'password' ? 'text': 'password';

}

</script>

@endpush
