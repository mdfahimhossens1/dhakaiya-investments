@extends('layouts.auth')

@section('title', 'Forgot Password')

@push('styles')

<style>     .forgot-card {         width: 100%;         max-width: 430px;         margin: 0 auto;     }      .forgot-icon {         width: 65px;         height: 65px;         margin: 0 auto 22px;         display: flex;         align-items: center;         justify-content: center;         border-radius: 18px;         background: rgba(214, 173, 98, .12);         border: 1px solid rgba(214, 173, 98, .3);         color: #d6ad62;         font-size: 27px;     }      .forgot-card h2 {         text-align: center;         margin-bottom: 10px;     }      .forgot-card .subtitle {         text-align: center;         line-height: 1.7;         margin-bottom: 28px;     }      .forgot-info {         padding: 13px 15px;         margin-bottom: 20px;         border-radius: 10px;         background: rgba(214, 173, 98, .09);         border: 1px solid rgba(214, 173, 98, .2);         color: #d6ad62;         font-size: 13px;         line-height: 1.6;     }      .forgot-success {         padding: 13px 15px;         margin-bottom: 20px;         border-radius: 10px;         background: rgba(34, 197, 94, .10);         border: 1px solid rgba(34, 197, 94, .25);         color: #4ade80;         font-size: 13px;         line-height: 1.6;     }      .forgot-error {         padding: 13px 15px;         margin-bottom: 20px;         border-radius: 10px;         background: rgba(239, 68, 68, .10);         border: 1px solid rgba(239, 68, 68, .25);         color: #f87171;         font-size: 13px;         line-height: 1.6;     }      .forgot-back {         text-align: center;         margin-top: 23px;         font-size: 13px;     }      .forgot-back a {         color: #d6ad62;         font-weight: 600;         text-decoration: none;     }      .forgot-back a\:hover {         color: #f0d49c;         text-decoration: underline;     } </style>

@endpush

@section('content')

<div class="forgot-card">

<div class="forgot-icon">
    <i class="fa-solid fa-lock"></i>
</div>

<h2>Forgot Password?</h2>

<p class="subtitle">
    No worries! Enter your registered email address
    and we will send you a password reset link.
</p>

@if (session('status'))
    <div class="forgot-success">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="forgot-error">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="forgot-info">
    <i class="fa-solid fa-circle-info"></i>
    Make sure you enter the email address associated
    with your account.
</div>

<form method="POST" action="{{ route('password.email') }}">

    @csrf

    <div class="form-group">

        <label for="email">Email Address</label>

        <input
            type="email"
            name="email"
            id="email"
            placeholder="Enter your registered email"
            value="{{ old('email') }}"
            autocomplete="email"
            required
            autofocus
        >

    </div>

    <button type="submit" class="auth-button">
        <i class="fa-solid fa-paper-plane"></i>
        Send Reset Link
    </button>

</form>

<div class="forgot-back">
    <a href="{{ route('login') }}">
        <i class="fa-solid fa-arrow-left"></i>
        Back to Login
    </a>
</div>
</div>

@endsection
