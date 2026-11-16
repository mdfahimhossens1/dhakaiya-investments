@extends('layouts.app')

@section('title', 'FAQ Management')

@section('content')

<div class="faq-page">

    {{-- Page Header --}}
    <div class="faq-header">
        <div>
            <div class="faq-breadcrumb">
                <span>Dashboard</span>
                <i class="fas fa-chevron-right"></i>
                <span>Website Content</span>
                <i class="fas fa-chevron-right"></i>
                <strong>FAQs</strong>
            </div>

            <h1>FAQ Management</h1>
            <p>Manage frequently asked questions and their answers.</p>
        </div>

        <button type="button" class="faq-btn faq-btn-primary" onclick="openCreateModal()">
            <i class="fas fa-plus"></i>
            Add New FAQ
        </button>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="faq-alert faq-alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Statistics --}}
    <div class="faq-stats">

        <div class="faq-stat-card">
            <div class="faq-stat-icon icon-blue">
                <i class="fas fa-question-circle"></i>
            </div>
            <div>
                <span>Total FAQs</span>
                <h3>{{ number_format($stats['total']) }}</h3>
            </div>
        </div>

        <div class="faq-stat-card">
            <div class="faq-stat-icon icon-green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <span>Published</span>
                <h3>{{ number_format($stats['published']) }}</h3>
            </div>
        </div>

        <div class="faq-stat-card">
            <div class="faq-stat-icon icon-orange">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <span>Draft</span>
                <h3>{{ number_format($stats['draft']) }}</h3>
            </div>
        </div>

        <div class="faq-stat-card">
            <div class="faq-stat-icon icon-purple">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <span>Categories</span>
                <h3>{{ number_format($stats['categories']) }}</h3>
            </div>
        </div>

    </div>

    {{-- FAQ Table Section --}}
    <div class="faq-card">

        <div class="faq-card-header">
            <div>
                <h3>Frequently Asked Questions</h3>
                <p>View and manage all FAQ entries.</p>
            </div>

            <div class="faq-total-badge">
                {{ $faqs->total() }} Records
            </div>
        </div>

        {{-- Filters --}}
        <form action="{{ route('super-admin.faqs.index') }}" method="GET" class="faq-filter-form">

            <div class="faq-search-box">
                <i class="fas fa-search"></i>
                <input
                    type="text"
                    name="search"
                    placeholder="Search question, answer or category..."
                    value="{{ request('search') }}"
                >
            </div>

            <select name="status" class="faq-filter-select">
                <option value="">All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                    Published
                </option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                    Draft
                </option>
            </select>

            <select name="category" class="faq-filter-select">
                <option value="">All Categories</option>

                @foreach($categories as $category)
                    <option value="{{ $category }}"
                        {{ request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="faq-btn faq-btn-filter">
                <i class="fas fa-filter"></i>
                Filter
            </button>

            <a href="{{ route('super-admin.faqs.index') }}" class="faq-btn faq-btn-reset">
                <i class="fas fa-redo"></i>
                Reset
            </a>

        </form>

        {{-- Table --}}
        <div class="faq-table-wrapper">

            <table class="faq-table">

                <thead>
                    <tr>
                        <th>FAQ DETAILS</th>
                        <th>CATEGORY</th>
                        <th>STATUS</th>
                        <th>SORT ORDER</th>
                        <th>CREATED</th>
                        <th class="text-right">ACTIONS</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($faqs as $faq)

                        <tr>

                            <td>
                                <div class="faq-question-cell">
                                    <div class="faq-question-icon">
                                        <i class="fas fa-question"></i>
                                    </div>

                                    <div>
                                        <strong>{{ $faq->question }}</strong>
                                        <small>
                                            {{ \Illuminate\Support\Str::limit(strip_tags($faq->answer), 75) }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($faq->category)
                                    <span class="faq-category">
                                        {{ $faq->category }}
                                    </span>
                                @else
                                    <span class="faq-muted">Uncategorized</span>
                                @endif
                            </td>

                            <td>
                                @if($faq->status === 'published')
                                    <span class="faq-status status-published">
                                        <span></span> Published
                                    </span>
                                @else
                                    <span class="faq-status status-draft">
                                        <span></span> Draft
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="faq-order">
                                    {{ $faq->sort_order }}
                                </span>
                            </td>

                            <td>
                                <div class="faq-date">
                                    {{ $faq->created_at->format('d M Y') }}
                                    <small>{{ $faq->created_at->format('h:i A') }}</small>
                                </div>
                            </td>

                            <td>
                                <div class="faq-actions">

                                    <button
                                        type="button"
                                        class="faq-action-btn action-view"
                                        title="View"
                                        onclick="viewFaq({{ $faq->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="faq-action-btn action-edit"
                                        title="Edit"
                                        onclick="editFaq(
                                            {{ $faq->id }},
                                            @js($faq->question),
                                            @js($faq->answer),
                                            @js($faq->category),
                                            @js($faq->status),
                                            {{ $faq->sort_order }}
                                        )">
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="faq-action-btn action-delete"
                                        title="Delete"
                                        onclick="deleteFaq(
                                            {{ $faq->id }},
                                            @js($faq->question)
                                        )">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">
                                <div class="faq-empty">
                                    <div>
                                        <i class="fas fa-question-circle"></i>
                                    </div>
                                    <h4>No FAQs Found</h4>
                                    <p>There are no FAQ records matching your search.</p>
                                    <button type="button"
                                        class="faq-btn faq-btn-primary"
                                        onclick="openCreateModal()">
                                        <i class="fas fa-plus"></i>
                                        Create First FAQ
                                    </button>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if($faqs->hasPages())
            <div class="faq-pagination">
                {{ $faqs->links() }}
            </div>
        @endif

    </div>

</div>


{{-- =====================================================
     CREATE FAQ MODAL
===================================================== --}}

<div class="faq-modal" id="createFaqModal">

    <div class="faq-modal-box">

        <div class="faq-modal-header">
            <div>
                <h3><i class="fas fa-plus-circle"></i> Add New FAQ</h3>
                <p>Create a new frequently asked question.</p>
            </div>

            <button type="button" class="faq-modal-close" onclick="closeModal('createFaqModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('super-admin.faqs.store') }}" method="POST">

            @csrf

            <div class="faq-modal-body">

                <div class="faq-form-group">
                    <label>Question <span>*</span></label>
                    <input
                        type="text"
                        name="question"
                        placeholder="Enter FAQ question"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="faq-form-group">
                    <label>Answer <span>*</span></label>
                    <textarea
                        name="answer"
                        rows="5"
                        placeholder="Write the answer here..."
                        required
                    ></textarea>
                </div>

                <div class="faq-form-row">

                    <div class="faq-form-group">
                        <label>Category</label>
                        <input
                            type="text"
                            name="category"
                            placeholder="e.g. General"
                            maxlength="100"
                        >
                    </div>

                    <div class="faq-form-group">
                        <label>Status <span>*</span></label>
                        <select name="status" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                </div>

                <div class="faq-form-group">
                    <label>Sort Order</label>
                    <input
                        type="number"
                        name="sort_order"
                        value="0"
                        min="0"
                        placeholder="0"
                    >
                    <small>Lower numbers will appear first.</small>
                </div>

            </div>

            <div class="faq-modal-footer">
                <button type="button" class="faq-btn faq-btn-cancel"
                    onclick="closeModal('createFaqModal')">
                    Cancel
                </button>

                <button type="submit" class="faq-btn faq-btn-primary">
                    <i class="fas fa-save"></i>
                    Save FAQ
                </button>
            </div>

        </form>

    </div>

</div>


{{-- =====================================================
     EDIT FAQ MODAL
===================================================== --}}

<div class="faq-modal" id="editFaqModal">

    <div class="faq-modal-box">

        <div class="faq-modal-header">
            <div>
                <h3><i class="fas fa-edit"></i> Edit FAQ</h3>
                <p>Update question and answer information.</p>
            </div>

            <button type="button" class="faq-modal-close" onclick="closeModal('editFaqModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="editFaqForm" method="POST">

            @csrf
            @method('PUT')

            <div class="faq-modal-body">

                <div class="faq-form-group">
                    <label>Question <span>*</span></label>
                    <input
                        type="text"
                        name="question"
                        id="edit_question"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="faq-form-group">
                    <label>Answer <span>*</span></label>
                    <textarea
                        name="answer"
                        id="edit_answer"
                        rows="5"
                        required
                    ></textarea>
                </div>

                <div class="faq-form-row">

                    <div class="faq-form-group">
                        <label>Category</label>
                        <input
                            type="text"
                            name="category"
                            id="edit_category"
                            maxlength="100"
                        >
                    </div>

                    <div class="faq-form-group">
                        <label>Status <span>*</span></label>
                        <select name="status" id="edit_status" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                </div>

                <div class="faq-form-group">
                    <label>Sort Order</label>
                    <input
                        type="number"
                        name="sort_order"
                        id="edit_sort_order"
                        min="0"
                    >
                </div>

            </div>

            <div class="faq-modal-footer">

                <button type="button" class="faq-btn faq-btn-cancel"
                    onclick="closeModal('editFaqModal')">
                    Cancel
                </button>

                <button type="submit" class="faq-btn faq-btn-primary">
                    <i class="fas fa-save"></i>
                    Update FAQ
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =====================================================
     VIEW FAQ MODAL
===================================================== --}}

<div class="faq-modal" id="viewFaqModal">

    <div class="faq-modal-box">

        <div class="faq-modal-header">

            <div>
                <h3><i class="fas fa-eye"></i> FAQ Details</h3>
                <p>View complete FAQ information.</p>
            </div>

            <button type="button" class="faq-modal-close" onclick="closeModal('viewFaqModal')">
                <i class="fas fa-times"></i>
            </button>

        </div>

        <div class="faq-modal-body">

            <div class="faq-view-item">
                <label>Question</label>
                <div id="view_question" class="faq-view-question"></div>
            </div>

            <div class="faq-view-item">
                <label>Answer</label>
                <div id="view_answer" class="faq-view-answer"></div>
            </div>

            <div class="faq-view-grid">

                <div class="faq-view-item">
                    <label>Category</label>
                    <div id="view_category"></div>
                </div>

                <div class="faq-view-item">
                    <label>Status</label>
                    <div id="view_status"></div>
                </div>

                <div class="faq-view-item">
                    <label>Sort Order</label>
                    <div id="view_sort_order"></div>
                </div>

                <div class="faq-view-item">
                    <label>Created At</label>
                    <div id="view_created_at"></div>
                </div>

            </div>

        </div>

        <div class="faq-modal-footer">
            <button type="button" class="faq-btn faq-btn-cancel"
                onclick="closeModal('viewFaqModal')">
                Close
            </button>
        </div>

    </div>

</div>


{{-- =====================================================
     DELETE FAQ MODAL
===================================================== --}}

<div class="faq-modal" id="deleteFaqModal">

    <div class="faq-modal-box faq-delete-box">

        <div class="faq-delete-icon">
            <i class="fas fa-trash-alt"></i>
        </div>

        <h3>Delete FAQ?</h3>

        <p>
            Are you sure you want to delete
            <strong id="deleteFaqName"></strong>?
            This action cannot be undone.
        </p>

        <form id="deleteFaqForm" method="POST">

            @csrf
            @method('DELETE')

            <div class="faq-modal-footer">

                <button type="button" class="faq-btn faq-btn-cancel"
                    onclick="closeModal('deleteFaqModal')">
                    Cancel
                </button>

                <button type="submit" class="faq-btn faq-btn-danger">
                    <i class="fas fa-trash"></i>
                    Yes, Delete
                </button>

            </div>

        </form>

    </div>

</div>


@endsection


@push('styles')

<style>

    /* =====================================================
       FAQ MANAGEMENT UI
    ===================================================== */

    .faq-page {
        width: 100%;
        color: #263238;
    }

    .faq-page * {
        box-sizing: border-box;
    }

    .faq-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    .faq-breadcrumb {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #89949b;
        font-size: 12px;
        margin-bottom: 10px;
    }

    .faq-breadcrumb i {
        font-size: 9px;
    }

    .faq-breadcrumb strong {
        color: #087f5b;
        font-weight: 600;
    }

    .faq-header h1 {
        font-size: 25px;
        font-weight: 700;
        color: #172b26;
        margin: 0 0 6px;
    }

    .faq-header p {
        color: #87939a;
        font-size: 13px;
        margin: 0;
    }

    /* Buttons */

    .faq-btn {
        border: none;
        border-radius: 8px;
        padding: 11px 17px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
        transition: .2s ease;
        font-family: inherit;
    }

    .faq-btn-primary {
        background: #087f5b;
        color: #fff;
        box-shadow: 0 4px 10px rgba(8,127,91,.12);
    }

    .faq-btn-primary:hover {
        background: #066b4c;
        color: #fff;
        transform: translateY(-1px);
    }

    .faq-btn-filter {
        background: #102b26;
        color: #fff;
    }

    .faq-btn-filter:hover {
        background: #1c4037;
        color: #fff;
    }

    .faq-btn-reset {
        background: #edf1f2;
        color: #58666d;
    }

    .faq-btn-reset:hover {
        background: #e1e7e9;
        color: #263238;
    }

    .faq-btn-cancel {
        background: #edf1f2;
        color: #59666d;
        border: 1px solid #e1e7e9;
    }

    .faq-btn-cancel:hover {
        background: #e1e7e9;
        color: #263238;
    }

    .faq-btn-danger {
        background: #dc3545;
        color: #fff;
    }

    .faq-btn-danger:hover {
        background: #c82333;
        color: #fff;
    }

    /* Alert */

    .faq-alert {
        padding: 13px 16px;
        border-radius: 9px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
    }

    .faq-alert-success {
        background: #e9f8f0;
        color: #16834e;
        border: 1px solid #c9edda;
    }

    .faq-alert button {
        margin-left: auto;
        border: none;
        background: transparent;
        color: inherit;
        cursor: pointer;
        font-size: 15px;
    }

    /* Statistics */

    .faq-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 23px;
    }

    .faq-stat-card {
        background: #fff;
        border: 1px solid #edf0f1;
        border-radius: 12px;
        padding: 19px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 3px 12px rgba(20,40,35,.025);
    }

    .faq-stat-icon {
        width: 47px;
        height: 47px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .icon-blue {
        color: #3478d4;
        background: #edf4ff;
    }

    .icon-green {
        color: #15965b;
        background: #e9f8f0;
    }

    .icon-orange {
        color: #df912a;
        background: #fff5e7;
    }

    .icon-purple {
        color: #8258c9;
        background: #f3edff;
    }

    .faq-stat-card span {
        display: block;
        color: #89949b;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .faq-stat-card h3 {
        color: #172b26;
        font-size: 23px;
        font-weight: 700;
        margin: 0;
    }

    /* Main Card */

    .faq-card {
        background: #fff;
        border: 1px solid #edf0f1;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(20,40,35,.025);
        overflow: hidden;
    }

    .faq-card-header {
        padding: 21px 23px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #edf0f1;
    }

    .faq-card-header h3 {
        font-size: 16px;
        color: #172b26;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .faq-card-header p {
        font-size: 12px;
        color: #929da3;
        margin: 0;
    }

    .faq-total-badge {
        background: #eaf5f1;
        color: #087f5b;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    /* Filters */

    .faq-filter-form {
        padding: 17px 22px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #edf0f1;
        flex-wrap: wrap;
    }

    .faq-search-box {
        position: relative;
        flex: 1;
        min-width: 220px;
    }

    .faq-search-box i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #9aa5aa;
        font-size: 13px;
    }

    .faq-search-box input {
        width: 100%;
        height: 40px;
        border: 1px solid #e5e9eb;
        border-radius: 7px;
        padding: 0 12px 0 36px;
        outline: none;
        font-size: 12px;
        font-family: inherit;
        color: #34433e;
    }

    .faq-search-box input:focus,
    .faq-filter-select:focus {
        border-color: #087f5b;
        box-shadow: 0 0 0 3px rgba(8,127,91,.07);
    }

    .faq-filter-select {
        height: 40px;
        min-width: 130px;
        border: 1px solid #e5e9eb;
        border-radius: 7px;
        padding: 0 12px;
        outline: none;
        font-size: 12px;
        background: #fff;
        color: #53615e;
        font-family: inherit;
    }

    /* Table */

    .faq-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .faq-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .faq-table thead {
        background: #f8faf9;
    }

    .faq-table th {
        padding: 14px 17px;
        color: #84918c;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .5px;
        text-align: left;
        white-space: nowrap;
    }

    .faq-table td {
        padding: 15px 17px;
        border-top: 1px solid #f0f2f2;
        vertical-align: middle;
    }

    .faq-table tbody tr:hover {
        background: #fbfdfc;
    }

    .faq-question-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 270px;
    }

    .faq-question-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: #eaf5f1;
        color: #087f5b;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .faq-question-cell strong {
        display: block;
        color: #263b34;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 5px;
        max-width: 300px;
    }

    .faq-question-cell small {
        display: block;
        color: #9aa4a8;
        font-size: 11px;
    }

    .faq-category {
        display: inline-block;
        background: #f0f4f3;
        color: #52665e;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }

    .faq-muted {
        color: #a3abad;
        font-size: 11px;
    }

    .faq-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .faq-status span {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-published {
        background: #e9f8f0;
        color: #16834e;
    }

    .status-published span {
        background: #16834e;
    }

    .status-draft {
        background: #fff5e7;
        color: #c28121;
    }

    .status-draft span {
        background: #df912a;
    }

    .faq-order {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 27px;
        padding: 0 7px;
        background: #f2f5f4;
        border-radius: 6px;
        color: #52645d;
        font-size: 11px;
        font-weight: 600;
    }

    .faq-date {
        color: #52615b;
        font-size: 11px;
        white-space: nowrap;
    }

    .faq-date small {
        display: block;
        color: #a0a9a8;
        margin-top: 4px;
    }

    .text-right {
        text-align: right !important;
    }

    /* Actions */

    .faq-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
    }

    .faq-action-btn {
        width: 31px;
        height: 31px;
        border: none;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: .2s;
        font-size: 11px;
    }

    .action-view {
        background: #edf4ff;
        color: #3478d4;
    }

    .action-edit {
        background: #fff5e7;
        color: #d58a24;
    }

    .action-delete {
        background: #fff0f0;
        color: #dc3545;
    }

    .faq-action-btn:hover {
        transform: translateY(-2px);
        filter: brightness(.96);
    }

    /* Empty State */

    .faq-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .faq-empty > div {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: #edf5f2;
        color: #087f5b;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 25px;
    }

    .faq-empty h4 {
        color: #34443d;
        font-size: 16px;
        margin: 0 0 7px;
    }

    .faq-empty p {
        color: #98a3a2;
        font-size: 12px;
        margin-bottom: 18px;
    }

    /* Pagination */

    .faq-pagination {
        padding: 17px 22px;
        border-top: 1px solid #edf0f1;
    }

    .faq-pagination nav {
        display: flex;
        justify-content: flex-end;
    }

    /* =====================================================
       MODALS
    ===================================================== */

    .faq-modal {
        position: fixed;
        inset: 0;
        background: rgba(12, 30, 25, .55);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        overflow-y: auto;
        backdrop-filter: blur(3px);
    }

    .faq-modal.active {
        display: flex;
    }

    .faq-modal-box {
        width: 100%;
        max-width: 570px;
        background: #fff;
        border-radius: 13px;
        box-shadow: 0 15px 60px rgba(0,0,0,.16);
        overflow: hidden;
        animation: faqModalShow .2s ease;
        margin: auto;
    }

    @keyframes faqModalShow {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .faq-modal-header {
        padding: 21px 23px;
        border-bottom: 1px solid #edf0f1;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .faq-modal-header h3 {
        color: #19352b;
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .faq-modal-header h3 i {
        color: #087f5b;
        margin-right: 6px;
    }

    .faq-modal-header p {
        color: #929da3;
        font-size: 11px;
        margin: 0;
    }

    .faq-modal-close {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 7px;
        background: #f1f4f3;
        color: #66736e;
        cursor: pointer;
        font-size: 13px;
    }

    .faq-modal-close:hover {
        background: #e7edeb;
        color: #263b34;
    }

    .faq-modal-body {
        padding: 22px 23px;
        max-height: 65vh;
        overflow-y: auto;
    }

    .faq-form-group {
        margin-bottom: 17px;
    }

    .faq-form-group label,
    .faq-view-item label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #34463e;
        margin-bottom: 8px;
    }

    .faq-form-group label span {
        color: #dc3545;
    }

    .faq-form-group input,
    .faq-form-group textarea,
    .faq-form-group select {
        width: 100%;
        border: 1px solid #e1e7e4;
        border-radius: 7px;
        padding: 11px 12px;
        outline: none;
        color: #34443d;
        font-family: inherit;
        font-size: 12px;
        background: #fff;
        transition: .2s;
    }

    .faq-form-group input {
        height: 42px;
    }

    .faq-form-group textarea {
        resize: vertical;
        min-height: 110px;
        line-height: 1.6;
    }

    .faq-form-group input:focus,
    .faq-form-group textarea:focus,
    .faq-form-group select:focus {
        border-color: #087f5b;
        box-shadow: 0 0 0 3px rgba(8,127,91,.07);
    }

    .faq-form-group small {
        display: block;
        color: #98a39f;
        font-size: 10px;
        margin-top: 6px;
    }

    .faq-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .faq-modal-footer {
        padding: 16px 23px;
        border-top: 1px solid #edf0f1;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
        background: #fcfdfd;
    }

    /* View Modal */

    .faq-view-item {
        margin-bottom: 18px;
    }

    .faq-view-item label {
        color: #89958f;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .faq-view-question {
        color: #263b34;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.6;
    }

    .faq-view-answer {
        background: #f8faf9;
        padding: 14px;
        border-radius: 8px;
        color: #52635b;
        font-size: 12px;
        line-height: 1.8;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .faq-view-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        padding-top: 10px;
    }

    .faq-view-grid .faq-view-item {
        background: #f8faf9;
        padding: 12px;
        border-radius: 8px;
        margin: 0;
    }

    .faq-view-grid .faq-view-item div {
        color: #34463e;
        font-size: 12px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    /* Delete Modal */

    .faq-delete-box {
        max-width: 410px;
        text-align: center;
        padding-top: 30px;
    }

    .faq-delete-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 15px;
        border-radius: 50%;
        background: #fff0f0;
        color: #dc3545;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .faq-delete-box h3 {
        color: #24372f;
        font-size: 18px;
        margin: 0 0 10px;
    }

    .faq-delete-box > p {
        color: #87938e;
        font-size: 12px;
        line-height: 1.7;
        padding: 0 25px;
        margin-bottom: 23px;
    }

    .faq-delete-box > p strong {
        color: #dc3545;
    }

    .faq-delete-box .faq-modal-footer {
        justify-content: center;
    }

    /* Responsive */

    @media(max-width: 1100px) {
        .faq-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 700px) {

        .faq-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .faq-header h1 {
            font-size: 21px;
        }

        .faq-stats {
            gap: 10px;
        }

        .faq-stat-card {
            padding: 13px;
            gap: 10px;
        }

        .faq-stat-icon {
            width: 38px;
            height: 38px;
            font-size: 15px;
        }

        .faq-stat-card h3 {
            font-size: 19px;
        }

        .faq-filter-form {
            align-items: stretch;
            flex-direction: column;
        }

        .faq-search-box {
            min-width: 100%;
        }

        .faq-filter-select {
            width: 100%;
        }

        .faq-filter-form .faq-btn {
            width: 100%;
        }

        .faq-form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .faq-view-grid {
            grid-template-columns: 1fr;
        }

        .faq-modal {
            padding: 10px;
        }

        .faq-modal-header,
        .faq-modal-body,
        .faq-modal-footer {
            padding-left: 16px;
            padding-right: 16px;
        }

    }

</style>

@endpush


@push('scripts')

<script>

    /* =====================================================
       MODAL FUNCTIONS
    ===================================================== */

    function openModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');

        if (!document.querySelector('.faq-modal.active')) {
            document.body.style.overflow = '';
        }
    }

    // Close modal when clicking outside
    document.querySelectorAll('.faq-modal').forEach(modal => {

        modal.addEventListener('click', function(e) {

            if (e.target === this) {
                closeModal(this.id);
            }

        });

    });

    // Close modal with Escape
    document.addEventListener('keydown', function(e) {

        if (e.key === 'Escape') {

            document.querySelectorAll('.faq-modal.active').forEach(modal => {
                closeModal(modal.id);
            });

        }

    });


    /* =====================================================
       CREATE FAQ
    ===================================================== */

    function openCreateModal() {

        const form = document.querySelector('#createFaqModal form');

        form.reset();

        openModal('createFaqModal');

    }


    /* =====================================================
       EDIT FAQ
    ===================================================== */

    function editFaq(id, question, answer, category, status, sortOrder) {

        const form = document.getElementById('editFaqForm');

        form.action = `/super-admin/faqs/${id}`;

        document.getElementById('edit_question').value = question ?? '';
        document.getElementById('edit_answer').value = answer ?? '';
        document.getElementById('edit_category').value = category ?? '';
        document.getElementById('edit_status').value = status ?? 'draft';
        document.getElementById('edit_sort_order').value = sortOrder ?? 0;

        openModal('editFaqModal');

    }


    /* =====================================================
       VIEW FAQ
    ===================================================== */

    async function viewFaq(id) {

        const questionElement = document.getElementById('view_question');
        const answerElement = document.getElementById('view_answer');

        questionElement.textContent = 'Loading...';
        answerElement.textContent = 'Loading...';

        openModal('viewFaqModal');

        try {

            const response = await fetch(`/super-admin/faqs/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Unable to load FAQ details.');
            }

            const result = await response.json();

            if (!result.success) {
                throw new Error('FAQ not found.');
            }

            const faq = result.faq;

            questionElement.textContent = faq.question;
            answerElement.textContent = faq.answer;

            document.getElementById('view_category').textContent =
                faq.category || 'Uncategorized';

            document.getElementById('view_status').textContent =
                faq.status.charAt(0).toUpperCase() + faq.status.slice(1);

            document.getElementById('view_sort_order').textContent =
                faq.sort_order;

            document.getElementById('view_created_at').textContent =
                faq.created_at || '-';

        } catch (error) {

            closeModal('viewFaqModal');

            alert(error.message);

        }

    }


    /* =====================================================
       DELETE FAQ
    ===================================================== */

    function deleteFaq(id, question) {

        const form = document.getElementById('deleteFaqForm');

        form.action = `/super-admin/faqs/${id}`;

        document.getElementById('deleteFaqName').textContent = question;

        openModal('deleteFaqModal');

    }

</script>

@endpush