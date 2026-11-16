@extends('layouts.app')

@section('title', 'Edit User')

@section('page-title', 'Edit User')

@section('content')

<div class="edit-user-page">

    <div class="eu-header">
        <div>
            <h2>Edit User Profile</h2>
            <p>Update the basic information of this platform user.</p>
        </div>

        <a href="{{ route('super-admin.users.show', $user->id) }}"
           class="eu-back-btn">
            ← Back to Details
        </a>
    </div>


    <div class="eu-card">

        <div class="eu-card-heading">
            <div class="eu-avatar">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>

            <div>
                <h3>{{ $user->name }}</h3>
                <p>User ID: #{{ $user->id }}</p>
            </div>
        </div>


        @if($errors->any())
            <div class="eu-alert eu-error">
                <strong>Please fix the following errors:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST"
              action="{{ route('super-admin.users.update', $user->id) }}">

            @csrf
            @method('PUT')


            <div class="eu-form-grid">

                {{-- Name --}}
                <div class="eu-field">
                    <label>Full Name <span>*</span></label>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           placeholder="Enter full name"
                           required>
                </div>


                {{-- Country --}}
                <div class="eu-field">
                    <label>Country Code <span>*</span></label>

                    <input type="text"
                           name="country"
                           value="{{ old('country', $user->country) }}"
                           placeholder="BD"
                           maxlength="2"
                           pattern="[A-Za-z]{2}"
                           style="text-transform:uppercase"
                           required>

                    <small>Use the two-letter ISO country code, e.g. BD or US.</small>
                </div>


                {{-- Phone --}}
                <div class="eu-field">
                    <label>Phone Number <span>*</span></label>

                    <input type="text"
                           name="phone"
                           value="{{ old('phone', $user->phone) }}"
                           placeholder="+8801XXXXXXXXX"
                           required>

                    <small>Include the country dial code, e.g. +880.</small>
                </div>


                {{-- Email --}}
                <div class="eu-field">
                    <label>Email Address</label>

                    <input type="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           placeholder="Enter email address">
                </div>

            </div>


            <div class="eu-note">
                <strong>Account Security Notice</strong>
                <p>
                    This form only updates basic profile information.
                    Account role, password and referral information remain unchanged.
                </p>
            </div>


            <div class="eu-actions">

                <a href="{{ route('super-admin.users.show', $user->id) }}"
                   class="eu-cancel-btn">
                    Cancel
                </a>

                <button type="submit" class="eu-submit-btn">
                    Save Changes →
                </button>

            </div>

        </form>

    </div>

</div>


<style>

.edit-user-page {
    width: 100%;
    color: #1e293b;
}

.eu-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 22px;
}

.eu-header h2 {
    margin: 0 0 6px;
    font-size: 23px;
    font-weight: 750;
}

.eu-header p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
}

.eu-back-btn,
.eu-cancel-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    color: #334155;
    text-decoration: none;
    font-size: 12px;
    font-weight: 650;
}

.eu-card {
    max-width: 950px;
    background: #fff;
    border: 1px solid #e8edf4;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
}

.eu-card-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    padding-bottom: 22px;
    margin-bottom: 23px;
    border-bottom: 1px solid #edf0f5;
}

.eu-avatar {
    height: 48px;
    width: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #e0e7ff;
    color: #3730a3;
    font-size: 19px;
    font-weight: 750;
}

.eu-card-heading h3 {
    margin: 0 0 5px;
    font-size: 15px;
    font-weight: 750;
}

.eu-card-heading p {
    margin: 0;
    color: #94a3b8;
    font-size: 11px;
}

.eu-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 21px;
}

.eu-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.eu-field label {
    font-size: 12px;
    color: #334155;
    font-weight: 700;
}

.eu-field label span {
    color: #ef4444;
}

.eu-field input {
    width: 100%;
    height: 43px;
    padding: 0 13px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    outline: none;
    font-size: 12px;
    color: #334155;
    background: #fff;
    box-sizing: border-box;
}

.eu-field input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, .08);
}

.eu-field small {
    font-size: 10px;
    color: #94a3b8;
}

.eu-note {
    margin-top: 25px;
    padding: 15px;
    border: 1px solid #dbeafe;
    background: #eff6ff;
    border-radius: 9px;
}

.eu-note strong {
    display: block;
    color: #1d4ed8;
    font-size: 12px;
    margin-bottom: 5px;
}

.eu-note p {
    margin: 0;
    color: #475569;
    font-size: 11px;
    line-height: 1.7;
}

.eu-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #edf0f5;
}

.eu-submit-btn {
    height: 40px;
    padding: 0 20px;
    border: none;
    border-radius: 8px;
    background: #1e3a8a;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.eu-submit-btn:hover {
    background: #1e40af;
}

.eu-alert {
    padding: 14px 16px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-size: 12px;
}

.eu-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
}

.eu-error ul {
    margin: 8px 0 0;
    padding-left: 18px;
}

@media(max-width: 650px) {
    .eu-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .eu-form-grid {
        grid-template-columns: 1fr;
    }

    .eu-card {
        padding: 18px;
    }
}

</style>

@include('partials.dashboard-styles')

@endsection