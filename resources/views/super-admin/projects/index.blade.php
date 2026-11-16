@extends('layouts.app')

@section('content')

<div class="project-page">

    {{-- ================= PAGE HEADER ================= --}}
    <div class="project-header">
        <div>
            <h2>Share Products</h2>
            <p>Manage and organize all company product catalogue listings.</p>
        </div>

        <button type="button" class="btn-primary" onclick="openProjectModal('createProjectModal')">
            <i class="fas fa-plus"></i>
            Add New Product
        </button>
    </div>


    {{-- ================= ALERTS ================= --}}
    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <strong>Please check the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- ================= STATISTICS ================= --}}
    <div class="project-stats">

        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-layer-group"></i></div>
            <div>
                <span>Total Products</span>
                <h3>{{ number_format($totalProjects) }}</h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div>
                <span>Active Products</span>
                <h3>{{ number_format($activeProjects) }}</h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-star"></i></div>
            <div>
                <span>Featured Products</span>
                <h3>{{ number_format($featuredProjects) }}</h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <span>Upcoming Products</span>
                <h3>{{ number_format($upcomingProjects) }}</h3>
            </div>
        </div>

    </div>


    {{-- ================= PRODUCTS TABLE ================= --}}
    <div class="project-card">

        <div class="project-card-header">
            <div>
                <h4>All Share Products</h4>
                <p>View and manage your product catalogue.</p>
            </div>

            <span class="total-badge">
                {{ $projects->total() }} Products
            </span>
        </div>


        {{-- ================= FILTERS ================= --}}
        <form action="{{ route('super-admin.projects.index') }}" method="GET" class="filter-form">

            <div class="filter-search">
                <i class="fas fa-search"></i>
                <input
                    type="text"
                    name="search"
                    placeholder="Search product or company..."
                    value="{{ request('search') }}">
            </div>

            <select name="company">
                <option value="">All Companies</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}"
                        {{ request('company') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>

            <select name="category">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="status">
                <option value="">All Statuses</option>
                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Paused</option>
            </select>

            <button type="submit" class="filter-btn">
                <i class="fas fa-filter"></i>
                Filter
            </button>

            <a href="{{ route('super-admin.projects.index') }}" class="reset-btn">Reset</a>

        </form>


        {{-- ================= TABLE ================= --}}
        <div class="table-responsive">

            <table class="project-table">

                <thead>
                    <tr>
                        <th>PRODUCT</th>
                        <th>COMPANY</th>
                        <th>CATEGORY</th>
                        <th>QUANTITY INFO</th>
                        <th>PRICE / PROFIT</th>
                        <th>STATUS</th>
                        <th>TEMPLATE</th>
                        <th>VISIBILITY</th>
                        <th>ACTION</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($projects as $project)

                        {{-- data-* attributes সরাসরি row-তে রাখা হয়েছে (tbody-র ভিতরে div invalid ছিল) --}}
                        <tr
                            id="project-data-{{ $project->id }}"
                            data-id="{{ $project->id }}"
                            data-name="{{ $project->name }}"
                            data-company="{{ $project->company_id }}"
                            data-company-name="{{ $project->company->name ?? 'N/A' }}"
                            data-category-id="{{ $project->project_category_id }}"
                            data-category-name="{{ $project->projectCategory->name ?? ucwords(str_replace('_', ' ', $project->category)) }}"
                            data-template-id="{{ $project->project_template_id }}"
                            data-template-name="{{ $project->projectTemplate->name ?? ucfirst($project->template) }}"
                            data-description="{{ $project->description }}"
                            data-total="{{ $project->total_shares }}"
                            data-available="{{ $project->available_shares }}"
                            data-price="{{ $project->share_price }}"
                            data-profit="{{ $project->daily_profit_percent }}"
                            data-min-buy="{{ $project->min_buy_shares }}"
                            data-status="{{ $project->status }}"
                            data-start="{{ $project->start_date?->format('Y-m-d') }}"
                            data-end="{{ $project->end_date?->format('Y-m-d') }}"
                            data-order="{{ $project->sort_order }}"
                            data-featured="{{ (int) $project->is_featured }}"
                            data-active="{{ (int) $project->is_active }}"
                            data-image="{{ $project->image ? Storage::disk('public')->url($project->image) : '' }}"
                            data-update-url="{{ route('super-admin.projects.update', $project->id) }}">

                            {{-- PRODUCT --}}
                            <td>
                                <div class="product-info">

                                    <div class="product-image">
                                        @if($project->image)
                                            <img src="{{ Storage::disk('public')->url($project->image) }}"
                                                 alt="{{ $project->name }}">
                                        @else
                                            <div class="no-image"><i class="fas fa-image"></i></div>
                                        @endif
                                    </div>

                                    <div>
                                        <strong>{{ $project->name }}</strong>
                                        <small>{{ $project->slug }}</small>

                                        @if($project->is_featured)
                                            <span class="featured-label">
                                                <i class="fas fa-star"></i> Featured
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            </td>

                            {{-- COMPANY --}}
                            <td>
                                <span class="company-name">{{ $project->company->name ?? 'N/A' }}</span>
                            </td>

                            {{-- CATEGORY --}}
                            <td>
                                <span class="category-badge">
                                    {{ $project->projectCategory->name ?? ucwords(str_replace('_', ' ', $project->category)) }}
                                </span>
                            </td>

                            {{-- QUANTITY --}}
                            <td>
                                <div class="quantity-info">
                                    <strong>
                                        {{ number_format($project->available_shares) }}
                                        /
                                        {{ number_format($project->total_shares) }}
                                    </strong>
                                    <small>Available / Total</small>
                                </div>
                            </td>

                            {{-- PRICE / PROFIT --}}
                            <td>
                                <div class="quantity-info">
                                    <strong>{{ number_format($project->share_price, 2) }}</strong>
                                    <small>{{ rtrim(rtrim(number_format($project->daily_profit_percent, 2), '0'), '.') }}% daily · min {{ $project->min_buy_shares }}</small>
                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                <span class="status-badge {{ $project->status }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </td>

                            {{-- TEMPLATE --}}
                            <td>
                                <span class="template-badge">
                                    {{ $project->projectTemplate->name ?? ucfirst($project->template) }}
                                </span>
                            </td>

                            {{-- VISIBILITY --}}
                            <td>
                                @if($project->is_active)
                                    <span class="visibility active"><i class="fas fa-eye"></i> Visible</span>
                                @else
                                    <span class="visibility inactive"><i class="fas fa-eye-slash"></i> Hidden</span>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td>
                                <div class="action-buttons">

                                    <button type="button" class="action-btn view" title="View"
                                            onclick="showProject({{ $project->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button type="button" class="action-btn edit" title="Edit"
                                            onclick="editProject({{ $project->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('super-admin.projects.destroy', $project->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="action-btn delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <h4>No Share Products Found</h4>
                                    <p>There are no products matching your search criteria.</p>

                                    <button type="button" class="btn-primary"
                                            onclick="openProjectModal('createProjectModal')">
                                        <i class="fas fa-plus"></i>
                                        Add New Product
                                    </button>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ================= PAGINATION ================= --}}
        @if($projects->hasPages())
            <div class="pagination-wrapper">
                {{ $projects->links() }}
            </div>
        @endif

    </div>


    {{-- INFORMATION NOTICE --}}
    <div class="catalogue-note">
        <i class="fas fa-info-circle"></i>
        Product quantities and availability are informational catalogue data only.
    </div>

</div>



{{-- =========================================================
    CREATE PRODUCT MODAL
========================================================= --}}
<div class="custom-modal" id="createProjectModal">

    <div class="custom-modal-content">

        <div class="modal-header">
            <div>
                <h3>Add New Share Product</h3>
                <p>Create a new product catalogue entry.</p>
            </div>

            <button type="button" class="modal-close" onclick="closeProjectModal('createProjectModal')">&times;</button>
        </div>


        <form action="{{ route('super-admin.projects.store') }}" method="POST" enctype="multipart/form-data">

            @csrf
            <input type="hidden" name="_form" value="create">

            <div class="modal-form-body">

                <div class="form-row">

                    <div class="form-group">
                        <label>Company <span>*</span></label>
                        <select name="company_id" class="form-control" required>
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ old('_form') === 'create' && old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Product Name <span>*</span></label>
                        <input type="text" name="name" class="form-control"
                               placeholder="Enter product name"
                               value="{{ old('_form') === 'create' ? old('name') : '' }}" required>
                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">
                        <label>Category <span>*</span></label>
                        <select name="project_category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('_form') === 'create' && old('project_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Display Template <span>*</span></label>
                        <select name="project_template_id" class="form-control" required>
                            <option value="">Select Template</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}"
                                    {{ old('_form') === 'create' && old('project_template_id') == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">
                        <label>Total Quantity <span>*</span></label>
                        <input type="number" name="total_shares" class="form-control" min="1"
                               value="{{ old('_form') === 'create' ? old('total_shares') : '' }}"
                               placeholder="e.g. 1000" required>
                    </div>

                    <div class="form-group">
                        <label>Available Quantity</label>
                        <input type="number" name="available_shares" class="form-control" min="0"
                               value="{{ old('_form') === 'create' ? old('available_shares') : '' }}"
                               placeholder="Leave empty = same as total">
                        <small class="input-help">Empty রাখলে Total Quantity-ই Available হবে।</small>
                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">
                        <label>Share Price <span>*</span></label>
                        <input type="number" name="share_price" class="form-control" min="1" step="0.01"
                               value="{{ old('_form') === 'create' ? old('share_price') : '' }}"
                               placeholder="e.g. 500" required>
                    </div>

                    <div class="form-group">
                        <label>Daily Profit (%) <span>*</span></label>
                        <input type="number" name="daily_profit_percent" class="form-control"
                               min="0" max="100" step="0.01"
                               value="{{ old('_form') === 'create' ? old('daily_profit_percent') : '' }}"
                               placeholder="e.g. 1.5" required>
                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">
                        <label>Minimum Buy Quantity <span>*</span></label>
                        <input type="number" name="min_buy_shares" class="form-control" min="1"
                               value="{{ old('_form') === 'create' ? old('min_buy_shares', 1) : 1 }}" required>
                    </div>

                    <div class="form-group">
                        <label>Status <span>*</span></label>
                        <select name="status" class="form-control" required>
                            @foreach(['upcoming' => 'Upcoming', 'active' => 'Active', 'completed' => 'Completed', 'paused' => 'Paused'] as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('_form') === 'create' && old('status') == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>


                <div class="form-group">
                    <label>Product Image</label>
                    <input type="file" name="image" class="form-control"
                           accept="image/png,image/jpeg,image/webp">
                    <small class="input-help">JPG, PNG or WEBP. Maximum 2MB.</small>
                </div>


                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4"
                              placeholder="Enter product description">{{ old('_form') === 'create' ? old('description') : '' }}</textarea>
                </div>


                <div class="form-row">

                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control"
                               value="{{ old('_form') === 'create' ? old('start_date') : '' }}">
                    </div>

                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control"
                               value="{{ old('_form') === 'create' ? old('end_date') : '' }}">
                    </div>

                </div>


                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0"
                           value="{{ old('_form') === 'create' ? old('sort_order', 0) : 0 }}">
                </div>


                <div class="form-checkboxes">

                    <label>
                        <input type="checkbox" name="is_featured" value="1"
                               {{ old('_form') === 'create' && old('is_featured') ? 'checked' : '' }}>
                        Featured Product
                    </label>

                    <label>
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('_form') === 'create' ? (old('is_active') ? 'checked' : '') : 'checked' }}>
                        Visible on Website
                    </label>

                </div>

            </div>


            <div class="modal-footer">
                <button type="button" class="modal-cancel" onclick="closeProjectModal('createProjectModal')">Cancel</button>

                <button type="submit" class="modal-submit">
                    <i class="fas fa-plus"></i>
                    Create Product
                </button>
            </div>

        </form>

    </div>

</div>



{{-- =========================================================
    EDIT PRODUCT MODAL
========================================================= --}}
<div class="custom-modal" id="editProjectModal">

    <div class="custom-modal-content">

        <div class="modal-header">
            <div>
                <h3>Edit Share Product</h3>
                <p>Update product catalogue information.</p>
            </div>

            <button type="button" class="modal-close" onclick="closeProjectModal('editProjectModal')">&times;</button>
        </div>


        <form id="editProjectForm" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')
            <input type="hidden" name="_form" value="edit">
            <input type="hidden" name="_edit_id" id="editProjectId">

            <div class="modal-form-body">

                <div class="form-row">

                    <div class="form-group">
                        <label>Company <span>*</span></label>
                        <select name="company_id" class="form-control" required>
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Product Name <span>*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">
                        <label>Category <span>*</span></label>
                        <select name="project_category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Display Template <span>*</span></label>
                        <select name="project_template_id" class="form-control" required>
                            <option value="">Select Template</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">
                        <label>Total Quantity <span>*</span></label>
                        <input type="number" name="total_shares" class="form-control" min="1" required>
                    </div>

                    <div class="form-group">
                        <label>Available Quantity</label>
                        <input type="number" id="editAvailable" class="form-control" disabled>
                        <small class="input-help">Auto হিসাব হয় (Total − Sold)। এখান থেকে এডিট করা যাবে না।</small>
                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">
                        <label>Share Price <span>*</span></label>
                        <input type="number" name="share_price" class="form-control" min="1" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label>Daily Profit (%) <span>*</span></label>
                        <input type="number" name="daily_profit_percent" class="form-control"
                               min="0" max="100" step="0.01" required>
                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">
                        <label>Minimum Buy Quantity <span>*</span></label>
                        <input type="number" name="min_buy_shares" class="form-control" min="1" required>
                    </div>

                    <div class="form-group">
                        <label>Status <span>*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="upcoming">Upcoming</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="paused">Paused</option>
                        </select>
                    </div>

                </div>


                <div class="form-group">
                    <label>Product Image</label>
                    <input type="file" name="image" class="form-control"
                           accept="image/png,image/jpeg,image/webp">
                    <small class="input-help">Leave empty to keep the current image.</small>

                    <div id="editImagePreview" class="edit-image-preview"></div>
                </div>


                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                </div>


                <div class="form-row">

                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>

                </div>


                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0">
                </div>


                <div class="form-checkboxes">

                    <label>
                        <input type="checkbox" name="is_featured" value="1">
                        Featured Product
                    </label>

                    <label>
                        <input type="checkbox" name="is_active" value="1">
                        Visible on Website
                    </label>

                </div>

            </div>


            <div class="modal-footer">
                <button type="button" class="modal-cancel" onclick="closeProjectModal('editProjectModal')">Cancel</button>

                <button type="submit" class="modal-submit">
                    <i class="fas fa-save"></i>
                    Update Product
                </button>
            </div>

        </form>

    </div>

</div>



{{-- =========================================================
    SHOW PRODUCT MODAL
========================================================= --}}
<div class="custom-modal" id="showProjectModal">

    <div class="custom-modal-content">

        <div class="modal-header">
            <div>
                <h3>Share Product Details</h3>
                <p>Complete product catalogue information.</p>
            </div>

            <button type="button" class="modal-close" onclick="closeProjectModal('showProjectModal')">&times;</button>
        </div>


        <div class="show-project-content">

            <div class="show-project-image" id="showProjectImage">
                <i class="fas fa-image"></i>
            </div>

            <div class="show-project-heading">
                <h3 id="showProjectName"></h3>
                <p id="showProjectCompany"></p>
            </div>


            <div class="show-project-grid">

                <div><small>Category</small><strong id="showProjectCategory"></strong></div>
                <div><small>Status</small><strong id="showProjectStatus"></strong></div>
                <div><small>Total Quantity</small><strong id="showProjectTotal"></strong></div>
                <div><small>Available Quantity</small><strong id="showProjectAvailable"></strong></div>
                <div><small>Share Price</small><strong id="showProjectPrice"></strong></div>
                <div><small>Daily Profit</small><strong id="showProjectProfit"></strong></div>
                <div><small>Minimum Buy Quantity</small><strong id="showProjectMinBuy"></strong></div>
                <div><small>Display Template</small><strong id="showProjectTemplate"></strong></div>
                <div><small>Visibility</small><strong id="showProjectVisibility"></strong></div>
                <div><small>Featured</small><strong id="showProjectFeatured"></strong></div>
                <div><small>Start Date</small><strong id="showProjectStart"></strong></div>
                <div><small>End Date</small><strong id="showProjectEnd"></strong></div>

            </div>


            <div class="show-description">
                <small>Description</small>
                <p id="showProjectDescription"></p>
            </div>

        </div>


        <div class="modal-footer">
            <button type="button" class="modal-cancel" onclick="closeProjectModal('showProjectModal')">Close</button>
        </div>

    </div>

</div>



{{-- =========================================================
    JAVASCRIPT
========================================================= --}}
<script>

    // Open Modal
    function openProjectModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }


    // Close Modal
    function closeProjectModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;

        modal.classList.remove('active');
        document.body.style.overflow = '';
    }


    // Get Product Data
    function getProjectData(id) {
        return document.getElementById('project-data-' + id);
    }


    // Edit Product
    function editProject(id) {

        const data = getProjectData(id);
        if (!data) return;

        const form = document.getElementById('editProjectForm');
        const d = data.dataset;

        form.reset();

        form.action = d.updateUrl;

        document.getElementById('editProjectId').value = d.id;

        form.querySelector('[name="company_id"]').value = d.company;
        form.querySelector('[name="name"]').value = d.name;
        form.querySelector('[name="project_category_id"]').value = d.categoryId || '';
        form.querySelector('[name="project_template_id"]').value = d.templateId || '';
        form.querySelector('[name="total_shares"]').value = d.total;
        document.getElementById('editAvailable').value = d.available;
        form.querySelector('[name="share_price"]').value = d.price;
        form.querySelector('[name="daily_profit_percent"]').value = d.profit;
        form.querySelector('[name="min_buy_shares"]').value = d.minBuy;
        form.querySelector('[name="status"]').value = d.status;
        form.querySelector('[name="description"]').value = d.description || '';
        form.querySelector('[name="start_date"]').value = d.start || '';
        form.querySelector('[name="end_date"]').value = d.end || '';
        form.querySelector('[name="sort_order"]').value = d.order || 0;
        form.querySelector('[name="is_featured"]').checked = d.featured === '1';
        form.querySelector('[name="is_active"]').checked = d.active === '1';

        // Current Image Preview
        const preview = document.getElementById('editImagePreview');
        preview.innerHTML = '';

        if (d.image) {
            const image = document.createElement('img');
            image.src = d.image;
            image.alt = d.name;
            preview.appendChild(image);
        }

        openProjectModal('editProjectModal');
    }


    // Show Product
    function showProject(id) {

        const data = getProjectData(id);
        if (!data) return;

        const d = data.dataset;
        const set = (elId, value) => document.getElementById(elId).textContent = value;

        set('showProjectName', d.name);
        set('showProjectCompany', d.companyName);
        set('showProjectCategory', d.categoryName);
        set('showProjectStatus', d.status);
        set('showProjectTotal', Number(d.total).toLocaleString());
        set('showProjectAvailable', Number(d.available).toLocaleString());
        set('showProjectPrice', Number(d.price).toLocaleString(undefined, {minimumFractionDigits: 2}));
        set('showProjectProfit', Number(d.profit) + '%');
        set('showProjectMinBuy', Number(d.minBuy).toLocaleString());
        set('showProjectTemplate', d.templateName);
        set('showProjectVisibility', d.active === '1' ? 'Visible' : 'Hidden');
        set('showProjectStart', d.start || 'Not specified');
        set('showProjectEnd', d.end || 'Not specified');
        set('showProjectFeatured', d.featured === '1' ? 'Yes' : 'No');
        set('showProjectDescription', d.description || 'No description available.');

        // Product Image
        const imageContainer = document.getElementById('showProjectImage');
        imageContainer.innerHTML = '';

        if (d.image) {
            const image = document.createElement('img');
            image.src = d.image;
            image.alt = d.name;
            imageContainer.appendChild(image);
        } else {
            imageContainer.innerHTML = '<i class="fas fa-image"></i>';
        }

        openProjectModal('showProjectModal');
    }


    // Close Modal When Clicking Outside
    document.querySelectorAll('.custom-modal').forEach(modal => {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeProjectModal(modal.id);
            }
        });
    });


    // Escape Key
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.custom-modal.active').forEach(modal => {
                closeProjectModal(modal.id);
            });
        }
    });


    // Validation error হলে সংশ্লিষ্ট modal আবার খুলে দেওয়া
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {

            const old = @json(old());

            if (old._form === 'create') {
                openProjectModal('createProjectModal');
            }

            if (old._form === 'edit' && old._edit_id) {

                editProject(old._edit_id);

                const form = document.getElementById('editProjectForm');

                ['company_id', 'name', 'project_category_id', 'project_template_id',
                 'total_shares', 'share_price', 'daily_profit_percent', 'min_buy_shares',
                 'status', 'description', 'start_date', 'end_date', 'sort_order'
                ].forEach(function (field) {
                    if (old[field] !== undefined && old[field] !== null) {
                        form.querySelector('[name="' + field + '"]').value = old[field];
                    }
                });

                form.querySelector('[name="is_featured"]').checked = !!old.is_featured;
                form.querySelector('[name="is_active"]').checked = !!old.is_active;
            }
        });
    @endif

</script>



{{-- =========================================================
    CSS
========================================================= --}}
<style>

/* PAGE */

.project-page {
    padding: 25px;
    color: #1e293b;
}

.project-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.project-header h2 {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 6px;
    color: #172554;
}

.project-header p {
    color: #64748b;
    font-size: 13px;
    margin: 0;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 17px;
    background: #172554;
    color: #fff;
    border-radius: 7px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    background: #263d78;
    color: #fff;
}


/* ALERTS */

.alert-success {
    padding: 13px 16px;
    background: #dcfce7;
    color: #166534;
    border-radius: 7px;
    margin-bottom: 20px;
    font-size: 13px;
}

.alert-error {
    padding: 13px 16px;
    background: #fee2e2;
    color: #991b1b;
    border-radius: 7px;
    margin-bottom: 20px;
    font-size: 13px;
}

.alert-error ul {
    margin: 8px 0 0;
    padding-left: 20px;
}


/* STATISTICS */

.project-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 17px;
    margin-bottom: 25px;
}

.stat-card {
    background: #fff;
    padding: 20px;
    border: 1px solid #e8edf4;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    width: 45px;
    height: 45px;
    border-radius: 9px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 18px;
    flex-shrink: 0;
}

.stat-icon.blue { background: #dbeafe; color: #2563eb; }
.stat-icon.green { background: #dcfce7; color: #16a34a; }
.stat-icon.orange { background: #ffedd5; color: #ea580c; }
.stat-icon.purple { background: #ede9fe; color: #7c3aed; }

.stat-card span {
    font-size: 12px;
    color: #64748b;
}

.stat-card h3 {
    font-size: 23px;
    margin: 5px 0 0;
    font-weight: 700;
    color: #172554;
}


/* CARD */

.project-card {
    background: #fff;
    border: 1px solid #e8edf4;
    border-radius: 10px;
    overflow: hidden;
}

.project-card-header {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #edf0f5;
}

.project-card-header h4 {
    font-size: 16px;
    font-weight: 700;
    color: #172554;
    margin: 0 0 5px;
}

.project-card-header p {
    color: #94a3b8;
    font-size: 12px;
    margin: 0;
}

.total-badge {
    background: #eff6ff;
    color: #2563eb;
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}


/* FILTERS */

.filter-form {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    padding: 18px 20px;
    border-bottom: 1px solid #edf0f5;
}

.filter-search {
    position: relative;
    flex: 1;
    min-width: 200px;
}

.filter-search i {
    position: absolute;
    left: 12px;
    top: 13px;
    color: #94a3b8;
    font-size: 13px;
}

.filter-search input {
    width: 100%;
    padding: 10px 12px 10px 35px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    outline: none;
    font-size: 12px;
}

.filter-form select {
    min-width: 135px;
    padding: 10px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    background: #fff;
    color: #475569;
    font-size: 12px;
    outline: none;
}

.filter-search input:focus,
.filter-form select:focus {
    border-color: #647fbd;
}

.filter-btn,
.reset-btn {
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
}

.filter-btn {
    border: none;
    background: #172554;
    color: #fff;
}

.reset-btn {
    background: #f1f5f9;
    color: #475569;
}


/* TABLE */

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.project-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1250px;
}

.project-table thead {
    background: #f8fafc;
}

.project-table th {
    padding: 14px 16px;
    text-align: left;
    font-size: 10px;
    font-weight: 700;
    color: #64748b;
    white-space: nowrap;
}

.project-table td {
    padding: 15px 16px;
    border-top: 1px solid #f1f5f9;
    font-size: 12px;
    vertical-align: middle;
}

.project-table tbody tr:hover {
    background: #fafcff;
}


/* PRODUCT */

.product-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 190px;
}

.product-image,
.no-image {
    width: 43px;
    height: 43px;
    border-radius: 7px;
    overflow: hidden;
    flex-shrink: 0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 16px;
}

.product-info strong {
    display: block;
    color: #1e293b;
    font-size: 12px;
    margin-bottom: 4px;
}

.product-info small {
    display: block;
    color: #94a3b8;
    font-size: 10px;
}

.featured-label {
    display: inline-block;
    color: #d97706;
    background: #fef3c7;
    padding: 3px 6px;
    border-radius: 4px;
    font-size: 9px;
    margin-top: 5px;
}

.company-name {
    color: #475569;
    font-weight: 600;
}


/* BADGES */

.category-badge,
.template-badge {
    display: inline-block;
    padding: 6px 9px;
    border-radius: 5px;
    background: #f1f5f9;
    color: #475569;
    font-size: 10px;
    white-space: nowrap;
}

.quantity-info strong {
    display: block;
    color: #172554;
    font-size: 12px;
}

.quantity-info small {
    color: #94a3b8;
    font-size: 10px;
}

.status-badge {
    display: inline-block;
    padding: 6px 9px;
    border-radius: 5px;
    font-size: 10px;
    font-weight: 600;
}

.status-badge.active { background: #dcfce7; color: #15803d; }
.status-badge.upcoming { background: #dbeafe; color: #1d4ed8; }
.status-badge.completed { background: #f1f5f9; color: #475569; }
.status-badge.paused { background: #ffedd5; color: #c2410c; }

.visibility {
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.visibility.active { color: #16a34a; }
.visibility.inactive { color: #dc2626; }


/* ACTIONS */

.action-buttons {
    display: flex;
    align-items: center;
    gap: 6px;
}

.action-buttons form {
    margin: 0;
}

.action-btn {
    width: 30px;
    height: 30px;
    border: none;
    border-radius: 5px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-decoration: none;
    font-size: 11px;
}

.action-btn.view { background: #dbeafe; color: #2563eb; }
.action-btn.edit { background: #fef3c7; color: #d97706; }
.action-btn.delete { background: #fee2e2; color: #dc2626; }

.action-btn:hover {
    opacity: .75;
}


/* EMPTY STATE */

.empty-state {
    text-align: center;
    padding: 55px 20px;
}

.empty-state > i {
    font-size: 40px;
    color: #cbd5e1;
    margin-bottom: 15px;
}

.empty-state h4 {
    color: #334155;
    font-size: 16px;
    margin-bottom: 7px;
}

.empty-state p {
    color: #94a3b8;
    font-size: 12px;
    margin-bottom: 20px;
}


/* PAGINATION */

.pagination-wrapper {
    padding: 18px 20px;
    border-top: 1px solid #edf0f5;
}


/* NOTICE */

.catalogue-note {
    margin-top: 15px;
    padding: 12px 15px;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    color: #1d4ed8;
    border-radius: 7px;
    font-size: 11px;
}

.catalogue-note i {
    margin-right: 5px;
}


/* MODALS */

.custom-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(15, 23, 42, .65);
    padding: 25px;
    align-items: center;
    justify-content: center;
}

.custom-modal.active {
    display: flex;
}

.custom-modal-content {
    width: 100%;
    max-width: 750px;
    max-height: 90vh;
    overflow-y: auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.modal-header h3 {
    margin: 0 0 5px;
    font-size: 17px;
    color: #172554;
}

.modal-header p {
    margin: 0;
    font-size: 11px;
    color: #94a3b8;
}

.modal-close {
    border: none;
    background: transparent;
    font-size: 27px;
    cursor: pointer;
    color: #64748b;
}

.modal-form-body {
    padding: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.form-group {
    margin-bottom: 17px;
}

.form-group label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 7px;
}

.form-group label span {
    color: #dc2626;
}

.form-control {
    width: 100%;
    padding: 11px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    outline: none;
    font-size: 12px;
    background: #fff;
    box-sizing: border-box;
}

.form-control:focus {
    border-color: #647fbd;
}

.form-control:disabled {
    background: #f1f5f9;
    color: #64748b;
    cursor: not-allowed;
}

.input-help {
    display: block;
    margin-top: 5px;
    color: #94a3b8;
    font-size: 10px;
}

.form-checkboxes {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 15px;
    font-size: 12px;
    color: #475569;
}

.form-checkboxes label {
    display: flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
}

.edit-image-preview {
    margin-top: 10px;
}

.edit-image-preview img {
    width: 75px;
    height: 75px;
    object-fit: cover;
    border-radius: 7px;
    border: 1px solid #e2e8f0;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 20px;
    border-top: 1px solid #e2e8f0;
}

.modal-cancel,
.modal-submit {
    padding: 10px 17px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    border: none;
}

.modal-cancel {
    background: #f1f5f9;
    color: #475569;
}

.modal-submit {
    background: #172554;
    color: #fff;
}


/* SHOW MODAL */

.show-project-content {
    padding: 25px;
}

.show-project-image {
    width: 100%;
    height: 180px;
    background: #f1f5f9;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 35px;
    margin-bottom: 20px;
    overflow: hidden;
}

.show-project-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.show-project-heading h3 {
    margin: 0 0 5px;
    color: #172554;
    font-size: 20px;
}

.show-project-heading p {
    color: #64748b;
    font-size: 12px;
    margin: 0;
}

.show-project-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-top: 20px;
}

.show-project-grid div {
    padding: 13px;
    background: #f8fafc;
    border-radius: 7px;
}

.show-project-grid small,
.show-project-grid strong {
    display: block;
}

.show-project-grid small,
.show-description small {
    color: #94a3b8;
    font-size: 11px;
    margin-bottom: 6px;
}

.show-project-grid strong {
    color: #334155;
    font-size: 13px;
    text-transform: capitalize;
}

.show-description {
    margin-top: 18px;
}

.show-description p {
    font-size: 12px;
    line-height: 1.7;
    color: #475569;
    white-space: pre-line;
}


/* RESPONSIVE */

@media(max-width: 1100px) {
    .project-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width: 600px) {

    .project-page {
        padding: 15px;
    }

    .project-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .project-stats {
        grid-template-columns: 1fr;
    }

    .filter-form {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-search {
        min-width: 100%;
    }

    .filter-form select {
        width: 100%;
    }

    .form-row,
    .show-project-grid {
        grid-template-columns: 1fr;
    }

    .custom-modal {
        padding: 10px;
    }

}

</style>

@endsection