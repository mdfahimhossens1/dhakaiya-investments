@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

<div class="sa-page">

    <div class="sa-header">
        <div>
            <div class="sa-breadcrumb">
                <span>Dashboard</span><i class="fas fa-chevron-right"></i>
                <span>Communication & Support</span><i class="fas fa-chevron-right"></i>
                <strong>Notifications</strong>
            </div>
            <h1>Notifications</h1>
            <p>Send in-app notifications to your users and review what was sent.</p>
        </div>

        <button type="button" class="sa-btn sa-btn-primary" onclick="openSendModal()">
            <i class="fas fa-paper-plane"></i> Send Notification
        </button>
    </div>

    @include('partials.alerts')

    @unless($inboxReady)
        <div class="sa-alert sa-alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                The <strong>notifications</strong> table does not exist yet. Run
                <code>php artisan make:notifications-table</code> then <code>php artisan migrate</code> before sending.
            </div>
        </div>
    @endunless

    <div class="sa-stats">
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-blue"><i class="fas fa-bullhorn"></i></div>
            <div><span>Broadcasts sent</span><h3>{{ number_format($stats['broadcasts']) }}</h3></div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-green"><i class="fas fa-inbox"></i></div>
            <div><span>Total deliveries</span><h3>{{ number_format($stats['deliveries']) }}</h3></div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-orange"><i class="fas fa-calendar-day"></i></div>
            <div><span>Sent today</span><h3>{{ number_format($stats['today']) }}</h3></div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon sa-ic-purple"><i class="fas fa-clock"></i></div>
            <div>
                <span>Last sent</span>
                <h3 style="font-size:15px">{{ $stats['last'] ? $stats['last']->diffForHumans() : 'Never' }}</h3>
            </div>
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-header">
            <div><h3>Sent notifications</h3><p>Newest first.</p></div>
            <div class="sa-badge">{{ $broadcasts->total() }} Records</div>
        </div>

        <div class="sa-table-wrapper">
            <table class="sa-table">
                <thead>
                    <tr>
                        <th>NOTIFICATION</th>
                        <th>TYPE</th>
                        <th>AUDIENCE</th>
                        <th>RECIPIENTS</th>
                        <th>SENT BY</th>
                        <th>SENT AT</th>
                        <th class="text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($broadcasts as $b)
                        <tr>
                            <td>
                                <div class="sa-name-cell">
                                    <div class="sa-name-icon"><i class="fas fa-bell"></i></div>
                                    <div>
                                        <strong>{{ $b->title }}</strong>
                                        <small>{{ \Illuminate\Support\Str::limit($b->message, 70) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="sa-nt sa-nt-{{ $b->type }}">{{ \App\Models\NotificationBroadcast::TYPES[$b->type] ?? ucfirst($b->type) }}</span></td>
                            <td>
                                {{ \App\Models\NotificationBroadcast::AUDIENCES[$b->audience] ?? $b->audience }}
                                @if($b->audience === 'specific')
                                    <small style="display:block;color:#9aa4a8;margin-top:3px">{{ $b->target?->email ?? 'Deleted user' }}</small>
                                @endif
                            </td>
                            <td><span class="sa-order">{{ number_format($b->recipients_count) }}</span></td>
                            <td>{{ $b->sender?->name ?? '-' }}</td>
                            <td style="white-space:nowrap">
                                {{ $b->created_at->format('d M Y') }}
                                <small style="display:block;color:#a0a9a8;margin-top:4px">{{ $b->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="sa-actions">
                                    <button type="button" class="sa-action-btn" style="background:#edf4ff;color:#3478d4" title="View"
                                        data-item="{{ json_encode([
                                            'title' => $b->title,
                                            'message' => $b->message,
                                            'type' => \App\Models\NotificationBroadcast::TYPES[$b->type] ?? $b->type,
                                            'audience' => (\App\Models\NotificationBroadcast::AUDIENCES[$b->audience] ?? $b->audience) . ($b->audience === 'specific' ? ' (' . ($b->target?->email ?? 'deleted user') . ')' : ''),
                                            'recipients' => $b->recipients_count,
                                            'sender' => $b->sender?->name ?? '-',
                                            'url' => $b->action_url,
                                            'time' => $b->created_at->format('d M Y, h:i A'),
                                        ]) }}"
                                        onclick="viewBroadcast(this)"><i class="fas fa-eye"></i></button>
                                    <button type="button" class="sa-action-btn sa-action-delete" title="Delete"
                                        onclick="deleteBroadcast({{ $b->id }}, @js($b->title))"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="sa-empty">
                                    <div><i class="fas fa-bell"></i></div>
                                    <h4>Nothing sent yet</h4>
                                    <p>Send your first notification to keep users informed.</p>
                                    <button type="button" class="sa-btn sa-btn-primary" onclick="openSendModal()"><i class="fas fa-paper-plane"></i> Send Notification</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($broadcasts->hasPages())
            <div class="sa-pagination">{{ $broadcasts->links() }}</div>
        @endif
    </div>
</div>


{{-- Send modal --}}
<div class="sa-modal" id="sendModal">
    <div class="sa-modal-box">
        <div class="sa-modal-header">
            <div>
                <h3><i class="fas fa-paper-plane"></i> Send Notification</h3>
                <p>Appears in the user's System Notifications page.</p>
            </div>
            <button type="button" class="sa-modal-close" onclick="closeModal('sendModal')"><i class="fas fa-times"></i></button>
        </div>

        <form id="sendForm" method="POST" action="{{ route('super-admin.notifications.store') }}">
            @csrf
            <div class="sa-modal-body">
                <div class="sa-form-group">
                    <label>Title <span>*</span></label>
                    <input type="text" name="title" maxlength="120" value="{{ old('title') }}" placeholder="e.g. New share product is live" required>
                </div>

                <div class="sa-form-group">
                    <label>Message <span>*</span></label>
                    <textarea name="message" rows="4" maxlength="1000" placeholder="Write the notification text..." required>{{ old('message') }}</textarea>
                </div>

                <div class="sa-form-row">
                    <div class="sa-form-group">
                        <label>Type <span>*</span></label>
                        <select name="type" required>
                            @foreach(\App\Models\NotificationBroadcast::TYPES as $v => $l)
                                <option value="{{ $v }}" {{ old('type', 'info') === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sa-form-group">
                        <label>Send to <span>*</span></label>
                        <select name="audience" id="n_audience" required>
                            @foreach(\App\Models\NotificationBroadcast::AUDIENCES as $v => $l)
                                <option value="{{ $v }}" data-count="{{ $audienceCounts[$v] ?? 1 }}" {{ old('audience', 'all_users') === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sa-form-group" id="n_email_wrap" style="display:none">
                    <label>User email <span>*</span></label>
                    <input type="text" name="target_email" id="n_email" value="{{ old('target_email') }}" placeholder="user@example.com">
                </div>

                <div class="sa-form-group" style="margin-bottom:6px">
                    <label>Link (optional)</label>
                    <input type="text" name="action_url" value="{{ old('action_url') }}" placeholder="/user/available-projects">
                    <small>Starts with / for a page on this site, or a full https:// address.</small>
                </div>

                <div class="sa-total" id="n_reach" style="margin-top:6px"></div>
            </div>

            <div class="sa-modal-footer">
                <button type="button" class="sa-btn sa-btn-cancel" onclick="closeModal('sendModal')">Cancel</button>
                <button type="submit" class="sa-btn sa-btn-primary"><i class="fas fa-paper-plane"></i> Send now</button>
            </div>
        </form>
    </div>
</div>

{{-- View modal --}}
<div class="sa-modal" id="viewBroadcastModal">
    <div class="sa-modal-box">
        <div class="sa-modal-header">
            <div><h3><i class="fas fa-bell"></i> <span id="v_title"></span></h3><p id="v_time"></p></div>
            <button type="button" class="sa-modal-close" onclick="closeModal('viewBroadcastModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="sa-modal-body">
            <p class="sa-section-label">Message</p>
            <div class="sa-quote" id="v_message" style="margin-bottom:16px"></div>
            <div class="sa-detail-grid">
                <div><label>Type</label><span id="v_type"></span></div>
                <div><label>Audience</label><span id="v_audience"></span></div>
                <div><label>Recipients</label><span id="v_recipients"></span></div>
                <div><label>Sent by</label><span id="v_sender"></span></div>
            </div>
            <div class="sa-detail-grid" style="grid-template-columns:1fr"><div><label>Link</label><span id="v_url" class="sa-mono"></span></div></div>
        </div>
        <div class="sa-modal-footer"><button type="button" class="sa-btn sa-btn-cancel" onclick="closeModal('viewBroadcastModal')">Close</button></div>
    </div>
</div>

{{-- Delete modal --}}
<div class="sa-modal" id="deleteBroadcastModal">
    <div class="sa-modal-box sa-delete-box">
        <div class="sa-delete-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Remove from history?</h3>
        <p>Remove <strong id="deleteBroadcastName"></strong> from this list? Users who already received it will still see it.</p>
        <form id="deleteBroadcastForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="sa-modal-footer">
                <button type="button" class="sa-btn sa-btn-cancel" onclick="closeModal('deleteBroadcastModal')">Cancel</button>
                <button type="submit" class="sa-btn sa-btn-danger"><i class="fas fa-trash"></i> Yes, remove</button>
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
        const nBase = @js(url('super-admin/notifications'));
        const nAudience = document.getElementById('n_audience');

        function refreshReach() {
            const opt = nAudience.options[nAudience.selectedIndex];
            const specific = nAudience.value === 'specific';
            document.getElementById('n_email_wrap').style.display = specific ? 'block' : 'none';
            document.getElementById('n_email').required = specific;
            const n = parseInt(opt.dataset.count || '0', 10);
            document.getElementById('n_reach').textContent = specific ? 'This will reach 1 user' : 'This will reach ' + n.toLocaleString() + (n === 1 ? ' user' : ' users');
        }
        nAudience.addEventListener('change', refreshReach);

        function openSendModal() { refreshReach(); openModal('sendModal'); }

        document.getElementById('sendForm').addEventListener('submit', function (e) {
            if (nAudience.value !== 'specific') {
                const n = nAudience.options[nAudience.selectedIndex].dataset.count;
                if (!confirm('Send this notification to ' + Number(n).toLocaleString() + ' users? This cannot be undone.')) e.preventDefault();
            }
        });

        function viewBroadcast(btn) {
            const d = JSON.parse(btn.dataset.item);
            document.getElementById('v_title').textContent = d.title;
            document.getElementById('v_time').textContent = d.time;
            document.getElementById('v_message').textContent = d.message;
            document.getElementById('v_type').textContent = d.type;
            document.getElementById('v_audience').textContent = d.audience;
            document.getElementById('v_recipients').textContent = d.recipients;
            document.getElementById('v_sender').textContent = d.sender;
            document.getElementById('v_url').textContent = d.url || 'None';
            openModal('viewBroadcastModal');
        }

        function deleteBroadcast(id, title) {
            document.getElementById('deleteBroadcastForm').action = nBase + '/' + id;
            document.getElementById('deleteBroadcastName').textContent = title;
            openModal('deleteBroadcastModal');
        }

        @if($errors->any() && old('title') !== null)
            openSendModal();
        @endif
    </script>
@endpush
