@extends('layouts.app')

@section('title', 'Banner Management')

@section('content')

<div class="banner-management-page">

    {{-- Page Header --}}
    <div class="bm-page-header">
        <div>
            <div class="bm-breadcrumb">
                <span>Website Content</span>
                <i class="fa-solid fa-chevron-right"></i>
                <span class="active">Banner Management</span>
            </div>

            <h1>Banner Management</h1>
            <p>Manage and organize your website banners.</p>
        </div>

        <button type="button" class="bm-primary-btn" id="openCreateModal">
            <i class="fa-solid fa-plus"></i>
            Add New Banner
        </button>
    </div>

    {{-- Statistics --}}
    <div class="bm-stats-grid">

        <div class="bm-stat-card">
            <div class="bm-stat-icon total">
                <i class="fa-solid fa-images"></i>
            </div>
            <div>
                <span>Total Banners</span>
                <h3>{{ $totalBanners ?? 0 }}</h3>
            </div>
        </div>

        <div class="bm-stat-card">
            <div class="bm-stat-icon active">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span>Active Banners</span>
                <h3>{{ $activeBanners ?? 0 }}</h3>
            </div>
        </div>

        <div class="bm-stat-card">
            <div class="bm-stat-icon inactive">
                <i class="fa-solid fa-circle-pause"></i>
            </div>
            <div>
                <span>Inactive Banners</span>
                <h3>{{ $inactiveBanners ?? 0 }}</h3>
            </div>
        </div>

    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bm-alert bm-alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="bm-alert-close">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bm-alert bm-alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="bm-alert-close">&times;</button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="bm-alert bm-alert-error">
            <div>
                <strong>Please check the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="bm-alert-close">&times;</button>
        </div>
    @endif

    {{-- Main Content --}}
    <div class="bm-content-card">

        <div class="bm-card-header">
            <div>
                <h2>Website Banners</h2>
                <p>View, edit and manage all your banners.</p>
            </div>

            <div class="bm-card-header-right">
                <span class="bm-record-count">
                    {{ $banners->total() }} Records
                </span>
            </div>
        </div>

        {{-- Table --}}
        <div class="bm-table-wrapper">

            <table class="bm-table">
                <thead>
                    <tr>
                        <th>Banner</th>
                        <th>Title & Description</th>
                        <th>Button</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="bm-text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($banners as $banner)

                        <tr>

                            {{-- Image --}}
                            <td>
                                <div class="bm-banner-thumbnail">
                                    <img
                                        src="{{ asset('storage/' . $banner->image) }}"
                                        alt="{{ $banner->title }}"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                    >

                                    <div class="bm-image-fallback">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                </div>
                            </td>

                            {{-- Details --}}
                            <td>
                                <div class="bm-banner-info">
                                    <strong>{{ $banner->title }}</strong>

                                    @if($banner->subtitle)
                                        <span>{{ $banner->subtitle }}</span>
                                    @endif

                                    @if($banner->description)
                                        <small>
                                            {{ \Illuminate\Support\Str::limit($banner->description, 65) }}
                                        </small>
                                    @endif
                                </div>
                            </td>

                            {{-- Button --}}
                            <td>
                                @if($banner->button_text)
                                    <div class="bm-button-info">
                                        <span>{{ $banner->button_text }}</span>

                                        @if($banner->button_url)
                                            <small>
                                                <i class="fa-solid fa-link"></i>
                                                Link configured
                                            </small>
                                        @endif
                                    </div>
                                @else
                                    <span class="bm-empty-value">No button</span>
                                @endif
                            </td>

                            {{-- Position --}}
                            <td>
                                <span class="bm-position">
                                    {{ $banner->sort_order }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($banner->status === 'active')
                                    <span class="bm-status active">
                                        <span></span>
                                        Active
                                    </span>
                                @else
                                    <span class="bm-status inactive">
                                        <span></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Date --}}
                            <td>
                                <div class="bm-date">
                                    {{ $banner->created_at?->format('d M Y') }}
                                    <small>
                                        {{ $banner->created_at?->format('h:i A') }}
                                    </small>
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="bm-actions">

                                    <button
                                        type="button"
                                        class="bm-action-btn view"
                                        title="View Banner"
                                        data-view-url="{{ route('super-admin.banners.show', $banner->id) }}"
                                    >
                                        <i class="fa-regular fa-eye"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="bm-action-btn edit"
                                        title="Edit Banner"

                                        data-id="{{ $banner->id }}"
                                        data-title="{{ $banner->title }}"
                                        data-subtitle="{{ $banner->subtitle }}"
                                        data-description="{{ $banner->description }}"
                                        data-image="{{ asset('storage/' . $banner->image) }}"
                                        data-button-text="{{ $banner->button_text }}"
                                        data-button-url="{{ $banner->button_url }}"
                                        data-status="{{ $banner->status }}"
                                        data-sort-order="{{ $banner->sort_order }}"
                                    >
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="bm-action-btn delete"
                                        title="Delete Banner"
                                        data-id="{{ $banner->id }}"
                                        data-title="{{ $banner->title }}"
                                    >
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">
                                <div class="bm-empty-state">
                                    <div class="bm-empty-icon">
                                        <i class="fa-regular fa-images"></i>
                                    </div>

                                    <h3>No Banners Found</h3>

                                    <p>
                                        You haven't created any banners yet.
                                        Start by adding your first banner.
                                    </p>

                                    <button
                                        type="button"
                                        class="bm-primary-btn"
                                        id="emptyCreateButton"
                                    >
                                        <i class="fa-solid fa-plus"></i>
                                        Create First Banner
                                    </button>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

        {{-- Pagination --}}
        @if($banners->hasPages())
            <div class="bm-pagination">
                {{ $banners->links() }}
            </div>
        @endif

    </div>

</div>


{{-- =========================================================
    CREATE / EDIT MODAL
========================================================= --}}

<div class="bm-modal-overlay" id="bannerFormModal">

    <div class="bm-modal bm-modal-large">

        <div class="bm-modal-header">
            <div>
                <h2 id="bannerModalTitle">Add New Banner</h2>
                <p id="bannerModalSubtitle">
                    Create a new website banner.
                </p>
            </div>

            <button type="button" class="bm-modal-close" data-close-modal>
                &times;
            </button>
        </div>

        <form
            id="bannerForm"
            action="{{ route('super-admin.banners.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            <div id="bannerMethodField"></div>

            <div class="bm-modal-body">

                <div class="bm-form-grid">

                    {{-- Title --}}
                    <div class="bm-form-group bm-full">
                        <label>
                            Banner Title <span>*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            id="bannerTitle"
                            placeholder="Enter banner title"
                            required
                            maxlength="255"
                        >
                    </div>

                    {{-- Subtitle --}}
                    <div class="bm-form-group bm-full">
                        <label>Subtitle</label>

                        <input
                            type="text"
                            name="subtitle"
                            id="bannerSubtitle"
                            placeholder="Enter banner subtitle"
                        >
                    </div>

                    {{-- Description --}}
                    <div class="bm-form-group bm-full">
                        <label>Description</label>

                        <textarea
                            name="description"
                            id="bannerDescription"
                            rows="4"
                            placeholder="Write banner description..."
                        ></textarea>
                    </div>

                    {{-- Image --}}
                    <div class="bm-form-group bm-full">

                        <label>
                            Banner Image
                            <span id="imageRequiredMark">*</span>
                        </label>

                        <div class="bm-upload-box" id="bannerUploadBox">

                            <input
                                type="file"
                                name="image"
                                id="bannerImage"
                                accept=".jpg,.jpeg,.png,.webp"
                            >

                            <div class="bm-upload-content" id="uploadPlaceholder">
                                <div class="bm-upload-icon">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>

                                <strong>Click to upload banner image</strong>

                                <span>
                                    JPG, JPEG, PNG or WEBP · Maximum 5MB
                                </span>
                            </div>

                            <div class="bm-upload-preview" id="uploadPreview">
                                <img src="" id="bannerImagePreview" alt="Preview">

                                <button
                                    type="button"
                                    id="removeBannerImage"
                                    title="Remove image preview"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                        </div>

                        <div class="bm-existing-image" id="existingImageBox">
                            <span>Current image:</span>
                            <img src="" id="existingBannerImage" alt="Current banner">
                        </div>

                    </div>

                    {{-- Button Text --}}
                    <div class="bm-form-group">
                        <label>Button Text</label>

                        <input
                            type="text"
                            name="button_text"
                            id="bannerButtonText"
                            placeholder="Example: Learn More"
                        >
                    </div>

                    {{-- Button URL --}}
                    <div class="bm-form-group">
                        <label>Button URL</label>

                        <input
                            type="url"
                            name="button_url"
                            id="bannerButtonUrl"
                            placeholder="https://example.com"
                        >
                    </div>

                    {{-- Status --}}
                    <div class="bm-form-group">
                        <label>Status <span>*</span></label>

                        <select name="status" id="bannerStatus" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    {{-- Sort Order --}}
                    <div class="bm-form-group">
                        <label>Display Position</label>

                        <input
                            type="number"
                            name="sort_order"
                            id="bannerSortOrder"
                            min="0"
                            value="0"
                        >
                    </div>

                </div>

            </div>

            <div class="bm-modal-footer">

                <button
                    type="button"
                    class="bm-secondary-btn"
                    data-close-modal
                >
                    Cancel
                </button>

                <button type="submit" class="bm-primary-btn" id="bannerSubmitButton">
                    <i class="fa-solid fa-check"></i>
                    <span>Save Banner</span>
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
    VIEW MODAL
========================================================= --}}

<div class="bm-modal-overlay" id="bannerViewModal">

    <div class="bm-modal">

        <div class="bm-modal-header">
            <div>
                <h2>Banner Details</h2>
                <p>View complete banner information.</p>
            </div>

            <button type="button" class="bm-modal-close" data-close-modal>
                &times;
            </button>
        </div>

        <div class="bm-modal-body">

            <div class="bm-view-image">
                <img src="" id="viewBannerImage" alt="Banner">
            </div>

            <div class="bm-view-details">

                <div class="bm-view-item">
                    <span>Title</span>
                    <strong id="viewBannerTitle">-</strong>
                </div>

                <div class="bm-view-item">
                    <span>Subtitle</span>
                    <strong id="viewBannerSubtitle">-</strong>
                </div>

                <div class="bm-view-item bm-view-full">
                    <span>Description</span>
                    <p id="viewBannerDescription">-</p>
                </div>

                <div class="bm-view-item">
                    <span>Button Text</span>
                    <strong id="viewBannerButtonText">-</strong>
                </div>

                <div class="bm-view-item">
                    <span>Button URL</span>
                    <strong id="viewBannerButtonUrl">-</strong>
                </div>

                <div class="bm-view-item">
                    <span>Status</span>
                    <strong id="viewBannerStatus">-</strong>
                </div>

                <div class="bm-view-item">
                    <span>Display Position</span>
                    <strong id="viewBannerSortOrder">-</strong>
                </div>

                <div class="bm-view-item">
                    <span>Created At</span>
                    <strong id="viewBannerCreatedAt">-</strong>
                </div>

            </div>

        </div>

        <div class="bm-modal-footer">
            <button type="button" class="bm-secondary-btn" data-close-modal>
                Close
            </button>
        </div>

    </div>

</div>


{{-- =========================================================
    DELETE MODAL
========================================================= --}}

<div class="bm-modal-overlay" id="bannerDeleteModal">

    <div class="bm-modal bm-delete-modal">

        <div class="bm-delete-icon">
            <i class="fa-regular fa-trash-can"></i>
        </div>

        <h2>Delete Banner?</h2>

        <p>
            Are you sure you want to delete
            <strong id="deleteBannerTitle"></strong>?
            This action cannot be undone.
        </p>

        <form id="bannerDeleteForm" method="POST">

            @csrf
            @method('DELETE')

            <div class="bm-modal-footer">
                <button
                    type="button"
                    class="bm-secondary-btn"
                    data-close-modal
                >
                    Cancel
                </button>

                <button type="submit" class="bm-danger-btn">
                    <i class="fa-solid fa-trash"></i>
                    Delete Banner
                </button>
            </div>

        </form>

    </div>

</div>


@endsection


{{-- =========================================================
    PAGE CSS
========================================================= --}}

@push('styles')

<style>

.banner-management-page {
    width: 100%;
    color: #1e293b;
    font-family: 'Inter', sans-serif;
}

/* Header */

.banner-management-page .bm-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 26px;
}

.banner-management-page .bm-breadcrumb {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    color: #94a3b8;
    margin-bottom: 10px;
}

.banner-management-page .bm-breadcrumb i {
    font-size: 9px;
}

.banner-management-page .bm-breadcrumb .active {
    color: #087f5b;
    font-weight: 600;
}

.banner-management-page .bm-page-header h1 {
    font-size: 25px;
    font-weight: 800;
    margin: 0 0 7px;
    color: #172b25;
}

.banner-management-page .bm-page-header p {
    font-size: 13px;
    color: #84919f;
    margin: 0;
}

/* Buttons */

.banner-management-page .bm-primary-btn,
.banner-management-page .bm-secondary-btn,
.banner-management-page .bm-danger-btn {
    border: none;
    border-radius: 9px;
    padding: 11px 17px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: .2s;
    font-family: inherit;
}

.banner-management-page .bm-primary-btn {
    background: #087f5b;
    color: #fff;
}

.banner-management-page .bm-primary-btn:hover {
    background: #066b4c;
    color: #fff;
}

.banner-management-page .bm-secondary-btn {
    background: #f1f5f9;
    color: #475569;
}

.banner-management-page .bm-secondary-btn:hover {
    background: #e2e8f0;
}

.banner-management-page .bm-danger-btn {
    background: #dc3545;
    color: #fff;
}

.banner-management-page .bm-danger-btn:hover {
    background: #bd2635;
}

/* Statistics */

.banner-management-page .bm-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 24px;
}

.banner-management-page .bm-stat-card {
    display: flex;
    align-items: center;
    gap: 15px;
    background: #fff;
    padding: 20px;
    border: 1px solid #e8edf0;
    border-radius: 13px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .025);
}

.banner-management-page .bm-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
    flex-shrink: 0;
}

.banner-management-page .bm-stat-icon.total {
    color: #2563eb;
    background: #eff6ff;
}

.banner-management-page .bm-stat-icon.active {
    color: #087f5b;
    background: #e8f7f0;
}

.banner-management-page .bm-stat-icon.inactive {
    color: #d97706;
    background: #fff7ed;
}

.banner-management-page .bm-stat-card span {
    color: #84919f;
    font-size: 12px;
    display: block;
    margin-bottom: 5px;
}

.banner-management-page .bm-stat-card h3 {
    font-size: 23px;
    font-weight: 800;
    margin: 0;
    color: #1e293b;
}

/* Alerts */

.banner-management-page .bm-alert {
    padding: 14px 16px;
    border-radius: 9px;
    margin-bottom: 18px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    font-size: 13px;
}

.banner-management-page .bm-alert-success {
    background: #eaf8f0;
    color: #087f5b;
    border: 1px solid #c6edda;
}

.banner-management-page .bm-alert-error {
    background: #fff0f0;
    color: #bd2635;
    border: 1px solid #ffd4d4;
}

.banner-management-page .bm-alert ul {
    padding-left: 20px;
    margin: 8px 0 0;
}

.banner-management-page .bm-alert-close {
    background: transparent;
    border: none;
    font-size: 21px;
    cursor: pointer;
    color: inherit;
}

/* Content Card */

.banner-management-page .bm-content-card {
    background: #fff;
    border: 1px solid #e8edf0;
    border-radius: 13px;
    overflow: hidden;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .025);
}

.banner-management-page .bm-card-header {
    padding: 20px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    border-bottom: 1px solid #edf1f3;
}

.banner-management-page .bm-card-header h2 {
    font-size: 16px;
    font-weight: 800;
    margin: 0 0 5px;
    color: #1e293b;
}

.banner-management-page .bm-card-header p {
    color: #94a3b8;
    font-size: 12px;
    margin: 0;
}

.banner-management-page .bm-record-count {
    padding: 7px 11px;
    background: #f0fdf4;
    color: #087f5b;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 700;
}

/* Table */

.banner-management-page .bm-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.banner-management-page .bm-table {
    width: 100%;
    min-width: 850px;
    border-collapse: collapse;
}

.banner-management-page .bm-table thead {
    background: #f8fafb;
}

.banner-management-page .bm-table th {
    padding: 14px 16px;
    color: #84919f;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
    text-align: left;
    white-space: nowrap;
    border-bottom: 1px solid #edf1f3;
}

.banner-management-page .bm-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f0f2f4;
    vertical-align: middle;
}

.banner-management-page .bm-table tbody tr:last-child td {
    border-bottom: none;
}

.banner-management-page .bm-table tbody tr:hover {
    background: #fbfdfc;
}

.banner-management-page .bm-text-right {
    text-align: right !important;
}

/* Thumbnail */

.banner-management-page .bm-banner-thumbnail {
    width: 90px;
    height: 57px;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    background: #f1f5f9;
}

.banner-management-page .bm-banner-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.banner-management-page .bm-image-fallback {
    display: none;
    position: absolute;
    inset: 0;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 20px;
}

/* Table Details */

.banner-management-page .bm-banner-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
    max-width: 230px;
}

.banner-management-page .bm-banner-info strong {
    font-size: 12px;
    color: #253b33;
    font-weight: 700;
}

.banner-management-page .bm-banner-info span {
    color: #64748b;
    font-size: 11px;
}

.banner-management-page .bm-banner-info small {
    color: #94a3b8;
    font-size: 10px;
    line-height: 1.5;
}

.banner-management-page .bm-button-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.banner-management-page .bm-button-info span {
    color: #475569;
    font-size: 11px;
    font-weight: 700;
}

.banner-management-page .bm-button-info small {
    color: #94a3b8;
    font-size: 10px;
}

.banner-management-page .bm-empty-value {
    font-size: 11px;
    color: #a1aab5;
}

.banner-management-page .bm-position {
    background: #f1f5f9;
    color: #475569;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
}

/* Status */

.banner-management-page .bm-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
}

.banner-management-page .bm-status span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.banner-management-page .bm-status.active {
    background: #eaf8f0;
    color: #087f5b;
}

.banner-management-page .bm-status.active span {
    background: #087f5b;
}

.banner-management-page .bm-status.inactive {
    background: #fff1f2;
    color: #dc3545;
}

.banner-management-page .bm-status.inactive span {
    background: #dc3545;
}

/* Date */

.banner-management-page .bm-date {
    font-size: 11px;
    color: #475569;
    white-space: nowrap;
}

.banner-management-page .bm-date small {
    display: block;
    margin-top: 4px;
    color: #94a3b8;
    font-size: 10px;
}

/* Actions */

.banner-management-page .bm-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.banner-management-page .bm-action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 7px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: .2s;
}

.banner-management-page .bm-action-btn.view {
    background: #eff6ff;
    color: #2563eb;
}

.banner-management-page .bm-action-btn.edit {
    background: #fff7ed;
    color: #d97706;
}

.banner-management-page .bm-action-btn.delete {
    background: #fff1f2;
    color: #dc3545;
}

.banner-management-page .bm-action-btn:hover {
    transform: translateY(-2px);
    filter: brightness(.96);
}

/* Empty State */

.banner-management-page .bm-empty-state {
    text-align: center;
    padding: 55px 20px;
}

.banner-management-page .bm-empty-icon {
    width: 65px;
    height: 65px;
    background: #effaf5;
    color: #087f5b;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    margin: 0 auto 17px;
}

.banner-management-page .bm-empty-state h3 {
    font-size: 16px;
    font-weight: 800;
    margin: 0 0 8px;
}

.banner-management-page .bm-empty-state p {
    font-size: 12px;
    color: #94a3b8;
    margin: 0 0 18px;
}

/* Pagination */

.banner-management-page .bm-pagination {
    padding: 18px 22px;
    border-top: 1px solid #edf1f3;
}

.banner-management-page .bm-pagination nav {
    display: flex;
    justify-content: flex-end;
}

/* Modal */

.bm-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: rgba(15, 23, 42, .58);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto;
}

.bm-modal-overlay.bm-modal-open {
    display: flex;
}

.bm-modal {
    width: 100%;
    max-width: 570px;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
    animation: bmModalIn .2s ease;
}

.bm-modal-large {
    max-width: 720px;
}

@keyframes bmModalIn {
    from {
        opacity: 0;
        transform: translateY(12px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bm-modal-header {
    padding: 20px 23px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    border-bottom: 1px solid #edf1f3;
}

.bm-modal-header h2 {
    font-size: 17px;
    font-weight: 800;
    margin: 0 0 6px;
    color: #1e293b;
}

.bm-modal-header p {
    font-size: 11px;
    color: #94a3b8;
    margin: 0;
}

.bm-modal-close {
    width: 32px;
    height: 32px;
    background: #f1f5f9;
    color: #64748b;
    border: none;
    border-radius: 8px;
    font-size: 22px;
    cursor: pointer;
    line-height: 1;
}

.bm-modal-body {
    padding: 22px 23px;
}

.bm-modal-footer {
    padding: 16px 23px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    border-top: 1px solid #edf1f3;
}

/* Form */

.bm-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.bm-form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.bm-form-group.bm-full {
    grid-column: 1 / -1;
}

.bm-form-group label {
    font-size: 12px;
    font-weight: 700;
    color: #334155;
}

.bm-form-group label span {
    color: #dc3545;
}

.bm-form-group input,
.bm-form-group textarea,
.bm-form-group select {
    width: 100%;
    border: 1px solid #dfe6eb;
    border-radius: 8px;
    padding: 11px 12px;
    font-size: 12px;
    font-family: inherit;
    color: #334155;
    background: #fff;
    outline: none;
    transition: .2s;
}

.bm-form-group input:focus,
.bm-form-group textarea:focus,
.bm-form-group select:focus {
    border-color: #087f5b;
    box-shadow: 0 0 0 3px rgba(8, 127, 91, .09);
}

.bm-form-group textarea {
    resize: vertical;
    min-height: 95px;
}

/* Upload */

.bm-upload-box {
    position: relative;
    min-height: 145px;
    border: 2px dashed #d8e4dd;
    border-radius: 10px;
    background: #fbfefc;
    overflow: hidden;
}

.bm-upload-box > input {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.bm-upload-content {
    min-height: 145px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 8px;
    pointer-events: none;
}

.bm-upload-icon {
    font-size: 25px;
    color: #087f5b;
}

.bm-upload-content strong {
    font-size: 12px;
    color: #334155;
}

.bm-upload-content span {
    font-size: 10px;
    color: #94a3b8;
}

.bm-upload-preview {
    display: none;
    position: relative;
    padding: 10px;
}

.bm-upload-preview img {
    width: 100%;
    max-height: 190px;
    object-fit: contain;
    display: block;
    border-radius: 7px;
}

.bm-upload-preview button {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 29px;
    height: 29px;
    border: none;
    border-radius: 50%;
    background: #dc3545;
    color: #fff;
    cursor: pointer;
    z-index: 4;
}

.bm-existing-image {
    display: none;
    align-items: center;
    gap: 12px;
    margin-top: 12px;
    font-size: 11px;
    color: #64748b;
}

.bm-existing-image img {
    width: 90px;
    height: 55px;
    border-radius: 6px;
    object-fit: cover;
}

/* View Details */

.bm-view-image {
    width: 100%;
    height: 200px;
    background: #f1f5f9;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px;
}

.bm-view-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.bm-view-details {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px;
}

.bm-view-item {
    padding: 12px;
    background: #f8fafb;
    border-radius: 8px;
    overflow-wrap: anywhere;
}

.bm-view-item span {
    display: block;
    font-size: 10px;
    color: #94a3b8;
    margin-bottom: 6px;
}

.bm-view-item strong,
.bm-view-item p {
    font-size: 12px;
    color: #334155;
    margin: 0;
}

.bm-view-full {
    grid-column: 1 / -1;
}

/* Delete Modal */

.bm-delete-modal {
    max-width: 420px;
    padding-top: 28px;
    text-align: center;
}

.bm-delete-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 17px;
    border-radius: 50%;
    background: #fff1f2;
    color: #dc3545;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 23px;
}

.bm-delete-modal h2 {
    font-size: 18px;
    font-weight: 800;
    margin: 0 0 9px;
}

.bm-delete-modal > p {
    padding: 0 25px;
    font-size: 12px;
    color: #64748b;
    line-height: 1.7;
    margin-bottom: 20px;
}

.bm-delete-modal .bm-modal-footer {
    justify-content: center;
}

/* Responsive */

@media(max-width: 991px) {

    .banner-management-page .bm-stats-grid {
        gap: 12px;
    }

    .banner-management-page .bm-stat-card {
        padding: 15px;
    }

}

@media(max-width: 700px) {

    .banner-management-page .bm-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .banner-management-page .bm-stats-grid {
        grid-template-columns: 1fr;
    }

    .banner-management-page .bm-stat-card {
        padding: 15px 18px;
    }

}

@media(max-width: 500px) {

    .bm-form-grid,
    .bm-view-details {
        grid-template-columns: 1fr;
    }

    .bm-form-group.bm-full,
    .bm-view-full {
        grid-column: auto;
    }

    .bm-modal-overlay {
        padding: 10px;
    }

    .bm-modal-header,
    .bm-modal-body,
    .bm-modal-footer {
        padding-left: 16px;
        padding-right: 16px;
    }

}

</style>

@endpush


{{-- =========================================================
    PAGE JAVASCRIPT
========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const baseUrl = @json(url('/super-admin/banners'));

    const formModal = document.getElementById('bannerFormModal');
    const viewModal = document.getElementById('bannerViewModal');
    const deleteModal = document.getElementById('bannerDeleteModal');

    const bannerForm = document.getElementById('bannerForm');
    const bannerImage = document.getElementById('bannerImage');

    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const uploadPreview = document.getElementById('uploadPreview');
    const bannerImagePreview = document.getElementById('bannerImagePreview');

    const existingImageBox = document.getElementById('existingImageBox');
    const existingBannerImage = document.getElementById('existingBannerImage');

    const submitButton = document.getElementById('bannerSubmitButton');

    /* Modal Helpers */

    function openModal(modal) {
        if (!modal) return;

        modal.classList.add('bm-modal-open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modal) {
        if (!modal) return;

        modal.classList.remove('bm-modal-open');

        if (!document.querySelector('.bm-modal-overlay.bm-modal-open')) {
            document.body.style.overflow = '';
        }
    }

    document.querySelectorAll('[data-close-modal]').forEach(function (button) {
        button.addEventListener('click', function () {
            closeModal(button.closest('.bm-modal-overlay'));
        });
    });

    document.querySelectorAll('.bm-modal-overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (event) {
            if (event.target === overlay) {
                closeModal(overlay);
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.bm-modal-overlay.bm-modal-open')
                .forEach(closeModal);
        }
    });

    /* Image Preview */

    function resetImagePreview() {
        bannerImage.value = '';
        bannerImagePreview.src = '';

        uploadPreview.style.display = 'none';
        uploadPlaceholder.style.display = 'flex';
    }

    bannerImage.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) return;

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!allowedTypes.includes(file.type)) {
            alert('Please select a JPG, PNG or WEBP image.');
            resetImagePreview();
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('Image size must be less than 5MB.');
            resetImagePreview();
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            bannerImagePreview.src = event.target.result;
            uploadPlaceholder.style.display = 'none';
            uploadPreview.style.display = 'block';
        };

        reader.readAsDataURL(file);

        existingImageBox.style.display = 'none';
    });

    document.getElementById('removeBannerImage').addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        resetImagePreview();
    });

    /* Reset Form */

    function resetBannerForm() {

        bannerForm.reset();

        bannerForm.action = baseUrl;

        document.getElementById('bannerMethodField').innerHTML = '';

        document.getElementById('bannerModalTitle').textContent = 'Add New Banner';

        document.getElementById('bannerModalSubtitle').textContent =
            'Create a new website banner.';

        document.getElementById('bannerSubmitButton').querySelector('span').textContent =
            'Save Banner';

        document.getElementById('bannerSortOrder').value = 0;

        document.getElementById('bannerStatus').value = 'active';

        document.getElementById('imageRequiredMark').style.display = 'inline';

        existingImageBox.style.display = 'none';

        resetImagePreview();
    }

    /* Create */

    function openCreateModal() {

        resetBannerForm();

        openModal(formModal);
    }

    document.getElementById('openCreateModal').addEventListener('click', openCreateModal);

    const emptyCreateButton = document.getElementById('emptyCreateButton');

    if (emptyCreateButton) {
        emptyCreateButton.addEventListener('click', openCreateModal);
    }

    /* Edit */

    document.querySelectorAll('.bm-action-btn.edit').forEach(function (button) {

        button.addEventListener('click', function () {

            resetBannerForm();

            const data = this.dataset;

            bannerForm.action = baseUrl + '/' + data.id;

            document.getElementById('bannerMethodField').innerHTML =
                '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('bannerModalTitle').textContent = 'Edit Banner';

            document.getElementById('bannerModalSubtitle').textContent =
                'Update your existing website banner.';

            document.getElementById('bannerSubmitButton').querySelector('span').textContent =
                'Update Banner';

            document.getElementById('bannerTitle').value = data.title || '';

            document.getElementById('bannerSubtitle').value = data.subtitle || '';

            document.getElementById('bannerDescription').value = data.description || '';

            document.getElementById('bannerButtonText').value = data.buttonText || '';

            document.getElementById('bannerButtonUrl').value = data.buttonUrl || '';

            document.getElementById('bannerStatus').value = data.status || 'active';

            document.getElementById('bannerSortOrder').value = data.sortOrder || 0;

            document.getElementById('imageRequiredMark').style.display = 'none';

            if (data.image) {
                existingBannerImage.src = data.image;
                existingImageBox.style.display = 'flex';
            }

            openModal(formModal);

        });

    });

    /* View */

    document.querySelectorAll('.bm-action-btn.view').forEach(function (button) {

        button.addEventListener('click', async function () {

            const url = this.dataset.viewUrl;

            const viewButton = this;

            viewButton.disabled = true;

            try {

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Unable to load banner information.');
                }

                const result = await response.json();

                const banner = result.banner ?? result;

                document.getElementById('viewBannerImage').src =
                    banner.image_url || banner.image || '';

                document.getElementById('viewBannerTitle').textContent =
                    banner.title || '-';

                document.getElementById('viewBannerSubtitle').textContent =
                    banner.subtitle || '-';

                document.getElementById('viewBannerDescription').textContent =
                    banner.description || '-';

                document.getElementById('viewBannerButtonText').textContent =
                    banner.button_text || '-';

                document.getElementById('viewBannerButtonUrl').textContent =
                    banner.button_url || '-';

                document.getElementById('viewBannerStatus').textContent =
                    banner.status || '-';

                document.getElementById('viewBannerSortOrder').textContent =
                    banner.sort_order ?? 0;

                document.getElementById('viewBannerCreatedAt').textContent =
                    banner.created_at || '-';

                openModal(viewModal);

            } catch (error) {

                alert(error.message);

            } finally {

                viewButton.disabled = false;

            }

        });

    });

    /* Delete */

    document.querySelectorAll('.bm-action-btn.delete').forEach(function (button) {

        button.addEventListener('click', function () {

            const id = this.dataset.id;
            const title = this.dataset.title;

            document.getElementById('deleteBannerTitle').textContent = title;

            document.getElementById('bannerDeleteForm').action =
                baseUrl + '/' + id;

            openModal(deleteModal);

        });

    });

    /* Loading State */

    bannerForm.addEventListener('submit', function () {

        submitButton.disabled = true;

        submitButton.querySelector('span').textContent = 'Please wait...';

    });

    /* Alert Close */

    document.querySelectorAll('.bm-alert-close').forEach(function (button) {

        button.addEventListener('click', function () {

            this.closest('.bm-alert').remove();

        });

    });

});

</script>

@endpush