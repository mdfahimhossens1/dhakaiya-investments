@extends('layouts.app')

@section('title', 'Product Templates')

@section('content')

<style>
    .template-page {
        padding: 24px;
        color: #1e293b;
    }

    .template-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .template-header h2 {
        font-size: 25px;
        font-weight: 750;
        color: #172554;
        margin: 0 0 6px;
    }

    .template-header p {
        color: #64748b;
        margin: 0;
        font-size: 14px;
    }

    .template-btn {
        border: 0;
        border-radius: 9px;
        padding: 11px 17px;
        font-size: 13px;
        font-weight: 650;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: .2s;
    }

    .template-btn-primary {
        background: #172554;
        color: #fff;
    }

    .template-btn-primary:hover {
        background: #263d79;
    }

    .template-btn-light {
        background: #f1f5f9;
        color: #334155;
    }

    .template-btn-light:hover {
        background: #e2e8f0;
    }

    .template-stats {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        margin-bottom: 24px;
    }

    .template-stat {
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 12px;
        padding: 19px;
        display: flex;
        align-items: center;
        gap: 13px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .025);
    }

    .template-stat-icon {
        width: 43px;
        height: 43px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    .stat-blue {
        background: #eff6ff;
        color: #2563eb;
    }

    .stat-green {
        background: #ecfdf5;
        color: #059669;
    }

    .stat-red {
        background: #fef2f2;
        color: #dc2626;
    }

    .stat-gold {
        background: #fffbeb;
        color: #d97706;
    }

    .stat-purple {
        background: #f5f3ff;
        color: #7c3aed;
    }

    .template-stat h3 {
        font-size: 21px;
        font-weight: 750;
        margin: 0;
        color: #172554;
    }

    .template-stat p {
        font-size: 12px;
        color: #64748b;
        margin: 4px 0 0;
    }

    .template-panel {
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 13px;
        overflow: hidden;
    }

    .template-panel-header {
        padding: 19px 21px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .template-panel-header h3 {
        font-size: 16px;
        font-weight: 750;
        color: #172554;
        margin: 0;
    }

    .template-panel-header p {
        font-size: 12px;
        color: #94a3b8;
        margin: 5px 0 0;
    }

    .template-filters {
        padding: 16px 21px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        border-bottom: 1px solid #edf0f5;
    }

    .template-input,
    .template-select,
    .template-textarea {
        width: 100%;
        border: 1px solid #dce3ed;
        border-radius: 8px;
        padding: 10px 12px;
        outline: none;
        color: #334155;
        font-size: 13px;
        background: #fff;
        transition: .2s;
    }

    .template-input:focus,
    .template-select:focus,
    .template-textarea:focus {
        border-color: #6479b8;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .07);
    }

    .template-search {
        max-width: 280px;
    }

    .template-filter-select {
        max-width: 180px;
    }

    .template-table-wrapper {
        overflow-x: auto;
    }

    .template-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .template-table th {
        background: #f8fafc;
        color: #64748b;
        padding: 13px 18px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 750;
        text-align: left;
        white-space: nowrap;
    }

    .template-table td {
        padding: 15px 18px;
        border-top: 1px solid #edf0f5;
        vertical-align: middle;
        font-size: 13px;
    }

    .template-table tbody tr:hover {
        background: #fafcff;
    }

    .template-preview {
        width: 75px;
        height: 55px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .template-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .template-placeholder {
        color: #94a3b8;
        font-size: 20px;
    }

    .template-name {
        color: #172554;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .template-slug {
        color: #94a3b8;
        font-size: 11px;
    }

    .template-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
    }

    .badge-active {
        color: #047857;
        background: #ecfdf5;
    }

    .badge-inactive {
        color: #b91c1c;
        background: #fef2f2;
    }

    .badge-default {
        color: #b45309;
        background: #fffbeb;
    }

    .badge-layout {
        color: #3730a3;
        background: #eef2ff;
    }

    .template-actions {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .template-action {
        width: 31px;
        height: 31px;
        border: 0;
        border-radius: 7px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: .2s;
    }

    .action-view {
        background: #eff6ff;
        color: #2563eb;
    }

    .action-edit {
        background: #fffbeb;
        color: #d97706;
    }

    .action-delete {
        background: #fef2f2;
        color: #dc2626;
    }

    .template-action:hover {
        opacity: .75;
    }

    .template-pagination {
        padding: 17px 21px;
        border-top: 1px solid #edf0f5;
    }

    .template-empty {
        text-align: center;
        padding: 55px 20px;
        color: #94a3b8;
    }

    .template-empty i {
        font-size: 38px;
        margin-bottom: 13px;
    }

    .template-empty h4 {
        color: #334155;
        font-size: 16px;
        margin-bottom: 6px;
    }

    /* Keep the empty-state Create Template button compact */
    .template-empty .template-btn {
        display: inline-flex !important;
        width: auto !important;
        min-width: 0 !important;
        max-width: max-content;
        height: 38px !important;
        min-height: 38px !important;
        padding: 9px 14px !important;
        flex: 0 0 auto;
        line-height: 1;
        white-space: nowrap;
        vertical-align: middle;
        margin-top: 15px;
    }

    .template-empty .template-btn i {
        font-size: 12px;
        margin: 0;
    }

    /* Modal */

    .template-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .62);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        overflow-y: auto;
    }

    .template-modal.show {
        display: flex;
    }

    .template-modal-box {
        width: 100%;
        max-width: 650px;
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
        margin: auto;
        animation: templateModalIn .2s ease;
    }

    .template-modal-large {
        max-width: 850px;
    }

    @keyframes templateModalIn {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .template-modal-header {
        padding: 19px 23px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .template-modal-header h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 750;
        color: #172554;
    }

    .template-modal-close {
        width: 32px;
        height: 32px;
        border: 0;
        border-radius: 7px;
        background: #f1f5f9;
        color: #64748b;
        cursor: pointer;
    }

    .template-modal-body {
        padding: 23px;
        max-height: 70vh;
        overflow-y: auto;
    }

    .template-modal-footer {
        padding: 16px 23px;
        border-top: 1px solid #edf0f5;
        display: flex;
        justify-content: flex-end;
        gap: 9px;
    }

    .template-form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 17px;
    }

    .template-form-group {
        margin-bottom: 17px;
    }

    .template-form-group label {
        display: block;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .template-form-group label span {
        color: #dc2626;
    }

    .template-textarea {
        min-height: 95px;
        resize: vertical;
    }

    .template-checkbox-row {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 13px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #f8fafc;
    }

    .template-checkbox-row input {
        width: 16px;
        height: 16px;
        accent-color: #172554;
    }

    .template-checkbox-row label {
        margin: 0;
        font-size: 12px;
        font-weight: 650;
        color: #334155;
        cursor: pointer;
    }

    .template-image-preview {
        margin-top: 10px;
        width: 120px;
        height: 85px;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        display: none;
        overflow: hidden;
    }

    .template-image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .template-show-preview {
        width: 100%;
        height: 220px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .template-show-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .template-details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 13px;
    }

    .template-detail-item {
        background: #f8fafc;
        padding: 13px;
        border-radius: 8px;
    }

    .template-detail-item small {
        display: block;
        color: #94a3b8;
        font-size: 10px;
        margin-bottom: 5px;
        text-transform: uppercase;
        font-weight: 700;
    }

    .template-detail-item strong {
        color: #172554;
        font-size: 13px;
        word-break: break-word;
    }

    .template-alert {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    .template-alert-success {
        background: #ecfdf5;
        color: #047857;
    }

    .template-alert-danger {
        background: #fef2f2;
        color: #b91c1c;
    }

    @media(max-width: 1100px) {
        .template-stats {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media(max-width: 650px) {
        .template-page {
            padding: 15px;
        }

        .template-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .template-form-grid,
        .template-details-grid {
            grid-template-columns: 1fr;
        }

        .template-search,
        .template-filter-select {
            max-width: 100%;
        }

        .template-filters > * {
            width: 100%;
        }

        .template-modal {
            padding: 10px;
        }

        .template-modal-body {
            padding: 17px;
        }
    }
</style>

<div class="template-page">

    {{-- PAGE HEADER --}}
    <div class="template-header">
        <div>
            <h2>Product Templates</h2>
            <p>Manage and customize the layouts used to display share products.</p>
        </div>

        <button type="button" class="template-btn template-btn-primary"
                onclick="openTemplateModal('createTemplateModal')">
            <i class="fas fa-plus"></i>
            Add New Template
        </button>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="template-alert template-alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="template-alert template-alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="template-alert template-alert-danger">
            <strong>Please check the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- STATISTICS --}}
    <div class="template-stats">

        <div class="template-stat">
            <div class="template-stat-icon stat-blue">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <h3>{{ $totalTemplates }}</h3>
                <p>Total Templates</p>
            </div>
        </div>

        <div class="template-stat">
            <div class="template-stat-icon stat-green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h3>{{ $activeTemplates }}</h3>
                <p>Active Templates</p>
            </div>
        </div>

        <div class="template-stat">
            <div class="template-stat-icon stat-red">
                <i class="fas fa-ban"></i>
            </div>
            <div>
                <h3>{{ $inactiveTemplates }}</h3>
                <p>Inactive Templates</p>
            </div>
        </div>

        <div class="template-stat">
            <div class="template-stat-icon stat-gold">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <h3>{{ $defaultTemplate ? 1 : 0 }}</h3>
                <p>Default Template</p>
            </div>
        </div>

        <div class="template-stat">
            <div class="template-stat-icon stat-purple">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <h3>{{ $totalProjects }}</h3>
                <p>Assigned Products</p>
            </div>
        </div>

    </div>

    {{-- TEMPLATE TABLE --}}
    <div class="template-panel">

        <div class="template-panel-header">
            <div>
                <h3>All Product Templates</h3>
                <p>View, manage and organize your product display layouts.</p>
            </div>

            <span class="template-badge badge-layout">
                {{ $templates->total() }} Records
            </span>
        </div>

        <form action="{{ route('super-admin.project-templates.index') }}"
              method="GET"
              class="template-filters">

            <input type="text"
                   name="search"
                   class="template-input template-search"
                   placeholder="Search template..."
                   value="{{ request('search') }}">

            <select name="layout_type"
                    class="template-select template-filter-select">
                <option value="">All Layout Types</option>
                <option value="grid" {{ request('layout_type') == 'grid' ? 'selected' : '' }}>Grid</option>
                <option value="horizontal" {{ request('layout_type') == 'horizontal' ? 'selected' : '' }}>Horizontal</option>
                <option value="featured" {{ request('layout_type') == 'featured' ? 'selected' : '' }}>Featured</option>
                <option value="large" {{ request('layout_type') == 'large' ? 'selected' : '' }}>Large</option>
            </select>

            <select name="status"
                    class="template-select template-filter-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="template-btn template-btn-primary">
                <i class="fas fa-search"></i> Filter
            </button>

            <a href="{{ route('super-admin.project-templates.index') }}"
               class="template-btn template-btn-light">
                <i class="fas fa-redo"></i> Reset
            </a>

        </form>

        <div class="template-table-wrapper">

            <table class="template-table">

                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Template Information</th>
                        <th>Layout Type</th>
                        <th>Assigned Products</th>
                        <th>Default</th>
                        <th>Status</th>
                        <th>Sort Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($templates as $template)

                        <tr
                            data-id="{{ $template->id }}"
                            data-name="{{ $template->name }}"
                            data-slug="{{ $template->slug }}"
                            data-layout="{{ $template->layout_type }}"
                            data-description="{{ $template->description }}"
                            data-status="{{ $template->status }}"
                            data-default="{{ $template->is_default ? '1' : '0' }}"
                            data-sort="{{ $template->sort_order }}"
                            data-projects="{{ $template->projects_count }}"
                            data-image="{{ $template->preview_image ? Storage::disk('public')->url($template->preview_image) : '' }}"
                            data-created="{{ $template->created_at?->format('d M Y') }}"
                        >

                            <td>
                                <div class="template-preview">
                                    @if($template->preview_image)
                                        <img src="{{ Storage::disk('public')->url($template->preview_image) }}"
                                             alt="{{ $template->name }}">
                                    @else
                                        <i class="fas fa-th-large template-placeholder"></i>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <div class="template-name">
                                    {{ $template->name }}
                                </div>
                                <div class="template-slug">
                                    {{ $template->slug }}
                                </div>
                            </td>

                            <td>
                                <span class="template-badge badge-layout">
                                    {{ ucfirst($template->layout_type) }}
                                </span>
                            </td>

                            <td>
                                <strong>{{ $template->projects_count }}</strong>
                                <span style="color:#94a3b8;font-size:11px;">Products</span>
                            </td>

                            <td>
                                @if($template->is_default)
                                    <span class="template-badge badge-default">
                                        <i class="fas fa-star me-1"></i> Default
                                    </span>
                                @else
                                    <span style="color:#94a3b8;">—</span>
                                @endif
                            </td>

                            <td>
                                @if($template->status === 'active')
                                    <span class="template-badge badge-active">Active</span>
                                @else
                                    <span class="template-badge badge-inactive">Inactive</span>
                                @endif
                            </td>

                            <td>{{ $template->sort_order }}</td>

                            <td>
                                <div class="template-actions">

                                    <button type="button"
                                            class="template-action action-view"
                                            title="View"
                                            onclick="showTemplate(this.closest('tr'))">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button type="button"
                                            class="template-action action-edit"
                                            title="Edit"
                                            onclick="editTemplate(this.closest('tr'))">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('super-admin.project-templates.destroy', $template->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this template?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="template-action action-delete"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8">
                                <div class="template-empty">
                                    <i class="fas fa-layer-group"></i>
                                    <h4>No Templates Found</h4>
                                    <p>Create your first product template to organize product displays.</p>
                                    <button type="button"
                                            class="template-btn template-btn-primary mt-2"
                                            onclick="openTemplateModal('createTemplateModal')">
                                        <i class="fas fa-plus"></i> Create Template
                                    </button>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="template-pagination">
            {{ $templates->withQueryString()->links() }}
        </div>

    </div>

</div>


{{-- =========================================================
    CREATE TEMPLATE MODAL
========================================================= --}}

<div class="template-modal" id="createTemplateModal">

    <div class="template-modal-box">

        <div class="template-modal-header">
            <h3><i class="fas fa-plus-circle me-2"></i>Create Product Template</h3>
            <button type="button" class="template-modal-close"
                    onclick="closeTemplateModal('createTemplateModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('super-admin.project-templates.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="template-modal-body">

                <div class="template-form-group">
                    <label>Template Name <span>*</span></label>
                    <input type="text"
                           name="name"
                           class="template-input"
                           placeholder="Example: Modern Grid Layout"
                           required>
                </div>

                <div class="template-form-grid">

                    <div class="template-form-group">
                        <label>Layout Type <span>*</span></label>
                        <select name="layout_type" class="template-select" required>
                            <option value="">Select Layout</option>
                            <option value="grid">Grid Layout</option>
                            <option value="horizontal">Horizontal Layout</option>
                            <option value="featured">Featured Layout</option>
                            <option value="large">Large Layout</option>
                        </select>
                    </div>

                    <div class="template-form-group">
                        <label>Status <span>*</span></label>
                        <select name="status" class="template-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                </div>

                <div class="template-form-group">
                    <label>Preview Image</label>
                    <input type="file"
                           name="preview_image"
                           class="template-input"
                           accept="image/png,image/jpeg,image/webp"
                           onchange="previewTemplateImage(this, 'createPreview')">

                    <div class="template-image-preview" id="createPreview">
                        <img src="" alt="Preview">
                    </div>

                    <small class="text-muted">JPG, PNG or WEBP. Maximum 2MB.</small>
                </div>

                <div class="template-form-group">
                    <label>Description</label>
                    <textarea name="description"
                              class="template-textarea"
                              placeholder="Describe how this template displays products..."></textarea>
                </div>

                <div class="template-form-grid">

                    <div class="template-form-group">
                        <label>Sort Order</label>
                        <input type="number"
                               name="sort_order"
                               class="template-input"
                               value="0"
                               min="0">
                    </div>

                    <div class="template-form-group">
                        <label>Default Template</label>

                        <div class="template-checkbox-row">
                            <input type="hidden" name="is_default" value="0">
                            <input type="checkbox"
                                   id="createIsDefault"
                                   name="is_default"
                                   value="1">

                            <label for="createIsDefault">
                                Set as default template
                            </label>
                        </div>
                    </div>

                </div>

            </div>

            <div class="template-modal-footer">
                <button type="button"
                        class="template-btn template-btn-light"
                        onclick="closeTemplateModal('createTemplateModal')">
                    Cancel
                </button>

                <button type="submit" class="template-btn template-btn-primary">
                    <i class="fas fa-save"></i> Save Template
                </button>
            </div>

        </form>

    </div>

</div>


{{-- =========================================================
    EDIT TEMPLATE MODAL
========================================================= --}}

<div class="template-modal" id="editTemplateModal">

    <div class="template-modal-box">

        <div class="template-modal-header">
            <h3><i class="fas fa-edit me-2"></i>Edit Product Template</h3>
            <button type="button" class="template-modal-close"
                    onclick="closeTemplateModal('editTemplateModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="editTemplateForm"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="template-modal-body">

                <div class="template-form-group">
                    <label>Template Name <span>*</span></label>
                    <input type="text"
                           name="name"
                           id="editTemplateName"
                           class="template-input"
                           required>
                </div>

                <div class="template-form-grid">

                    <div class="template-form-group">
                        <label>Layout Type <span>*</span></label>
                        <select name="layout_type"
                                id="editTemplateLayout"
                                class="template-select"
                                required>
                            <option value="grid">Grid Layout</option>
                            <option value="horizontal">Horizontal Layout</option>
                            <option value="featured">Featured Layout</option>
                            <option value="large">Large Layout</option>
                        </select>
                    </div>

                    <div class="template-form-group">
                        <label>Status <span>*</span></label>
                        <select name="status"
                                id="editTemplateStatus"
                                class="template-select"
                                required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                </div>

                <div class="template-form-group">
                    <label>Change Preview Image</label>

                    <input type="file"
                           name="preview_image"
                           class="template-input"
                           accept="image/png,image/jpeg,image/webp"
                           onchange="previewTemplateImage(this, 'editPreview')">

                    <div class="template-image-preview"
                         id="editPreview"
                         style="display:block;">
                        <img id="editPreviewImage" src="" alt="Current Preview">
                    </div>
                </div>

                <div class="template-form-group">
                    <label>Description</label>
                    <textarea name="description"
                              id="editTemplateDescription"
                              class="template-textarea"></textarea>
                </div>

                <div class="template-form-grid">

                    <div class="template-form-group">
                        <label>Sort Order</label>
                        <input type="number"
                               name="sort_order"
                               id="editTemplateSort"
                               class="template-input"
                               min="0">
                    </div>

                    <div class="template-form-group">
                        <label>Default Template</label>

                        <div class="template-checkbox-row">
                            <input type="hidden" name="is_default" value="0">
                            <input type="checkbox"
                                   name="is_default"
                                   id="editTemplateDefault"
                                   value="1">

                            <label for="editTemplateDefault">
                                Set as default template
                            </label>
                        </div>
                    </div>

                </div>

            </div>

            <div class="template-modal-footer">
                <button type="button"
                        class="template-btn template-btn-light"
                        onclick="closeTemplateModal('editTemplateModal')">
                    Cancel
                </button>

                <button type="submit" class="template-btn template-btn-primary">
                    <i class="fas fa-save"></i> Update Template
                </button>
            </div>

        </form>

    </div>

</div>


{{-- =========================================================
    SHOW TEMPLATE MODAL
========================================================= --}}

<div class="template-modal" id="showTemplateModal">

    <div class="template-modal-box template-modal-large">

        <div class="template-modal-header">
            <h3><i class="fas fa-eye me-2"></i>Template Details</h3>
            <button type="button" class="template-modal-close"
                    onclick="closeTemplateModal('showTemplateModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="template-modal-body">

            <div class="template-show-preview" id="showTemplatePreview">
                <i class="fas fa-layer-group template-placeholder"
                   style="font-size:45px;"></i>
            </div>

            <h4 id="showTemplateName"
                style="font-size:20px;font-weight:750;color:#172554;margin-bottom:6px;">
            </h4>

            <p id="showTemplateDescription"
               style="font-size:13px;color:#64748b;margin-bottom:22px;">
            </p>

            <div class="template-details-grid">

                <div class="template-detail-item">
                    <small>Template Slug</small>
                    <strong id="showTemplateSlug"></strong>
                </div>

                <div class="template-detail-item">
                    <small>Layout Type</small>
                    <strong id="showTemplateLayout"></strong>
                </div>

                <div class="template-detail-item">
                    <small>Status</small>
                    <strong id="showTemplateStatus"></strong>
                </div>

                <div class="template-detail-item">
                    <small>Default Template</small>
                    <strong id="showTemplateDefault"></strong>
                </div>

                <div class="template-detail-item">
                    <small>Assigned Products</small>
                    <strong id="showTemplateProjects"></strong>
                </div>

                <div class="template-detail-item">
                    <small>Sort Order</small>
                    <strong id="showTemplateSort"></strong>
                </div>

                <div class="template-detail-item">
                    <small>Created At</small>
                    <strong id="showTemplateCreated"></strong>
                </div>

            </div>

        </div>

        <div class="template-modal-footer">
            <button type="button"
                    class="template-btn template-btn-light"
                    onclick="closeTemplateModal('showTemplateModal')">
                Close
            </button>
        </div>

    </div>

</div>


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}

<script>
    function openTemplateModal(id) {
        document.getElementById(id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeTemplateModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = '';
    }

    function previewTemplateImage(input, previewId) {
        const preview = document.getElementById(previewId);

        if (!input.files || !input.files[0]) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            preview.querySelector('img').src = event.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    }

    function editTemplate(row) {
        const id = row.dataset.id;

        const form = document.getElementById('editTemplateForm');

        form.action = "{{ url('/super-admin/project-templates') }}/" + id;

        document.getElementById('editTemplateName').value = row.dataset.name;
        document.getElementById('editTemplateLayout').value = row.dataset.layout;
        document.getElementById('editTemplateStatus').value = row.dataset.status;
        document.getElementById('editTemplateDescription').value = row.dataset.description || '';
        document.getElementById('editTemplateSort').value = row.dataset.sort || 0;
        document.getElementById('editTemplateDefault').checked = row.dataset.default === '1';

        const preview = document.getElementById('editPreview');
        const previewImage = document.getElementById('editPreviewImage');

        if (row.dataset.image) {
            previewImage.src = row.dataset.image;
            preview.style.display = 'block';
        } else {
            previewImage.src = '';
            preview.style.display = 'none';
        }

        openTemplateModal('editTemplateModal');
    }

    function showTemplate(row) {
        document.getElementById('showTemplateName').textContent = row.dataset.name;
        document.getElementById('showTemplateSlug').textContent = row.dataset.slug;
        document.getElementById('showTemplateLayout').textContent = row.dataset.layout;
        document.getElementById('showTemplateStatus').textContent =
            row.dataset.status.charAt(0).toUpperCase() + row.dataset.status.slice(1);

        document.getElementById('showTemplateDefault').textContent =
            row.dataset.default === '1' ? 'Yes' : 'No';

        document.getElementById('showTemplateProjects').textContent =
            row.dataset.projects + ' Products';

        document.getElementById('showTemplateSort').textContent = row.dataset.sort;
        document.getElementById('showTemplateCreated').textContent = row.dataset.created || '—';

        document.getElementById('showTemplateDescription').textContent =
            row.dataset.description || 'No description available.';

        const preview = document.getElementById('showTemplatePreview');

        if (row.dataset.image) {
            preview.innerHTML = '';

            const image = document.createElement('img');
            image.src = row.dataset.image;
            image.alt = row.dataset.name;

            preview.appendChild(image);
        } else {
            preview.innerHTML =
                '<i class="fas fa-layer-group template-placeholder" style="font-size:45px;"></i>';
        }

        openTemplateModal('showTemplateModal');
    }

    document.querySelectorAll('.template-modal').forEach(function (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.template-modal.show').forEach(function (modal) {
                modal.classList.remove('show');
            });

            document.body.style.overflow = '';
        }
    });
</script>

@endsection