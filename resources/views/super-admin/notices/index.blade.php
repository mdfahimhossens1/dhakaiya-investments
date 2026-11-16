@extends('layouts.app')

@section('title', 'Notice Board')

@section('content')

<div class="notice-page">

    {{-- Page Header --}}
    <div class="notice-header">
        <div>
            <div class="notice-breadcrumb">
                <span>Website Content</span>
                <i class="fa-solid fa-chevron-right"></i>
                <span class="active">Notice Board</span>
            </div>

            <h1>Notice Board</h1>
            <p>Create and manage website notices.</p>
        </div>

        <button type="button" class="notice-btn-primary" id="openCreateNotice">
            <i class="fa-solid fa-plus"></i>
            Create Notice
        </button>
    </div>

    {{-- Statistics --}}
    <div class="notice-stats">

        <div class="notice-stat-card">
            <div class="notice-stat-icon total">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
            <div>
                <span>Total Notices</span>
                <h3>{{ $totalNotices }}</h3>
            </div>
        </div>

        <div class="notice-stat-card">
            <div class="notice-stat-icon published">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span>Published</span>
                <h3>{{ $publishedNotices }}</h3>
            </div>
        </div>

        <div class="notice-stat-card">
            <div class="notice-stat-icon draft">
                <i class="fa-solid fa-file-pen"></i>
            </div>
            <div>
                <span>Drafts</span>
                <h3>{{ $draftNotices }}</h3>
            </div>
        </div>

        <div class="notice-stat-card">
            <div class="notice-stat-icon pinned">
                <i class="fa-solid fa-thumbtack"></i>
            </div>
            <div>
                <span>Pinned Notices</span>
                <h3>{{ $pinnedNotices }}</h3>
            </div>
        </div>

    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="notice-alert success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="notice-alert-close">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="notice-alert error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="notice-alert-close">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="notice-alert error">
            <div>
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <button type="button" class="notice-alert-close">&times;</button>
        </div>
    @endif

    {{-- Main Card --}}
    <div class="notice-card">

        <div class="notice-card-header">
            <div>
                <h2>All Notices</h2>
                <p>Manage your website notice board.</p>
            </div>

            <span class="notice-record-count">
                {{ $notices->total() }} Records
            </span>
        </div>

        {{-- Filters --}}
        <form action="{{ route('super-admin.notices.index') }}"
              method="GET"
              class="notice-filter-form">

            <div class="notice-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search notices..."
                >
            </div>

            <select name="status">
                <option value="">All Status</option>
                <option value="published" @selected(request('status') === 'published')>
                    Published
                </option>
                <option value="draft" @selected(request('status') === 'draft')>
                    Draft
                </option>
            </select>

            <select name="priority">
                <option value="">All Priorities</option>
                <option value="normal" @selected(request('priority') === 'normal')>
                    Normal
                </option>
                <option value="important" @selected(request('priority') === 'important')>
                    Important
                </option>
                <option value="urgent" @selected(request('priority') === 'urgent')>
                    Urgent
                </option>
            </select>

            <button type="submit" class="notice-filter-btn">
                <i class="fa-solid fa-filter"></i>
                Filter
            </button>

            <a href="{{ route('super-admin.notices.index') }}"
               class="notice-reset-btn">
                Reset
            </a>

        </form>

        {{-- Notice Table --}}
        <div class="notice-table-wrapper">

            <table class="notice-table">

                <thead>
                    <tr>
                        <th>Notice Information</th>
                        <th>Priority</th>
                        <th>Publish Date</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th>Pinned</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($notices as $notice)

                        <tr>

                            <td>
                                <div class="notice-info">

                                    <strong>{{ $notice->title }}</strong>

                                    <small>
                                        {{ $notice->slug }}
                                    </small>

                                    @if($notice->content)
                                        <p>
                                            {{ \Illuminate\Support\Str::limit(strip_tags($notice->content), 65) }}
                                        </p>
                                    @endif

                                </div>
                            </td>

                            {{-- Priority --}}
                            <td>
                                <span class="notice-priority {{ $notice->priority }}">
                                    {{ ucfirst($notice->priority) }}
                                </span>
                            </td>

                            {{-- Publish Date --}}
                            <td>
                                <span class="notice-date">
                                    {{ $notice->publish_date?->format('d M Y') ?? 'Not scheduled' }}
                                </span>
                            </td>

                            {{-- Expiry Date --}}
                            <td>
                                <span class="notice-date">
                                    {{ $notice->expiry_date?->format('d M Y') ?? 'No expiry' }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($notice->status === 'published')
                                    <span class="notice-status published">
                                        <span></span>
                                        Published
                                    </span>
                                @else
                                    <span class="notice-status draft">
                                        <span></span>
                                        Draft
                                    </span>
                                @endif
                            </td>

                            {{-- Pinned --}}
                            <td>
                                @if($notice->is_pinned)
                                    <span class="notice-pinned">
                                        <i class="fa-solid fa-thumbtack"></i>
                                        Pinned
                                    </span>
                                @else
                                    <span class="notice-not-pinned">No</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="notice-actions">

                                    <button
                                        type="button"
                                        class="notice-action view"
                                        title="View"
                                        data-view-url="{{ route('super-admin.notices.show', $notice->id) }}"
                                    >
                                        <i class="fa-regular fa-eye"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="notice-action edit"
                                        title="Edit"

                                        data-id="{{ $notice->id }}"
                                        data-title="{{ $notice->title }}"
                                        data-content="{{ $notice->content }}"
                                        data-priority="{{ $notice->priority }}"
                                        data-status="{{ $notice->status }}"
                                        data-pinned="{{ $notice->is_pinned ? '1' : '0' }}"
                                        data-publish-date="{{ $notice->publish_date?->format('Y-m-d') }}"
                                        data-expiry-date="{{ $notice->expiry_date?->format('Y-m-d') }}"
                                    >
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="notice-action delete"
                                        title="Delete"
                                        data-id="{{ $notice->id }}"
                                        data-title="{{ $notice->title }}"
                                    >
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">

                                <div class="notice-empty-state">

                                    <div class="notice-empty-icon">
                                        <i class="fa-regular fa-clipboard"></i>
                                    </div>

                                    <h3>No Notices Found</h3>

                                    <p>
                                        No notices are available.
                                        Create your first notice to get started.
                                    </p>

                                    <button
                                        type="button"
                                        class="notice-btn-primary"
                                        id="emptyCreateNotice"
                                    >
                                        <i class="fa-solid fa-plus"></i>
                                        Create Notice
                                    </button>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if($notices->hasPages())
            <div class="notice-pagination">
                {{ $notices->links() }}
            </div>
        @endif

    </div>

</div>


{{-- CREATE / EDIT MODAL --}}
<div class="notice-modal-overlay" id="noticeFormModal">

    <div class="notice-modal">

        <div class="notice-modal-header">
            <div>
                <h2 id="noticeModalTitle">Create Notice</h2>
                <p id="noticeModalSubtitle">Add a new website notice.</p>
            </div>

            <button type="button" class="notice-modal-close" data-close-notice>
                &times;
            </button>
        </div>

        <form
            id="noticeForm"
            action="{{ route('super-admin.notices.store') }}"
            method="POST"
        >

            @csrf

            <div id="noticeMethodField"></div>

            <div class="notice-modal-body">

                <div class="notice-form-grid">

                    <div class="notice-form-group full">
                        <label>Notice Title <span>*</span></label>

                        <input
                            type="text"
                            name="title"
                            id="noticeTitle"
                            placeholder="Enter notice title"
                            maxlength="255"
                            required
                        >
                    </div>

                    <div class="notice-form-group full">
                        <label>Notice Content <span>*</span></label>

                        <textarea
                            name="content"
                            id="noticeContent"
                            rows="6"
                            placeholder="Write notice details..."
                            required
                        ></textarea>
                    </div>

                    <div class="notice-form-group">
                        <label>Priority <span>*</span></label>

                        <select name="priority" id="noticePriority" required>
                            <option value="normal">Normal</option>
                            <option value="important">Important</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>

                    <div class="notice-form-group">
                        <label>Status <span>*</span></label>

                        <select name="status" id="noticeStatus" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                    <div class="notice-form-group">
                        <label>Publish Date</label>

                        <input
                            type="date"
                            name="publish_date"
                            id="noticePublishDate"
                        >
                    </div>

                    <div class="notice-form-group">
                        <label>Expiry Date</label>

                        <input
                            type="date"
                            name="expiry_date"
                            id="noticeExpiryDate"
                        >
                    </div>

                    <div class="notice-form-group full">
                        <label class="notice-checkbox-label">
                            <input
                                type="checkbox"
                                name="is_pinned"
                                id="noticePinned"
                                value="1"
                            >

                            <span>
                                <strong>Pin this notice</strong>
                                <small>
                                    Pinned notices will appear before other notices.
                                </small>
                            </span>
                        </label>
                    </div>

                </div>

            </div>

            <div class="notice-modal-footer">

                <button type="button" class="notice-btn-secondary" data-close-notice>
                    Cancel
                </button>

                <button type="submit" class="notice-btn-primary" id="noticeSubmitButton">
                    <i class="fa-solid fa-check"></i>
                    <span>Save Notice</span>
                </button>

            </div>

        </form>

    </div>

</div>


{{-- VIEW MODAL --}}
<div class="notice-modal-overlay" id="noticeViewModal">

    <div class="notice-modal">

        <div class="notice-modal-header">
            <div>
                <h2>Notice Details</h2>
                <p>Complete notice information.</p>
            </div>

            <button type="button" class="notice-modal-close" data-close-notice>
                &times;
            </button>
        </div>

        <div class="notice-modal-body">

            <div class="notice-view-content">

                <h2 id="viewNoticeTitle"></h2>

                <div class="notice-view-meta">
                    <span id="viewNoticePriority"></span>
                    <span id="viewNoticeStatus"></span>
                </div>

                <div class="notice-view-description" id="viewNoticeContent"></div>

                <div class="notice-view-grid">

                    <div>
                        <span>Publish Date</span>
                        <strong id="viewNoticePublishDate">-</strong>
                    </div>

                    <div>
                        <span>Expiry Date</span>
                        <strong id="viewNoticeExpiryDate">-</strong>
                    </div>

                    <div>
                        <span>Pinned</span>
                        <strong id="viewNoticePinned">-</strong>
                    </div>

                    <div>
                        <span>Created At</span>
                        <strong id="viewNoticeCreatedAt">-</strong>
                    </div>

                </div>

            </div>

        </div>

        <div class="notice-modal-footer">
            <button type="button" class="notice-btn-secondary" data-close-notice>
                Close
            </button>
        </div>

    </div>

</div>


{{-- DELETE MODAL --}}
<div class="notice-modal-overlay" id="noticeDeleteModal">

    <div class="notice-modal notice-delete-modal">

        <div class="notice-delete-icon">
            <i class="fa-regular fa-trash-can"></i>
        </div>

        <h2>Delete Notice?</h2>

        <p>
            Are you sure you want to delete
            <strong id="deleteNoticeTitle"></strong>?
        </p>

        <form id="noticeDeleteForm" method="POST">

            @csrf
            @method('DELETE')

            <div class="notice-modal-footer">

                <button type="button" class="notice-btn-secondary" data-close-notice>
                    Cancel
                </button>

                <button type="submit" class="notice-btn-danger">
                    <i class="fa-solid fa-trash"></i>
                    Delete Notice
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('styles')
<style>

.notice-page {
    width: 100%;
    font-family: 'Inter', sans-serif;
    color: #1e293b;
}

/* Header */

.notice-page .notice-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.notice-page .notice-breadcrumb {
    display: flex;
    align-items: center;
    gap: 9px;
    color: #94a3b8;
    font-size: 12px;
    margin-bottom: 9px;
}

.notice-page .notice-breadcrumb i {
    font-size: 9px;
}

.notice-page .notice-breadcrumb .active {
    color: #087f5b;
    font-weight: 700;
}

.notice-page .notice-header h1 {
    font-size: 25px;
    font-weight: 800;
    margin: 0 0 6px;
    color: #172b25;
}

.notice-page .notice-header p {
    color: #84919f;
    font-size: 13px;
    margin: 0;
}

/* Buttons */

.notice-page .notice-btn-primary,
.notice-page .notice-btn-secondary,
.notice-page .notice-btn-danger,
.notice-page .notice-filter-btn,
.notice-page .notice-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 15px;
    border: 0;
    border-radius: 8px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: .2s;
}

.notice-page .notice-btn-primary {
    background: #087f5b;
    color: #fff;
}

.notice-page .notice-btn-primary:hover {
    background: #066b4c;
    color: #fff;
}

.notice-page .notice-btn-secondary {
    background: #f1f5f9;
    color: #475569;
}

.notice-page .notice-btn-danger {
    background: #dc3545;
    color: #fff;
}

.notice-page .notice-filter-btn {
    background: #087f5b;
    color: #fff;
}

.notice-page .notice-reset-btn {
    background: #f1f5f9;
    color: #64748b;
}

/* Statistics */

.notice-page .notice-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 23px;
}

.notice-page .notice-stat-card {
    background: #fff;
    border: 1px solid #e8edf0;
    border-radius: 12px;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 13px;
}

.notice-page .notice-stat-icon {
    width: 45px;
    height: 45px;
    border-radius: 11px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 18px;
    flex-shrink: 0;
}

.notice-page .notice-stat-icon.total {
    color: #2563eb;
    background: #eff6ff;
}

.notice-page .notice-stat-icon.published {
    color: #087f5b;
    background: #eaf8f0;
}

.notice-page .notice-stat-icon.draft {
    color: #d97706;
    background: #fff7ed;
}

.notice-page .notice-stat-icon.pinned {
    color: #7c3aed;
    background: #f5f0ff;
}

.notice-page .notice-stat-card span {
    display: block;
    color: #84919f;
    font-size: 11px;
    margin-bottom: 5px;
}

.notice-page .notice-stat-card h3 {
    font-size: 22px;
    font-weight: 800;
    margin: 0;
    color: #1e293b;
}

/* Alerts */

.notice-page .notice-alert {
    padding: 13px 15px;
    border-radius: 9px;
    margin-bottom: 18px;
    display: flex;
    justify-content: space-between;
    gap: 12px;
    font-size: 12px;
}

.notice-page .notice-alert.success {
    background: #eaf8f0;
    color: #087f5b;
    border: 1px solid #c6edda;
}

.notice-page .notice-alert.error {
    background: #fff0f0;
    color: #bd2635;
    border: 1px solid #ffd4d4;
}

.notice-page .notice-alert ul {
    padding-left: 20px;
    margin: 8px 0 0;
}

.notice-page .notice-alert-close {
    background: transparent;
    border: 0;
    font-size: 20px;
    color: inherit;
    cursor: pointer;
}

/* Card */

.notice-page .notice-card {
    background: #fff;
    border: 1px solid #e8edf0;
    border-radius: 12px;
    overflow: hidden;
}

.notice-page .notice-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 19px 21px;
    border-bottom: 1px solid #edf1f3;
}

.notice-page .notice-card-header h2 {
    font-size: 16px;
    font-weight: 800;
    margin: 0 0 5px;
}

.notice-page .notice-card-header p {
    font-size: 11px;
    color: #94a3b8;
    margin: 0;
}

.notice-page .notice-record-count {
    padding: 7px 10px;
    background: #effaf5;
    color: #087f5b;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 700;
}

/* Filters */

.notice-page .notice-filter-form {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    padding: 16px 21px;
    border-bottom: 1px solid #edf1f3;
}

.notice-page .notice-search-box {
    position: relative;
    flex: 1 1 220px;
}

.notice-page .notice-search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 12px;
}

.notice-page .notice-search-box input,
.notice-page .notice-filter-form select {
    height: 38px;
    border: 1px solid #e2e8f0;
    border-radius: 7px;
    padding: 0 11px;
    background: #fff;
    color: #475569;
    font-size: 11px;
    outline: none;
    font-family: inherit;
}

.notice-page .notice-search-box input {
    width: 100%;
    padding-left: 34px;
}

.notice-page .notice-filter-form select {
    min-width: 125px;
}

.notice-page .notice-search-box input:focus,
.notice-page .notice-filter-form select:focus {
    border-color: #087f5b;
}

/* Table */

.notice-page .notice-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.notice-page .notice-table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
}

.notice-page .notice-table thead {
    background: #f8fafb;
}

.notice-page .notice-table th {
    padding: 13px 15px;
    text-align: left;
    color: #84919f;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .4px;
    white-space: nowrap;
    border-bottom: 1px solid #edf1f3;
}

.notice-page .notice-table td {
    padding: 14px 15px;
    border-bottom: 1px solid #f0f2f4;
    vertical-align: middle;
}

.notice-page .notice-table tbody tr:last-child td {
    border-bottom: 0;
}

.notice-page .notice-table tbody tr:hover {
    background: #fbfdfc;
}

.notice-page .text-right {
    text-align: right;
}

/* Notice Info */

.notice-page .notice-info {
    max-width: 260px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.notice-page .notice-info strong {
    color: #253b33;
    font-size: 12px;
    font-weight: 700;
}

.notice-page .notice-info small {
    color: #94a3b8;
    font-size: 10px;
}

.notice-page .notice-info p {
    color: #84919f;
    font-size: 10px;
    line-height: 1.5;
    margin: 0;
}

/* Priority */

.notice-page .notice-priority {
    display: inline-block;
    padding: 6px 9px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
}

.notice-page .notice-priority.normal {
    background: #eff6ff;
    color: #2563eb;
}

.notice-page .notice-priority.important {
    background: #fff7ed;
    color: #d97706;
}

.notice-page .notice-priority.urgent {
    background: #fff1f2;
    color: #dc3545;
}

/* Status */

.notice-page .notice-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
}

.notice-page .notice-status span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.notice-page .notice-status.published {
    color: #087f5b;
    background: #eaf8f0;
}

.notice-page .notice-status.published span {
    background: #087f5b;
}

.notice-page .notice-status.draft {
    color: #d97706;
    background: #fff7ed;
}

.notice-page .notice-status.draft span {
    background: #d97706;
}

.notice-page .notice-date {
    color: #64748b;
    font-size: 11px;
    white-space: nowrap;
}

.notice-page .notice-pinned {
    color: #7c3aed;
    font-size: 10px;
    font-weight: 700;
}

.notice-page .notice-not-pinned {
    color: #94a3b8;
    font-size: 11px;
}

/* Actions */

.notice-page .notice-actions {
    display: flex;
    justify-content: flex-end;
    gap: 6px;
}

.notice-page .notice-action {
    width: 31px;
    height: 31px;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    transition: .2s;
}

.notice-page .notice-action.view {
    background: #eff6ff;
    color: #2563eb;
}

.notice-page .notice-action.edit {
    background: #fff7ed;
    color: #d97706;
}

.notice-page .notice-action.delete {
    background: #fff1f2;
    color: #dc3545;
}

.notice-page .notice-action:hover {
    transform: translateY(-2px);
}

/* Empty State */

.notice-page .notice-empty-state {
    padding: 55px 20px;
    text-align: center;
}

.notice-page .notice-empty-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    background: #effaf5;
    color: #087f5b;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.notice-page .notice-empty-state h3 {
    font-size: 16px;
    margin: 0 0 7px;
}

.notice-page .notice-empty-state p {
    color: #94a3b8;
    font-size: 11px;
    margin: 0 0 17px;
}

/* Pagination */

.notice-page .notice-pagination {
    padding: 17px 20px;
    border-top: 1px solid #edf1f3;
}

/* Modal */

.notice-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: rgba(15, 23, 42, .58);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 18px;
    overflow-y: auto;
}

.notice-modal-overlay.notice-open {
    display: flex;
}

.notice-modal {
    width: 100%;
    max-width: 650px;
    max-height: calc(100vh - 36px);
    overflow-y: auto;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
    animation: noticeModalIn .2s ease;
}

@keyframes noticeModalIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notice-modal-header {
    padding: 19px 22px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 1px solid #edf1f3;
}

.notice-modal-header h2 {
    font-size: 17px;
    font-weight: 800;
    margin: 0 0 5px;
}

.notice-modal-header p {
    color: #94a3b8;
    font-size: 11px;
    margin: 0;
}

.notice-modal-close {
    width: 31px;
    height: 31px;
    border: 0;
    border-radius: 7px;
    background: #f1f5f9;
    color: #64748b;
    font-size: 21px;
    cursor: pointer;
}

.notice-modal-body {
    padding: 22px;
}

.notice-modal-footer {
    padding: 15px 22px;
    border-top: 1px solid #edf1f3;
    display: flex;
    justify-content: flex-end;
    gap: 9px;
}

/* Form */

.notice-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 17px;
}

.notice-form-group {
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.notice-form-group.full {
    grid-column: 1 / -1;
}

.notice-form-group label {
    font-size: 11px;
    font-weight: 700;
    color: #334155;
}

.notice-form-group label > span {
    color: #dc3545;
}

.notice-form-group input:not([type="checkbox"]),
.notice-form-group textarea,
.notice-form-group select {
    width: 100%;
    border: 1px solid #dfe6eb;
    border-radius: 8px;
    padding: 11px 12px;
    color: #334155;
    font-size: 12px;
    font-family: inherit;
    background: #fff;
    outline: none;
}

.notice-form-group input:focus,
.notice-form-group textarea:focus,
.notice-form-group select:focus {
    border-color: #087f5b;
    box-shadow: 0 0 0 3px rgba(8, 127, 91, .08);
}

.notice-form-group textarea {
    resize: vertical;
    min-height: 130px;
}

.notice-checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px;
    background: #f8fafb;
    border-radius: 8px;
    cursor: pointer;
}

.notice-checkbox-label input {
    width: 16px;
    height: 16px;
    accent-color: #087f5b;
}

.notice-checkbox-label span {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.notice-checkbox-label strong {
    font-size: 11px;
    color: #334155;
}

.notice-checkbox-label small {
    font-size: 10px;
    color: #94a3b8;
}

/* View */

.notice-view-content h2 {
    font-size: 20px;
    font-weight: 800;
    color: #1e293b;
    margin: 0 0 12px;
    overflow-wrap: anywhere;
}

.notice-view-meta {
    display: flex;
    gap: 8px;
    margin-bottom: 18px;
    flex-wrap: wrap;
}

.notice-view-meta span {
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
    background: #eff6ff;
    color: #2563eb;
}

.notice-view-description {
    font-size: 13px;
    line-height: 1.8;
    color: #475569;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
    margin-bottom: 20px;
}

.notice-view-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.notice-view-grid > div {
    background: #f8fafb;
    padding: 12px;
    border-radius: 8px;
}

.notice-view-grid span {
    display: block;
    color: #94a3b8;
    font-size: 10px;
    margin-bottom: 6px;
}

.notice-view-grid strong {
    color: #334155;
    font-size: 11px;
}

/* Delete */

.notice-delete-modal {
    max-width: 420px;
    text-align: center;
    padding-top: 25px;
}

.notice-delete-icon {
    width: 58px;
    height: 58px;
    margin: 0 auto 15px;
    border-radius: 50%;
    background: #fff1f2;
    color: #dc3545;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 23px;
}

.notice-delete-modal h2 {
    font-size: 18px;
    margin: 0 0 8px;
}

.notice-delete-modal > p {
    color: #64748b;
    font-size: 12px;
    padding: 0 20px;
    line-height: 1.7;
}

.notice-delete-modal .notice-modal-footer {
    justify-content: center;
}

/* Responsive */

@media(max-width: 1100px) {
    .notice-page .notice-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media(max-width: 650px) {
    .notice-page .notice-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .notice-page .notice-stats {
        grid-template-columns: 1fr;
    }

    .notice-form-grid,
    .notice-view-grid {
        grid-template-columns: 1fr;
    }

    .notice-form-group.full {
        grid-column: auto;
    }

    .notice-page .notice-filter-form {
        align-items: stretch;
        flex-direction: column;
    }

    .notice-page .notice-filter-form select {
        width: 100%;
    }
}

</style>
@endpush


@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', function () {

    const baseUrl = @json(url('/super-admin/notices'));

    const formModal = document.getElementById('noticeFormModal');
    const viewModal = document.getElementById('noticeViewModal');
    const deleteModal = document.getElementById('noticeDeleteModal');

    const noticeForm = document.getElementById('noticeForm');

    /* Modal Functions */

    function openNoticeModal(modal) {
        modal.classList.add('notice-open');
        document.body.style.overflow = 'hidden';
    }

    function closeNoticeModal(modal) {
        modal.classList.remove('notice-open');

        if (!document.querySelector('.notice-modal-overlay.notice-open')) {
            document.body.style.overflow = '';
        }
    }

    document.querySelectorAll('[data-close-notice]').forEach(function (button) {
        button.addEventListener('click', function () {
            closeNoticeModal(button.closest('.notice-modal-overlay'));
        });
    });

    document.querySelectorAll('.notice-modal-overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (event) {
            if (event.target === overlay) {
                closeNoticeModal(overlay);
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.notice-modal-overlay.notice-open')
                .forEach(closeNoticeModal);
        }
    });

    /* Reset Form */

    function resetNoticeForm() {

        noticeForm.reset();

        noticeForm.action = baseUrl;

        document.getElementById('noticeMethodField').innerHTML = '';

        document.getElementById('noticeModalTitle').textContent =
            'Create Notice';

        document.getElementById('noticeModalSubtitle').textContent =
            'Add a new website notice.';

        document.querySelector('#noticeSubmitButton span').textContent =
            'Save Notice';

        document.getElementById('noticePriority').value = 'normal';

        document.getElementById('noticeStatus').value = 'draft';
    }

    /* Create */

    function openCreateNotice() {
        resetNoticeForm();
        openNoticeModal(formModal);
    }

    document.getElementById('openCreateNotice')
        .addEventListener('click', openCreateNotice);

    const emptyCreateButton = document.getElementById('emptyCreateNotice');

    if (emptyCreateButton) {
        emptyCreateButton.addEventListener('click', openCreateNotice);
    }

    /* Edit */

    document.querySelectorAll('.notice-action.edit').forEach(function (button) {

        button.addEventListener('click', function () {

            resetNoticeForm();

            const data = this.dataset;

            noticeForm.action = baseUrl + '/' + data.id;

            document.getElementById('noticeMethodField').innerHTML =
                '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('noticeModalTitle').textContent =
                'Edit Notice';

            document.getElementById('noticeModalSubtitle').textContent =
                'Update your existing notice.';

            document.querySelector('#noticeSubmitButton span').textContent =
                'Update Notice';

            document.getElementById('noticeTitle').value = data.title || '';

            document.getElementById('noticeContent').value = data.content || '';

            document.getElementById('noticePriority').value = data.priority || 'normal';

            document.getElementById('noticeStatus').value = data.status || 'draft';

            document.getElementById('noticePinned').checked = data.pinned === '1';

            document.getElementById('noticePublishDate').value =
                data.publishDate || '';

            document.getElementById('noticeExpiryDate').value =
                data.expiryDate || '';

            openNoticeModal(formModal);

        });

    });

    /* View */

    document.querySelectorAll('.notice-action.view').forEach(function (button) {

        button.addEventListener('click', async function () {

            try {

                const response = await fetch(this.dataset.viewUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Unable to load notice details.');
                }

                const notice = await response.json();

                document.getElementById('viewNoticeTitle').textContent =
                    notice.title || '-';

                document.getElementById('viewNoticeContent').textContent =
                    notice.content || '-';

                document.getElementById('viewNoticePriority').textContent =
                    'Priority: ' + (notice.priority || '-');

                document.getElementById('viewNoticeStatus').textContent =
                    'Status: ' + (notice.status || '-');

                document.getElementById('viewNoticePublishDate').textContent =
                    notice.publish_date || 'Not scheduled';

                document.getElementById('viewNoticeExpiryDate').textContent =
                    notice.expiry_date || 'No expiry';

                document.getElementById('viewNoticePinned').textContent =
                    notice.is_pinned ? 'Yes' : 'No';

                document.getElementById('viewNoticeCreatedAt').textContent =
                    notice.created_at || '-';

                openNoticeModal(viewModal);

            } catch (error) {
                alert(error.message);
            }

        });

    });

    /* Delete */

    document.querySelectorAll('.notice-action.delete').forEach(function (button) {

        button.addEventListener('click', function () {

            document.getElementById('deleteNoticeTitle').textContent =
                this.dataset.title;

            document.getElementById('noticeDeleteForm').action =
                baseUrl + '/' + this.dataset.id;

            openNoticeModal(deleteModal);

        });

    });

    /* Submit Loading */

    noticeForm.addEventListener('submit', function () {

        const submitButton = document.getElementById('noticeSubmitButton');

        submitButton.disabled = true;

        submitButton.querySelector('span').textContent = 'Please wait...';

    });

    /* Alert Close */

    document.querySelectorAll('.notice-alert-close').forEach(function (button) {

        button.addEventListener('click', function () {
            this.closest('.notice-alert').remove();
        });

    });

});

</script>
@endpush