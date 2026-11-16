@extends('layouts.app')

@section('title', 'User Details')

@section('page-title', 'User Details')

@section('content')

<div class="user-details-page">

    {{-- Header --}}
    <div class="ud-header">

        <div class="ud-header-content">
            <h2>User Details</h2>
            <p>View platform user profile and account information.</p>
        </div>

        <div class="ud-header-actions">

            <a href="{{ route('super-admin.users.index') }}" class="ud-back-btn">
                ← Back to Users
            </a>

            <a href="{{ route('super-admin.users.edit', $user->id) }}"
               class="ud-edit-btn">
                ✎ Edit Profile
            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="ud-alert success">
            ✓ {{ session('success') }}
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="ud-alert error">
            <strong>Please check the following:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Profile Card --}}
    <div class="ud-profile-card">

        <div class="ud-profile-avatar">
            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
        </div>

        <div class="ud-profile-info">

            <h2>{{ $user->name }}</h2>

            <p>User ID: #{{ $user->id }}</p>

            <div class="ud-profile-badges">

                <span class="ud-role-badge">
                    Platform User
                </span>

                @if($user->status === 'active')
                    <span class="ud-profile-status active">
                        ● Active
                    </span>
                @else
                    <span class="ud-profile-status inactive">
                        ● Inactive
                    </span>
                @endif

            </div>

        </div>

    </div>


    {{-- Personal Information --}}
    <div class="ud-section">

        <div class="ud-section-heading">
            <h3>Personal Information</h3>
            <p>Basic information associated with this account.</p>
        </div>

        <div class="ud-info-grid">

            <div class="ud-info-item">
                <span>Full Name</span>
                <strong>{{ $user->name ?: 'Not provided' }}</strong>
            </div>

            <div class="ud-info-item">
                <span>Country</span>
                <strong>{{ $user->country ?: 'Not provided' }}</strong>
            </div>

            <div class="ud-info-item">
                <span>Phone Number</span>
                <strong>{{ $user->phone ?: 'Not provided' }}</strong>
            </div>

            <div class="ud-info-item">
                <span>Email Address</span>
                <strong>{{ $user->email ?: 'Not provided' }}</strong>
            </div>

        </div>

    </div>


    {{-- Account Information --}}
    <div class="ud-section">

        <div class="ud-section-heading">
            <h3>Account Information</h3>
            <p>Registration and account identification details.</p>
        </div>

        <div class="ud-info-grid">

            <div class="ud-info-item">
                <span>Account ID</span>
                <strong>#{{ $user->id }}</strong>
            </div>

            <div class="ud-info-item">
                <span>Account Role</span>
                <strong>{{ ucfirst($user->role) }}</strong>
            </div>

            <div class="ud-info-item">
                <span>Referral Code</span>
                <strong>{{ $user->referral_code ?: 'Not available' }}</strong>
            </div>

            <div class="ud-info-item">
                <span>Referred By</span>

                <strong>
                    {{ $user->referred_by_id ? '#'.$user->referred_by_id : 'Direct Registration' }}
                </strong>
            </div>

            <div class="ud-info-item">
                <span>Registration Date</span>

                <strong>
                    {{ $user->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                </strong>
            </div>

            <div class="ud-info-item">
                <span>Last Updated</span>

                <strong>
                    {{ $user->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}
                </strong>
            </div>

            <div class="ud-info-item">
                <span>Account Status</span>

                @if($user->status === 'active')
                    <strong class="ud-text-active">● Active</strong>
                @else
                    <strong class="ud-text-inactive">● Inactive</strong>
                @endif

            </div>

        </div>


        {{-- Account Status Control --}}
        <div class="ud-status-control">

            <div class="ud-status-description">

                <h4>Account Status Control</h4>

                <p>
                    Change the current status of this user account.
                </p>

            </div>


            <form method="POST"
                  action="{{ route('super-admin.users.update-status', $user->id) }}"
                  onsubmit="return confirm('Are you sure you want to change this account status?')">

                @csrf
                @method('PATCH')

                @if($user->status === 'active')

                    <input type="hidden" name="status" value="inactive">

                    <button type="submit" class="ud-status-btn deactivate">
                        Deactivate Account
                    </button>

                @else

                    <input type="hidden" name="status" value="active">

                    <button type="submit" class="ud-status-btn activate">
                        Activate Account
                    </button>

                @endif

            </form>

        </div>

    </div>


    {{-- Footer Action --}}
    <div class="ud-footer">

        <a href="{{ route('super-admin.users.index') }}" class="ud-back-btn">
            ← Back to User List
        </a>

    </div>

</div>


<style>

.user-details-page {
    width: 100%;
    color: #1e293b;
}


/* Header */

.ud-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 22px;
}

.ud-header-content h2 {
    margin: 0 0 6px;
    font-size: 23px;
    font-weight: 750;
}

.ud-header-content p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
}

.ud-header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}


/* Buttons */

.ud-back-btn,
.ud-edit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 650;
    white-space: nowrap;
}

.ud-back-btn {
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #334155;
}

.ud-back-btn:hover {
    background: #f8fafc;
}

.ud-edit-btn {
    border: 1px solid #1e3a8a;
    background: #1e3a8a;
    color: #fff;
}

.ud-edit-btn:hover {
    background: #1e40af;
}


/* Alerts */

.ud-alert {
    padding: 13px 16px;
    border-radius: 8px;
    margin-bottom: 18px;
    font-size: 13px;
}

.ud-alert.success {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
}

.ud-alert.error {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

.ud-alert ul {
    margin: 8px 0 0;
    padding-left: 18px;
}


/* Profile Card */

.ud-profile-card,
.ud-section {
    background: #fff;
    border: 1px solid #e8edf4;
    border-radius: 13px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
    margin-bottom: 20px;
}

.ud-profile-card {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 25px;
}

.ud-profile-avatar {
    width: 65px;
    height: 65px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 17px;
    background: #e0e7ff;
    color: #3730a3;
    font-size: 25px;
    font-weight: 750;
}

.ud-profile-info h2 {
    margin: 0 0 5px;
    font-size: 19px;
    font-weight: 750;
}

.ud-profile-info p {
    margin: 0 0 10px;
    color: #64748b;
    font-size: 12px;
}

.ud-profile-badges {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.ud-role-badge,
.ud-profile-status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

.ud-role-badge {
    background: #eff6ff;
    color: #2563eb;
}

.ud-profile-status.active {
    background: #dcfce7;
    color: #15803d;
}

.ud-profile-status.inactive {
    background: #fee2e2;
    color: #dc2626;
}


/* Information Sections */

.ud-section {
    padding: 22px;
}

.ud-section-heading {
    padding-bottom: 17px;
    margin-bottom: 18px;
    border-bottom: 1px solid #edf0f5;
}

.ud-section-heading h3 {
    margin: 0 0 5px;
    font-size: 15px;
    font-weight: 750;
}

.ud-section-heading p {
    margin: 0;
    color: #94a3b8;
    font-size: 12px;
}

.ud-info-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.ud-info-item {
    padding: 13px 15px;
    border: 1px solid #edf0f5;
    border-radius: 9px;
    background: #fafcff;
    min-width: 0;
}

.ud-info-item span {
    display: block;
    color: #94a3b8;
    font-size: 11px;
    margin-bottom: 7px;
}

.ud-info-item strong {
    display: block;
    color: #334155;
    font-size: 13px;
    font-weight: 650;
    overflow-wrap: anywhere;
}

.ud-text-active {
    color: #16a34a !important;
}

.ud-text-inactive {
    color: #dc2626 !important;
}


/* Status Control */

.ud-status-control {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-top: 20px;
    padding: 18px;
    border: 1px solid #e8edf4;
    border-radius: 10px;
    background: #fafcff;
}

.ud-status-description h4 {
    margin: 0 0 5px;
    font-size: 13px;
    font-weight: 750;
}

.ud-status-description p {
    margin: 0;
    color: #94a3b8;
    font-size: 11px;
}

.ud-status-btn {
    padding: 10px 15px;
    border: none;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
}

.ud-status-btn.deactivate {
    background: #fee2e2;
    color: #dc2626;
}

.ud-status-btn.deactivate:hover {
    background: #fecaca;
}

.ud-status-btn.activate {
    background: #dcfce7;
    color: #15803d;
}

.ud-status-btn.activate:hover {
    background: #bbf7d0;
}


/* Footer */

.ud-footer {
    display: flex;
    justify-content: flex-end;
    padding-bottom: 10px;
}


/* Responsive */

@media(max-width: 650px) {

    .ud-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .ud-header-actions {
        width: 100%;
        flex-wrap: wrap;
    }

    .ud-info-grid {
        grid-template-columns: 1fr;
    }

    .ud-profile-card {
        padding: 18px;
    }

    .ud-section {
        padding: 18px;
    }

}

</style>

@include('partials.dashboard-styles')

@endsection