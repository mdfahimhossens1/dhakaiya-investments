@extends('layouts.app')

@section('title', 'User Activity Logs')

@section('content')

<div class="sa-page">

    <div class="sa-header">
        <div>
            <div class="sa-breadcrumb">
                <span>Dashboard</span><i class="fas fa-chevron-right"></i>
                <span>Reports & Monitoring</span><i class="fas fa-chevron-right"></i>
                <strong>User Activity Logs</strong>
            </div>
            <h1>User Activity Logs</h1>
            <p>Who did what, when and from where. Passwords and field values are never stored.</p>
        </div>
    </div>

    @include('partials.alerts')

    <div class="sa-stats">
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-blue"><i class="fas fa-list"></i></div>
            <div><span>Total activities</span><h3>{{ number_format($stats['total']) }}</h3></div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-green"><i class="fas fa-bolt"></i></div>
            <div><span>Today</span><h3>{{ number_format($stats['today']) }}</h3></div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-purple"><i class="fas fa-user-check"></i></div>
            <div><span>Active users today</span><h3>{{ number_format($stats['users_today']) }}</h3></div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-red"><i class="fas fa-trash"></i></div>
            <div><span>Deletions today</span><h3>{{ number_format($stats['deletes_today']) }}</h3></div>
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-header">
            <div><h3>Activity</h3><p>Newest first.</p></div>
            <div class="sa-badge">{{ $logs->total() }} Records</div>
        </div>

        <form method="GET" action="{{ route('super-admin.reports.activity-logs') }}" class="sa-filter-form">
            <div class="sa-search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search user, action or IP address..." value="{{ request('search') }}">
            </div>

            <select name="type" class="sa-filter-select">
                <option value="">All activity</option>
                @foreach(['auth' => 'Login / logout', 'create' => 'Created', 'update' => 'Updated', 'delete' => 'Deleted'] as $v => $l)
                    <option value="{{ $v }}" {{ request('type') === $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>

            <select name="role" class="sa-filter-select">
                <option value="">All roles</option>
                @foreach(['super_admin' => 'Super Admin', 'admin' => 'Admin', 'user' => 'User'] as $v => $l)
                    <option value="{{ $v }}" {{ request('role') === $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>

            <input type="date" name="date_from" class="sa-filter-date" value="{{ request('date_from') }}" title="From date">
            <input type="date" name="date_to" class="sa-filter-date" value="{{ request('date_to') }}" title="To date">

            <button type="submit" class="sa-btn sa-btn-filter"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('super-admin.reports.activity-logs') }}" class="sa-btn sa-btn-reset"><i class="fas fa-redo"></i> Reset</a>
        </form>

        <div class="sa-table-wrapper">
            <table class="sa-table">
                <thead>
                    <tr>
                        <th>TIME</th>
                        <th>USER</th>
                        <th>TYPE</th>
                        <th>ACTIVITY</th>
                        <th>IP ADDRESS</th>
                        <th class="text-right">DETAILS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        @php
                            $isAuth = str_starts_with($log->action, 'auth.');
                            $mClass = $isAuth ? 'sa-m-auth' : match($log->method) { 'DELETE' => 'sa-m-delete', 'PUT', 'PATCH' => 'sa-m-put', default => 'sa-m-post' };
                            $mLabel = $isAuth ? 'AUTH' : $log->method;
                        @endphp
                        <tr>
                            <td style="white-space:nowrap">
                                {{ $log->created_at->format('d M Y') }}
                                <small style="display:block;color:#a0a9a8;margin-top:4px">{{ $log->created_at->format('h:i:s A') }}</small>
                            </td>
                            <td>
                                @if($log->user)
                                    <div class="sa-user-line">
                                        <strong>{{ $log->user->name }}</strong>
                                        <small>{{ ucwords(str_replace('_', ' ', $log->user->role)) }} · {{ $log->user->email }}</small>
                                    </div>
                                @else
                                    <span style="color:#a3abad">Deleted / unknown user</span>
                                @endif
                            </td>
                            <td><span class="sa-method {{ $mClass }}">{{ $mLabel }}</span></td>
                            <td>
                                <strong style="color:#263b34">{{ $log->description }}</strong>
                                <small style="display:block;color:#9aa4a8;margin-top:4px" class="sa-mono">{{ $log->action }}</small>
                            </td>
                            <td class="sa-mono">{{ $log->ip_address ?? '-' }}</td>
                            <td>
                                <div class="sa-actions">
                                    <button type="button" class="sa-action-btn" style="background:#edf4ff;color:#3478d4" title="View details"
                                        data-item="{{ json_encode([
                                            'description' => $log->description,
                                            'user' => $log->user ? $log->user->name . ' (' . $log->user->email . ')' : 'Unknown',
                                            'method' => $log->method ?? 'AUTH',
                                            'status' => $log->status_code,
                                            'route' => $log->route_name ?? $log->action,
                                            'url' => $log->url,
                                            'ip' => $log->ip_address,
                                            'agent' => $log->user_agent,
                                            'time' => $log->created_at->format('d M Y, h:i:s A'),
                                            'properties' => $log->properties,
                                        ]) }}"
                                        onclick="viewLog(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="sa-empty">
                                    <div><i class="fas fa-history"></i></div>
                                    <h4>No activity found</h4>
                                    <p>Nothing matches these filters yet. Try a wider date range.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="sa-pagination">{{ $logs->links() }}</div>
        @endif
    </div>
</div>


<div class="sa-modal" id="logModal">
    <div class="sa-modal-box">
        <div class="sa-modal-header">
            <div>
                <h3><i class="fas fa-history"></i> <span id="l_title">Activity details</span></h3>
                <p id="l_time"></p>
            </div>
            <button type="button" class="sa-modal-close" onclick="closeModal('logModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="sa-modal-body">
            <div class="sa-detail-grid">
                <div><label>User</label><span id="l_user"></span></div>
                <div><label>Method / status</label><span id="l_method"></span></div>
                <div><label>Route</label><span id="l_route" class="sa-mono"></span></div>
                <div><label>IP address</label><span id="l_ip" class="sa-mono"></span></div>
            </div>
            <p class="sa-section-label">URL</p>
            <pre class="sa-json" id="l_url" style="margin-bottom:16px"></pre>
            <p class="sa-section-label">Browser</p>
            <pre class="sa-json" id="l_agent" style="margin-bottom:16px"></pre>
            <p class="sa-section-label">Submitted fields (names only) and route parameters</p>
            <pre class="sa-json" id="l_props"></pre>
        </div>
        <div class="sa-modal-footer">
            <button type="button" class="sa-btn sa-btn-cancel" onclick="closeModal('logModal')">Close</button>
        </div>
    </div>
</div>

@endsection

@push('styles')
    @include('partials.ui-styles')
    @include('super-admin.partials.report-styles')
@endpush

@push('scripts')
    @include('partials.ui-scripts')
    <script>
        function viewLog(btn) {
            const d = JSON.parse(btn.dataset.item);
            document.getElementById('l_title').textContent = d.description || 'Activity details';
            document.getElementById('l_time').textContent = d.time;
            document.getElementById('l_user').textContent = d.user;
            document.getElementById('l_method').textContent = d.method + (d.status ? ' · ' + d.status : '');
            document.getElementById('l_route').textContent = d.route || '-';
            document.getElementById('l_ip').textContent = d.ip || '-';
            document.getElementById('l_url').textContent = d.url || '-';
            document.getElementById('l_agent').textContent = d.agent || '-';
            document.getElementById('l_props').textContent = d.properties ? JSON.stringify(d.properties, null, 2) : 'Nothing recorded.';
            openModal('logModal');
        }
    </script>
@endpush