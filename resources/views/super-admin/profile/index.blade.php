@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

@php
    $roleLabel = ucwords(str_replace('_', ' ', (string) $user->role));
    $initial = strtoupper(mb_substr($user->name ?? 'U', 0, 1));
@endphp

<div class="profile-page">

    {{-- Page Header --}}
    <div class="profile-header">
        <div>
            <div class="profile-breadcrumb">
                <span>Dashboard</span>
                <i class="fas fa-chevron-right"></i>
                <span>System Management</span>
                <i class="fas fa-chevron-right"></i>
                <strong>My Profile</strong>
            </div>

            <h1>My Profile</h1>
            <p>View and manage your account information.</p>
        </div>

        <button type="button" class="profile-btn profile-btn-primary" id="openProfileModal">
            <i class="fas fa-user-pen"></i>
            Edit Profile
        </button>
    </div>

    @include('partials.alerts')

    {{-- Statistics --}}
    <div class="profile-stats">

        <div class="profile-stat-card">
            <div class="profile-stat-icon purple">
                <i class="fas fa-user-shield"></i>
            </div>

            <div>
                <span>Account Role</span>
                <h3>{{ $roleLabel }}</h3>
                <small>Your access level</small>
            </div>
        </div>

        <div class="profile-stat-card">
            <div class="profile-stat-icon green">
                <i class="fas fa-calendar-check"></i>
            </div>

            <div>
                <span>Member Since</span>
                <h3>{{ $user->created_at?->format('d M Y') ?? 'N/A' }}</h3>
                <small>Account creation date</small>
            </div>
        </div>

        <div class="profile-stat-card">
            <div class="profile-stat-icon blue">
                <i class="fas fa-clock-rotate-left"></i>
            </div>

            <div>
                <span>Last Updated</span>
                <h3>{{ $user->updated_at?->diffForHumans() ?? 'N/A' }}</h3>
                <small>Last profile change</small>
            </div>
        </div>

    </div>

    {{-- Profile Information --}}
    <div class="profile-card">

        <div class="profile-card-header">
            <div>
                <h3>
                    <i class="fas fa-id-card"></i>
                    Profile Details
                </h3>
                <p>Your personal and account information.</p>
            </div>

            <span class="profile-role-badge">{{ $roleLabel }}</span>
        </div>

        <div class="profile-card-body">

            {{-- Profile Identity --}}
            <div class="profile-identity">

                <div class="profile-avatar">
                    {{ $initial }}
                </div>

                <div class="profile-identity-info">
                    <h2>{{ $user->name }}</h2>
                    <p>
                        <i class="fas fa-envelope"></i>
                        {{ $user->email }}
                    </p>
                </div>

                <div class="profile-verified">
                    <i class="fas fa-shield-halved"></i>
                    Account
                </div>

            </div>

            {{-- Information Grid --}}
            <div class="profile-info-grid">

                <div class="profile-info-item">
                    <div class="profile-info-label">
                        <i class="fas fa-user"></i>
                        Full Name
                    </div>
                    <strong>{{ $user->name }}</strong>
                </div>

                <div class="profile-info-item">
                    <div class="profile-info-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </div>
                    <strong>{{ $user->email }}</strong>
                </div>

                <div class="profile-info-item">
                    <div class="profile-info-label">
                        <i class="fas fa-user-tag"></i>
                        Account Role
                    </div>
                    <strong>{{ $roleLabel }}</strong>
                </div>

                <div class="profile-info-item">
                    <div class="profile-info-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </div>
                    <strong class="profile-password">••••••••••••</strong>
                </div>

            </div>

        </div>

    </div>

</div>


{{-- Edit Profile Modal --}}
<div class="profile-modal" id="profileModal" aria-hidden="true">

    <div class="profile-modal-backdrop" data-close-modal></div>

    <div class="profile-modal-dialog" role="dialog" aria-modal="true">

        <form method="POST" action="{{ route('super-admin.profile.update') }}">

            @csrf
            @method('PUT')

            {{-- Modal Header --}}
            <div class="profile-modal-header">

                <div>
                    <h3>
                        <i class="fas fa-user-pen"></i>
                        Edit Profile
                    </h3>
                    <p>Update your account information.</p>
                </div>

                <button type="button" class="profile-modal-close" data-close-modal>
                    <i class="fas fa-xmark"></i>
                </button>

            </div>

            {{-- Modal Body --}}
            <div class="profile-modal-body">

                <div class="profile-form-group">

                    <label>Full Name <span>*</span></label>

                    <input
                        type="text"
                        name="name"
                        class="profile-input @error('name') profile-input-error @enderror"
                        value="{{ old('name', $user->name) }}"
                        placeholder="Enter your full name"
                        required
                    >

                    @error('name')
                        <small class="profile-error">{{ $message }}</small>
                    @enderror

                </div>

                <div class="profile-form-group">

                    <label>Email Address <span>*</span></label>

                    <input
                        type="email"
                        name="email"
                        class="profile-input @error('email') profile-input-error @enderror"
                        value="{{ old('email', $user->email) }}"
                        placeholder="Enter your email address"
                        required
                    >

                    @error('email')
                        <small class="profile-error">{{ $message }}</small>
                    @enderror

                </div>

                <div class="profile-form-divider">
                    <span>Change Password (Optional)</span>
                </div>

                <div class="profile-form-group">

                    <label>New Password</label>

                    <div class="profile-password-wrap">

                        <input
                            type="password"
                            name="password"
                            class="profile-input @error('password') profile-input-error @enderror"
                            autocomplete="new-password"
                            placeholder="Enter new password"
                        >

                        <button type="button" class="profile-eye" data-toggle-password>
                            <i class="fas fa-eye"></i>
                        </button>

                    </div>

                    @error('password')
                        <small class="profile-error">{{ $message }}</small>
                    @enderror

                </div>

                <div class="profile-form-group">

                    <label>Confirm New Password</label>

                    <div class="profile-password-wrap">

                        <input
                            type="password"
                            name="password_confirmation"
                            class="profile-input"
                            autocomplete="new-password"
                            placeholder="Confirm new password"
                        >

                        <button type="button" class="profile-eye" data-toggle-password>
                            <i class="fas fa-eye"></i>
                        </button>

                    </div>

                </div>

                <div class="profile-password-note">
                    <i class="fas fa-info-circle"></i>
                    Leave both password fields empty if you don't want to change your password.
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="profile-modal-footer">

                <button type="button" class="profile-btn profile-btn-light" data-close-modal>
                    <i class="fas fa-xmark"></i>
                    Cancel
                </button>

                <button type="submit" class="profile-btn profile-btn-primary">
                    <i class="fas fa-check"></i>
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


{{-- Styles --}}
@push('styles')

<style>

    /* =====================================
       PROFILE PAGE
    ===================================== */

    .profile-page {
        width: 100%;
        min-width: 0;
        color: #263b40;
    }

    .profile-page *,
    .profile-modal * {
        box-sizing: border-box;
    }

    /* Header */

    .profile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .profile-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin-bottom: 10px;
        color: #94a3a8;
        font-size: 12px;
    }

    .profile-breadcrumb i {
        font-size: 9px;
    }

    .profile-breadcrumb strong {
        color: #087f5b;
        font-weight: 600;
    }

    .profile-header h1 {
        margin: 0;
        color: #203b35;
        font-size: 25px;
        font-weight: 700;
    }

    .profile-header p {
        margin: 7px 0 0;
        color: #87969a;
        font-size: 13px;
    }

    /* Buttons */

    .profile-btn {
        min-height: 40px;
        padding: 0 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s ease;
        white-space: nowrap;
    }

    .profile-btn-primary {
        background: #087f5b;
        border-color: #087f5b;
        color: #fff;
    }

    .profile-btn-primary:hover {
        background: #066b4c;
        border-color: #066b4c;
        color: #fff;
    }

    .profile-btn-light {
        background: #fff;
        color: #64757a;
        border-color: #dfe7e9;
    }

    .profile-btn-light:hover {
        background: #f4f7f8;
    }

    /* Statistics */

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .profile-stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
        padding: 19px;
        background: #fff;
        border: 1px solid #edf1f2;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(30, 50, 55, .035);
    }

    .profile-stat-icon {
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 19px;
    }

    .profile-stat-icon.purple {
        color: #8b5cf6;
        background: #f2edff;
    }

    .profile-stat-icon.green {
        color: #0c9b70;
        background: #e7f8f1;
    }

    .profile-stat-icon.blue {
        color: #3b82f6;
        background: #eaf2ff;
    }

    .profile-stat-card span {
        display: block;
        color: #829197;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .profile-stat-card h3 {
        margin: 0;
        color: #263b40;
        font-size: 17px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .profile-stat-card small {
        display: block;
        margin-top: 5px;
        color: #a0aaad;
        font-size: 11px;
    }

    /* Main Card */

    .profile-card {
        background: #fff;
        border: 1px solid #edf1f2;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(30, 50, 55, .035);
        overflow: hidden;
    }

    .profile-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 19px 22px;
        border-bottom: 1px solid #eef2f3;
    }

    .profile-card-header h3 {
        margin: 0;
        color: #263b40;
        font-size: 15px;
        font-weight: 700;
    }

    .profile-card-header h3 i {
        margin-right: 7px;
        color: #087f5b;
    }

    .profile-card-header p {
        margin: 6px 0 0;
        color: #91a0a4;
        font-size: 12px;
    }

    .profile-role-badge {
        padding: 6px 11px;
        border-radius: 20px;
        background: #e8f7f1;
        color: #087f5b;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .profile-card-body {
        padding: 24px;
    }

    /* Identity */

    .profile-identity {
        display: flex;
        align-items: center;
        gap: 16px;
        padding-bottom: 23px;
        margin-bottom: 23px;
        border-bottom: 1px solid #eef2f3;
    }

    .profile-avatar {
        width: 68px;
        height: 68px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: linear-gradient(135deg, #087f5b, #16a085);
        color: #fff;
        font-size: 27px;
        font-weight: 700;
    }

    .profile-identity-info {
        min-width: 0;
        flex: 1;
    }

    .profile-identity-info h2 {
        margin: 0 0 6px;
        color: #263b40;
        font-size: 19px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .profile-identity-info p {
        margin: 0;
        color: #8a989c;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .profile-identity-info p i {
        margin-right: 5px;
    }

    .profile-verified {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        background: #e9f8f2;
        color: #0b956c;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Information Grid */

    .profile-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px;
    }

    .profile-info-item {
        padding: 17px;
        background: #f8fafb;
        border: 1px solid #edf1f2;
        border-radius: 10px;
        min-width: 0;
    }

    .profile-info-label {
        display: block;
        margin-bottom: 9px;
        color: #8a989c;
        font-size: 12px;
    }

    .profile-info-label i {
        margin-right: 6px;
        color: #087f5b;
    }

    .profile-info-item strong {
        display: block;
        color: #263b40;
        font-size: 14px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .profile-password {
        letter-spacing: 2px;
    }


    /* =====================================
       PROFILE MODAL
    ===================================== */

    .profile-modal {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
    }

    .profile-modal.is-open {
        display: flex;
    }

    .profile-modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 35, 31, .58);
        backdrop-filter: blur(3px);
    }

    .profile-modal-dialog {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 490px;
        max-height: 92vh;
        overflow-y: auto;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
        animation: profileModalIn .2s ease;
    }

    @keyframes profileModalIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Modal Header */

    .profile-modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 15px;
        padding: 20px 22px;
        border-bottom: 1px solid #edf1f2;
    }

    .profile-modal-header h3 {
        margin: 0 0 6px;
        color: #263b40;
        font-size: 16px;
    }

    .profile-modal-header h3 i {
        margin-right: 6px;
        color: #087f5b;
    }

    .profile-modal-header p {
        margin: 0;
        color: #8a989c;
        font-size: 12px;
    }

    .profile-modal-close {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        border: 0;
        border-radius: 8px;
        background: #f0f4f5;
        color: #718187;
        font-size: 15px;
        cursor: pointer;
    }

    .profile-modal-close:hover {
        background: #e5edef;
    }

    /* Modal Body */

    .profile-modal-body {
        padding: 22px;
    }

    .profile-form-group {
        margin-bottom: 17px;
    }

    .profile-form-group label {
        display: block;
        margin-bottom: 7px;
        color: #52656a;
        font-size: 12px;
        font-weight: 600;
    }

    .profile-form-group label span {
        color: #ef4444;
    }

    .profile-input {
        display: block;
        width: 100%;
        height: 43px;
        padding: 0 12px;
        border: 1px solid #dfe7e9;
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: #263b40;
        font-size: 13px;
        transition: .2s;
    }

    .profile-input:focus {
        border-color: #087f5b;
        box-shadow: 0 0 0 3px rgba(8, 127, 91, .09);
    }

    .profile-input-error {
        border-color: #ef4444;
    }

    .profile-error {
        display: block;
        margin-top: 6px;
        color: #dc2626;
        font-size: 11px;
    }

    .profile-password-wrap {
        position: relative;
    }

    .profile-password-wrap .profile-input {
        padding-right: 43px;
    }

    .profile-eye {
        position: absolute;
        top: 50%;
        right: 6px;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        border: 0;
        background: transparent;
        color: #89999e;
        cursor: pointer;
    }

    .profile-form-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 23px 0 18px;
        color: #8a989c;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
    }

    .profile-form-divider::before,
    .profile-form-divider::after {
        content: "";
        height: 1px;
        flex: 1;
        background: #edf1f2;
    }

    .profile-password-note {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 11px 12px;
        border-radius: 8px;
        background: #f0f7f5;
        color: #648078;
        font-size: 11px;
        line-height: 1.6;
    }

    .profile-password-note i {
        margin-top: 2px;
        color: #087f5b;
    }

    /* Modal Footer */

    .profile-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 15px 22px;
        border-top: 1px solid #edf1f2;
        background: #fcfdfd;
    }


    /* =====================================
       RESPONSIVE
    ===================================== */

    @media (max-width: 1000px) {
        .profile-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 650px) {
        .profile-stats {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .profile-header {
            align-items: flex-start;
        }

        .profile-header h1 {
            font-size: 22px;
        }

        .profile-card-body {
            padding: 17px;
        }

        .profile-info-grid {
            grid-template-columns: 1fr;
        }

        .profile-verified {
            display: none;
        }

        .profile-identity {
            gap: 12px;
        }

        .profile-avatar {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            font-size: 22px;
        }

        .profile-identity-info h2 {
            font-size: 16px;
        }

        .profile-modal {
            padding: 10px;
        }

        .profile-modal-dialog {
            max-height: 95vh;
        }

        .profile-modal-header,
        .profile-modal-body {
            padding: 17px;
        }

        .profile-modal-footer {
            padding: 13px 17px;
        }

        .profile-modal-footer .profile-btn {
            flex: 1;
        }
    }

</style>

@endpush


{{-- Scripts --}}
@push('scripts')

<script>
(function () {

    const modal = document.getElementById('profileModal');
    const openBtn = document.getElementById('openProfileModal');

    if (!modal || !openBtn) {
        return;
    }

    function openModal() {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    openBtn.addEventListener('click', openModal);

    modal.querySelectorAll('[data-close-modal]').forEach(function (element) {
        element.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal();
        }
    });

    // Password visibility
    modal.querySelectorAll('[data-toggle-password]').forEach(function (button) {

        button.addEventListener('click', function () {

            const input = button.parentElement.querySelector('input');
            const icon = button.querySelector('i');

            const isPassword = input.type === 'password';

            input.type = isPassword ? 'text' : 'password';

            icon.className = isPassword
                ? 'fas fa-eye-slash'
                : 'fas fa-eye';

        });

    });

    // Reopen modal if validation errors exist
    @if ($errors->any())
        openModal();
    @endif

})();
</script>

@endpush