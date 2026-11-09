@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')

<div class="welcome-banner">
    <div>
        <span class="welcome-label">ADMIN PANEL</span>
        <h1>Welcome back, {{ auth()->user()->name }}!</h1>
        <p>Manage your assigned platform operations.</p>
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
        <p>Registered users</p>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span>Total Accounts</span>
            <div class="stat-icon blue">▦</div>
        </div>
        <h2>{{ \App\Models\User::count() }}</h2>
        <p>Platform accounts</p>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span>My Account</span>
            <div class="stat-icon purple">♧</div>
        </div>
        <h2>Active</h2>
        <p>Administrator access</p>
    </div>

</div>

<div class="dashboard-grid">

    <div class="dashboard-card">
        <h3>Quick Actions</h3>
        <p>Access your management tools</p>

        <div class="quick-actions">

            <a href="#" class="action-item">
                <div class="action-icon green">♙</div>
                <div>
                    <h4>User Management</h4>
                    <p>View registered users</p>
                </div>
                <span>→</span>
            </a>

            <a href="#" class="action-item">
                <div class="action-icon blue">▤</div>
                <div>
                    <h4>Reports</h4>
                    <p>View platform reports</p>
                </div>
                <span>→</span>
            </a>

            <a href="#" class="action-item">
                <div class="action-icon orange">⚙</div>
                <div>
                    <h4>Settings</h4>
                    <p>Manage account settings</p>
                </div>
                <span>→</span>
            </a>

        </div>
    </div>

    <div class="dashboard-card account-card">
        <h3>My Account</h3>

        <div class="account-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <h3 class="account-name">{{ auth()->user()->name }}</h3>

        <span class="role-badge">ADMIN</span>

        <div class="account-details">
            <div>
                <span>Phone</span>
                <strong>{{ auth()->user()->phone }}</strong>
            </div>

            <div>
                <span>Email</span>
                <strong>{{ auth()->user()->email ?? 'Not provided' }}</strong>
            </div>
        </div>
    </div>

</div>

@endsection

@include('partials.dashboard-styles')