@extends('layouts.app')

@section('title', 'User Management')

@section('page-title', 'User Management')

@section('content')

<div class="user-management-page">

    {{-- Page Header --}}
    <div class="um-header">
        <div>
            <h2>User Management</h2>
            <p>Manage and monitor all platform accounts.</p>
        </div>

        <div class="um-header-badge">
            <span class="um-status-dot"></span>
            Platform Users
        </div>
    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="um-alert success">
            ✓ {{ session('success') }}
        </div>
    @endif


    {{-- Statistics --}}
    <div class="um-stats">

        <div class="um-stat-card">
            <div class="um-stat-icon blue">👥</div>
            <div>
                <span>Total Users</span>
                <h3>{{ number_format($totalUsers) }}</h3>
            </div>
        </div>

        <div class="um-stat-card">
            <div class="um-stat-icon purple">🛡️</div>
            <div>
                <span>Total Admins</span>
                <h3>{{ number_format($totalAdmins) }}</h3>
            </div>
        </div>

        <div class="um-stat-card">
            <div class="um-stat-icon gold">⭐</div>
            <div>
                <span>Super Admins</span>
                <h3>{{ number_format($totalSuperAdmins) }}</h3>
            </div>
        </div>

    </div>


    {{-- User Table Card --}}
    <div class="um-card">

        <div class="um-card-header">
            <div>
                <h3>All Platform Users</h3>
                <p>View and manage registered user accounts.</p>
            </div>

            <div class="um-total-badge">
                {{ number_format($users->total()) }} Users
            </div>
        </div>


        {{-- Filters --}}
        <form method="GET"
              action="{{ route('super-admin.users.index') }}"
              class="um-filter-form">

            <div class="um-search-box">
                <span>⌕</span>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, phone or email..."
                >
            </div>

            <select name="country" class="um-select">

                <option value="">All Countries</option>

                @foreach($countries as $country)
                    <option
                        value="{{ $country }}"
                        {{ request('country') === $country ? 'selected' : '' }}
                    >
                        {{ $country }}
                    </option>
                @endforeach

            </select>

            <select name="sort" class="um-select">

                <option value="latest"
                    {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                    Newest First
                </option>

                <option value="oldest"
                    {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                    Oldest First
                </option>

            </select>

            <button type="submit" class="um-filter-btn">
                Apply Filters
            </button>

            @if(request()->hasAny(['search', 'country', 'sort']) &&
                (request('search') || request('country') || request('sort') === 'oldest'))

                <a href="{{ route('super-admin.users.index') }}"
                   class="um-reset-btn">
                    Reset
                </a>

            @endif

        </form>


        {{-- Table --}}
        <div class="um-table-wrapper">

            <table class="um-table">

                <thead>
                    <tr>
                        <th>USER</th>
                        <th>COUNTRY</th>
                        <th>PHONE NUMBER</th>
                        <th>EMAIL ADDRESS</th>
                        <th>JOINED DATE</th>
                        <th>ACCOUNT STATUS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)

                        <tr>

                            {{-- User --}}
                            <td>
                                <div class="um-user-info">

                                    <div class="um-avatar">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </div>

                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        <small>User ID: #{{ $user->id }}</small>
                                    </div>

                                </div>
                            </td>


                            {{-- Country --}}
                            <td>
                                <span class="um-country">
                                    {{ $user->country ?? 'N/A' }}
                                </span>
                            </td>


                            {{-- Phone --}}
                            <td>
                                {{ $user->phone ?? 'N/A' }}
                            </td>


                            {{-- Email --}}
                            <td>
                                {{ $user->email ?? 'Not provided' }}
                            </td>


                            {{-- Joined Date --}}
                            <td>
                                <div class="um-date">
                                    {{ $user->created_at?->format('d M Y') ?? 'N/A' }}

                                    <small>
                                        {{ $user->created_at?->format('h:i A') ?? '' }}
                                    </small>
                                </div>
                            </td>


                            {{-- Account Status --}}
                            <td>
                                @if($user->status === 'active')

                                    <span class="um-status-badge active">
                                        <span></span>
                                        Active
                                    </span>

                                @else

                                    <span class="um-status-badge inactive">
                                        <span></span>
                                        Inactive
                                    </span>

                                @endif
                            </td>


                            {{-- Action --}}
                            <td>
                                <a href="{{ route('super-admin.users.show', $user->id) }}"
                                   class="um-view-btn"
                                   title="User details">

                                    View
                                    <span>→</span>

                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">

                                <div class="um-empty-state">

                                    <div class="um-empty-icon">👥</div>

                                    <h3>No Users Found</h3>

                                    <p>
                                        No user accounts match your current search.
                                    </p>

                                    <a href="{{ route('super-admin.users.index') }}">
                                        Clear Filters
                                    </a>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($users->hasPages())

            <div class="um-pagination">
                {{ $users->links() }}
            </div>

        @endif

    </div>

</div>


<style>

.user-management-page {
    width: 100%;
    color: #1e293b;
}

.um-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 25px;
}

.um-header h2 {
    font-size: 23px;
    font-weight: 750;
    margin: 0 0 6px;
}

.um-header p {
    color: #64748b;
    font-size: 13px;
    margin: 0;
}

.um-header-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 9px 14px;
    border: 1px solid #dbeafe;
    border-radius: 30px;
    background: #eff6ff;
    color: #2563eb;
    font-size: 12px;
    font-weight: 650;
}

.um-status-dot {
    height: 7px;
    width: 7px;
    border-radius: 50%;
    background: #2563eb;
}

.um-alert {
    padding: 13px 16px;
    border-radius: 8px;
    margin-bottom: 18px;
    font-size: 13px;
}

.um-alert.success {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
}

.um-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 17px;
    margin-bottom: 25px;
}

.um-stat-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #fff;
    border: 1px solid #e8edf4;
    border-radius: 13px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
}

.um-stat-icon {
    height: 46px;
    width: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 21px;
}

.um-stat-icon.blue {
    background: #eff6ff;
}

.um-stat-icon.purple {
    background: #f3e8ff;
}

.um-stat-icon.gold {
    background: #fef3c7;
}

.um-stat-card span {
    display: block;
    color: #64748b;
    font-size: 12px;
    font-weight: 550;
    margin-bottom: 5px;
}

.um-stat-card h3 {
    font-size: 23px;
    font-weight: 750;
    margin: 0;
    color: #172033;
}

.um-card {
    background: #fff;
    border: 1px solid #e8edf4;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
}

.um-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding: 22px;
    border-bottom: 1px solid #edf0f5;
}

.um-card-header h3 {
    font-size: 16px;
    font-weight: 750;
    margin: 0 0 5px;
}

.um-card-header p {
    font-size: 12px;
    color: #64748b;
    margin: 0;
}

.um-total-badge {
    background: #eff6ff;
    color: #2563eb;
    padding: 8px 13px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
}

.um-filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 18px 22px;
    border-bottom: 1px solid #edf0f5;
}

.um-search-box {
    display: flex;
    align-items: center;
    gap: 9px;
    flex: 1;
    min-width: 220px;
    height: 40px;
    padding: 0 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}

.um-search-box span {
    font-size: 21px;
    color: #94a3b8;
}

.um-search-box input {
    width: 100%;
    border: none;
    outline: none;
    font-size: 12px;
    background: transparent;
    color: #334155;
}

.um-select {
    height: 40px;
    min-width: 130px;
    padding: 0 10px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    color: #475569;
    font-size: 12px;
    outline: none;
}

.um-filter-btn {
    height: 40px;
    padding: 0 17px;
    border: none;
    border-radius: 8px;
    background: #1e3a8a;
    color: #fff;
    font-size: 12px;
    font-weight: 650;
    cursor: pointer;
}

.um-filter-btn:hover {
    background: #1e40af;
}

.um-reset-btn {
    display: flex;
    align-items: center;
    padding: 0 12px;
    color: #64748b;
    font-size: 12px;
    text-decoration: none;
}

.um-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.um-table {
    width: 100%;
    min-width: 1000px;
    border-collapse: collapse;
}

.um-table thead {
    background: #f8fafc;
}

.um-table th {
    padding: 14px 18px;
    text-align: left;
    color: #64748b;
    font-size: 10px;
    font-weight: 750;
    letter-spacing: .5px;
    white-space: nowrap;
}

.um-table td {
    padding: 15px 18px;
    border-top: 1px solid #f0f2f6;
    color: #475569;
    font-size: 12px;
    white-space: nowrap;
}

.um-table tbody tr:hover {
    background: #fafcff;
}

.um-user-info {
    display: flex;
    align-items: center;
    gap: 11px;
}

.um-avatar {
    height: 38px;
    width: 38px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #e0e7ff;
    color: #3730a3;
    font-size: 15px;
    font-weight: 750;
}

.um-user-info strong {
    display: block;
    color: #1e293b;
    font-size: 12px;
    font-weight: 700;
}

.um-user-info small,
.um-date small {
    display: block;
    color: #94a3b8;
    font-size: 10px;
    margin-top: 4px;
}

.um-country {
    display: inline-block;
    padding: 5px 9px;
    background: #f1f5f9;
    color: #475569;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 650;
}

.um-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

.um-status-badge span {
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.um-status-badge.active {
    background: #dcfce7;
    color: #15803d;
}

.um-status-badge.active span {
    background: #16a34a;
}

.um-status-badge.inactive {
    background: #fee2e2;
    color: #dc2626;
}

.um-status-badge.inactive span {
    background: #dc2626;
}

.um-view-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 12px;
    border: 1px solid #dbeafe;
    border-radius: 7px;
    color: #2563eb;
    background: #eff6ff;
    text-decoration: none;
    font-size: 11px;
    font-weight: 700;
}

.um-view-btn:hover {
    background: #dbeafe;
}

.um-empty-state {
    padding: 55px 15px;
    text-align: center;
}

.um-empty-icon {
    font-size: 35px;
    margin-bottom: 12px;
}

.um-empty-state h3 {
    color: #1e293b;
    font-size: 16px;
    margin: 0 0 7px;
}

.um-empty-state p {
    color: #94a3b8;
    font-size: 12px;
    margin: 0 0 15px;
}

.um-empty-state a {
    color: #2563eb;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
}

.um-pagination {
    padding: 18px 22px;
    border-top: 1px solid #edf0f5;
}

@media(max-width: 768px) {

    .um-stats {
        grid-template-columns: 1fr;
    }

    .um-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .um-card-header {
        align-items: flex-start;
    }

    .um-filter-form {
        flex-direction: column;
    }

    .um-search-box,
    .um-select,
    .um-filter-btn {
        width: 100%;
    }

}

</style>

@include('partials.dashboard-styles')

@endsection