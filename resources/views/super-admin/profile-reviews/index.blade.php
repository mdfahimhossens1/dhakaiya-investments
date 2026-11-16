@extends('layouts.app')

@section('content')

<style>
    .review-page {
        padding: 24px;
        color: #1e293b;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .review-header h2 {
        font-size: 25px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .review-header p {
        color: #64748b;
        margin: 0;
        font-size: 14px;
    }

    /* Statistics */

    .review-stats {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .review-stat {
        background: #fff;
        border: 1px solid #e8edf3;
        border-radius: 12px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 13px;
        box-shadow: 0 3px 12px rgba(0,0,0,.025);
    }

    .review-stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }

    .review-stat h3 {
        font-size: 23px;
        font-weight: 700;
        margin: 0;
    }

    .review-stat p {
        color: #64748b;
        font-size: 12px;
        margin: 4px 0 0;
        white-space: nowrap;
    }

    .stat-blue {
        background: #eaf2ff;
        color: #2563eb;
    }

    .stat-orange {
        background: #fff4e5;
        color: #ea8a16;
    }

    .stat-green {
        background: #e9f9ef;
        color: #16a34a;
    }

    .stat-red {
        background: #fff0f0;
        color: #dc2626;
    }

    .stat-purple {
        background: #f3e8ff;
        color: #9333ea;
    }

    /* Main Card */

    .review-card {
        background: #fff;
        border: 1px solid #e8edf3;
        border-radius: 12px;
        overflow: hidden;
    }

    .review-card-header {
        padding: 20px;
        border-bottom: 1px solid #edf0f4;
    }

    .review-card-header h4 {
        font-size: 17px;
        font-weight: 700;
        margin: 0;
    }

    .review-filters {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .review-input {
        height: 42px;
        border: 1px solid #dce3eb;
        border-radius: 8px;
        padding: 0 13px;
        font-size: 13px;
        outline: none;
        background: #fff;
        color: #334155;
    }

    .review-input:focus {
        border-color: #2563eb;
    }

    .review-search {
        flex: 1;
        min-width: 220px;
    }

    .review-btn {
        height: 42px;
        border: none;
        border-radius: 8px;
        padding: 0 17px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        transition: .2s;
    }

    .review-btn-primary {
        background: #172554;
        color: #fff;
    }

    .review-btn-primary:hover {
        background: #1e3a8a;
        color: #fff;
    }

    .review-btn-light {
        background: #f1f5f9;
        color: #475569;
    }

    .review-btn-light:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* Table */

    .review-table-wrapper {
        overflow-x: auto;
    }

    .review-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1050px;
    }

    .review-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 14px 18px;
        text-align: left;
        white-space: nowrap;
    }

    .review-table td {
        padding: 15px 18px;
        border-top: 1px solid #edf0f4;
        font-size: 13px;
        vertical-align: middle;
    }

    .review-table tbody tr:hover {
        background: #fafcff;
    }

    .review-user {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .review-avatar {
        width: 39px;
        height: 39px;
        border-radius: 50%;
        background: #eaf2ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .review-user strong {
        display: block;
        font-size: 13px;
        color: #1e293b;
    }

    .review-user small {
        color: #94a3b8;
        font-size: 11px;
    }

    /* Status Badges */

    .review-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-pending {
        background: #fff4e5;
        color: #c77a0a;
    }

    .badge-reviewed {
        background: #e9f9ef;
        color: #15803d;
    }

    .badge-needs-update {
        background: #fff0f0;
        color: #dc2626;
    }

    .badge-under-review {
        background: #eaf2ff;
        color: #2563eb;
    }

    .badge-active {
        background: #e9f9ef;
        color: #15803d;
    }

    .badge-inactive {
        background: #fff0f0;
        color: #dc2626;
    }

    .review-action-btn {
        height: 34px;
        padding: 0 12px;
        border-radius: 6px;
        border: 1px solid #dce3eb;
        background: #fff;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    .review-action-btn:hover {
        background: #172554;
        border-color: #172554;
        color: #fff;
    }

    /* Empty State */

    .review-empty {
        padding: 55px 20px;
        text-align: center;
        color: #94a3b8;
    }

    .review-empty i {
        font-size: 35px;
        margin-bottom: 12px;
    }

    .review-empty h5 {
        color: #475569;
        font-weight: 700;
    }

    /* Pagination */

    .review-pagination {
        padding: 18px;
        border-top: 1px solid #edf0f4;
    }

    /* Modal */

    .review-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(15,23,42,.60);
        padding: 20px;
        align-items: center;
        justify-content: center;
    }

    .review-modal.active {
        display: flex;
    }

    .review-modal-content {
        width: 100%;
        max-width: 620px;
        max-height: 90vh;
        overflow-y: auto;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 20px 60px rgba(0,0,0,.18);
    }

    .review-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 19px 22px;
        border-bottom: 1px solid #edf0f4;
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 2;
    }

    .review-modal-header h4 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
    }

    .review-modal-close {
        border: none;
        background: #f1f5f9;
        width: 34px;
        height: 34px;
        border-radius: 7px;
        cursor: pointer;
        color: #475569;
    }

    .review-modal-body {
        padding: 22px;
    }

    .review-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #172554;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid #edf0f4;
    }

    .review-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 17px;
    }

    .review-detail-row {
        margin-bottom: 16px;
        min-width: 0;
    }

    .review-detail-row label {
        display: block;
        font-size: 11px;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .review-detail-row p {
        margin: 0;
        font-size: 14px;
        color: #334155;
        font-weight: 500;
        overflow-wrap: anywhere;
    }

    .review-form-group {
        margin-bottom: 17px;
    }

    .review-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #334155;
    }

    .review-form-group select,
    .review-form-group textarea {
        width: 100%;
        border: 1px solid #dce3eb;
        border-radius: 8px;
        padding: 11px 13px;
        font-size: 13px;
        outline: none;
        color: #334155;
        background: #fff;
    }

    .review-form-group select:focus,
    .review-form-group textarea:focus {
        border-color: #2563eb;
    }

    .review-form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .review-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 17px;
        border-top: 1px solid #edf0f4;
    }

    .review-error {
        color: #dc2626;
        font-size: 12px;
        margin-bottom: 12px;
    }

    .review-info-box {
        padding: 13px 15px;
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #dbeafe;
        border-radius: 8px;
        font-size: 12px;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .review-notes-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        min-height: 45px;
        font-size: 13px;
        color: #475569;
        overflow-wrap: anywhere;
    }

    .review-alert {
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .review-alert-success {
        background: #e9f9ef;
        color: #15803d;
    }

    .review-alert-danger {
        background: #fff0f0;
        color: #dc2626;
    }

    /* Responsive */

    @media(max-width: 1100px) {
        .review-stats {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media(max-width: 700px) {
        .review-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .review-detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 576px) {
        .review-page {
            padding: 14px;
        }

        .review-stats {
            grid-template-columns: 1fr;
        }

        .review-filters {
            flex-direction: column;
        }

        .review-filters > * {
            width: 100%;
        }

        .review-modal {
            padding: 10px;
        }

        .review-modal-body {
            padding: 17px;
        }

        .review-modal-header {
            padding: 16px;
        }

        .review-modal-footer {
            flex-direction: column-reverse;
        }

        .review-modal-footer button {
            width: 100%;
        }
    }
</style>


<div class="review-page">

    {{-- Page Header --}}

    <div class="review-header">

        <div>
            <h2>
                <i class="fas fa-user-shield me-2"></i>
                KYC / Profile Review
            </h2>

            <p>
                Review and manage platform user profile information.
            </p>
        </div>

    </div>


    {{-- Success Message --}}

    @if(session('success'))

        <div class="review-alert review-alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>

    @endif


    {{-- Statistics --}}

    <div class="review-stats">

        <div class="review-stat">

            <div class="review-stat-icon stat-blue">
                <i class="fas fa-users"></i>
            </div>

            <div>
                <h3>{{ number_format($totalUsers) }}</h3>
                <p>Total Users</p>
            </div>

        </div>


        <div class="review-stat">

            <div class="review-stat-icon stat-orange">
                <i class="fas fa-clock"></i>
            </div>

            <div>
                <h3>{{ number_format($pendingUsers) }}</h3>
                <p>Pending</p>
            </div>

        </div>


        <div class="review-stat">

            <div class="review-stat-icon stat-purple">
                <i class="fas fa-spinner"></i>
            </div>

            <div>
                <h3>{{ number_format($underReviewUsers) }}</h3>
                <p>Under Review</p>
            </div>

        </div>


        <div class="review-stat">

            <div class="review-stat-icon stat-green">
                <i class="fas fa-check-circle"></i>
            </div>

            <div>
                <h3>{{ number_format($reviewedUsers) }}</h3>
                <p>Reviewed</p>
            </div>

        </div>


        <div class="review-stat">

            <div class="review-stat-icon stat-red">
                <i class="fas fa-edit"></i>
            </div>

            <div>
                <h3>{{ number_format($needsUpdateUsers) }}</h3>
                <p>Needs Update</p>
            </div>

        </div>

    </div>


    {{-- Main Card --}}

    <div class="review-card">

        <div class="review-card-header">

            <h4>
                <i class="fas fa-user-check me-2"></i>
                User Profile Reviews
            </h4>


            {{-- Search & Filters --}}

            <form method="GET"
                  action="{{ route('super-admin.profile-reviews.index') }}"
                  class="review-filters">

                <input
                    type="text"
                    name="search"
                    class="review-input review-search"
                    placeholder="Search name, phone or email..."
                    value="{{ request('search') }}"
                >


                <select name="status" class="review-input">

                    <option value="">All Review Status</option>

                    <option value="pending"
                        @selected(request('status') === 'pending')>
                        Pending
                    </option>

                    <option value="under_review"
                        @selected(request('status') === 'under_review')>
                        Under Review
                    </option>

                    <option value="reviewed"
                        @selected(request('status') === 'reviewed')>
                        Reviewed
                    </option>

                    <option value="needs_update"
                        @selected(request('status') === 'needs_update')>
                        Needs Update
                    </option>

                </select>


                <button type="submit" class="review-btn review-btn-primary">

                    <i class="fas fa-search"></i>
                    Filter

                </button>


                <a href="{{ route('super-admin.profile-reviews.index') }}"
                   class="review-btn review-btn-light">

                    <i class="fas fa-redo"></i>
                    Reset

                </a>

            </form>

        </div>


        {{-- Table --}}

        <div class="review-table-wrapper">

            <table class="review-table">

                <thead>

                    <tr>
                        <th>User Information</th>
                        <th>Phone</th>
                        <th>Country</th>
                        <th>Account Status</th>
                        <th>Registration Date</th>
                        <th>Review Status</th>
                        <th>Last Reviewed</th>
                        <th>Action</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($users as $user)

                        @php

                            $review = $user->profileReview;

                            $status = $review?->status ?? 'pending';

                            $badgeClass = match($status) {

                                'reviewed' => 'badge-reviewed',

                                'under_review' => 'badge-under-review',

                                'needs_update' => 'badge-needs-update',

                                default => 'badge-pending',

                            };

                            $statusLabel = match($status) {

                                'reviewed' => 'Reviewed',

                                'under_review' => 'Under Review',

                                'needs_update' => 'Needs Update',

                                default => 'Pending',

                            };

                            $accountStatusClass = $user->status === 'active'
                                ? 'badge-active'
                                : 'badge-inactive';

                        @endphp


                        <tr>

                            {{-- User Information --}}

                            <td>

                                <div class="review-user">

                                    <div class="review-avatar">

                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}

                                    </div>

                                    <div>

                                        <strong>{{ $user->name }}</strong>

                                        <small>
                                            {{ $user->email ?? 'No email provided' }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- Phone --}}

                            <td>
                                {{ $user->phone ?? 'N/A' }}
                            </td>


                            {{-- Country --}}

                            <td>
                                {{ $user->country ?? 'N/A' }}
                            </td>


                            {{-- Account Status --}}

                            <td>

                                <span class="review-badge {{ $accountStatusClass }}">

                                    <i class="fas fa-circle"
                                       style="font-size:6px"></i>

                                    {{ ucfirst($user->status ?? 'Inactive') }}

                                </span>

                            </td>


                            {{-- Registration Date --}}

                            <td>
                                {{ $user->created_at?->format('d M Y') ?? 'N/A' }}
                            </td>


                            {{-- Review Status --}}

                            <td>

                                <span class="review-badge {{ $badgeClass }}">

                                    <i class="fas fa-circle"
                                       style="font-size:6px"></i>

                                    {{ $statusLabel }}

                                </span>

                            </td>


                            {{-- Last Reviewed --}}

                            <td>

                                {{ $review?->reviewed_at?->format('d M Y') ?? 'Not reviewed' }}

                            </td>


                            {{-- Action --}}

                            <td>

                                <button
                                    type="button"
                                    class="review-action-btn"
                                    data-show-url="{{ route('super-admin.profile-reviews.show', $user->id) }}"
                                    data-update-url="{{ route('super-admin.profile-reviews.update', $user->id) }}"
                                    onclick="openProfileReview(this)"
                                >

                                    <i class="fas fa-eye me-1"></i>
                                    Review

                                </button>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="8">

                                <div class="review-empty">

                                    <i class="fas fa-users"></i>

                                    <h5>No Users Found</h5>

                                    <p>
                                        There are no user profiles matching your search.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}

        @if($users->hasPages())

            <div class="review-pagination">

                {{ $users->links() }}

            </div>

        @endif

    </div>

</div>



{{-- =========================================
     PROFILE REVIEW MODAL
========================================= --}}

<div class="review-modal" id="profileReviewModal">

    <div class="review-modal-content">


        {{-- Modal Header --}}

        <div class="review-modal-header">

            <h4>
                <i class="fas fa-user-shield me-2"></i>
                Profile Review Details
            </h4>

            <button
                type="button"
                class="review-modal-close"
                onclick="closeProfileReview()"
            >

                <i class="fas fa-times"></i>

            </button>

        </div>


        {{-- Modal Body --}}

        <div class="review-modal-body">


            {{-- Loading --}}

            <div id="reviewLoading"
                 style="text-align:center;padding:30px;">

                <i class="fas fa-spinner fa-spin"></i>

                Loading profile information...

            </div>


            {{-- Details --}}

            <div id="reviewDetails" style="display:none;">


                {{-- Information Notice --}}

                <div class="review-info-box">

                    <i class="fas fa-info-circle me-1"></i>

                    Review the user's available profile information and update the review status below.

                </div>


                {{-- User Information --}}

                <div class="review-section-title">

                    <i class="fas fa-user me-2"></i>

                    User Information

                </div>


                <div class="review-detail-grid">


                    <div class="review-detail-row">

                        <label>Full Name</label>

                        <p id="detailName">N/A</p>

                    </div>


                    <div class="review-detail-row">

                        <label>Email Address</label>

                        <p id="detailEmail">N/A</p>

                    </div>


                    <div class="review-detail-row">

                        <label>Phone Number</label>

                        <p id="detailPhone">N/A</p>

                    </div>


                    <div class="review-detail-row">

                        <label>Country</label>

                        <p id="detailCountry">N/A</p>

                    </div>


                    <div class="review-detail-row">

                        <label>Account Status</label>

                        <p id="detailAccountStatus">N/A</p>

                    </div>


                    <div class="review-detail-row">

                        <label>Registration Date</label>

                        <p id="detailCreated">N/A</p>

                    </div>

                </div>


                {{-- Previous Review Information --}}

                <div class="review-section-title mt-3">

                    <i class="fas fa-history me-2"></i>

                    Review Information

                </div>


                <div class="review-detail-grid">


                    <div class="review-detail-row">

                        <label>Last Reviewer</label>

                        <p id="detailReviewer">Not reviewed</p>

                    </div>


                    <div class="review-detail-row">

                        <label>Last Reviewed Date</label>

                        <p id="detailReviewedAt">Not reviewed</p>

                    </div>

                </div>


                <div class="review-detail-row">

                    <label>Previous Review Notes</label>

                    <div class="review-notes-box"
                         id="detailNotes">

                        No review notes.

                    </div>

                </div>


                <hr>


                {{-- Review Form --}}

                <div class="review-section-title">

                    <i class="fas fa-clipboard-check me-2"></i>

                    Update Profile Review

                </div>


                <form
                    id="profileReviewForm"
                    method="POST"
                >

                    @csrf

                    @method('PUT')


                    {{-- Review Status --}}

                    <div class="review-form-group">

                        <label for="reviewStatus">

                            Review Status

                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="status"
                            id="reviewStatus"
                            required
                        >

                            <option value="pending">
                                Pending
                            </option>

                            <option value="under_review">
                                Under Review
                            </option>

                            <option value="reviewed">
                                Reviewed
                            </option>

                            <option value="needs_update">
                                Needs Update
                            </option>

                        </select>

                    </div>


                    {{-- Review Notes --}}

                    <div class="review-form-group">

                        <label for="reviewNotes">
                            Review Notes
                        </label>


                        <textarea
                            name="review_notes"
                            id="reviewNotes"
                            rows="4"
                            maxlength="2000"
                            placeholder="Write review notes or profile update instructions..."
                        ></textarea>


                        <small class="text-muted">
                            Maximum 2000 characters.
                        </small>

                    </div>


                    {{-- Errors --}}

                    <div id="reviewFormErrors"
                         class="review-error">

                    </div>


                    {{-- Footer --}}

                    <div class="review-modal-footer">

                        <button
                            type="button"
                            class="review-btn review-btn-light"
                            onclick="closeProfileReview()"
                        >

                            Cancel

                        </button>


                        <button
                            type="submit"
                            class="review-btn review-btn-primary"
                            id="saveReviewButton"
                        >

                            <i class="fas fa-save"></i>

                            Save Review

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>



{{-- =========================================
     JAVASCRIPT
========================================= --}}

<script>

    let reviewRequestController = null;


    /**
     * Open Profile Review Modal
     */
    function openProfileReview(button) {

        const modal = document.getElementById('profileReviewModal');

        const loading = document.getElementById('reviewLoading');

        const details = document.getElementById('reviewDetails');

        const form = document.getElementById('profileReviewForm');

        const saveButton = document.getElementById('saveReviewButton');


        // Cancel previous request if any
        if (reviewRequestController) {
            reviewRequestController.abort();
        }

        reviewRequestController = new AbortController();


        // Open Modal
        modal.classList.add('active');

        document.body.style.overflow = 'hidden';


        // Loading State
        loading.style.display = 'block';

        loading.innerHTML = `
            <i class="fas fa-spinner fa-spin"></i>
            Loading profile information...
        `;

        details.style.display = 'none';


        // Reset Form
        form.reset();

        form.action = button.dataset.updateUrl;

        document.getElementById('reviewFormErrors').textContent = '';


        saveButton.disabled = false;

        saveButton.innerHTML = `
            <i class="fas fa-save"></i>
            Save Review
        `;


        // Fetch User Information
        fetch(button.dataset.showUrl, {

            method: 'GET',

            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },

            signal: reviewRequestController.signal

        })

        .then(response => {

            if (!response.ok) {

                throw new Error('Unable to load profile information.');

            }

            return response.json();

        })

        .then(data => {

            const user = data.user;

            const review = data.review;


            // User Information

            document.getElementById('detailName').textContent =
                user.name || 'N/A';


            document.getElementById('detailEmail').textContent =
                user.email || 'N/A';


            document.getElementById('detailPhone').textContent =
                user.phone || 'N/A';


            document.getElementById('detailCountry').textContent =
                user.country || 'N/A';


            document.getElementById('detailAccountStatus').textContent =
                user.status
                    ? user.status.charAt(0).toUpperCase() + user.status.slice(1)
                    : 'N/A';


            document.getElementById('detailCreated').textContent =
                user.created_at || 'N/A';


            // Review Information

            document.getElementById('detailReviewer').textContent =
                review?.reviewer || 'Not reviewed';


            document.getElementById('detailReviewedAt').textContent =
                review?.reviewed_at || 'Not reviewed';


            document.getElementById('detailNotes').textContent =
                review?.review_notes || 'No review notes.';


            // Review Form

            document.getElementById('reviewStatus').value =
                review?.status || 'pending';


            document.getElementById('reviewNotes').value =
                review?.review_notes || '';


            // Show Details

            loading.style.display = 'none';

            details.style.display = 'block';

        })

        .catch(error => {

            if (error.name === 'AbortError') {
                return;
            }

            loading.innerHTML = `
                <div style="color:#dc2626;">

                    <i class="fas fa-exclamation-circle"></i>

                    ${error.message}

                </div>
            `;

        });

    }


    /**
     * Close Modal
     */
    function closeProfileReview() {

        const modal = document.getElementById('profileReviewModal');

        modal.classList.remove('active');

        document.body.style.overflow = '';


        if (reviewRequestController) {

            reviewRequestController.abort();

            reviewRequestController = null;

        }

    }


    /**
     * Close Modal By Outside Click
     */
    document.getElementById('profileReviewModal')
        .addEventListener('click', function(e) {

            if (e.target === this) {

                closeProfileReview();

            }

        });


    /**
     * Close Modal By Escape
     */
    document.addEventListener('keydown', function(e) {

        if (e.key === 'Escape') {

            closeProfileReview();

        }

    });


    /**
     * Prevent Multiple Form Submission
     */
    document.getElementById('profileReviewForm')
        .addEventListener('submit', function() {

            const button = document.getElementById('saveReviewButton');

            button.disabled = true;

            button.innerHTML = `
                <i class="fas fa-spinner fa-spin"></i>
                Saving...
            `;

        });

</script>

@endsection