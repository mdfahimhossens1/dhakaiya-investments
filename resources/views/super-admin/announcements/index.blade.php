@extends('layouts.app')

@section('title', 'Announcement Management')

@section('content')

<div class="announcement-page">

    {{-- Page Header --}}
    <div class="announcement-header">
        <div>
            <div class="announcement-breadcrumb">
                <span>Dashboard</span>
                <i class="fas fa-chevron-right"></i>
                <span>Website Content</span>
                <i class="fas fa-chevron-right"></i>
                <strong>Announcements</strong>
            </div>

            <h1>Announcement Management</h1>

            <p>
                Create, manage and publish website announcements.
            </p>
        </div>

        <button type="button"
                class="announcement-btn announcement-btn-primary"
                onclick="openAnnouncementModal('create')">

            <i class="fas fa-plus"></i>
            Create Announcement

        </button>
    </div>


    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="announcement-alert announcement-alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>

            <button type="button" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="announcement-alert announcement-alert-danger">
            <i class="fas fa-exclamation-circle"></i>

            <div>
                <strong>Please check the following errors:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <button type="button" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif


    {{-- Statistics --}}
    <div class="announcement-stats">

        <div class="announcement-stat-card">
            <div class="announcement-stat-icon icon-blue">
                <i class="fas fa-bullhorn"></i>
            </div>

            <div>
                <span>Total Announcements</span>
                <h3>{{ number_format($totalAnnouncements) }}</h3>
            </div>
        </div>

        <div class="announcement-stat-card">
            <div class="announcement-stat-icon icon-green">
                <i class="fas fa-check-circle"></i>
            </div>

            <div>
                <span>Published</span>
                <h3>{{ number_format($publishedAnnouncements) }}</h3>
            </div>
        </div>

        <div class="announcement-stat-card">
            <div class="announcement-stat-icon icon-orange">
                <i class="fas fa-file-alt"></i>
            </div>

            <div>
                <span>Draft</span>
                <h3>{{ number_format($draftAnnouncements) }}</h3>
            </div>
        </div>

        <div class="announcement-stat-card">
            <div class="announcement-stat-icon icon-purple">
                <i class="fas fa-eye"></i>
            </div>

            <div>
                <span>Currently Active</span>
                <h3>{{ number_format($activeAnnouncements) }}</h3>
            </div>
        </div>

    </div>


    {{-- Main Card --}}
    <div class="announcement-card">

        <div class="announcement-card-header">

            <div>
                <h3>All Announcements</h3>
                <p>Manage your website announcements here.</p>
            </div>

            <span class="announcement-total-badge">
                {{ $announcements->total() }} Records
            </span>

        </div>


        {{-- Filters --}}
        <form method="GET"
              action="{{ route('super-admin.announcements.index') }}"
              class="announcement-filter-form">

            <div class="announcement-search-box">
                <i class="fas fa-search"></i>

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by title or message...">
            </div>

            <select name="status" class="announcement-filter-select">
                <option value="">All Status</option>
                <option value="published"
                    {{ request('status') === 'published' ? 'selected' : '' }}>
                    Published
                </option>
                <option value="draft"
                    {{ request('status') === 'draft' ? 'selected' : '' }}>
                    Draft
                </option>
            </select>

            <select name="type" class="announcement-filter-select">
                <option value="">All Types</option>
                <option value="info"
                    {{ request('type') === 'info' ? 'selected' : '' }}>
                    Information
                </option>
                <option value="success"
                    {{ request('type') === 'success' ? 'selected' : '' }}>
                    Success
                </option>
                <option value="warning"
                    {{ request('type') === 'warning' ? 'selected' : '' }}>
                    Warning
                </option>
                <option value="danger"
                    {{ request('type') === 'danger' ? 'selected' : '' }}>
                    Alert
                </option>
            </select>

            <button type="submit" class="announcement-btn announcement-btn-filter">
                <i class="fas fa-filter"></i>
                Filter
            </button>

            <a href="{{ route('super-admin.announcements.index') }}"
               class="announcement-btn announcement-btn-reset">
                <i class="fas fa-undo"></i>
                Reset
            </a>

        </form>


        {{-- Table --}}
        <div class="announcement-table-wrapper">

            <table class="announcement-table">

                <thead>
                    <tr>
                        <th>ANNOUNCEMENT</th>
                        <th>TYPE</th>
                        <th>STATUS</th>
                        <th>VISIBILITY</th>
                        <th>SCHEDULE</th>
                        <th>CREATED</th>
                        <th class="text-center">ACTIONS</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($announcements as $announcement)

                        <tr>

                            {{-- Title --}}
                            <td>
                                <div class="announcement-title-cell">

                                    <div class="announcement-row-icon type-{{ $announcement->type }}">
                                        @if($announcement->type === 'info')
                                            <i class="fas fa-info"></i>
                                        @elseif($announcement->type === 'success')
                                            <i class="fas fa-check"></i>
                                        @elseif($announcement->type === 'warning')
                                            <i class="fas fa-exclamation"></i>
                                        @else
                                            <i class="fas fa-bell"></i>
                                        @endif
                                    </div>

                                    <div>
                                        <strong>{{ $announcement->title }}</strong>

                                        <small>
                                            {{ \Illuminate\Support\Str::limit(strip_tags($announcement->message), 65) }}
                                        </small>
                                    </div>

                                </div>
                            </td>


                            {{-- Type --}}
                            <td>
                                <span class="announcement-type-badge type-badge-{{ $announcement->type }}">
                                    {{ ucfirst($announcement->type) }}
                                </span>
                            </td>


                            {{-- Status --}}
                            <td>
                                @if($announcement->status === 'published')
                                    <span class="announcement-status status-published">
                                        <span></span>
                                        Published
                                    </span>
                                @else
                                    <span class="announcement-status status-draft">
                                        <span></span>
                                        Draft
                                    </span>
                                @endif
                            </td>


                            {{-- Visibility --}}
                            <td>
                                @if($announcement->is_dismissible)
                                    <span class="announcement-visibility">
                                        <i class="fas fa-check-circle"></i>
                                        Dismissible
                                    </span>
                                @else
                                    <span class="announcement-visibility disabled">
                                        <i class="fas fa-lock"></i>
                                        Required
                                    </span>
                                @endif
                            </td>


                            {{-- Schedule --}}
                            <td>
                                <div class="announcement-schedule">
                                    <strong>
                                        {{ $announcement->start_at?->format('d M Y') ?? 'Immediately' }}
                                    </strong>

                                    <small>
                                        Until:
                                        {{ $announcement->end_at?->format('d M Y') ?? 'No expiry' }}
                                    </small>
                                </div>
                            </td>


                            {{-- Created --}}
                            <td>
                                <div class="announcement-created">
                                    {{ $announcement->created_at->format('d M Y') }}

                                    <small>
                                        {{ $announcement->created_at->format('h:i A') }}
                                    </small>
                                </div>
                            </td>


                            {{-- Actions --}}
                            <td>
                                <div class="announcement-actions">

                                    <button type="button"
                                            class="announcement-action-btn action-view"
                                            title="View"
                                            onclick="viewAnnouncement('{{ route('super-admin.announcements.show', $announcement) }}')">

                                        <i class="fas fa-eye"></i>
                                    </button>


                                    <button type="button"
                                            class="announcement-action-btn action-edit"
                                            title="Edit"
                                            data-id="{{ $announcement->id }}"
                                            data-title="{{ $announcement->title }}"
                                            data-message="{{ $announcement->message }}"
                                            data-type="{{ $announcement->type }}"
                                            data-status="{{ $announcement->status }}"
                                            data-dismissible="{{ $announcement->is_dismissible ? 1 : 0 }}"
                                            data-start="{{ $announcement->start_at?->format('Y-m-d\TH:i') }}"
                                            data-end="{{ $announcement->end_at?->format('Y-m-d\TH:i') }}"
                                            onclick="editAnnouncement(this)">

                                        <i class="fas fa-pen"></i>
                                    </button>


                                    <button type="button"
                                            class="announcement-action-btn action-delete"
                                            title="Delete"
                                            onclick="deleteAnnouncement(
                                                '{{ route('super-admin.announcements.destroy', $announcement) }}',
                                                @js($announcement->title)
                                            )">

                                        <i class="fas fa-trash"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">
                                <div class="announcement-empty">

                                    <div>
                                        <i class="fas fa-bullhorn"></i>
                                    </div>

                                    <h3>No Announcements Found</h3>

                                    <p>
                                        There are no announcements matching your search.
                                    </p>

                                    <button type="button"
                                            class="announcement-btn announcement-btn-primary"
                                            onclick="openAnnouncementModal('create')">

                                        <i class="fas fa-plus"></i>
                                        Create Announcement

                                    </button>

                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($announcements->hasPages())
            <div class="announcement-pagination">
                {{ $announcements->links() }}
            </div>
        @endif

    </div>

</div>


{{-- =====================================================
     CREATE / EDIT MODAL
===================================================== --}}

<div class="announcement-modal-overlay"
     id="announcementFormModal">

    <div class="announcement-modal announcement-form-modal">

        <div class="announcement-modal-header">

            <div>
                <div class="announcement-modal-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>

                <div>
                    <h3 id="announcementFormTitle">Create Announcement</h3>
                    <p id="announcementFormSubtitle">
                        Add a new website announcement.
                    </p>
                </div>
            </div>

            <button type="button"
                    class="announcement-modal-close"
                    onclick="closeAnnouncementModal('announcementFormModal')">

                <i class="fas fa-times"></i>

            </button>

        </div>


        <form id="announcementForm"
              method="POST"
              action="{{ route('super-admin.announcements.store') }}">

            @csrf

            <div id="announcementMethod"></div>

            <div class="announcement-modal-body">

                {{-- Title --}}
                <div class="announcement-form-group">

                    <label>
                        Announcement Title
                        <span>*</span>
                    </label>

                    <input type="text"
                           name="title"
                           id="announcementTitle"
                           placeholder="Enter announcement title"
                           maxlength="255"
                           required>

                </div>


                {{-- Message --}}
                <div class="announcement-form-group">

                    <label>
                        Announcement Message
                        <span>*</span>
                    </label>

                    <textarea name="message"
                              id="announcementMessage"
                              rows="5"
                              placeholder="Write your announcement message..."
                              required></textarea>

                </div>


                {{-- Type and Status --}}
                <div class="announcement-form-grid">

                    <div class="announcement-form-group">

                        <label>
                            Announcement Type
                            <span>*</span>
                        </label>

                        <select name="type" id="announcementType" required>

                            <option value="info">Information</option>
                            <option value="success">Success</option>
                            <option value="warning">Warning</option>
                            <option value="danger">Alert</option>

                        </select>

                    </div>


                    <div class="announcement-form-group">

                        <label>
                            Publication Status
                            <span>*</span>
                        </label>

                        <select name="status" id="announcementStatus" required>

                            <option value="draft">Draft</option>
                            <option value="published">Published</option>

                        </select>

                    </div>

                </div>


                {{-- Schedule --}}
                <div class="announcement-section-title">
                    <i class="fas fa-calendar-alt"></i>
                    Publication Schedule
                </div>

                <div class="announcement-form-grid">

                    <div class="announcement-form-group">

                        <label>Start Date & Time</label>

                        <input type="datetime-local"
                               name="start_at"
                               id="announcementStart">

                        <small>Leave empty to make it immediately eligible for display.</small>

                    </div>


                    <div class="announcement-form-group">

                        <label>End Date & Time</label>

                        <input type="datetime-local"
                               name="end_at"
                               id="announcementEnd">

                        <small>Leave empty if there is no expiry date.</small>

                    </div>

                </div>


                {{-- Dismissible --}}
                <div class="announcement-toggle-box">

                    <div>
                        <strong>Allow users to dismiss</strong>

                        <small>
                            Users can close this announcement.
                        </small>
                    </div>

                    <label class="announcement-switch">

                        <input type="checkbox"
                               name="is_dismissible"
                               id="announcementDismissible"
                               value="1"
                               checked>

                        <span class="announcement-switch-slider"></span>

                    </label>

                </div>

            </div>


            {{-- Modal Footer --}}
            <div class="announcement-modal-footer">

                <button type="button"
                        class="announcement-btn announcement-btn-cancel"
                        onclick="closeAnnouncementModal('announcementFormModal')">

                    <i class="fas fa-times"></i>
                    Cancel

                </button>


                <button type="submit"
                        class="announcement-btn announcement-btn-primary"
                        id="announcementSaveButton">

                    <i class="fas fa-save"></i>
                    <span>Save Announcement</span>

                </button>

            </div>

        </form>

    </div>

</div>


{{-- =====================================================
     VIEW MODAL
===================================================== --}}

<div class="announcement-modal-overlay"
     id="announcementViewModal">

    <div class="announcement-modal announcement-view-modal">

        <div class="announcement-modal-header">

            <div>
                <div class="announcement-modal-icon">
                    <i class="fas fa-eye"></i>
                </div>

                <div>
                    <h3>Announcement Details</h3>
                    <p>View complete announcement information.</p>
                </div>
            </div>

            <button type="button"
                    class="announcement-modal-close"
                    onclick="closeAnnouncementModal('announcementViewModal')">

                <i class="fas fa-times"></i>

            </button>

        </div>


        <div class="announcement-modal-body">

            <div class="announcement-view-type" id="viewAnnouncementType">
                Information
            </div>

            <h2 id="viewAnnouncementTitle"></h2>

            <div class="announcement-view-message"
                 id="viewAnnouncementMessage"></div>


            <div class="announcement-view-grid">

                <div>
                    <span>Status</span>
                    <strong id="viewAnnouncementStatus"></strong>
                </div>

                <div>
                    <span>Dismissible</span>
                    <strong id="viewAnnouncementDismissible"></strong>
                </div>

                <div>
                    <span>Start Date</span>
                    <strong id="viewAnnouncementStart"></strong>
                </div>

                <div>
                    <span>End Date</span>
                    <strong id="viewAnnouncementEnd"></strong>
                </div>

                <div>
                    <span>Created At</span>
                    <strong id="viewAnnouncementCreated"></strong>
                </div>

                <div>
                    <span>Last Updated</span>
                    <strong id="viewAnnouncementUpdated"></strong>
                </div>

            </div>

        </div>


        <div class="announcement-modal-footer">

            <button type="button"
                    class="announcement-btn announcement-btn-cancel"
                    onclick="closeAnnouncementModal('announcementViewModal')">

                <i class="fas fa-times"></i>
                Close

            </button>

        </div>

    </div>

</div>


{{-- =====================================================
     DELETE MODAL
===================================================== --}}

<div class="announcement-modal-overlay"
     id="announcementDeleteModal">

    <div class="announcement-modal announcement-delete-modal">

        <div class="announcement-delete-content">

            <div class="announcement-delete-icon">
                <i class="fas fa-trash-alt"></i>
            </div>

            <h3>Delete Announcement?</h3>

            <p>
                Are you sure you want to delete
                <strong id="deleteAnnouncementTitle"></strong>?
                This action cannot be undone.
            </p>

        </div>


        <form id="announcementDeleteForm" method="POST">

            @csrf
            @method('DELETE')

            <div class="announcement-modal-footer">

                <button type="button"
                        class="announcement-btn announcement-btn-cancel"
                        onclick="closeAnnouncementModal('announcementDeleteModal')">

                    Cancel

                </button>


                <button type="submit"
                        class="announcement-btn announcement-btn-danger">

                    <i class="fas fa-trash"></i>
                    Delete Announcement

                </button>

            </div>

        </form>

    </div>

</div>

@endsection


{{-- =====================================================
     STYLES
===================================================== --}}

@push('styles')

<style>

.announcement-page {
    width: 100%;
    color: #25332f;
    font-family: inherit;
}

.announcement-page * {
    box-sizing: border-box;
}


/* Header */

.announcement-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.announcement-breadcrumb {
    display: flex;
    align-items: center;
    gap: 9px;
    color: #89958f;
    font-size: 12px;
    margin-bottom: 10px;
}

.announcement-breadcrumb i {
    font-size: 9px;
}

.announcement-breadcrumb strong {
    color: #087f5b;
    font-weight: 600;
}

.announcement-header h1 {
    font-size: 25px;
    font-weight: 750;
    color: #173a30;
    margin: 0 0 7px;
}

.announcement-header p {
    color: #82908a;
    font-size: 13px;
    margin: 0;
}


/* Buttons */

.announcement-btn {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 17px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 650;
    font-family: inherit;
    text-decoration: none;
    cursor: pointer;
    transition: all .2s ease;
}

.announcement-btn i {
    font-size: 12px;
}

.announcement-btn-primary {
    background: #087f5b;
    color: #fff;
    border-color: #087f5b;
    box-shadow: 0 3px 8px rgba(8,127,91,.12);
}

.announcement-btn-primary:hover {
    background: #06694b;
    border-color: #06694b;
    color: #fff;
    transform: translateY(-1px);
}

.announcement-btn-filter {
    background: #173a30;
    color: #fff;
}

.announcement-btn-filter:hover {
    background: #102c24;
    color: #fff;
}

.announcement-btn-reset {
    background: #fff;
    color: #69766f;
    border-color: #e2e8e4;
}

.announcement-btn-reset:hover {
    background: #f5f8f6;
    color: #173a30;
}

.announcement-btn-cancel {
    background: #f3f5f4;
    color: #56645e;
    border-color: #e5e9e6;
}

.announcement-btn-cancel:hover {
    background: #e8ece9;
    color: #25332f;
}

.announcement-btn-danger {
    background: #dc3545;
    color: #fff;
    border-color: #dc3545;
}

.announcement-btn-danger:hover {
    background: #bd2635;
    color: #fff;
}


/* Alerts */

.announcement-alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 9px;
    margin-bottom: 20px;
    font-size: 13px;
}

.announcement-alert > i {
    font-size: 17px;
    margin-top: 1px;
}

.announcement-alert button {
    margin-left: auto;
    border: none;
    background: transparent;
    cursor: pointer;
    color: inherit;
    font-size: 14px;
}

.announcement-alert-success {
    color: #167448;
    background: #eaf8f0;
    border: 1px solid #c9ead7;
}

.announcement-alert-danger {
    color: #b42332;
    background: #fff0f1;
    border: 1px solid #f3d0d4;
}

.announcement-alert ul {
    margin: 7px 0 0;
    padding-left: 18px;
}


/* Statistics */

.announcement-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}

.announcement-stat-card {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #fff;
    padding: 19px;
    border: 1px solid #edf0ee;
    border-radius: 11px;
    box-shadow: 0 2px 8px rgba(20,50,40,.025);
}

.announcement-stat-icon {
    width: 46px;
    height: 46px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 11px;
    font-size: 18px;
}

.icon-blue {
    background: #eaf2ff;
    color: #3976d6;
}

.icon-green {
    background: #e8f7ef;
    color: #168653;
}

.icon-orange {
    background: #fff3e6;
    color: #e68a2e;
}

.icon-purple {
    background: #f2edff;
    color: #8058d6;
}

.announcement-stat-card span {
    display: block;
    color: #84918b;
    font-size: 11px;
    margin-bottom: 5px;
}

.announcement-stat-card h3 {
    color: #173a30;
    font-size: 23px;
    font-weight: 750;
    margin: 0;
}


/* Main Card */

.announcement-card {
    background: #fff;
    border: 1px solid #edf0ee;
    border-radius: 11px;
    box-shadow: 0 2px 10px rgba(20,50,40,.025);
    overflow: hidden;
}

.announcement-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 21px 22px;
    border-bottom: 1px solid #edf0ee;
}

.announcement-card-header h3 {
    font-size: 16px;
    color: #173a30;
    font-weight: 700;
    margin: 0 0 5px;
}

.announcement-card-header p {
    font-size: 12px;
    color: #89958f;
    margin: 0;
}

.announcement-total-badge {
    background: #edf7f2;
    color: #087f5b;
    padding: 7px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}


/* Filters */

.announcement-filter-form {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    padding: 17px 22px;
    border-bottom: 1px solid #edf0ee;
}

.announcement-search-box {
    flex: 1;
    min-width: 220px;
    position: relative;
}

.announcement-search-box i {
    position: absolute;
    top: 50%;
    left: 13px;
    transform: translateY(-50%);
    color: #9ba69f;
    font-size: 13px;
}

.announcement-search-box input,
.announcement-filter-select {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    background: #fff;
    border: 1px solid #e4eae6;
    border-radius: 7px;
    outline: none;
    color: #394941;
    font-size: 12px;
    font-family: inherit;
}

.announcement-search-box input {
    padding-left: 37px;
}

.announcement-search-box input:focus,
.announcement-filter-select:focus {
    border-color: #087f5b;
    box-shadow: 0 0 0 3px rgba(8,127,91,.08);
}

.announcement-filter-select {
    width: 145px;
    cursor: pointer;
}


/* Table */

.announcement-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.announcement-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1050px;
}

.announcement-table thead {
    background: #f8faf9;
}

.announcement-table th {
    padding: 14px 17px;
    text-align: left;
    color: #85918b;
    font-size: 10px;
    font-weight: 750;
    letter-spacing: .45px;
    white-space: nowrap;
    border-bottom: 1px solid #edf0ee;
}

.announcement-table td {
    padding: 15px 17px;
    border-bottom: 1px solid #f0f2f0;
    vertical-align: middle;
}

.announcement-table tbody tr:last-child td {
    border-bottom: none;
}

.announcement-table tbody tr:hover {
    background: #fcfdfc;
}

.announcement-title-cell {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 240px;
}

.announcement-row-icon {
    width: 37px;
    height: 37px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    font-size: 13px;
}

.announcement-row-icon.type-info {
    background: #eaf2ff;
    color: #3976d6;
}

.announcement-row-icon.type-success {
    background: #e8f7ef;
    color: #168653;
}

.announcement-row-icon.type-warning {
    background: #fff3e6;
    color: #e68a2e;
}

.announcement-row-icon.type-danger {
    background: #fff0f1;
    color: #dc3545;
}

.announcement-title-cell strong {
    display: block;
    color: #293a32;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 5px;
}

.announcement-title-cell small {
    display: block;
    color: #89958f;
    font-size: 10px;
    max-width: 250px;
    line-height: 1.5;
}


/* Type Badges */

.announcement-type-badge {
    display: inline-flex;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
}

.type-badge-info {
    background: #eaf2ff;
    color: #3976d6;
}

.type-badge-success {
    background: #e8f7ef;
    color: #168653;
}

.type-badge-warning {
    background: #fff3e6;
    color: #d98226;
}

.type-badge-danger {
    background: #fff0f1;
    color: #dc3545;
}


/* Status */

.announcement-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 11px;
    font-weight: 650;
    white-space: nowrap;
}

.announcement-status span {
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.status-published {
    color: #168653;
}

.status-published span {
    background: #20ae6a;
}

.status-draft {
    color: #c47c27;
}

.status-draft span {
    background: #e8a34b;
}


/* Visibility */

.announcement-visibility {
    color: #168653;
    font-size: 10px;
    white-space: nowrap;
}

.announcement-visibility i {
    margin-right: 4px;
}

.announcement-visibility.disabled {
    color: #89958f;
}


/* Schedule */

.announcement-schedule strong {
    display: block;
    color: #47574f;
    font-size: 11px;
    font-weight: 650;
    white-space: nowrap;
}

.announcement-schedule small {
    display: block;
    margin-top: 5px;
    color: #929d97;
    font-size: 10px;
    white-space: nowrap;
}


/* Created */

.announcement-created {
    color: #47574f;
    font-size: 11px;
    white-space: nowrap;
}

.announcement-created small {
    display: block;
    margin-top: 4px;
    color: #929d97;
    font-size: 10px;
}


/* Actions */

.announcement-actions {
    display: flex;
    justify-content: center;
    gap: 6px;
}

.announcement-action-btn {
    width: 31px;
    height: 31px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    border: 1px solid transparent;
    cursor: pointer;
    font-size: 11px;
    transition: all .2s ease;
}

.action-view {
    background: #edf4ff;
    color: #3976d6;
    border-color: #e0ebff;
}

.action-view:hover {
    background: #3976d6;
    color: #fff;
}

.action-edit {
    background: #fff6e9;
    color: #d98b2c;
    border-color: #f7e9d0;
}

.action-edit:hover {
    background: #d98b2c;
    color: #fff;
}

.action-delete {
    background: #fff0f1;
    color: #dc3545;
    border-color: #f7dfe2;
}

.action-delete:hover {
    background: #dc3545;
    color: #fff;
}


/* Empty State */

.announcement-empty {
    padding: 55px 20px;
    text-align: center;
}

.announcement-empty > div {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    border-radius: 50%;
    background: #edf7f2;
    color: #087f5b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 23px;
}

.announcement-empty h3 {
    color: #25332f;
    font-size: 16px;
    margin: 0 0 7px;
}

.announcement-empty p {
    color: #89958f;
    font-size: 12px;
    margin-bottom: 18px;
}


/* Pagination */

.announcement-pagination {
    padding: 17px 22px;
    border-top: 1px solid #edf0ee;
}


/* Modal Overlay */

.announcement-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(13,31,25,.55);
    backdrop-filter: blur(3px);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.announcement-modal-overlay.active {
    display: flex;
}

.announcement-modal {
    width: 100%;
    max-width: 650px;
    max-height: 90vh;
    overflow-y: auto;
    background: #fff;
    border-radius: 13px;
    box-shadow: 0 20px 60px rgba(0,0,0,.17);
    animation: announcementModalIn .2s ease;
}

@keyframes announcementModalIn {
    from {
        opacity: 0;
        transform: translateY(12px) scale(.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}


/* Modal Header */

.announcement-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 23px;
    border-bottom: 1px solid #edf0ee;
}

.announcement-modal-header > div {
    display: flex;
    align-items: center;
    gap: 12px;
}

.announcement-modal-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eaf7f1;
    color: #087f5b;
    border-radius: 10px;
    font-size: 16px;
}

.announcement-modal-header h3 {
    margin: 0 0 5px;
    color: #173a30;
    font-size: 16px;
    font-weight: 750;
}

.announcement-modal-header p {
    margin: 0;
    color: #89958f;
    font-size: 11px;
}

.announcement-modal-close {
    width: 33px;
    height: 33px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f5f4;
    border: none;
    border-radius: 7px;
    color: #68756e;
    cursor: pointer;
    transition: .2s;
}

.announcement-modal-close:hover {
    background: #ffe9eb;
    color: #dc3545;
}


/* Modal Body */

.announcement-modal-body {
    padding: 22px 23px;
}

.announcement-form-group {
    margin-bottom: 18px;
}

.announcement-form-group label {
    display: block;
    margin-bottom: 8px;
    color: #35463e;
    font-size: 12px;
    font-weight: 700;
}

.announcement-form-group label span {
    color: #dc3545;
}

.announcement-form-group input,
.announcement-form-group textarea,
.announcement-form-group select {
    width: 100%;
    min-height: 41px;
    padding: 10px 12px;
    border: 1px solid #e0e7e2;
    border-radius: 7px;
    background: #fff;
    outline: none;
    color: #35463e;
    font-size: 12px;
    font-family: inherit;
    transition: .2s;
}

.announcement-form-group textarea {
    min-height: 115px;
    resize: vertical;
    line-height: 1.6;
}

.announcement-form-group input:focus,
.announcement-form-group textarea:focus,
.announcement-form-group select:focus {
    border-color: #087f5b;
    box-shadow: 0 0 0 3px rgba(8,127,91,.08);
}

.announcement-form-group input::placeholder,
.announcement-form-group textarea::placeholder {
    color: #a5afa9;
}

.announcement-form-group small {
    display: block;
    color: #929d97;
    font-size: 10px;
    margin-top: 6px;
}

.announcement-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px;
}

.announcement-section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #173a30;
    font-size: 12px;
    font-weight: 750;
    margin: 5px 0 16px;
    padding-top: 17px;
    border-top: 1px solid #edf0ee;
}

.announcement-section-title i {
    color: #087f5b;
}


/* Toggle */

.announcement-toggle-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 14px;
    border: 1px solid #e8eee9;
    background: #f9fbfa;
    border-radius: 8px;
}

.announcement-toggle-box strong {
    display: block;
    color: #35463e;
    font-size: 12px;
    margin-bottom: 5px;
}

.announcement-toggle-box small {
    display: block;
    color: #89958f;
    font-size: 10px;
}

.announcement-switch {
    position: relative;
    display: inline-block;
    width: 42px;
    height: 23px;
    flex-shrink: 0;
}

.announcement-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.announcement-switch-slider {
    position: absolute;
    inset: 0;
    background: #cbd3ce;
    border-radius: 30px;
    cursor: pointer;
    transition: .2s;
}

.announcement-switch-slider::before {
    content: "";
    position: absolute;
    width: 17px;
    height: 17px;
    left: 3px;
    top: 3px;
    background: #fff;
    border-radius: 50%;
    transition: .2s;
}

.announcement-switch input:checked + .announcement-switch-slider {
    background: #087f5b;
}

.announcement-switch input:checked + .announcement-switch-slider::before {
    transform: translateX(19px);
}


/* Modal Footer */

.announcement-modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 17px 23px;
    border-top: 1px solid #edf0ee;
    background: #fcfdfc;
}


/* View Modal */

.announcement-view-modal {
    max-width: 600px;
}

.announcement-view-type {
    display: inline-flex;
    padding: 6px 11px;
    background: #eaf2ff;
    color: #3976d6;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 750;
    margin-bottom: 14px;
}

.announcement-view-modal .announcement-modal-body h2 {
    color: #173a30;
    font-size: 20px;
    margin: 0 0 15px;
    font-weight: 750;
}

.announcement-view-message {
    padding: 15px;
    background: #f8faf9;
    border: 1px solid #edf0ee;
    border-radius: 8px;
    color: #53625a;
    font-size: 13px;
    line-height: 1.8;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.announcement-view-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.announcement-view-grid > div {
    padding: 12px;
    background: #f8faf9;
    border-radius: 7px;
}

.announcement-view-grid span {
    display: block;
    color: #89958f;
    font-size: 10px;
    margin-bottom: 6px;
}

.announcement-view-grid strong {
    display: block;
    color: #35463e;
    font-size: 11px;
    overflow-wrap: anywhere;
}


/* Delete Modal */

.announcement-delete-modal {
    max-width: 420px;
}

.announcement-delete-content {
    text-align: center;
    padding: 35px 25px 25px;
}

.announcement-delete-icon {
    width: 62px;
    height: 62px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 17px;
    background: #fff0f1;
    color: #dc3545;
    border-radius: 50%;
    font-size: 23px;
}

.announcement-delete-content h3 {
    color: #25332f;
    font-size: 18px;
    font-weight: 750;
    margin: 0 0 10px;
}

.announcement-delete-content p {
    color: #89958f;
    font-size: 12px;
    line-height: 1.7;
    margin: 0;
}

.announcement-delete-content strong {
    color: #35463e;
}

.announcement-delete-modal .announcement-modal-footer {
    justify-content: center;
}


/* Responsive */

@media(max-width: 1100px) {

    .announcement-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media(max-width: 700px) {

    .announcement-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .announcement-header .announcement-btn {
        width: 100%;
    }

    .announcement-header h1 {
        font-size: 21px;
    }

    .announcement-stats {
        gap: 10px;
    }

    .announcement-stat-card {
        padding: 13px;
        gap: 9px;
    }

    .announcement-stat-icon {
        width: 37px;
        height: 37px;
        font-size: 15px;
    }

    .announcement-stat-card span {
        font-size: 10px;
    }

    .announcement-stat-card h3 {
        font-size: 19px;
    }

    .announcement-card-header {
        padding: 17px;
    }

    .announcement-filter-form {
        padding: 15px;
    }

    .announcement-search-box {
        flex-basis: 100%;
    }

    .announcement-filter-select {
        flex: 1;
        width: auto;
        min-width: 120px;
    }

    .announcement-filter-form .announcement-btn {
        flex: 1;
    }

    .announcement-form-grid {
        grid-template-columns: 1fr;
        gap: 0;
    }

    .announcement-modal-overlay {
        padding: 10px;
    }

    .announcement-modal-header,
    .announcement-modal-body,
    .announcement-modal-footer {
        padding-left: 16px;
        padding-right: 16px;
    }

}

@media(max-width: 420px) {

    .announcement-stats {
        grid-template-columns: 1fr;
    }

    .announcement-view-grid {
        grid-template-columns: 1fr;
    }

    .announcement-modal-footer {
        flex-wrap: wrap;
    }

    .announcement-modal-footer .announcement-btn {
        flex: 1;
    }

}

</style>

@endpush


{{-- =====================================================
     JAVASCRIPT
===================================================== --}}

@push('scripts')

<script>

/* Open / Close Modal */

function openAnnouncementModal(type) {

    const modal = document.getElementById('announcementFormModal');
    const form = document.getElementById('announcementForm');

    form.reset();

    document.getElementById('announcementMethod').innerHTML = '';

    form.action = "{{ route('super-admin.announcements.store') }}";

    document.getElementById('announcementFormTitle').textContent =
        'Create Announcement';

    document.getElementById('announcementFormSubtitle').textContent =
        'Add a new website announcement.';

    document.getElementById('announcementSaveButton').innerHTML =
        '<i class="fas fa-save"></i><span>Save Announcement</span>';

    document.getElementById('announcementDismissible').checked = true;

    modal.classList.add('active');

}


function closeAnnouncementModal(id) {

    document.getElementById(id).classList.remove('active');

}


/* Edit Announcement */

function editAnnouncement(button) {

    const id = button.dataset.id;

    const form = document.getElementById('announcementForm');

    form.reset();

    form.action = `/super-admin/announcements/${id}`;

    document.getElementById('announcementMethod').innerHTML =
        '<input type="hidden" name="_method" value="PUT">';

    document.getElementById('announcementFormTitle').textContent =
        'Edit Announcement';

    document.getElementById('announcementFormSubtitle').textContent =
        'Update announcement information.';

    document.getElementById('announcementTitle').value =
        button.dataset.title;

    document.getElementById('announcementMessage').value =
        button.dataset.message;

    document.getElementById('announcementType').value =
        button.dataset.type;

    document.getElementById('announcementStatus').value =
        button.dataset.status;

    document.getElementById('announcementDismissible').checked =
        button.dataset.dismissible === '1';

    document.getElementById('announcementStart').value =
        button.dataset.start || '';

    document.getElementById('announcementEnd').value =
        button.dataset.end || '';

    document.getElementById('announcementSaveButton').innerHTML =
        '<i class="fas fa-save"></i><span>Update Announcement</span>';

    document.getElementById('announcementFormModal').classList.add('active');

}


/* View Announcement */

async function viewAnnouncement(url) {

    const modal = document.getElementById('announcementViewModal');

    try {

        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Unable to load announcement.');
        }

        const announcement = await response.json();

        document.getElementById('viewAnnouncementTitle').textContent =
            announcement.title;

        document.getElementById('viewAnnouncementMessage').textContent =
            announcement.message;

        const typeElement = document.getElementById('viewAnnouncementType');

        typeElement.textContent = announcement.type.toUpperCase();

        const typeColors = {
            info: ['#eaf2ff', '#3976d6'],
            success: ['#e8f7ef', '#168653'],
            warning: ['#fff3e6', '#d98226'],
            danger: ['#fff0f1', '#dc3545']
        };

        const selectedColor = typeColors[announcement.type] || typeColors.info;

        typeElement.style.background = selectedColor[0];
        typeElement.style.color = selectedColor[1];

        document.getElementById('viewAnnouncementStatus').textContent =
            announcement.status.charAt(0).toUpperCase() +
            announcement.status.slice(1);

        document.getElementById('viewAnnouncementDismissible').textContent =
            announcement.is_dismissible ? 'Yes' : 'No';

        document.getElementById('viewAnnouncementStart').textContent =
            announcement.start_at || 'Immediately';

        document.getElementById('viewAnnouncementEnd').textContent =
            announcement.end_at || 'No Expiry';

        document.getElementById('viewAnnouncementCreated').textContent =
            announcement.created_at || '-';

        document.getElementById('viewAnnouncementUpdated').textContent =
            announcement.updated_at || '-';

        modal.classList.add('active');

    } catch (error) {

        alert(error.message);

    }

}


/* Delete Announcement */

function deleteAnnouncement(url, title) {

    document.getElementById('deleteAnnouncementTitle').textContent = title;

    document.getElementById('announcementDeleteForm').action = url;

    document.getElementById('announcementDeleteModal').classList.add('active');

}


/* Close Modal by Clicking Outside */

document.querySelectorAll('.announcement-modal-overlay').forEach(modal => {

    modal.addEventListener('click', function(event) {

        if (event.target === this) {
            this.classList.remove('active');
        }

    });

});


/* Close Modal with Escape */

document.addEventListener('keydown', function(event) {

    if (event.key === 'Escape') {

        document.querySelectorAll('.announcement-modal-overlay.active')
            .forEach(modal => modal.classList.remove('active'));

    }

});


/* Prevent Invalid Schedule */

document.getElementById('announcementForm').addEventListener('submit', function(event) {

    const start = document.getElementById('announcementStart').value;
    const end = document.getElementById('announcementEnd').value;

    if (start && end && end < start) {

        event.preventDefault();

        alert('End date must be after or equal to start date.');

    }

});

</script>

@endpush