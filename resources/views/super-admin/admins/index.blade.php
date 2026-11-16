@extends('layouts.app')

@section('title', 'Admin Management')
@section('page-title', 'Admin Management')

@section('content')

{{-- Page Banner --}}
<div class="welcome-banner">
    <div>
        <span class="welcome-label">SUPER ADMIN PANEL</span>
        <h1>Admin Management</h1>
        <p>Create, view and manage platform administrators.</p>
    </div>

    <div class="welcome-icon">♙</div>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="admin-alert-success">
        <span>✓</span>
        {{ session('success') }}
    </div>
@endif

{{-- Admin Statistics --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-top">
            <span>Total Admins</span>
            <div class="stat-icon blue">♙</div>
        </div>
        <h2>{{ $admins->total() }}</h2>
        <p>Registered administrators</p>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span>Current Page</span>
            <div class="stat-icon purple">▦</div>
        </div>
        <h2>{{ $admins->currentPage() }}</h2>
        <p>Admin list page</p>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span>Per Page</span>
            <div class="stat-icon green">☷</div>
        </div>
        <h2>{{ $admins->perPage() }}</h2>
        <p>Admins displayed per page</p>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span>Total Pages</span>
            <div class="stat-icon orange">▤</div>
        </div>
        <h2>{{ $admins->lastPage() }}</h2>
        <p>Available admin pages</p>
    </div>

</div>

{{-- Admin Management Table --}}
<div class="dashboard-card admin-list-card">

    {{-- Card Header --}}
    <div class="admin-list-header">

        <div class="admin-list-heading">
            <h3>Platform Administrators</h3>
            <p>Manage all registered administrators.</p>
        </div>

        <a href="{{ route('super-admin.admins.create') }}"
           class="admin-add-btn">
            <span>+</span>
            Add New Admin
        </a>

    </div>

    {{-- Table --}}
    <div class="table-responsive">

        <table class="dashboard-table">

            <thead>
                <tr>
                    <th>ADMIN</th>
                    <th>PHONE NUMBER</th>
                    <th>EMAIL ADDRESS</th>
                    <th>JOINED DATE</th>
                    <th>ROLE</th>
                </tr>
            </thead>

            <tbody>

                @forelse($admins as $admin)

                    <tr>

                        <td>
                            <div class="table-user">

                                <div class="table-avatar">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>

                                <strong>{{ $admin->name }}</strong>

                            </div>
                        </td>

                        <td>
                            {{ $admin->phone ?? '—' }}
                        </td>

                        <td>
                            {{ $admin->email ?? 'Not provided' }}
                        </td>

                        <td>
                            {{ $admin->created_at?->format('d M Y') ?? '—' }}
                        </td>

                        <td>
                            <span class="table-role">ADMIN</span>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5">
                            <div class="admin-empty-state">
                                <div class="empty-icon">♙</div>
                                <h4>No Administrators Found</h4>
                                <p>There are currently no administrators available.</p>
                                <a href="{{ route('super-admin.admins.create') }}">
                                    Create Your First Admin →
                                </a>
                            </div>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    @if($admins->hasPages())
        <div class="admin-pagination">
            {{ $admins->links() }}
        </div>
    @endif

</div>

@endsection

@include('partials.dashboard-styles')

<style>

/* Main Admin List Card */

.admin-list-card {
    padding: 26px;
    overflow: hidden;
}

/* Header Flex Layout */

.admin-list-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    width: 100%;
    margin-bottom: 25px;
}

.admin-list-heading {
    flex: 1;
    min-width: 0;
}

.admin-list-heading h3 {
    margin: 0 0 7px;
    color: #172033;
    font-size: 19px;
    font-weight: 700;
}

.admin-list-heading p {
    margin: 0;
    color: #8a94a6;
    font-size: 13px;
    line-height: 1.6;
}

/* Add Admin Button */

.admin-add-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 44px;
    padding: 0 20px;
    flex-shrink: 0;
    border-radius: 8px;
    background: #c49a45;
    color: #fff;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    transition: all .25s ease;
}

.admin-add-btn span {
    font-size: 21px;
    line-height: 1;
}

.admin-add-btn:hover {
    background: #a77f30;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 5px 14px rgba(196,154,69,.20);
}

/* Success Alert */

.admin-alert-success {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 15px 18px;
    margin-bottom: 22px;
    border: 1px solid #ccebd7;
    border-radius: 9px;
    background: #f0fbf4;
    color: #21864b;
    font-size: 14px;
    font-weight: 500;
}

.admin-alert-success span {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 23px;
    height: 23px;
    border-radius: 50%;
    background: #d7f3e0;
    font-weight: 700;
}

/* Table */

.admin-list-card .table-responsive {
    width: 100%;
    overflow-x: auto;
}

.admin-list-card .dashboard-table {
    width: 100%;
    min-width: 720px;
    border-collapse: collapse;
}

.admin-list-card .dashboard-table th {
    padding: 15px 16px;
    background: #f8f9fb;
    color: #7c8799;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .5px;
    white-space: nowrap;
}

.admin-list-card .dashboard-table td {
    padding: 17px 16px;
    border-bottom: 1px solid #edf0f4;
    color: #475467;
    font-size: 13px;
}

.admin-list-card .dashboard-table tbody tr:last-child td {
    border-bottom: none;
}

.admin-list-card .dashboard-table tbody tr:hover {
    background: #fcfcfd;
}

.admin-list-card .table-user {
    display: flex;
    align-items: center;
    gap: 11px;
    white-space: nowrap;
}

.admin-list-card .table-user strong {
    color: #172033;
    font-size: 13px;
    font-weight: 600;
}

.admin-list-card .table-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    border-radius: 50%;
    background: #f3ead7;
    color: #a77f30;
    font-size: 14px;
    font-weight: 700;
}

.admin-list-card .table-role {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    background: #edf3ff;
    color: #4774bd;
    font-size: 11px;
    font-weight: 700;
}

/* Empty State */

.admin-empty-state {
    padding: 45px 20px;
    text-align: center;
}

.admin-empty-state .empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 58px;
    height: 58px;
    margin: 0 auto 15px;
    border-radius: 50%;
    background: #f5f1e8;
    color: #b28a3e;
    font-size: 25px;
}

.admin-empty-state h4 {
    margin: 0 0 8px;
    color: #172033;
    font-size: 16px;
}

.admin-empty-state p {
    margin: 0 0 15px;
    color: #8a94a6;
    font-size: 13px;
}

.admin-empty-state a {
    color: #b28a3e;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
}

/* Pagination */

.admin-pagination {
    display: flex;
    justify-content: flex-end;
    padding-top: 22px;
    margin-top: 18px;
    border-top: 1px solid #edf0f4;
}

/* Responsive */

@media(max-width: 650px) {

    .admin-list-card {
        padding: 18px;
    }

    .admin-list-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 16px;
    }

    .admin-add-btn {
        width: 100%;
    }

    .admin-pagination {
        justify-content: center;
    }

}

</style>