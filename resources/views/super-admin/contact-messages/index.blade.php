@extends('layouts.app')

@section('title', 'Contact Messages')

@section('content')

<div class="sa-page">

    <div class="sa-header">
        <div>
            <div class="sa-breadcrumb">
                <span>Dashboard</span><i class="fas fa-chevron-right"></i>
                <span>Communication & Support</span><i class="fas fa-chevron-right"></i>
                <strong>Contact Messages</strong>
            </div>
            <h1>Contact Messages</h1>
            <p>Messages users send from the Contact Support page.</p>
        </div>
    </div>

    @include('partials.alerts')

    <div class="sa-stats">
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-blue"><i class="fas fa-envelope"></i></div>
            <div><span>New</span><h3>{{ number_format($stats['new']) }}</h3></div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-orange"><i class="fas fa-envelope-open"></i></div>
            <div><span>Read</span><h3>{{ number_format($stats['read']) }}</h3></div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-green"><i class="fas fa-reply"></i></div>
            <div><span>Replied</span><h3>{{ number_format($stats['replied']) }}</h3></div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-purple"><i class="fas fa-inbox"></i></div>
            <div><span>Total messages</span><h3>{{ number_format($stats['total']) }}</h3></div>
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-header">
            <div><h3>Inbox</h3><p>Unread messages are marked with a blue edge.</p></div>
            <div class="sa-badge">{{ $messages->total() }} Records</div>
        </div>

        <form method="GET" action="{{ route('super-admin.contact-messages.index') }}" class="sa-filter-form">
            <div class="sa-search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search name, email, subject or message..." value="{{ request('search') }}">
            </div>
            <select name="status" class="sa-filter-select">
                <option value="">All status</option>
                @foreach(\App\Models\ContactMessage::STATUSES as $v => $l)<option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
            </select>
            <button type="submit" class="sa-btn sa-btn-filter"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('super-admin.contact-messages.index') }}" class="sa-btn sa-btn-reset"><i class="fas fa-redo"></i> Reset</a>
        </form>

        <div class="sa-table-wrapper">
            <table class="sa-table">
                <thead>
                    <tr>
                        <th>MESSAGE</th>
                        <th>FROM</th>
                        <th>PHONE</th>
                        <th>STATUS</th>
                        <th>RECEIVED</th>
                        <th class="text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $m)
                        <tr class="{{ $m->status === 'new' ? 'sa-unread' : '' }}">
                            <td>
                                <a href="{{ route('super-admin.contact-messages.show', $m) }}" class="sa-link">
                                    <div class="sa-name-cell">
                                        <div class="sa-name-icon"><i class="fas fa-envelope"></i></div>
                                        <div>
                                            <strong>{{ \Illuminate\Support\Str::limit($m->subject, 50) }}</strong>
                                            <small>{{ \Illuminate\Support\Str::limit($m->message, 70) }}</small>
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td><div class="sa-user-line"><strong>{{ $m->name }}</strong><small>{{ $m->email }}</small></div></td>
                            <td>{{ $m->phone ?: '—' }}</td>
                            <td><span class="sa-tk sa-tk-{{ $m->status }}">{{ \App\Models\ContactMessage::STATUSES[$m->status] ?? $m->status }}</span></td>
                            <td style="white-space:nowrap">
                                {{ $m->created_at->format('d M Y') }}
                                <small style="display:block;color:#a0a9a8;margin-top:4px">{{ $m->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="sa-actions">
                                    <a href="{{ route('super-admin.contact-messages.show', $m) }}" class="sa-action-btn" style="background:#edf4ff;color:#3478d4;text-decoration:none" title="Open"><i class="fas fa-eye"></i></a>
                                    <button type="button" class="sa-action-btn sa-action-delete" title="Delete" onclick="deleteMessage({{ $m->id }}, @js($m->subject))"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="sa-empty">
                                    <div><i class="fas fa-envelope-open"></i></div>
                                    <h4>No messages found</h4>
                                    <p>Messages sent from the user Contact Support page will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
            <div class="sa-pagination">{{ $messages->links() }}</div>
        @endif
    </div>
</div>

<div class="sa-modal" id="deleteMessageModal">
    <div class="sa-modal-box sa-delete-box">
        <div class="sa-delete-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Delete message?</h3>
        <p>Delete <strong id="deleteMessageName"></strong>? This cannot be undone.</p>
        <form id="deleteMessageForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="sa-modal-footer">
                <button type="button" class="sa-btn sa-btn-cancel" onclick="closeModal('deleteMessageModal')">Cancel</button>
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
        function deleteMessage(id, subject) {
            document.getElementById('deleteMessageForm').action = @js(url('super-admin/contact-messages')) + '/' + id;
            document.getElementById('deleteMessageName').textContent = subject;
            openModal('deleteMessageModal');
        }
    </script>
@endpush
