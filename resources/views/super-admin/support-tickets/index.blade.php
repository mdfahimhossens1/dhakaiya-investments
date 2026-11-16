@extends('layouts.app')

@section('title', 'Support Tickets')

@section('content')

<div class="sa-page">

    <div class="sa-header">
        <div>
            <div class="sa-breadcrumb">
                <span>Dashboard</span><i class="fas fa-chevron-right"></i>
                <span>Communication & Support</span><i class="fas fa-chevron-right"></i>
                <strong>Support Tickets</strong>
            </div>
            <h1>Support Tickets</h1>
            <p>Answer questions and problems that users send from their Support page.</p>
        </div>
    </div>

    @include('partials.alerts')

    <div class="sa-stats">
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-blue"><i class="fas fa-envelope-open-text"></i></div>
            <div><span>Open (needs reply)</span><h3>{{ number_format($stats['open']) }}</h3></div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-orange"><i class="fas fa-spinner"></i></div>
            <div><span>In progress</span><h3>{{ number_format($stats['in_progress']) }}</h3></div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-purple"><i class="fas fa-reply"></i></div>
            <div><span>Answered</span><h3>{{ number_format($stats['answered']) }}</h3></div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-green"><i class="fas fa-check-circle"></i></div>
            <div><span>Resolved / closed</span><h3>{{ number_format($stats['done']) }}</h3></div>
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-header">
            <div><h3>All tickets</h3><p>Most recently active first.</p></div>
            <div class="sa-badge">{{ $tickets->total() }} Records</div>
        </div>

        <form method="GET" action="{{ route('super-admin.support-tickets.index') }}" class="sa-filter-form">
            <div class="sa-search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search ticket no, subject or user..." value="{{ request('search') }}">
            </div>
            <select name="status" class="sa-filter-select">
                <option value="">All status</option>
                @foreach(\App\Models\SupportTicket::STATUSES as $v => $l)<option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
            </select>
            <select name="priority" class="sa-filter-select">
                <option value="">All priority</option>
                @foreach(\App\Models\SupportTicket::PRIORITIES as $v => $l)<option value="{{ $v }}" {{ request('priority') === $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
            </select>
            <select name="category" class="sa-filter-select">
                <option value="">All categories</option>
                @foreach(\App\Models\SupportTicket::CATEGORIES as $v => $l)<option value="{{ $v }}" {{ request('category') === $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
            </select>
            <button type="submit" class="sa-btn sa-btn-filter"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('super-admin.support-tickets.index') }}" class="sa-btn sa-btn-reset"><i class="fas fa-redo"></i> Reset</a>
        </form>

        <div class="sa-table-wrapper">
            <table class="sa-table">
                <thead>
                    <tr>
                        <th>TICKET</th>
                        <th>USER</th>
                        <th>CATEGORY</th>
                        <th>PRIORITY</th>
                        <th>STATUS</th>
                        <th>ASSIGNED</th>
                        <th>LAST ACTIVITY</th>
                        <th class="text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $t)
                        <tr class="{{ $t->status === 'open' ? 'sa-unread' : '' }}">
                            <td>
                                <a href="{{ route('super-admin.support-tickets.show', $t) }}" class="sa-link">
                                    <div class="sa-name-cell">
                                        <div class="sa-name-icon"><i class="fas fa-ticket-alt"></i></div>
                                        <div>
                                            <strong>{{ \Illuminate\Support\Str::limit($t->subject, 48) }}</strong>
                                            <small class="sa-mono">{{ $t->ticket_no }}</small>
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td>
                                @if($t->user)
                                    <div class="sa-user-line"><strong>{{ $t->user->name }}</strong><small>{{ $t->user->email }}</small></div>
                                @else
                                    <span style="color:#a3abad">Deleted user</span>
                                @endif
                            </td>
                            <td><span class="sa-tag">{{ \App\Models\SupportTicket::CATEGORIES[$t->category] ?? ucfirst($t->category) }}</span></td>
                            <td><span class="sa-pr sa-pr-{{ $t->priority }}">{{ \App\Models\SupportTicket::PRIORITIES[$t->priority] ?? $t->priority }}</span></td>
                            <td><span class="sa-tk sa-tk-{{ $t->status }}">{{ \App\Models\SupportTicket::STATUSES[$t->status] ?? $t->status }}</span></td>
                            <td>{{ $t->assignee?->name ?? '—' }}</td>
                            <td style="white-space:nowrap">
                                {{ ($t->last_reply_at ?? $t->created_at)->diffForHumans() }}
                                <small style="display:block;color:#a0a9a8;margin-top:4px">Opened {{ $t->created_at->format('d M Y') }}</small>
                            </td>
                            <td>
                                <div class="sa-actions">
                                    <a href="{{ route('super-admin.support-tickets.show', $t) }}" class="sa-action-btn" style="background:#edf4ff;color:#3478d4;text-decoration:none" title="Open"><i class="fas fa-eye"></i></a>
                                    <button type="button" class="sa-action-btn sa-action-delete" title="Delete" onclick="deleteTicket({{ $t->id }}, @js($t->ticket_no))"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="sa-empty">
                                    <div><i class="fas fa-ticket-alt"></i></div>
                                    <h4>No tickets found</h4>
                                    <p>Tickets submitted by users will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="sa-pagination">{{ $tickets->links() }}</div>
        @endif
    </div>
</div>

<div class="sa-modal" id="deleteTicketModal">
    <div class="sa-modal-box sa-delete-box">
        <div class="sa-delete-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Delete ticket?</h3>
        <p>Delete <strong id="deleteTicketName"></strong> and its whole conversation? This cannot be undone.</p>
        <form id="deleteTicketForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="sa-modal-footer">
                <button type="button" class="sa-btn sa-btn-cancel" onclick="closeModal('deleteTicketModal')">Cancel</button>
                <button type="submit" class="sa-btn sa-btn-danger"><i class="fas fa-trash"></i> Yes, delete</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
    @include('partials.ui-styles')
    @include('super-admin.partials.report-styles')
    @include('super-admin.partials.support-styles')
@endpush

@push('scripts')
    @include('partials.ui-scripts')
    <script>
        function deleteTicket(id, no) {
            document.getElementById('deleteTicketForm').action = @js(url('super-admin/support-tickets')) + '/' + id;
            document.getElementById('deleteTicketName').textContent = no;
            openModal('deleteTicketModal');
        }
    </script>
@endpush
