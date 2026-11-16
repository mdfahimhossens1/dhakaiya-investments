@extends('layouts.app')

@section('title', 'Platform Reports')

@section('content')

@php
    $maxRole = max(1, (int) $users['by_role']->max());
    $maxStatus = max(1, (int) $users['by_status']->max());
    $maxDay = max(1, (int) $daily->max('count'));

    $totalDaily = (int) $daily->sum('count');

    $roleColors = [
        'super_admin' => 'sa-ic-purple',
        'admin' => 'sa-ic-blue',
        'user' => 'sa-ic-green',
    ];
@endphp

<div class="sa-page">

    {{-- Header --}}
    <div class="sa-header">
        <div>
            <div class="sa-breadcrumb">
                <span>Dashboard</span>
                <i class="fas fa-chevron-right"></i>
                <span>Reports & Monitoring</span>
                <i class="fas fa-chevron-right"></i>
                <strong>Platform Reports</strong>
            </div>

            <h1>Platform Reports</h1>

            <p>
                Platform overview from
                {{ $from->format('d M Y') }}
                to
                {{ $to->format('d M Y') }}
            </p>
        </div>

        <div class="sa-report-generated">
            <i class="fas fa-clock"></i>
            Report generated {{ now()->format('d M Y, h:i A') }}
        </div>
    </div>

    @include('partials.alerts')

    {{-- Date Filter --}}
    <div class="sa-card">
        <div class="sa-card-header">
            <div>
                <h3><i class="fas fa-calendar-alt"></i> Report Period</h3>
                <p>Select a date range to filter platform registrations.</p>
            </div>
        </div>

        <div class="sa-card-body">
            <form
                method="GET"
                action="{{ route('super-admin.reports.platform') }}"
                class="sa-filter-form"
                style="border-bottom:0;padding:0"
            >
                <label class="sa-date-field">
                    From
                    <input
                        type="date"
                        name="from"
                        class="sa-filter-date"
                        value="{{ $from->toDateString() }}"
                        required
                    >
                </label>

                <label class="sa-date-field">
                    To
                    <input
                        type="date"
                        name="to"
                        class="sa-filter-date"
                        value="{{ $to->toDateString() }}"
                        required
                    >
                </label>

                <button type="submit" class="sa-btn sa-btn-filter">
                    <i class="fas fa-filter"></i>
                    Apply Filter
                </button>

                <a
                    href="{{ route('super-admin.reports.platform') }}"
                    class="sa-btn sa-btn-reset"
                >
                    <i class="fas fa-redo"></i>
                    This Month
                </a>
            </form>
        </div>
    </div>

    {{-- Overview Statistics --}}
    <div class="sa-stats">

        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-blue">
                <i class="fas fa-users"></i>
            </div>

            <div>
                <span>Total Users</span>
                <h3>{{ number_format($users['total']) }}</h3>
                <small>All registered users</small>
            </div>
        </div>

        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-green">
                <i class="fas fa-user-plus"></i>
            </div>

            <div>
                <span>New Users</span>
                <h3>{{ number_format($users['new']) }}</h3>
                <small>Within selected date range</small>
            </div>
        </div>

        <div class="sa-stat-card {{ is_null($catalogue['companies']) ? 'is-missing' : '' }}">
            <div class="sa-stat-icon sa-ic-orange">
                <i class="fas fa-building"></i>
            </div>

            <div>
                <span>Total Companies</span>

                <h3>
                    {{ $catalogue['companies'] === null
                        ? 'Not connected'
                        : number_format($catalogue['companies']) }}
                </h3>

                <small>Platform company records</small>
            </div>
        </div>

        <div class="sa-stat-card {{ is_null($catalogue['products']) ? 'is-missing' : '' }}">
            <div class="sa-stat-icon sa-ic-purple">
                <i class="fas fa-layer-group"></i>
            </div>

            <div>
                <span>Total Products</span>

                <h3>
                    {{ $catalogue['products'] === null
                        ? 'Not connected'
                        : number_format($catalogue['products']) }}
                </h3>

                <small>Platform catalogue records</small>
            </div>
        </div>

    </div>

    {{-- Users Breakdown --}}
    <div class="sa-grid-2">

        {{-- Users by Role --}}
        <div class="sa-card">

            <div class="sa-card-header">
                <div>
                    <h3>
                        <i class="fas fa-user-tag"></i>
                        Users by Role
                    </h3>
                    <p>Distribution of registered users by role.</p>
                </div>

                <span class="sa-badge">
                    {{ number_format($users['by_role']->sum()) }} Users
                </span>
            </div>

            <div class="sa-card-body">

                @forelse($users['by_role'] as $role => $total)

                    @php
                        $roleKey = (string) $role;
                        $roleColor = $roleColors[$roleKey] ?? 'sa-ic-blue';
                        $percentage = ($total / $maxRole) * 100;
                    @endphp

                    <div class="sa-bar-row">

                        <div class="sa-bar-label">
                            <span class="sa-role-dot {{ $roleColor }}"></span>
                            {{ ucwords(str_replace('_', ' ', $roleKey ?: 'Unknown')) }}
                        </div>

                        <div class="sa-bar-track">
                            <div
                                class="sa-bar-fill"
                                style="width: {{ $percentage }}%"
                            ></div>
                        </div>

                        <div class="sa-bar-val">
                            {{ number_format($total) }}
                        </div>

                    </div>

                @empty

                    <div class="sa-muted-note">
                        <i class="fas fa-info-circle"></i>
                        No user role data available.
                    </div>

                @endforelse

            </div>
        </div>

        {{-- Users by Status --}}
        <div class="sa-card">

            <div class="sa-card-header">
                <div>
                    <h3>
                        <i class="fas fa-chart-pie"></i>
                        Users by Status
                    </h3>
                    <p>Distribution of users by account status.</p>
                </div>

                <span class="sa-badge">
                    {{ number_format($users['by_status']->sum()) }} Users
                </span>
            </div>

            <div class="sa-card-body">

                @forelse($users['by_status'] as $status => $total)

                    <div class="sa-bar-row">

                        <div class="sa-bar-label">
                            {{ ucwords(str_replace('_', ' ', (string) $status)) ?: 'Unknown' }}
                        </div>

                        <div class="sa-bar-track">
                            <div
                                class="sa-bar-fill"
                                style="width: {{ ($total / $maxStatus) * 100 }}%"
                            ></div>
                        </div>

                        <div class="sa-bar-val">
                            {{ number_format($total) }}
                        </div>

                    </div>

                @empty

                    <div class="sa-muted-note">
                        <i class="fas fa-info-circle"></i>
                        No status data available or the users table does not have a status column.
                    </div>

                @endforelse

            </div>
        </div>

    </div>

    {{-- Daily Registration Chart --}}
    <div class="sa-card">

        <div class="sa-card-header">
            <div>
                <h3>
                    <i class="fas fa-chart-column"></i>
                    Daily Registrations
                </h3>

                <p>
                    New user registrations during the selected period.
                    The chart displays up to 60 days.
                </p>
            </div>

            <span class="sa-badge">
                {{ number_format($totalDaily) }} Registrations
            </span>
        </div>

        <div class="sa-card-body">

            @if($daily->isNotEmpty())

                <div class="sa-columns">

                    @foreach($daily as $day)

                        @php
                            $height = $day['count'] > 0
                                ? max(3, ($day['count'] / $maxDay) * 100)
                                : 0;
                        @endphp

                        <div
                            class="sa-col"
                            title="{{ $day['date'] }}: {{ number_format($day['count']) }} users"
                        >
                            <div style="height: {{ $height }}%"></div>
                        </div>

                    @endforeach

                </div>

                <div class="sa-col-axis">
                    <span>{{ $daily->first()['date'] }}</span>
                    <span>{{ $daily->last()['date'] }}</span>
                </div>

            @else

                <div class="sa-empty-state">
                    <div class="sa-empty-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>

                    <h4>No Registration Data</h4>

                    <p>
                        Registration data is not available for the selected period.
                    </p>
                </div>

            @endif

        </div>
    </div>

</div>

@endsection

@push('styles')

    @include('partials.ui-styles')
    @include('super-admin.partials.report-styles')

    <style>
        .sa-report-generated {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #718096;
            font-size: 12px;
            background: #fff;
            border: 1px solid #e8edef;
            border-radius: 8px;
            padding: 10px 13px;
        }

        .sa-role-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .sa-role-dot.sa-ic-purple {
            background: #8b5cf6;
        }

        .sa-role-dot.sa-ic-blue {
            background: #3b82f6;
        }

        .sa-role-dot.sa-ic-green {
            background: #10b981;
        }

        .sa-empty-state {
            text-align: center;
            padding: 45px 20px;
        }

        .sa-empty-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: #f0f4f5;
            color: #84939a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 23px;
            margin: 0 auto 15px;
        }

        .sa-empty-state h4 {
            margin: 0 0 7px;
            color: #263b40;
            font-size: 15px;
        }

        .sa-empty-state p {
            margin: 0;
            color: #89969b;
            font-size: 13px;
        }

        .sa-bar-label {
            display: flex;
            align-items: center;
        }

        .sa-stat-card small {
            display: block;
            margin-top: 5px;
            color: #8a989c;
            font-size: 11px;
        }

        @media (max-width: 650px) {
            .sa-report-generated {
                margin-top: 12px;
            }
        }
    </style>

@endpush