@extends('layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')

<div class="welcome-banner">
    <div>
        <span class="welcome-label">SUPER ADMIN PANEL</span>
        <h1>Welcome back, {{ auth()->user()->name }}!</h1>
        <p>Manage and monitor your Dhakaiya Investment platform.</p>
    </div>

    <div class="welcome-icon">⌂</div>
</div>

<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-top">
            <span>Total Users</span>
            <div class="stat-icon green">♙</div>
        </div>
        <h2>{{ \App\Models\User::where('role', 'user')->count() }}</h2>
        <p>Registered platform users</p>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span>Total Admins</span>
            <div class="stat-icon blue">♧</div>
        </div>
        <h2>{{ \App\Models\User::where('role', 'admin')->count() }}</h2>
        <p>Platform administrators</p>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span>Super Admins</span>
            <div class="stat-icon purple">♔</div>
        </div>
        <h2>{{ \App\Models\User::where('role', 'super_admin')->count() }}</h2>
        <p>Platform owners</p>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span>Total Accounts</span>
            <div class="stat-icon orange">▦</div>
        </div>
        <h2>{{ \App\Models\User::count() }}</h2>
        <p>All registered accounts</p>
    </div>

</div>

<div class="dashboard-grid">

    <div class="dashboard-card">
        <div class="card-heading">
            <div>
                <h3>Platform Management</h3>
                <p>Manage your platform operations</p>
            </div>
        </div>

        <div class="quick-actions">

            <a href="{{ route('super-admin.admins.index') }}" class="action-item">
                <div class="action-icon green">♙</div>
                <div>
                    <h4>Admin Management</h4>
                    <p>Create and manage administrators</p>
                </div>
                <span>→</span>
            </a>

            <a href="#" class="action-item">
                <div class="action-icon blue">♧</div>
                <div>
                    <h4>User Management</h4>
                    <p>View and manage platform users</p>
                </div>
                <span>→</span>
            </a>

            <a href="#" class="action-item">
                <div class="action-icon purple">▣</div>
                <div>
                    <h4>Subscription Plans</h4>
                    <p>Manage platform plans</p>
                </div>
                <span>→</span>
            </a>

            <a href="#" class="action-item">
                <div class="action-icon orange">⚙</div>
                <div>
                    <h4>Platform Settings</h4>
                    <p>Configure system settings</p>
                </div>
                <span>→</span>
            </a>

        </div>
    </div>

    <div class="dashboard-card account-card">
        <h3>Account Information</h3>
        <p>Your administrator account details</p>

        <div class="account-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <h3 class="account-name">{{ auth()->user()->name }}</h3>

        <span class="role-badge">SUPER ADMIN</span>

        <div class="account-details">
            <div>
                <span>Phone</span>
                <strong>{{ auth()->user()->phone }}</strong>
            </div>

            <div>
                <span>Email</span>
                <strong>{{ auth()->user()->email ?? 'Not provided' }}</strong>
            </div>

            <div>
                <span>Account Status</span>
                <strong class="status-active">Active</strong>
            </div>
        </div>
    </div>

</div>

<div class="dashboard-card activity-card">
    <div class="card-heading">
        <div>
            <h3>Recent Platform Accounts</h3>
            <p>Latest registered users and administrators</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>USER</th>
                    <th>PHONE</th>
                    <th>ROLE</th>
                    <th>REGISTERED</th>
                </tr>
            </thead>

            <tbody>
                @forelse(\App\Models\User::latest()->take(5)->get() as $user)
                    <tr>
                        <td>
                            <div class="table-user">
                                <div class="table-avatar">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>

                        <td>{{ $user->phone }}</td>

                        <td>
                            <span class="table-role">
                                {{ ucwords(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>

                        <td>{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No accounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@include('partials.dashboard-styles')