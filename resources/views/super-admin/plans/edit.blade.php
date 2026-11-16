@extends('layouts.app')

@section('title', 'Edit Plan')

@section('content')
<div class="plan-page">

    {{-- Page Header --}}
    <div class="plan-header">
        <div>
            <h1>Edit Plan</h1>
            <p>Update platform plan information and settings.</p>
        </div>

        <a href="{{ route('super-admin.plans.index') }}" class="plan-btn plan-btn-light">
            <i class="fas fa-arrow-left"></i>
            Back to Plans
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="plan-alert plan-alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="plan-alert plan-alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('super-admin.plans.update', $plan) }}"
          method="POST"
          id="planForm">

        @csrf
        @method('PUT')

        <div class="plan-form-grid">

            {{-- Main Form --}}
            <div class="plan-form-main">

                {{-- Basic Information --}}
                <div class="plan-card">
                    <div class="plan-card-header">
                        <div>
                            <h3>Basic Information</h3>
                            <p>Update the basic details of this plan.</p>
                        </div>
                    </div>

                    <div class="plan-card-body">

                        <div class="plan-form-group">
                            <label for="name">Plan Name <span>*</span></label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $plan->name) }}"
                                placeholder="Example: Basic Plan"
                                required
                            >
                        </div>

                        <div class="plan-form-group">
                            <label for="description">Description</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                placeholder="Write a short description..."
                            >{{ old('description', $plan->description) }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Plan Details --}}
                <div class="plan-card">
                    <div class="plan-card-header">
                        <div>
                            <h3>Plan Details</h3>
                            <p>Configure the duration and display information.</p>
                        </div>
                    </div>

                    <div class="plan-card-body">

                        <div class="plan-form-row">

                            <div class="plan-form-group">
                                <label for="duration_days">
                                    Duration (Days) <span>*</span>
                                </label>

                                <input
                                    type="number"
                                    id="duration_days"
                                    name="duration_days"
                                    min="1"
                                    max="36500"
                                    value="{{ old('duration_days', $plan->duration_days) }}"
                                    required
                                >
                            </div>

                            <div class="plan-form-group">
                                <label for="display_price">
                                    Display Price <span>*</span>
                                </label>

                                <input
                                    type="number"
                                    id="display_price"
                                    name="display_price"
                                    min="0"
                                    step="0.01"
                                    value="{{ old('display_price', $plan->display_price) }}"
                                    required
                                >

                                <small>This is display-only information.</small>
                            </div>

                        </div>

                        <div class="plan-info-box">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <strong>Display Information Only</strong>
                                <p>
                                    The duration and price are shown as plan information.
                                    This form does not process payments or subscriptions.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Features --}}
                <div class="plan-card">
                    <div class="plan-card-header plan-feature-header">
                        <div>
                            <h3>Plan Features</h3>
                            <p>Update the features displayed with this plan.</p>
                        </div>

                        <button type="button" class="plan-btn plan-btn-primary" id="addFeature">
                            <i class="fas fa-plus"></i>
                            Add Feature
                        </button>
                    </div>

                    <div class="plan-card-body">
                        <div id="featuresContainer">

                            @php
                                $features = old('features', $plan->features ?? []);

                                if (empty($features)) {
                                    $features = [''];
                                }
                            @endphp

                            @foreach($features as $feature)
                                <div class="plan-feature-row">
                                    <div class="plan-feature-input">
                                        <i class="fas fa-check-circle"></i>

                                        <input
                                            type="text"
                                            name="features[]"
                                            value="{{ $feature }}"
                                            placeholder="Example: Responsive Dashboard"
                                            maxlength="255"
                                        >
                                    </div>

                                    <button
                                        type="button"
                                        class="plan-remove-feature"
                                        title="Remove Feature"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach

                        </div>

                        <small class="plan-help-text">
                            You can add up to 30 features.
                        </small>
                    </div>
                </div>

            </div>

            {{-- Sidebar Settings --}}
            <div class="plan-form-sidebar">

                <div class="plan-card">
                    <div class="plan-card-header">
                        <div>
                            <h3>Plan Settings</h3>
                            <p>Manage visibility and display order.</p>
                        </div>
                    </div>

                    <div class="plan-card-body">

                        <div class="plan-form-group">
                            <label for="status">Plan Status <span>*</span></label>

                            <select name="status" id="status" required>
                                <option value="active"
                                    {{ old('status', $plan->status) === 'active' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="inactive"
                                    {{ old('status', $plan->status) === 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>

                            <small>Inactive plans can be hidden from public listings.</small>
                        </div>

                        <div class="plan-form-group">
                            <label for="sort_order">Display Order</label>

                            <input
                                type="number"
                                name="sort_order"
                                id="sort_order"
                                min="0"
                                value="{{ old('sort_order', $plan->sort_order) }}"
                            >

                            <small>Lower numbers appear first.</small>
                        </div>

                        <div class="plan-featured-option">
                            <label class="plan-checkbox-label">
                                <input
                                    type="checkbox"
                                    name="is_featured"
                                    value="1"
                                    {{ old('is_featured', $plan->is_featured) ? 'checked' : '' }}
                                >

                                <span>
                                    <strong>Featured Plan</strong>
                                    <small>Highlight this plan in the listing.</small>
                                </span>
                            </label>
                        </div>

                    </div>
                </div>

                {{-- Plan Information --}}
                <div class="plan-card">
                    <div class="plan-card-header">
                        <h3>Plan Information</h3>
                    </div>

                    <div class="plan-card-body">
                        <div class="plan-meta-item">
                            <span>Plan ID</span>
                            <strong>#{{ $plan->id }}</strong>
                        </div>

                        <div class="plan-meta-item">
                            <span>Created</span>
                            <strong>{{ $plan->created_at->format('d M Y') }}</strong>
                        </div>

                        <div class="plan-meta-item">
                            <span>Last Updated</span>
                            <strong>{{ $plan->updated_at->format('d M Y') }}</strong>
                        </div>

                        <div class="plan-meta-item">
                            <span>Current Slug</span>
                            <strong class="plan-slug">{{ $plan->slug }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="plan-submit-card">
                    <button type="submit" class="plan-btn plan-btn-primary plan-btn-full">
                        <i class="fas fa-save"></i>
                        Update Plan
                    </button>

                    <a href="{{ route('super-admin.plans.index') }}"
                       class="plan-cancel-link">
                        Cancel
                    </a>
                </div>

            </div>

        </div>

    </form>
</div>

<style>
.plan-page {
    padding: 24px;
    color: #1e293b;
}

.plan-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.plan-header h1 {
    font-size: 25px;
    font-weight: 700;
    margin: 0 0 6px;
}

.plan-header p,
.plan-card-header p {
    color: #64748b;
    margin: 0;
    font-size: 13px;
}

.plan-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 7px;
    border: none;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s;
}

.plan-btn-primary {
    background: #172554;
    color: #fff;
}

.plan-btn-primary:hover {
    background: #1e3a8a;
    color: #fff;
}

.plan-btn-light {
    background: #fff;
    color: #334155;
    border: 1px solid #e2e8f0;
}

.plan-btn-light:hover {
    background: #f8fafc;
}

.plan-btn-full {
    width: 100%;
}

.plan-alert {
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 13px;
}

.plan-alert-success {
    background: #dcfce7;
    color: #166534;
}

.plan-alert-danger {
    background: #fee2e2;
    color: #991b1b;
}

.plan-alert ul {
    margin: 8px 0 0;
    padding-left: 20px;
}

.plan-form-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 330px;
    gap: 22px;
    align-items: start;
}

.plan-form-main,
.plan-form-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
    min-width: 0;
}

.plan-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
}

.plan-card-header {
    padding: 18px 20px;
    border-bottom: 1px solid #edf0f4;
}

.plan-card-header h3 {
    font-size: 15px;
    font-weight: 700;
    margin: 0 0 5px;
}

.plan-card-body {
    padding: 20px;
}

.plan-form-group {
    margin-bottom: 20px;
}

.plan-form-group:last-child {
    margin-bottom: 0;
}

.plan-form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
}

.plan-form-group label span {
    color: #dc2626;
}

.plan-form-group input,
.plan-form-group textarea,
.plan-form-group select {
    width: 100%;
    padding: 11px 13px;
    border: 1px solid #dbe1e8;
    border-radius: 7px;
    outline: none;
    font-size: 13px;
    color: #334155;
    background: #fff;
    box-sizing: border-box;
}

.plan-form-group input:focus,
.plan-form-group textarea:focus,
.plan-form-group select:focus {
    border-color: #172554;
    box-shadow: 0 0 0 3px rgba(23, 37, 84, .08);
}

.plan-form-group textarea {
    resize: vertical;
}

.plan-form-group small,
.plan-help-text {
    display: block;
    margin-top: 7px;
    color: #94a3b8;
    font-size: 11px;
}

.plan-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.plan-info-box {
    display: flex;
    gap: 12px;
    padding: 14px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    color: #1e40af;
}

.plan-info-box > i {
    margin-top: 2px;
}

.plan-info-box strong {
    font-size: 12px;
}

.plan-info-box p {
    font-size: 11px;
    line-height: 1.6;
    margin: 5px 0 0;
}

.plan-feature-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

.plan-feature-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.plan-feature-input {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    border: 1px solid #dbe1e8;
    border-radius: 7px;
    padding-left: 12px;
}

.plan-feature-input i {
    color: #16a34a;
}

.plan-feature-input input {
    width: 100%;
    border: none;
    outline: none;
    padding: 11px 10px 11px 0;
    font-size: 13px;
}

.plan-remove-feature {
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border: 1px solid #fecaca;
    background: #fff;
    color: #dc2626;
    border-radius: 7px;
    cursor: pointer;
}

.plan-remove-feature:hover {
    background: #fee2e2;
}

.plan-featured-option {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 13px;
}

.plan-checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
}

.plan-checkbox-label input {
    margin-top: 3px;
    accent-color: #172554;
}

.plan-checkbox-label strong,
.plan-checkbox-label small {
    display: block;
}

.plan-checkbox-label strong {
    font-size: 12px;
}

.plan-checkbox-label small {
    color: #94a3b8;
    font-size: 11px;
    margin-top: 4px;
}

.plan-meta-item {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    padding: 11px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 12px;
}

.plan-meta-item:first-child {
    padding-top: 0;
}

.plan-meta-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.plan-meta-item span {
    color: #64748b;
}

.plan-meta-item strong {
    text-align: right;
    overflow-wrap: anywhere;
}

.plan-slug {
    font-size: 10px;
}

.plan-submit-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 18px;
}

.plan-cancel-link {
    display: block;
    text-align: center;
    margin-top: 13px;
    color: #64748b;
    font-size: 12px;
    text-decoration: none;
}

.plan-cancel-link:hover {
    color: #172554;
}

@media(max-width: 1000px) {
    .plan-form-grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 600px) {
    .plan-page {
        padding: 15px;
    }

    .plan-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .plan-form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }

    .plan-feature-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .plan-card-body {
        padding: 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('featuresContainer');
    const addButton = document.getElementById('addFeature');

    function updateFeatureButtons() {
        const rows = container.querySelectorAll('.plan-feature-row');

        rows.forEach(row => {
            const removeButton = row.querySelector('.plan-remove-feature');

            removeButton.disabled = rows.length <= 1;
            removeButton.style.opacity = rows.length <= 1 ? '.4' : '1';
            removeButton.style.cursor = rows.length <= 1 ? 'not-allowed' : 'pointer';
        });

        addButton.disabled = rows.length >= 30;
        addButton.style.opacity = rows.length >= 30 ? '.5' : '1';
        addButton.style.cursor = rows.length >= 30 ? 'not-allowed' : 'pointer';
    }

    addButton.addEventListener('click', function () {

        const rows = container.querySelectorAll('.plan-feature-row');

        if (rows.length >= 30) {
            alert('You can add a maximum of 30 features.');
            return;
        }

        const row = document.createElement('div');

        row.className = 'plan-feature-row';

        row.innerHTML = `
            <div class="plan-feature-input">
                <i class="fas fa-check-circle"></i>

                <input
                    type="text"
                    name="features[]"
                    placeholder="Example: Responsive Dashboard"
                    maxlength="255"
                >
            </div>

            <button
                type="button"
                class="plan-remove-feature"
                title="Remove Feature"
            >
                <i class="fas fa-trash"></i>
            </button>
        `;

        container.appendChild(row);

        updateFeatureButtons();

        row.querySelector('input').focus();
    });

    container.addEventListener('click', function (event) {

        const button = event.target.closest('.plan-remove-feature');

        if (!button || button.disabled) {
            return;
        }

        const rows = container.querySelectorAll('.plan-feature-row');

        if (rows.length > 1) {
            button.closest('.plan-feature-row').remove();
        }

        updateFeatureButtons();
    });

    updateFeatureButtons();
});
</script>
@endsection