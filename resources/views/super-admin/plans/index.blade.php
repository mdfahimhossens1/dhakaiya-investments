@extends('layouts.app')

@section('title', 'Platform Plans')

@section('page-title', 'Platform Plans')

@section('content')

<div class="plan-management">

    {{-- Page Header --}}
    <div class="plan-page-header">
        <div>
            <h2>Platform Plans</h2>
            <p>Create and manage platform plans and their features.</p>
        </div>

        <a href="{{ route('super-admin.plans.create') }}" class="plan-add-btn">
            <span>+</span>
            Add New Plan
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="plan-alert success">
            <span>✓</span>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="plan-alert error">
            <span>!</span>
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="plan-alert error">
            <div>
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Statistics --}}
    <div class="plan-stats">

        <div class="plan-stat-card">
            <div class="plan-stat-icon blue">▣</div>
            <div>
                <span>Total Plans</span>
                <h3>{{ number_format($stats['total']) }}</h3>
            </div>
        </div>

        <div class="plan-stat-card">
            <div class="plan-stat-icon green">✓</div>
            <div>
                <span>Active Plans</span>
                <h3>{{ number_format($stats['active']) }}</h3>
            </div>
        </div>

        <div class="plan-stat-card">
            <div class="plan-stat-icon orange">◷</div>
            <div>
                <span>Inactive Plans</span>
                <h3>{{ number_format($stats['inactive']) }}</h3>
            </div>
        </div>

        <div class="plan-stat-card">
            <div class="plan-stat-icon purple">★</div>
            <div>
                <span>Featured Plans</span>
                <h3>{{ number_format($stats['featured']) }}</h3>
            </div>
        </div>

    </div>

    {{-- Plans Table Card --}}
    <div class="plan-table-card">

        <div class="plan-table-header">
            <div>
                <h3>All Plans</h3>
                <p>View and manage all available platform plans.</p>
            </div>

            <span class="plan-total-badge">
                {{ $plans->total() }} Plans
            </span>
        </div>

        {{-- Search & Filter --}}
        <form action="{{ route('super-admin.plans.index') }}"
              method="GET"
              class="plan-filter-form">

            <div class="plan-search-box">
                <span>⌕</span>
                <input
                    type="text"
                    name="search"
                    placeholder="Search plans by name..."
                    value="{{ request('search') }}"
                >
            </div>

            <select name="status" class="plan-status-filter">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                    Active
                </option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>

            <button type="submit" class="plan-filter-btn">
                Apply Filter
            </button>

            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('super-admin.plans.index') }}"
                   class="plan-reset-btn">
                    Reset
                </a>
            @endif

        </form>

        {{-- Table --}}
        <div class="plan-table-responsive">

            <table class="plan-table">

                <thead>
                    <tr>
                        <th>PLAN</th>
                        <th>DURATION</th>
                        <th>DISPLAY PRICE</th>
                        <th>FEATURES</th>
                        <th>STATUS</th>
                        <th>CREATED</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($plans as $plan)

                        <tr>

                            {{-- Plan --}}
                            <td>
                                <div class="plan-info">

                                    <div class="plan-icon">
                                        {{ strtoupper(substr($plan->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <strong>{{ $plan->name }}</strong>

                                        <small>{{ $plan->slug }}</small>

                                        @if($plan->is_featured)
                                            <span class="plan-featured-badge">
                                                ★ Featured
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            </td>

                            {{-- Duration --}}
                            <td>
                                <span class="plan-duration">
                                    {{ number_format($plan->duration_days) }} Days
                                </span>
                            </td>

                            {{-- Display Price --}}
                            <td>
                                <span class="plan-price">
                                    {{ number_format((float) $plan->display_price, 2) }}
                                </span>
                                <small class="plan-price-note">Display only</small>
                            </td>

                            {{-- Features --}}
                            <td>
                                <span class="plan-feature-count">
                                    {{ count($plan->features ?? []) }} Features
                                </span>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($plan->status === 'active')
                                    <span class="plan-status active">
                                        <span></span> Active
                                    </span>
                                @else
                                    <span class="plan-status inactive">
                                        <span></span> Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Created --}}
                            <td>
                                <span class="plan-date">
                                    {{ $plan->created_at->format('d M Y') }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="plan-actions">

                                    {{-- View --}}
                                    <a
                                        href="{{ route('super-admin.plans.show', $plan) }}"
                                        class="plan-action-btn view"
                                        title="View Plan"
                                    >
                                        ◉
                                    </a>

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('super-admin.plans.edit', $plan) }}"
                                        class="plan-action-btn edit"
                                        title="Edit Plan"
                                    >
                                        ✎
                                    </a>

                                    {{-- Status Toggle --}}
                                    <form
                                        action="{{ route('super-admin.plans.update-status', $plan) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to change this plan status?')"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="hidden"
                                            name="status"
                                            value="{{ $plan->status === 'active' ? 'inactive' : 'active' }}"
                                        >

                                        <button
                                            type="submit"
                                            class="plan-action-btn toggle"
                                            title="{{ $plan->status === 'active' ? 'Deactivate' : 'Activate' }}"
                                        >
                                            {{ $plan->status === 'active' ? '⏸' : '▶' }}
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('super-admin.plans.destroy', $plan) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this plan? This action cannot be undone.')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="plan-action-btn delete"
                                            title="Delete Plan"
                                        >
                                            ×
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">

                                <div class="plan-empty-state">

                                    <div class="plan-empty-icon">▣</div>

                                    <h3>No Plans Found</h3>

                                    <p>
                                        @if(request()->filled('search') || request()->filled('status'))
                                            No plans match your search or selected filter.
                                        @else
                                            You haven't created any platform plans yet.
                                        @endif
                                    </p>

                                    @if(request()->filled('search') || request()->filled('status'))

                                        <a href="{{ route('super-admin.plans.index') }}">
                                            Clear Filters
                                        </a>

                                    @else

                                        <a href="{{ route('super-admin.plans.create') }}">
                                            + Create Your First Plan
                                        </a>

                                    @endif

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if($plans->hasPages())
            <div class="plan-pagination">
                {{ $plans->links() }}
            </div>
        @endif

    </div>

</div>

<style>

.plan-management {
    width: 100%;
}

/* Header */

.plan-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.plan-page-header h2 {
    margin: 0 0 7px;
    font-size: 23px;
    font-weight: 700;
    color: #172033;
}

.plan-page-header p,
.plan-table-header p {
    margin: 0;
    color: #8490a3;
    font-size: 13px;
}

.plan-add-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 12px 18px;
    background: #172d55;
    color: #fff;
    text-decoration: none;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 600;
    transition: .2s;
    white-space: nowrap;
}

.plan-add-btn:hover {
    background: #244477;
    color: #fff;
    transform: translateY(-2px);
}

.plan-add-btn span {
    font-size: 20px;
    line-height: 15px;
}

/* Alerts */

.plan-alert {
    padding: 14px 17px;
    border-radius: 9px;
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    font-size: 13px;
}

.plan-alert.success {
    color: #137547;
    background: #eaf8f0;
    border: 1px solid #c8ecd7;
}

.plan-alert.error {
    color: #b42318;
    background: #fff0ef;
    border: 1px solid #ffd5d1;
}

.plan-alert ul {
    margin: 7px 0 0;
    padding-left: 18px;
}

/* Statistics */

.plan-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 25px;
}

.plan-stat-card {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 12px;
    padding: 21px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 3px 12px rgba(20, 35, 60, .025);
}

.plan-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
    font-weight: 700;
    flex-shrink: 0;
}

.plan-stat-icon.blue {
    background: #edf3ff;
    color: #3568c8;
}

.plan-stat-icon.green {
    background: #e9f8f0;
    color: #15945b;
}

.plan-stat-icon.orange {
    background: #fff4e7;
    color: #e58b27;
}

.plan-stat-icon.purple {
    background: #f2edff;
    color: #8055c9;
}

.plan-stat-card span {
    display: block;
    color: #8490a3;
    font-size: 12px;
    margin-bottom: 6px;
}

.plan-stat-card h3 {
    margin: 0;
    color: #172033;
    font-size: 24px;
    font-weight: 700;
}

/* Table Card */

.plan-table-card {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 12px rgba(20, 35, 60, .025);
}

.plan-table-header {
    padding: 21px 23px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border-bottom: 1px solid #edf0f5;
}

.plan-table-header h3 {
    margin: 0 0 6px;
    font-size: 16px;
    font-weight: 700;
    color: #172033;
}

.plan-total-badge {
    background: #f0f4fb;
    color: #345b96;
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

/* Filters */

.plan-filter-form {
    padding: 17px 22px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    border-bottom: 1px solid #edf0f5;
}

.plan-search-box {
    display: flex;
    align-items: center;
    gap: 9px;
    flex: 1;
    min-width: 200px;
    max-width: 350px;
    border: 1px solid #e5e9f0;
    border-radius: 8px;
    padding: 0 12px;
    height: 39px;
}

.plan-search-box span {
    color: #8490a3;
    font-size: 20px;
}

.plan-search-box input {
    border: none;
    outline: none;
    width: 100%;
    font-size: 12px;
    color: #26334a;
}

.plan-search-box input::placeholder {
    color: #a0a8b6;
}

.plan-status-filter {
    height: 39px;
    min-width: 125px;
    padding: 0 12px;
    border: 1px solid #e5e9f0;
    border-radius: 8px;
    color: #526078;
    background: #fff;
    font-size: 12px;
    outline: none;
}

.plan-filter-btn,
.plan-reset-btn {
    height: 39px;
    padding: 0 15px;
    border-radius: 8px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
}

.plan-filter-btn {
    background: #172d55;
    color: #fff;
}

.plan-filter-btn:hover {
    background: #244477;
}

.plan-reset-btn {
    background: #f0f2f6;
    color: #647087;
}

/* Table */

.plan-table-responsive {
    width: 100%;
    overflow-x: auto;
}

.plan-table {
    width: 100%;
    min-width: 1050px;
    border-collapse: collapse;
}

.plan-table thead {
    background: #f8f9fc;
}

.plan-table th {
    padding: 14px 18px;
    color: #8490a3;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .5px;
    text-align: left;
    white-space: nowrap;
}

.plan-table td {
    padding: 16px 18px;
    border-top: 1px solid #f0f2f6;
    vertical-align: middle;
}

.plan-table tbody tr:hover {
    background: #fbfcfe;
}

/* Plan Info */

.plan-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 175px;
}

.plan-icon {
    width: 43px;
    height: 43px;
    border-radius: 9px;
    background: #edf2fa;
    color: #345b96;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 18px;
    font-weight: 700;
}

.plan-info strong {
    display: block;
    font-size: 13px;
    color: #26334a;
    font-weight: 600;
}

.plan-info small {
    display: block;
    margin-top: 5px;
    font-size: 10px;
    color: #929bad;
}

.plan-featured-badge {
    display: inline-block;
    margin-top: 5px;
    background: #fff4dc;
    color: #b7791f;
    padding: 3px 7px;
    border-radius: 5px;
    font-size: 9px;
    font-weight: 700;
}

/* Duration & Price */

.plan-duration {
    background: #f0f4fb;
    color: #345b96;
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.plan-price {
    color: #26334a;
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
}

.plan-price-note {
    display: block;
    color: #929bad;
    font-size: 9px;
    margin-top: 4px;
}

.plan-feature-count {
    background: #f2edff;
    color: #8055c9;
    padding: 6px 9px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

/* Status */

.plan-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.plan-status > span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.plan-status.active {
    background: #e9f8f0;
    color: #16814f;
}

.plan-status.active > span {
    background: #20a568;
}

.plan-status.inactive {
    background: #fff1ed;
    color: #c65b3c;
}

.plan-status.inactive > span {
    background: #e7795a;
}

.plan-date {
    color: #647087;
    font-size: 11px;
    white-space: nowrap;
}

/* Actions */

.plan-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}

.plan-actions form {
    margin: 0;
}

.plan-action-btn {
    width: 30px;
    height: 30px;
    border: none;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-decoration: none;
    font-size: 15px;
    transition: .2s;
}

.plan-action-btn.view {
    background: #edf7ff;
    color: #2781bb;
}

.plan-action-btn.edit {
    background: #edf3ff;
    color: #3568c8;
}

.plan-action-btn.toggle {
    background: #fff4e7;
    color: #d58a27;
}

.plan-action-btn.delete {
    background: #fff0ef;
    color: #d34c43;
    font-size: 21px;
}

.plan-action-btn:hover {
    transform: translateY(-2px);
}

/* Empty State */

.plan-empty-state {
    text-align: center;
    padding: 55px 20px;
}

.plan-empty-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    border-radius: 15px;
    background: #f0f4fb;
    color: #5276b0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 25px;
}

.plan-empty-state h3 {
    margin: 0 0 8px;
    color: #26334a;
    font-size: 17px;
}

.plan-empty-state p {
    color: #8994a7;
    font-size: 13px;
    margin-bottom: 18px;
}

.plan-empty-state a {
    display: inline-block;
    background: #172d55;
    color: #fff;
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
}

/* Pagination */

.plan-pagination {
    padding: 18px 22px;
    border-top: 1px solid #edf0f5;
}

.plan-pagination nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Responsive */

@media (max-width: 1100px) {
    .plan-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {
    .plan-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .plan-stats {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .plan-table-header {
        padding: 17px;
    }

    .plan-filter-form {
        padding: 15px;
    }

    .plan-search-box {
        max-width: 100%;
        flex-basis: 100%;
    }

    .plan-status-filter {
        flex: 1;
    }
}

@media (max-width: 480px) {
    .plan-page-header h2 {
        font-size: 20px;
    }

    .plan-add-btn {
        width: 100%;
        justify-content: center;
    }

    .plan-filter-btn,
    .plan-reset-btn {
        flex: 1;
    }
}

</style>

@endsection