@extends('layouts.app')

@section('title', $ticket->ticket_no)

@section('content')

<div class="sa-page">

    <div class="sa-header">
        <div>
            <div class="sa-breadcrumb">
                <span>Dashboard</span><i class="fas fa-chevron-right"></i>
                <a href="{{ route('super-admin.support-tickets.index') }}" class="sa-link">Support Tickets</a><i class="fas fa-chevron-right"></i>
                <strong>{{ $ticket->ticket_no }}</strong>
            </div>
            <h1>{{ $ticket->subject }}</h1>
            <p>
                <span class="sa-tk sa-tk-{{ $ticket->status }}">{{ \App\Models\SupportTicket::STATUSES[$ticket->status] ?? $ticket->status }}</span>
                &nbsp;<span class="sa-pr sa-pr-{{ $ticket->priority }}">{{ \App\Models\SupportTicket::PRIORITIES[$ticket->priority] ?? $ticket->priority }}</span>
            </p>
        </div>
        <a href="{{ route('super-admin.support-tickets.index') }}" class="sa-btn sa-btn-cancel"><i class="fas fa-arrow-left"></i> Back to tickets</a>
    </div>

    @include('partials.alerts')

    <div class="sa-split">

        {{-- Conversation --}}
        <div class="sa-card">
            <div class="sa-card-header">
                <div><h3>Conversation</h3><p>{{ $ticket->replies->count() }} {{ \Illuminate\Support\Str::plural('message', $ticket->replies->count()) }}</p></div>
            </div>

            <div class="sa-card-body">
                <div class="sa-thread">
                    @foreach($ticket->replies as $reply)
                        <div class="sa-msg {{ $reply->is_admin ? 'is-admin' : '' }}">
                            <div class="sa-msg-head">
                                <strong>{{ $reply->user?->name ?? 'Deleted user' }}</strong>
                                @if($reply->is_admin)<span class="sa-msg-role">Support</span>@endif
                                <span>{{ $reply->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="sa-msg-body">{{ $reply->message }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <form method="POST" action="{{ route('super-admin.support-tickets.reply', $ticket) }}" class="sa-reply-box">
                @csrf
                <textarea name="message" placeholder="Write your reply to {{ $ticket->user?->name ?? 'the user' }}..." required>{{ old('message') }}</textarea>
                <div class="sa-reply-actions">
                    <label>After sending, set status to
                        <select name="status">
                            @foreach(\App\Models\SupportTicket::STATUSES as $v => $l)
                                <option value="{{ $v }}" {{ $v === 'answered' ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit" class="sa-btn sa-btn-primary"><i class="fas fa-paper-plane"></i> Send reply</button>
                </div>
            </form>
        </div>

        {{-- Sidebar --}}
        <div>
            <div class="sa-card">
                <div class="sa-card-header"><div><h3>Ticket details</h3></div></div>
                <div class="sa-card-body">
                    <ul class="sa-side-list">
                        <li><span>User</span><span>{{ $ticket->user?->name ?? 'Deleted user' }}</span></li>
                        <li><span>Email</span><span>{{ $ticket->user?->email ?? '-' }}</span></li>
                        <li><span>Category</span><span>{{ \App\Models\SupportTicket::CATEGORIES[$ticket->category] ?? $ticket->category }}</span></li>
                        <li><span>Opened</span><span>{{ $ticket->created_at->format('d M Y, h:i A') }}</span></li>
                        <li><span>Last activity</span><span>{{ ($ticket->last_reply_at ?? $ticket->created_at)->diffForHumans() }}</span></li>
                        @if($ticket->closed_at)<li><span>Closed</span><span>{{ $ticket->closed_at->format('d M Y') }}</span></li>@endif
                    </ul>
                </div>
            </div>

            <div class="sa-card">
                <div class="sa-card-header"><div><h3>Manage</h3></div></div>
                <form method="POST" action="{{ route('super-admin.support-tickets.update', $ticket) }}">
                    @csrf
                    @method('PUT')
                    <div class="sa-card-body">
                        <div class="sa-form-group">
                            <label>Status</label>
                            <select name="status">
                                @foreach(\App\Models\SupportTicket::STATUSES as $v => $l)<option value="{{ $v }}" {{ $ticket->status === $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
                            </select>
                        </div>
                        <div class="sa-form-group">
                            <label>Priority</label>
                            <select name="priority">
                                @foreach(\App\Models\SupportTicket::PRIORITIES as $v => $l)<option value="{{ $v }}" {{ $ticket->priority === $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
                            </select>
                        </div>
                        <div class="sa-form-group" style="margin-bottom:0">
                            <label>Assigned to</label>
                            <select name="assigned_to">
                                <option value="">Unassigned</option>
                                @foreach($admins as $a)<option value="{{ $a->id }}" {{ $ticket->assigned_to === $a->id ? 'selected' : '' }}>{{ $a->name }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="sa-save-bar">
                        <button type="button" class="sa-btn sa-btn-danger" onclick="openModal('deleteTicketModal')"><i class="fas fa-trash"></i> Delete</button>
                        <button type="submit" class="sa-btn sa-btn-primary"><i class="fas fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="sa-modal" id="deleteTicketModal">
    <div class="sa-modal-box sa-delete-box">
        <div class="sa-delete-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Delete ticket?</h3>
        <p>Delete <strong>{{ $ticket->ticket_no }}</strong> and its whole conversation? This cannot be undone.</p>
        <form method="POST" action="{{ route('super-admin.support-tickets.destroy', $ticket) }}">
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
@endpush
