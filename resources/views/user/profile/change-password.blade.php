@extends('layouts.app')

@section('title', 'Change Password')

@section('content')

<div class="password-page">

    <div class="page-heading">
        <div class="breadcrumb">
            <a href="{{ route('user.dashboard') }}">Dashboard</a>
            <span>/</span>
            <span>My Profile</span>
            <span>/</span>
            <strong>Change Password</strong>
        </div>

        <h1>Change Password</h1>
        <p>Update your account password to keep your account secure.</p>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <span>✓</span>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <strong>Please check the following:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="password-layout">

        <div class="security-sidebar">

            <div class="security-illustration">🔐</div>

            <h3>Keep Your Account Secure</h3>

            <p>
                Use a strong password that is difficult to guess.
                Never share your password with anyone.
            </p>

            <div class="security-tips">

                <div>
                    <span>✓</span>
                    At least 8 characters
                </div>

                <div>
                    <span>✓</span>
                    Include uppercase and lowercase letters
                </div>

                <div>
                    <span>✓</span>
                    Include at least one number
                </div>

                <div>
                    <span>✓</span>
                    Avoid using easily guessed information
                </div>

            </div>

        </div>

        <div class="password-form-card">

            <div class="form-heading">
                <h3>Update Your Password</h3>
                <p>Enter your current password and choose a new one.</p>
            </div>

            <form method="POST" action="{{ route('user.change-password.update') }}">

                @csrf
                @method('PUT')

                <div class="form-group">

                    <label for="current_password">Current Password <span>*</span></label>

                    <div class="password-input">
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            placeholder="Enter current password"
                            autocomplete="current-password"
                            required
                        >

                        <button type="button" class="toggle-password"
                                data-target="current_password">
                            Show
                        </button>
                    </div>

                </div>

                <div class="form-group">

                    <label for="password">New Password <span>*</span></label>

                    <div class="password-input">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter new password"
                            autocomplete="new-password"
                            required
                        >

                        <button type="button" class="toggle-password"
                                data-target="password">
                            Show
                        </button>
                    </div>

                </div>

                <div class="form-group">

                    <label for="password_confirmation">Confirm New Password <span>*</span></label>

                    <div class="password-input">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Confirm new password"
                            autocomplete="new-password"
                            required
                        >

                        <button type="button" class="toggle-password"
                                data-target="password_confirmation">
                            Show
                        </button>
                    </div>

                </div>

                <div class="password-guideline">
                    <span>ℹ️</span>
                    Your new password must contain at least 8 characters,
                    uppercase and lowercase letters, and a number.
                </div>

                <div class="form-actions">

                    <a href="{{ route('user.dashboard') }}" class="cancel-btn">
                        Cancel
                    </a>

                    <button type="submit" class="save-btn">
                        Update Password →
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
.password-page {
    max-width: 1200px;
    margin: 0 auto;
}

.page-heading {
    margin-bottom: 25px;
}

.breadcrumb {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
    align-items: center;
    color: #94a3b8;
    font-size: 12px;
    margin-bottom: 12px;
}

.breadcrumb a {
    color: #087f5b;
    text-decoration: none;
}

.breadcrumb strong {
    color: #334155;
}

.page-heading h1 {
    color: #1e293b;
    font-size: 25px;
    margin: 0 0 7px;
}

.page-heading p {
    color: #84919f;
    font-size: 13px;
    margin: 0;
}

.alert-success,
.alert-error {
    padding: 14px 17px;
    border-radius: 9px;
    margin-bottom: 20px;
    font-size: 13px;
}

.alert-success {
    display: flex;
    gap: 10px;
    align-items: center;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}

.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
}

.alert-error ul {
    margin: 8px 0 0;
    padding-left: 20px;
}

.alert-error li {
    margin-top: 4px;
}

.password-layout {
    display: grid;
    grid-template-columns: 320px minmax(0, 1fr);
    gap: 20px;
    align-items: start;
}

.security-sidebar {
    background: linear-gradient(145deg, #087f5b, #064e3b);
    color: white;
    border-radius: 13px;
    padding: 27px 23px;
}

.security-illustration {
    width: 62px;
    height: 62px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.15);
    border-radius: 14px;
    font-size: 29px;
    margin-bottom: 20px;
}

.security-sidebar h3 {
    font-size: 18px;
    margin: 0 0 10px;
    color: white;
}

.security-sidebar > p {
    font-size: 12px;
    line-height: 1.8;
    color: rgba(255,255,255,.8);
    margin: 0;
}

.security-tips {
    border-top: 1px solid rgba(255,255,255,.2);
    margin-top: 22px;
    padding-top: 19px;
}

.security-tips div {
    display: flex;
    gap: 9px;
    align-items: flex-start;
    margin-bottom: 14px;
    font-size: 11px;
    color: rgba(255,255,255,.9);
    line-height: 1.5;
}

.security-tips span {
    width: 17px;
    height: 17px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.18);
    border-radius: 50%;
    flex-shrink: 0;
    font-size: 10px;
}

.password-form-card {
    background: white;
    border: 1px solid #e8edf0;
    border-radius: 13px;
    padding: 27px;
}

.form-heading {
    border-bottom: 1px solid #edf0f2;
    padding-bottom: 20px;
    margin-bottom: 23px;
}

.form-heading h3 {
    color: #1e293b;
    font-size: 17px;
    margin: 0 0 6px;
}

.form-heading p {
    color: #94a3b8;
    font-size: 12px;
    margin: 0;
}

.form-group {
    margin-bottom: 21px;
}

.form-group label {
    display: block;
    font-size: 12px;
    color: #334155;
    font-weight: 600;
    margin-bottom: 8px;
}

.form-group label span {
    color: #dc2626;
}

.password-input {
    position: relative;
}

.password-input input {
    width: 100%;
    height: 45px;
    padding: 0 65px 0 13px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 12px;
    color: #334155;
    outline: none;
    box-sizing: border-box;
}

.password-input input:focus {
    border-color: #087f5b;
    box-shadow: 0 0 0 3px rgba(8,127,91,.08);
}

.toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    color: #087f5b;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
}

.password-guideline {
    display: flex;
    gap: 9px;
    align-items: flex-start;
    padding: 13px;
    background: #f8fafc;
    border-radius: 8px;
    color: #64748b;
    font-size: 11px;
    line-height: 1.6;
    margin-bottom: 24px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 11px;
    border-top: 1px solid #edf0f2;
    padding-top: 20px;
}

.cancel-btn,
.save-btn {
    padding: 11px 17px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}

.cancel-btn {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.save-btn {
    background: #087f5b;
    color: white;
    border: 1px solid #087f5b;
}

.save-btn:hover {
    background: #065f46;
}

@media(max-width: 850px) {
    .password-layout {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 500px) {
    .password-form-card {
        padding: 18px;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .form-actions a,
    .form-actions button {
        text-align: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function () {
        const input = document.getElementById(this.dataset.target);

        if (!input) return;

        const isPassword = input.type === 'password';

        input.type = isPassword ? 'text' : 'password';
        this.textContent = isPassword ? 'Hide' : 'Show';
    });
});
</script>
@endpush