@extends('layouts.app')

@section('title', 'Project Categories')

@section('content')

<style>
    .category-page {
        padding: 24px;
        color: #172033;
    }

    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .category-header h2 {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .category-header p {
        color: #7b8495;
        margin: 0;
        font-size: 14px;
    }

    .category-btn {
        border: none;
        padding: 10px 17px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        text-decoration: none;
    }

    .category-btn-primary {
        background: #172554;
        color: #fff;
    }

    .category-btn-primary:hover {
        background: #263b72;
        color: #fff;
    }

    .category-btn-light {
        background: #f1f3f8;
        color: #334155;
    }

    .category-btn-danger {
        background: #fee2e2;
        color: #dc2626;
    }

    .category-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .category-stat {
        background: #fff;
        border: 1px solid #edf0f5;
        border-radius: 10px;
        padding: 20px;
    }

    .category-stat small {
        color: #7b8495;
        font-size: 13px;
    }

    .category-stat h3 {
        font-size: 25px;
        font-weight: 700;
        margin: 8px 0 0;
    }

    .category-card {
        background: #fff;
        border: 1px solid #edf0f5;
        border-radius: 10px;
        overflow: hidden;
    }

    .category-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .category-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .category-table {
        width: 100%;
        border-collapse: collapse;
    }

    .category-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 14px 16px;
        text-align: left;
        white-space: nowrap;
    }

    .category-table td {
        padding: 15px 16px;
        border-top: 1px solid #edf0f5;
        font-size: 13px;
        vertical-align: middle;
    }

    .category-name {
        font-weight: 700;
        color: #172033;
    }

    .category-slug {
        color: #8490a3;
        font-size: 12px;
        margin-top: 4px;
    }

    .category-icon {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        background: #eef2ff;
        color: #334b91;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
    }

    .category-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .category-active {
        background: #dcfce7;
        color: #15803d;
    }

    .category-inactive {
        background: #fee2e2;
        color: #b91c1c;
    }

    .category-actions {
        display: flex;
        gap: 6px;
    }

    .category-action {
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 6px;
        background: #f1f5f9;
        color: #475569;
        cursor: pointer;
    }

    .category-action:hover {
        background: #e2e8f0;
    }

    .category-action.delete {
        background: #fff1f2;
        color: #dc2626;
    }

    .category-modal {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .55);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
        padding: 20px;
        overflow-y: auto;
    }

    .category-modal.show {
        display: flex;
    }

    .category-modal-content {
        background: #fff;
        width: 100%;
        max-width: 550px;
        border-radius: 12px;
        overflow: hidden;
        max-height: 90vh;
        overflow-y: auto;
    }

    .category-modal-header {
        padding: 18px 22px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-modal-header h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
    }

    .category-modal-close {
        border: none;
        background: transparent;
        font-size: 24px;
        color: #64748b;
        cursor: pointer;
    }

    .category-modal-body {
        padding: 22px;
    }

    .category-form-group {
        margin-bottom: 17px;
    }

    .category-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .category-form-control {
        width: 100%;
        border: 1px solid #dce2eb;
        border-radius: 7px;
        padding: 10px 12px;
        font-size: 13px;
        outline: none;
    }

    .category-form-control:focus {
        border-color: #334b91;
        box-shadow: 0 0 0 3px rgba(51, 75, 145, .1);
    }

    .category-form-group textarea {
        min-height: 90px;
        resize: vertical;
    }

    .category-modal-footer {
        padding: 15px 22px;
        border-top: 1px solid #edf0f5;
        display: flex;
        justify-content: flex-end;
        gap: 9px;
    }

    .category-alert {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    .category-alert-success {
        background: #dcfce7;
        color: #166534;
    }

    .category-alert-error {
        background: #fee2e2;
        color: #991b1b;
    }

    .category-empty {
        text-align: center;
        padding: 45px 20px;
        color: #8490a3;
    }

    .category-empty i {
        font-size: 35px;
        margin-bottom: 12px;
    }

    .category-pagination {
        padding: 18px;
        border-top: 1px solid #edf0f5;
    }

    .category-pagination nav {
        display: flex;
        justify-content: flex-end;
    }

    .category-pagination nav p {
        font-size: 12px;
    }

    .category-detail-row {
        margin-bottom: 16px;
    }

    .category-detail-row small {
        display: block;
        color: #8490a3;
        margin-bottom: 5px;
    }

    .category-detail-row strong {
        font-size: 14px;
        overflow-wrap: anywhere;
    }

    @media(max-width: 992px) {
        .category-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 600px) {
        .category-page {
            padding: 15px;
        }

        .category-stats {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .category-stat {
            padding: 14px;
        }

        .category-stat h3 {
            font-size: 21px;
        }

        .category-table-wrapper {
            overflow-x: auto;
        }

        .category-table {
            min-width: 850px;
        }
    }
</style>

<div class="category-page">

    {{-- Page Header --}}
    <div class="category-header">

        <div>
            <h2>Project Categories</h2>
            <p>Manage and organize your project catalogue categories.</p>
        </div>

        <button type="button"
                class="category-btn category-btn-primary"
                onclick="openCategoryModal('createCategoryModal')">
            <i class="fas fa-plus"></i>
            Add New Category
        </button>

    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="category-alert category-alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="category-alert category-alert-error">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="category-alert category-alert-error">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Statistics --}}
    <div class="category-stats">

        <div class="category-stat">
            <small>Total Categories</small>
            <h3>{{ number_format($totalCategories) }}</h3>
        </div>

        <div class="category-stat">
            <small>Active Categories</small>
            <h3>{{ number_format($activeCategories) }}</h3>
        </div>

        <div class="category-stat">
            <small>Inactive Categories</small>
            <h3>{{ number_format($inactiveCategories) }}</h3>
        </div>

        <div class="category-stat">
            <small>Assigned Projects</small>
            <h3>{{ number_format($totalProjects) }}</h3>
        </div>

    </div>

    {{-- Category Table --}}
    <div class="category-card">

        <div class="category-card-header">
            <h5>All Categories</h5>
            <span class="text-muted small">
                {{ $categories->total() }} categories found
            </span>
        </div>

        <div class="category-table-wrapper">

            <table class="category-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Icon</th>
                        <th>Projects</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($categories as $category)

                        <tr
                            data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}"
                            data-slug="{{ $category->slug }}"
                            data-description="{{ $category->description }}"
                            data-icon="{{ $category->icon }}"
                            data-status="{{ $category->status }}"
                            data-sort-order="{{ $category->sort_order }}"
                            data-projects="{{ $category->projects_count }}"
                            data-created="{{ $category->created_at->format('d M Y') }}"
                        >

                            <td>
                                {{ $categories->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="category-name">
                                    {{ $category->name }}
                                </div>

                                <div class="category-slug">
                                    {{ $category->slug }}
                                </div>
                            </td>

                            <td>
                                <div class="category-icon">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }}"></i>
                                    @else
                                        <i class="fas fa-folder"></i>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <strong>
                                    {{ number_format($category->projects_count) }}
                                </strong>
                            </td>

                            <td>
                                {{ $category->sort_order }}
                            </td>

                            <td>
                                @if($category->status === 'active')
                                    <span class="category-badge category-active">
                                        Active
                                    </span>
                                @else
                                    <span class="category-badge category-inactive">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $category->created_at->format('d M Y') }}
                            </td>

                            <td>
                                <div class="category-actions">

                                    <button type="button"
                                            class="category-action"
                                            title="View"
                                            onclick="showCategory({{ $category->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button type="button"
                                            class="category-action"
                                            title="Edit"
                                            onclick="editCategory({{ $category->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form
                                        action="{{ route('super-admin.project-categories.destroy', $category->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this category?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="category-action delete"
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
                                <div class="category-empty">
                                    <i class="fas fa-folder-open"></i>
                                    <h5>No Categories Found</h5>
                                    <p class="mb-0">
                                        Create your first project category.
                                    </p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if($categories->hasPages())
            <div class="category-pagination">
                {{ $categories->links() }}
            </div>
        @endif

    </div>

</div>


{{-- ================================================= --}}
{{-- CREATE CATEGORY MODAL --}}
{{-- ================================================= --}}

<div class="category-modal" id="createCategoryModal">

    <div class="category-modal-content">

        <div class="category-modal-header">
            <h5>Create New Category</h5>

            <button type="button"
                    class="category-modal-close"
                    onclick="closeCategoryModal('createCategoryModal')">
                &times;
            </button>
        </div>

        <form action="{{ route('super-admin.project-categories.store') }}"
              method="POST">

            @csrf

            <div class="category-modal-body">

                <div class="category-form-group">
                    <label>Category Name <span class="text-danger">*</span></label>

                    <input type="text"
                           name="name"
                           class="category-form-control"
                           placeholder="e.g. Technology"
                           required>
                </div>

                <div class="category-form-group">
                    <label>Icon Class</label>

                    <input type="text"
                           name="icon"
                           class="category-form-control"
                           placeholder="e.g. fas fa-laptop">

                    <small class="text-muted">
                        Use an available Font Awesome icon class.
                    </small>
                </div>

                <div class="category-form-group">
                    <label>Description</label>

                    <textarea name="description"
                              class="category-form-control"
                              placeholder="Write category description..."></textarea>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="category-form-group">
                            <label>Sort Order</label>

                            <input type="number"
                                   name="sort_order"
                                   class="category-form-control"
                                   value="0"
                                   min="0">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="category-form-group">
                            <label>Status</label>

                            <select name="status"
                                    class="category-form-control"
                                    required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                </div>

            </div>

            <div class="category-modal-footer">

                <button type="button"
                        class="category-btn category-btn-light"
                        onclick="closeCategoryModal('createCategoryModal')">
                    Cancel
                </button>

                <button type="submit"
                        class="category-btn category-btn-primary">
                    <i class="fas fa-save"></i>
                    Create Category
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ================================================= --}}
{{-- EDIT CATEGORY MODAL --}}
{{-- ================================================= --}}

<div class="category-modal" id="editCategoryModal">

    <div class="category-modal-content">

        <div class="category-modal-header">
            <h5>Edit Category</h5>

            <button type="button"
                    class="category-modal-close"
                    onclick="closeCategoryModal('editCategoryModal')">
                &times;
            </button>
        </div>

        <form id="editCategoryForm" method="POST">

            @csrf
            @method('PUT')

            <div class="category-modal-body">

                <div class="category-form-group">
                    <label>Category Name <span class="text-danger">*</span></label>

                    <input type="text"
                           name="name"
                           id="edit_category_name"
                           class="category-form-control"
                           required>
                </div>

                <div class="category-form-group">
                    <label>Icon Class</label>

                    <input type="text"
                           name="icon"
                           id="edit_category_icon"
                           class="category-form-control"
                           placeholder="e.g. fas fa-laptop">
                </div>

                <div class="category-form-group">
                    <label>Description</label>

                    <textarea name="description"
                              id="edit_category_description"
                              class="category-form-control"></textarea>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="category-form-group">
                            <label>Sort Order</label>

                            <input type="number"
                                   name="sort_order"
                                   id="edit_category_sort_order"
                                   class="category-form-control"
                                   min="0">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="category-form-group">
                            <label>Status</label>

                            <select name="status"
                                    id="edit_category_status"
                                    class="category-form-control"
                                    required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                </div>

            </div>

            <div class="category-modal-footer">

                <button type="button"
                        class="category-btn category-btn-light"
                        onclick="closeCategoryModal('editCategoryModal')">
                    Cancel
                </button>

                <button type="submit"
                        class="category-btn category-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Category
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ================================================= --}}
{{-- SHOW CATEGORY MODAL --}}
{{-- ================================================= --}}

<div class="category-modal" id="showCategoryModal">

    <div class="category-modal-content">

        <div class="category-modal-header">

            <h5>Category Details</h5>

            <button type="button"
                    class="category-modal-close"
                    onclick="closeCategoryModal('showCategoryModal')">
                &times;
            </button>

        </div>

        <div class="category-modal-body">

            <div class="category-detail-row">
                <small>Category Name</small>
                <strong id="show_category_name"></strong>
            </div>

            <div class="category-detail-row">
                <small>Slug</small>
                <strong id="show_category_slug"></strong>
            </div>

            <div class="category-detail-row">
                <small>Icon Class</small>
                <strong id="show_category_icon"></strong>
            </div>

            <div class="category-detail-row">
                <small>Description</small>
                <strong id="show_category_description"></strong>
            </div>

            <div class="category-detail-row">
                <small>Assigned Projects</small>
                <strong id="show_category_projects"></strong>
            </div>

            <div class="category-detail-row">
                <small>Sort Order</small>
                <strong id="show_category_sort_order"></strong>
            </div>

            <div class="category-detail-row">
                <small>Status</small>
                <strong id="show_category_status"></strong>
            </div>

            <div class="category-detail-row">
                <small>Created At</small>
                <strong id="show_category_created"></strong>
            </div>

        </div>

        <div class="category-modal-footer">

            <button type="button"
                    class="category-btn category-btn-light"
                    onclick="closeCategoryModal('showCategoryModal')">
                Close
            </button>

        </div>

    </div>

</div>


{{-- ================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ================================================= --}}

<script>

    function openCategoryModal(id) {
        document.getElementById(id).classList.add('show');
    }

    function closeCategoryModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    function getCategoryRow(id) {
        return document.querySelector(
            'tr[data-id="' + id + '"]'
        );
    }

    function editCategory(id) {

        const row = getCategoryRow(id);

        if (!row) return;

        const form = document.getElementById('editCategoryForm');

        form.action =
            "{{ url('/super-admin/project-categories') }}/" + id;

        document.getElementById('edit_category_name').value =
            row.dataset.name || '';

        document.getElementById('edit_category_icon').value =
            row.dataset.icon || '';

        document.getElementById('edit_category_description').value =
            row.dataset.description || '';

        document.getElementById('edit_category_sort_order').value =
            row.dataset.sortOrder || 0;

        document.getElementById('edit_category_status').value =
            row.dataset.status || 'active';

        openCategoryModal('editCategoryModal');
    }

    function showCategory(id) {

        const row = getCategoryRow(id);

        if (!row) return;

        document.getElementById('show_category_name').textContent =
            row.dataset.name || 'N/A';

        document.getElementById('show_category_slug').textContent =
            row.dataset.slug || 'N/A';

        document.getElementById('show_category_icon').textContent =
            row.dataset.icon || 'Not Set';

        document.getElementById('show_category_description').textContent =
            row.dataset.description || 'No description available.';

        document.getElementById('show_category_projects').textContent =
            row.dataset.projects || '0';

        document.getElementById('show_category_sort_order').textContent =
            row.dataset.sortOrder || '0';

        document.getElementById('show_category_status').textContent =
            row.dataset.status || 'N/A';

        document.getElementById('show_category_created').textContent =
            row.dataset.created || 'N/A';

        openCategoryModal('showCategoryModal');
    }

    // Close modal when clicking outside
    document.querySelectorAll('.category-modal').forEach(function(modal) {

        modal.addEventListener('click', function(event) {

            if (event.target === modal) {
                modal.classList.remove('show');
            }

        });

    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            document.querySelectorAll('.category-modal.show')
                .forEach(function(modal) {
                    modal.classList.remove('show');
                });

        }

    });

</script>

@endsection