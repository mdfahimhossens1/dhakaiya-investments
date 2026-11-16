@extends('layouts.app')

@section('title', 'Contact Message')

@section('content')

<div class="sa-page">

    <div class="sa-header">
        <div>
            <div class="sa-breadcrumb">
                <span>Dashboard</span><i class="fas fa-chevron-right"></i>
                <a href="{{ route('super-admin.contact-messages.index') }}" class="sa-link">Contact Messages</a><i class="fas fa-chevron-right"></i>
                <strong>#{{ $contact->id }}</strong>
            </div>
            <h1>{{ $contact->subject }}</h1>
            <p><span class="sa-tk sa-tk-{{ $contact->status }}">{{ \App\Models\ContactMessage::STATUSES[$contact->status] ?? $contact->status }}</span></p>
        </div>
        <a href="{{ route('super-admin.contact-messages.index') }}" class="sa-btn sa-btn-cancel"><i class="fas fa-arrow-left"></i> Back to inbox</a>
    </div>

    @include('partials.alerts')

    <div class="sa-split">

        <div class="sa-card">
            <div class="sa-card-header">
                <div><h3>Message</h3><p>Received {{ $contact->created_at->format('d M Y, h:i A') }}</p></div>
            </div>
            <div class="sa-card-body">
                <div class="sa-quote">{{ $contact->message }}</div>

                @if($contact->admin_reply)
                    <p class="sa-section-label" style="margin:22px 0 8px">
                        Your reply{{ $contact->repliedBy ? ' by ' . $contact->repliedBy->name : '' }}
                        @if($contact->replied_at) · {{ $contact->replied_at->format('d M Y, h:i A') }}@endif
                    </p>
                    <div class="sa-quote" style="border-left-color:#3478d4">{{ $contact->admin_reply }}</div>
                @endif
            </div>

            <form method="POST" action="{{ route('super-admin.contact-messages.reply', $contact) }}" class="sa-reply-box">
                @csrf
                <textarea name="admin_reply" placeholder="{{ $contact->admin_reply ? 'Send another reply...' : 'Write your reply...' }}" required>{{ old('admin_reply') }}</textarea>
                <div class="sa-reply-actions">
                    <small style="color:#98a39f;margin-right:auto">
                        {{ $contact->user_id ? 'The user gets an in-app notification and sees the reply on their Contact page.' : 'This sender has no account, so the reply is saved only.' }}
                    </small>
                    <button type="submit" class="sa-btn sa-btn-primary"><i class="fas fa-paper-plane"></i> {{ $contact->admin_reply ? 'Send again' : 'Send reply' }}</button>
                </div>
            </form>
        </div>

        <div>
            <div class="sa-card">
                <div class="sa-card-header"><div><h3>Sender</h3></div></div>
                <div class="sa-card-body">
                    <ul class="sa-side-list">
                        <li><span>Name</span><span>{{ $contact->name }}</span></li>
                        <li><span>Email</span><span>{{ $contact->email }}</span></li>
                        <li><span>Phone</span><span>{{ $contact->phone ?: '—' }}</span></li>
                        <li><span>Account</span><span>{{ $contact->user ? ucwords(str_replace('_', ' ', $contact->user->role)) : 'No account' }}</span></li>
                        <li><span>IP address</span><span class="sa-mono">{{ $contact->ip_address ?: '—' }}</span></li>
                        @if($contact->read_at)<li><span>First read</span><span>{{ $contact->read_at->format('d M Y, h:i A') }}</span></li>@endif
                    </ul>
                </div>
            </div>

            <div class="sa-card">
                <div class="sa-card-header"><div><h3>Manage</h3></div></div>
                <form method="POST" action="{{ route('super-admin.contact-messages.update', $contact) }}">
                    @csrf
                    @method('PUT')
                    <div class="sa-card-body">
                        <div class="sa-form-group" style="margin-bottom:0">
                            <label>Status</label>
                            <select name="status">
                                @foreach(\App\Models\ContactMessage::STATUSES as $v => $l)<option value="{{ $v }}" {{ $contact->status === $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="sa-save-bar">
                        <button type="button" class="sa-btn sa-btn-danger" onclick="openModal('deleteMessageModal')"><i class="fas fa-trash"></i> Delete</button>
                        <button type="submit" class="sa-btn sa-btn-primary"><i class="fas fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="sa-modal" id="deleteMessageModal">
    <div class="sa-modal-box sa-delete-box">
        <div class="sa-delete-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Delete message?</h3>
        <p>Delete <strong>{{ \Illuminate\Support\Str::limit($contact->subject, 40) }}</strong>? This cannot be undone.</p>
        <form method="POST" action="{{ route('super-admin.contact-messages.destroy', $contact) }}">
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
@endpush
