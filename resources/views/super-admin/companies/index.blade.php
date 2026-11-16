@extends('layouts.app')

@section('title', 'Company Management')
@section('page-title', 'Company Management')

@section('content')

<div class="company-management">

    {{-- PAGE HEADER --}}
    <div class="company-page-header">
        <div>
            <h2>Company Management</h2>
            <p>Manage and organize companies registered on your platform.</p>
        </div>

        <button type="button" class="company-add-btn" onclick="openCompanyModal()">
            <span>+</span> Add New Company
        </button>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="company-alert success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="company-alert error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="company-alert error">
            <div>
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- STATISTICS --}}
    <div class="company-stats">

        <div class="company-stat-card">
            <div class="company-stat-icon blue">▣</div>
            <div>
                <span>Total Companies</span>
                <h3>{{ number_format($totalCompanies) }}</h3>
            </div>
        </div>

        <div class="company-stat-card">
            <div class="company-stat-icon green">✓</div>
            <div>
                <span>Active Companies</span>
                <h3>{{ number_format($activeCompanies) }}</h3>
            </div>
        </div>

        <div class="company-stat-card">
            <div class="company-stat-icon orange">◷</div>
            <div>
                <span>Inactive Companies</span>
                <h3>{{ number_format($inactiveCompanies) }}</h3>
            </div>
        </div>

    </div>

    {{-- COMPANY TABLE --}}
    <div class="company-table-card">

        <div class="company-table-header">
            <div>
                <h3>All Companies</h3>
                <p>View and manage all registered companies.</p>
            </div>

            <span class="company-total-badge">
                {{ $companies->total() }} Companies
            </span>
        </div>

        <div class="company-table-responsive">

            <table class="company-table">

                <thead>
                    <tr>
                        <th>COMPANY</th>
                        <th>WEBSITE</th>
                        <th>PRODUCTS</th>
                        <th>STATUS</th>
                        <th>CREATED</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($companies as $company)

                        <tr>

                            {{-- COMPANY --}}
                            <td>
                                <div class="company-info">

                                    <div class="company-logo">
                                        @if($company->logo)
                                            <img
                                                src="{{ asset('storage/'.$company->logo) }}"
                                                alt="{{ $company->name }}"
                                            >
                                        @else
                                            <span>
                                                {{ strtoupper(substr($company->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div>
                                        <strong>{{ $company->name }}</strong>
                                        <small>{{ $company->slug }}</small>
                                    </div>

                                </div>
                            </td>

                            {{-- WEBSITE --}}
                            <td>
                                @if($company->website_url)
                                    <a
                                        href="{{ $company->website_url }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="company-website"
                                    >
                                        Visit Website ↗
                                    </a>
                                @else
                                    <span class="company-muted">Not added</span>
                                @endif
                            </td>

                            {{-- PRODUCTS --}}
                            <td>
                                <span class="company-product-count">
                                    {{ number_format($company->projects_count ?? 0) }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($company->status === 'active')
                                    <span class="company-status active">
                                        <span></span> Active
                                    </span>
                                @else
                                    <span class="company-status inactive">
                                        <span></span> Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- CREATED --}}
                            <td>
                                <span class="company-date">
                                    {{ $company->created_at->format('d M Y') }}
                                </span>
                            </td>

                            {{-- ACTIONS --}}
                            <td>

                                <div class="company-actions">

                                    {{-- SHOW --}}
                                    <button
                                        type="button"
                                        class="company-action-btn view"
                                        title="View Company"
                                        data-name="{{ $company->name }}"
                                        data-slug="{{ $company->slug }}"
                                        data-website="{{ $company->website_url }}"
                                        data-description="{{ $company->description }}"
                                        data-status="{{ $company->status }}"
                                        data-logo="{{ $company->logo ? asset('storage/'.$company->logo) : '' }}"
                                        data-products="{{ $company->projects_count ?? 0 }}"
                                        data-created="{{ $company->created_at->format('d M Y') }}"
                                        onclick="openShowCompanyModal(this)"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    {{-- EDIT --}}
                                    <button
                                        type="button"
                                        class="company-action-btn edit"
                                        title="Edit Company"
                                        data-name="{{ $company->name }}"
                                        data-website="{{ $company->website_url }}"
                                        data-description="{{ $company->description }}"
                                        data-status="{{ $company->status }}"
                                        data-logo="{{ $company->logo ? asset('storage/'.$company->logo) : '' }}"
                                        data-update-url="{{ route('super-admin.companies.update', $company) }}"
                                        onclick="openEditCompanyModal(this)"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route('super-admin.companies.destroy', $company) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete {{ addslashes($company->name) }}?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="company-action-btn delete"
                                            title="Delete Company"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">

                                <div class="company-empty-state">

                                    <div class="company-empty-icon">
                                        <i class="fas fa-building"></i>
                                    </div>

                                    <h3>No Companies Found</h3>

                                    <p>You haven't added any companies yet.</p>

                                    <button
                                        type="button"
                                        class="company-empty-add-btn"
                                        onclick="openCompanyModal()"
                                    >
                                        + Add Your First Company
                                    </button>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        @if($companies->hasPages())
            <div class="company-pagination">
                {{ $companies->links() }}
            </div>
        @endif

    </div>

</div>


{{-- =====================================================
    CREATE COMPANY MODAL
===================================================== --}}

<div class="company-modal-overlay" id="companyCreateModal">

    <div class="company-modal">

        <div class="company-modal-header">
            <div>
                <h3>Add New Company</h3>
                <p>Register a new company on your platform.</p>
            </div>

            <button type="button" class="company-modal-close" onclick="closeCompanyModal()">
                &times;
            </button>
        </div>

        <form
            action="{{ route('super-admin.companies.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            <div class="company-modal-body">

                <div class="company-modal-group">
                    <label>Company Name <span>*</span></label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter company name"
                        maxlength="150"
                        required
                    >

                    @error('name')
                        <small class="company-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="company-modal-group">
                    <label>Website URL</label>

                    <input
                        type="url"
                        name="website_url"
                        value="{{ old('website_url') }}"
                        placeholder="https://example.com"
                    >

                    @error('website_url')
                        <small class="company-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="company-modal-group">
                    <label>Company Logo</label>

                    <input
                        type="file"
                        name="logo"
                        id="company_logo"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small>JPG, PNG or WEBP. Maximum size 2MB.</small>

                    <div class="company-logo-preview" id="companyLogoPreview">
                        <img src="" alt="Logo Preview">
                    </div>

                    @error('logo')
                        <small class="company-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="company-modal-group">
                    <label>Company Description</label>

                    <textarea
                        name="description"
                        rows="5"
                        placeholder="Write a short description..."
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <small class="company-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="company-modal-group">
                    <label>Company Status</label>

                    <select name="status">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>

            <div class="company-modal-footer">

                <button type="button" class="company-modal-cancel" onclick="closeCompanyModal()">
                    Cancel
                </button>

                <button type="submit" class="company-modal-submit">
                    <i class="fas fa-save"></i> Save Company
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =====================================================
    EDIT COMPANY MODAL
===================================================== --}}

<div class="company-modal-overlay" id="companyEditModal">

    <div class="company-modal">

        <div class="company-modal-header">
            <div>
                <h3>Edit Company</h3>
                <p>Update company information.</p>
            </div>

            <button type="button" class="company-modal-close" onclick="closeEditCompanyModal()">
                &times;
            </button>
        </div>

        <form
            id="companyEditForm"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            <div class="company-modal-body">

                <div class="company-modal-group">
                    <label>Company Name <span>*</span></label>

                    <input
                        type="text"
                        name="name"
                        id="edit_company_name"
                        required
                        maxlength="150"
                    >
                </div>

                <div class="company-modal-group">
                    <label>Website URL</label>

                    <input
                        type="url"
                        name="website_url"
                        id="edit_company_website"
                        placeholder="https://example.com"
                    >
                </div>

                <div class="company-modal-group">
                    <label>Company Logo</label>

                    <input
                        type="file"
                        name="logo"
                        id="edit_company_logo"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small>Upload a new logo to replace the existing one.</small>

                    <div class="company-logo-preview" id="editCompanyLogoPreview">
                        <img src="" alt="Logo Preview">
                    </div>
                </div>

                <div class="company-modal-group">
                    <label>Company Description</label>

                    <textarea
                        name="description"
                        id="edit_company_description"
                        rows="5"
                        placeholder="Write a short description..."
                    ></textarea>
                </div>

                <div class="company-modal-group">
                    <label>Company Status</label>

                    <select name="status" id="edit_company_status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

            </div>

            <div class="company-modal-footer">

                <button type="button" class="company-modal-cancel" onclick="closeEditCompanyModal()">
                    Cancel
                </button>

                <button type="submit" class="company-modal-submit">
                    <i class="fas fa-save"></i> Update Company
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =====================================================
    SHOW COMPANY MODAL
===================================================== --}}

<div class="company-modal-overlay" id="companyShowModal">

    <div class="company-modal company-show-modal">

        <div class="company-modal-header">

            <div>
                <h3>Company Details</h3>
                <p>View complete company information.</p>
            </div>

            <button type="button" class="company-modal-close" onclick="closeShowCompanyModal()">
                &times;
            </button>

        </div>

        <div class="company-modal-body">

            <div class="company-show-profile">

                <div class="company-show-logo" id="showCompanyLogo">
                    <i class="fas fa-building"></i>
                </div>

                <div>
                    <h3 id="showCompanyName">Company Name</h3>
                    <span id="showCompanySlug">company-slug</span>
                </div>

            </div>

            <div class="company-show-grid">

                <div class="company-show-item">
                    <span>Company Status</span>
                    <strong id="showCompanyStatus">Active</strong>
                </div>

                <div class="company-show-item">
                    <span>Total Products</span>
                    <strong id="showCompanyProducts">0</strong>
                </div>

                <div class="company-show-item">
                    <span>Created Date</span>
                    <strong id="showCompanyCreated">-</strong>
                </div>

                <div class="company-show-item">
                    <span>Website</span>
                    <strong id="showCompanyWebsite">Not added</strong>
                </div>

            </div>

            <div class="company-show-description">
                <h4>Company Description</h4>
                <p id="showCompanyDescription">
                    No description available.
                </p>
            </div>

        </div>

        <div class="company-modal-footer">

            <button
                type="button"
                class="company-modal-cancel"
                onclick="closeShowCompanyModal()"
            >
                Close
            </button>

        </div>

    </div>

</div>


<style>

/* =========================================
   GENERAL
========================================= */

.company-management {
    width: 100%;
}

.company-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.company-page-header h2 {
    margin: 0 0 7px;
    font-size: 23px;
    font-weight: 700;
    color: #172033;
}

.company-page-header p,
.company-table-header p {
    margin: 0;
    color: #8490a3;
    font-size: 13px;
}

.company-add-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 12px 18px;
    background: #172d55;
    color: #fff;
    border: none;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s;
}

.company-add-btn:hover {
    background: #244477;
    transform: translateY(-2px);
}

.company-add-btn span {
    font-size: 20px;
}

/* ALERT */

.company-alert {
    padding: 14px 17px;
    border-radius: 9px;
    margin-bottom: 20px;
    font-size: 13px;
}

.company-alert.success {
    color: #137547;
    background: #eaf8f0;
    border: 1px solid #c8ecd7;
}

.company-alert.error {
    color: #b42318;
    background: #fff0ef;
    border: 1px solid #ffd5d1;
}

.company-alert ul {
    margin: 7px 0 0;
    padding-left: 18px;
}

/* STATISTICS */

.company-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 25px;
}

.company-stat-card {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 12px;
    padding: 21px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 3px 12px rgba(20,35,60,.025);
}

.company-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
    font-weight: 700;
}

.company-stat-icon.blue {
    background: #edf3ff;
    color: #3568c8;
}

.company-stat-icon.green {
    background: #e9f8f0;
    color: #15945b;
}

.company-stat-icon.orange {
    background: #fff4e7;
    color: #e58b27;
}

.company-stat-card span {
    display: block;
    color: #8490a3;
    font-size: 12px;
    margin-bottom: 6px;
}

.company-stat-card h3 {
    margin: 0;
    color: #172033;
    font-size: 24px;
}

/* TABLE */

.company-table-card {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 12px rgba(20,35,60,.025);
}

.company-table-header {
    padding: 21px 23px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border-bottom: 1px solid #edf0f5;
}

.company-table-header h3 {
    margin: 0 0 6px;
    font-size: 16px;
    color: #172033;
}

.company-total-badge {
    background: #f0f4fb;
    color: #345b96;
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.company-table-responsive {
    width: 100%;
    overflow-x: auto;
}

.company-table {
    width: 100%;
    min-width: 850px;
    border-collapse: collapse;
}

.company-table thead {
    background: #f8f9fc;
}

.company-table th {
    padding: 14px 20px;
    color: #8490a3;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .5px;
    text-align: left;
}

.company-table td {
    padding: 16px 20px;
    border-top: 1px solid #f0f2f6;
    vertical-align: middle;
}

.company-table tbody tr:hover {
    background: #fbfcfe;
}

/* COMPANY INFO */

.company-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 190px;
}

.company-logo {
    width: 43px;
    height: 43px;
    border-radius: 9px;
    overflow: hidden;
    background: #edf2fa;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.company-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.company-logo span {
    color: #345b96;
    font-size: 18px;
    font-weight: 700;
}

.company-info strong {
    display: block;
    font-size: 13px;
    color: #26334a;
}

.company-info small {
    display: block;
    margin-top: 5px;
    font-size: 11px;
    color: #929bad;
}

.company-website {
    color: #376ac4;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
}

.company-website:hover {
    text-decoration: underline;
}

.company-muted {
    color: #a0a8b6;
    font-size: 12px;
}

.company-product-count {
    background: #f0f4fb;
    color: #345b96;
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 12px;
    font-weight: 700;
}

/* STATUS */

.company-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.company-status > span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.company-status.active {
    background: #e9f8f0;
    color: #16814f;
}

.company-status.active > span {
    background: #20a568;
}

.company-status.inactive {
    background: #fff1ed;
    color: #c65b3c;
}

.company-status.inactive > span {
    background: #e7795a;
}

.company-date {
    color: #647087;
    font-size: 12px;
    white-space: nowrap;
}

/* ACTION BUTTONS */

.company-actions {
    display: flex;
    align-items: center;
    gap: 7px;
}

.company-actions form {
    margin: 0;
}

.company-action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-decoration: none;
    font-size: 13px;
    transition: .2s;
}

.company-action-btn.view {
    background: #eaf7f5;
    color: #168577;
}

.company-action-btn.edit {
    background: #edf3ff;
    color: #3568c8;
}

.company-action-btn.delete {
    background: #fff0ef;
    color: #d34c43;
}

.company-action-btn:hover {
    transform: translateY(-2px);
}

/* EMPTY STATE */

.company-empty-state {
    text-align: center;
    padding: 55px 20px;
}

.company-empty-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    border-radius: 15px;
    background: #f0f4fb;
    color: #5276b0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 25px;
}

.company-empty-state h3 {
    margin: 0 0 8px;
    color: #26334a;
    font-size: 17px;
}

.company-empty-state p {
    color: #8994a7;
    font-size: 13px;
    margin-bottom: 18px;
}

.company-empty-add-btn {
    background: #172d55;
    color: #fff;
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.company-empty-add-btn:hover {
    background: #244477;
}

/* PAGINATION */

.company-pagination {
    padding: 18px 22px;
    border-top: 1px solid #edf0f5;
}

/* MODAL */

.company-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.60);
    backdrop-filter: blur(3px);
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto;
}

.company-modal-overlay.show {
    display: flex;
}

.company-modal {
    width: 100%;
    max-width: 580px;
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,.20);
    animation: companyModalOpen .25s ease;
}

@keyframes companyModalOpen {
    from {
        opacity: 0;
        transform: translateY(15px) scale(.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.company-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 22px 25px;
    border-bottom: 1px solid #edf0f5;
}

.company-modal-header h3 {
    margin: 0 0 6px;
    font-size: 19px;
    color: #172033;
}

.company-modal-header p {
    margin: 0;
    color: #8490a3;
    font-size: 12px;
}

.company-modal-close {
    width: 35px;
    height: 35px;
    border: none;
    border-radius: 8px;
    background: #f1f4f8;
    color: #647087;
    font-size: 24px;
    cursor: pointer;
}

.company-modal-close:hover {
    background: #fff0ef;
    color: #d34c43;
}

.company-modal-body {
    padding: 24px 25px;
    max-height: 65vh;
    overflow-y: auto;
}

.company-modal-group {
    margin-bottom: 19px;
}

.company-modal-group:last-child {
    margin-bottom: 0;
}

.company-modal-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #344054;
}

.company-modal-group label span {
    color: #e04444;
}

.company-modal-group input,
.company-modal-group textarea,
.company-modal-group select {
    width: 100%;
    padding: 11px 13px;
    border: 1px solid #dce2ea;
    border-radius: 8px;
    outline: none;
    background: #fff;
    color: #344054;
    font-family: inherit;
    font-size: 13px;
    box-sizing: border-box;
}

.company-modal-group input:focus,
.company-modal-group textarea:focus,
.company-modal-group select:focus {
    border-color: #345b96;
    box-shadow: 0 0 0 3px rgba(52,91,150,.08);
}

.company-modal-group textarea {
    resize: vertical;
}

.company-modal-group small {
    display: block;
    margin-top: 7px;
    color: #929bad;
    font-size: 11px;
}

.company-field-error {
    color: #dc2626 !important;
}

/* LOGO PREVIEW */

.company-logo-preview {
    display: none;
    margin-top: 12px;
    padding: 10px;
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
    width: fit-content;
}

.company-logo-preview.show {
    display: block;
}

.company-logo-preview img {
    width: 90px;
    height: 70px;
    object-fit: contain;
}

/* MODAL FOOTER */

.company-modal-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    padding: 17px 25px;
    background: #f9fafc;
    border-top: 1px solid #edf0f5;
}

.company-modal-cancel,
.company-modal-submit {
    padding: 11px 18px;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
}

.company-modal-cancel {
    background: #edf0f5;
    color: #475467;
}

.company-modal-submit {
    background: #172d55;
    color: #fff;
}

.company-modal-submit:hover {
    background: #244477;
}

/* SHOW COMPANY */

.company-show-profile {
    display: flex;
    align-items: center;
    gap: 16px;
    padding-bottom: 22px;
    border-bottom: 1px solid #edf0f5;
    margin-bottom: 22px;
}

.company-show-logo {
    width: 75px;
    height: 75px;
    border-radius: 13px;
    background: #edf2fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5276b0;
    font-size: 27px;
    overflow: hidden;
    flex-shrink: 0;
}

.company-show-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.company-show-profile h3 {
    margin: 0 0 7px;
    color: #172033;
    font-size: 19px;
}

.company-show-profile span {
    color: #929bad;
    font-size: 12px;
}

.company-show-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 13px;
}

.company-show-item {
    padding: 14px;
    background: #f8f9fc;
    border: 1px solid #edf0f5;
    border-radius: 9px;
    min-width: 0;
}

.company-show-item span {
    display: block;
    margin-bottom: 8px;
    color: #8994a7;
    font-size: 11px;
}

.company-show-item strong {
    display: block;
    color: #26334a;
    font-size: 13px;
    overflow-wrap: anywhere;
}

.company-show-description {
    margin-top: 20px;
    padding: 16px;
    border: 1px solid #edf0f5;
    border-radius: 9px;
}

.company-show-description h4 {
    margin: 0 0 10px;
    font-size: 13px;
    color: #344054;
}

.company-show-description p {
    margin: 0;
    color: #647087;
    font-size: 13px;
    line-height: 1.7;
    white-space: pre-line;
    overflow-wrap: anywhere;
}

/* RESPONSIVE */

@media (max-width: 768px) {

    .company-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .company-stats {
        grid-template-columns: 1fr;
        gap: 12px;
    }

}

@media (max-width: 600px) {

    .company-modal-overlay {
        padding: 10px;
    }

    .company-modal-header,
    .company-modal-body {
        padding: 18px;
    }

    .company-modal-footer {
        padding: 15px 18px;
    }

    .company-modal-footer button {
        flex: 1;
    }

    .company-show-grid {
        grid-template-columns: 1fr;
    }

}

@media (max-width: 480px) {

    .company-page-header h2 {
        font-size: 20px;
    }

    .company-add-btn {
        width: 100%;
        justify-content: center;
    }

}

</style>


<script>

/* =========================================
   COMMON MODAL HELPERS
========================================= */

function openModal(id) {

    const modal = document.getElementById(id);

    if (!modal) return;

    modal.classList.add('show');

    document.body.style.overflow = 'hidden';

}

function closeModal(id) {

    const modal = document.getElementById(id);

    if (!modal) return;

    modal.classList.remove('show');

    document.body.style.overflow = '';

}


/* =========================================
   CREATE COMPANY
========================================= */

function openCompanyModal() {
    openModal('companyCreateModal');
}

function closeCompanyModal() {
    closeModal('companyCreateModal');
}


/* CREATE LOGO PREVIEW */

document.getElementById('company_logo').addEventListener('change', function(event) {

    const preview = document.getElementById('companyLogoPreview');

    const previewImage = preview.querySelector('img');

    const file = event.target.files[0];

    if (file) {

        previewImage.src = URL.createObjectURL(file);

        preview.classList.add('show');

    } else {

        previewImage.src = '';

        preview.classList.remove('show');

    }

});


/* =========================================
   EDIT COMPANY
========================================= */

function openEditCompanyModal(button) {

    const form = document.getElementById('companyEditForm');

    form.action = button.dataset.updateUrl;

    document.getElementById('edit_company_name').value =
        button.dataset.name || '';

    document.getElementById('edit_company_website').value =
        button.dataset.website || '';

    document.getElementById('edit_company_description').value =
        button.dataset.description || '';

    document.getElementById('edit_company_status').value =
        button.dataset.status || 'active';

    const preview = document.getElementById('editCompanyLogoPreview');

    const previewImage = preview.querySelector('img');

    if (button.dataset.logo) {

        previewImage.src = button.dataset.logo;

        preview.classList.add('show');

    } else {

        previewImage.src = '';

        preview.classList.remove('show');

    }

    document.getElementById('edit_company_logo').value = '';

    openModal('companyEditModal');

}

function closeEditCompanyModal() {
    closeModal('companyEditModal');
}


/* EDIT LOGO PREVIEW */

document.getElementById('edit_company_logo').addEventListener('change', function(event) {

    const preview = document.getElementById('editCompanyLogoPreview');

    const previewImage = preview.querySelector('img');

    const file = event.target.files[0];

    if (file) {

        previewImage.src = URL.createObjectURL(file);

        preview.classList.add('show');

    }

});


/* =========================================
   SHOW COMPANY
========================================= */

function openShowCompanyModal(button) {

    const name = button.dataset.name || 'N/A';

    const slug = button.dataset.slug || 'N/A';

    const website = button.dataset.website || '';

    const description = button.dataset.description || '';

    const status = button.dataset.status || 'inactive';

    const logo = button.dataset.logo || '';

    const products = button.dataset.products || 0;

    const created = button.dataset.created || '-';


    document.getElementById('showCompanyName').textContent = name;

    document.getElementById('showCompanySlug').textContent = slug;

    document.getElementById('showCompanyProducts').textContent = products;

    document.getElementById('showCompanyCreated').textContent = created;

    document.getElementById('showCompanyWebsite').textContent =
        website || 'Not added';

    document.getElementById('showCompanyDescription').textContent =
        description || 'No description available.';


    // Status

    const statusElement = document.getElementById('showCompanyStatus');

    statusElement.textContent =
        status.charAt(0).toUpperCase() + status.slice(1);

    statusElement.style.color =
        status === 'active' ? '#16814f' : '#c65b3c';


    // Logo

    const logoContainer = document.getElementById('showCompanyLogo');

    if (logo) {

        logoContainer.innerHTML = '';

        const image = document.createElement('img');

        image.src = logo;

        image.alt = name;

        logoContainer.appendChild(image);

    } else {

        logoContainer.innerHTML = '<i class="fas fa-building"></i>';

    }


    openModal('companyShowModal');

}

function closeShowCompanyModal() {
    closeModal('companyShowModal');
}


/* =========================================
   CLOSE MODALS
========================================= */

// Click outside modal

document.querySelectorAll('.company-modal-overlay').forEach(function(modal) {

    modal.addEventListener('click', function(event) {

        if (event.target === modal) {

            modal.classList.remove('show');

            document.body.style.overflow = '';

        }

    });

});


// Escape key

document.addEventListener('keydown', function(event) {

    if (event.key === 'Escape') {

        document.querySelectorAll('.company-modal-overlay.show').forEach(function(modal) {

            modal.classList.remove('show');

        });

        document.body.style.overflow = '';

    }

});


/* =========================================
   VALIDATION ERROR - REOPEN CREATE MODAL
========================================= */

@if($errors->any())

    openCompanyModal();

@endif

</script>

@endsection