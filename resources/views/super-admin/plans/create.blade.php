@extends('layouts.app')

@section('title', 'Add New Plan')

@section('page-title', 'Add New Plan')

@section('content')

<div class="plan-create-page">

    {{-- Page Header --}}
    <div class="plan-create-header">
        <div>
            <h2>Add New Plan</h2>
            <p>Create a new platform plan and configure its details.</p>
        </div>

        <a href="{{ route('super-admin.plans.index') }}" class="plan-back-btn">
            ← Back to Plans
        </a>
    </div>

    {{-- Alerts --}}
    @if($errors->any())
        <div class="plan-alert error">
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

    <form
        action="{{ route('super-admin.plans.store') }}"
        method="POST"
        id="createPlanForm"
    >
        @csrf

        <div class="plan-create-layout">

            {{-- Main Form --}}
            <div class="plan-create-main">

                {{-- Basic Information --}}
                <div class="plan-form-card">

                    <div class="plan-form-card-header">
                        <div class="plan-form-icon">▣</div>
                        <div>
                            <h3>Basic Information</h3>
                            <p>Enter the basic details of your platform plan.</p>
                        </div>
                    </div>

                    <div class="plan-form-body">

                        <div class="plan-form-group">
                            <label for="name">
                                Plan Name <span>*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                placeholder="e.g. Basic Plan"
                                maxlength="100"
                                required
                            >

                            <small>Enter a unique and meaningful plan name.</small>
                        </div>

                        <div class="plan-form-group">
                            <label for="description">Description</label>

                            <textarea
                                name="description"
                                id="description"
                                rows="5"
                                placeholder="Write a short description about this plan..."
                            >{{ old('description') }}</textarea>

                            <small>Provide a short overview of this plan.</small>
                        </div>

                    </div>

                </div>

                {{-- Plan Configuration --}}
                <div class="plan-form-card">

                    <div class="plan-form-card-header">
                        <div class="plan-form-icon">⚙</div>
                        <div>
                            <h3>Plan Configuration</h3>
                            <p>Configure duration and display information.</p>
                        </div>
                    </div>

                    <div class="plan-form-body">

                        <div class="plan-form-row">

                            <div class="plan-form-group">
                                <label for="duration_days">
                                    Duration (Days) <span>*</span>
                                </label>

                                <input
                                    type="number"
                                    name="duration_days"
                                    id="duration_days"
                                    value="{{ old('duration_days', 30) }}"
                                    min="1"
                                    max="36500"
                                    required
                                >

                                <small>Plan duration in days.</small>
                            </div>

                            <div class="plan-form-group">
                                <label for="display_price">
                                    Display Price <span>*</span>
                                </label>

                                <input
                                    type="number"
                                    name="display_price"
                                    id="display_price"
                                    value="{{ old('display_price', '0.00') }}"
                                    min="0"
                                    max="99999999.99"
                                    step="0.01"
                                    required
                                >

                                <small>Display information only. No payment processing.</small>
                            </div>

                        </div>

                    </div>

                </div>

                {{-- Plan Features --}}
                <div class="plan-form-card">

                    <div class="plan-form-card-header">
                        <div class="plan-form-icon">✦</div>
                        <div>
                            <h3>Plan Features</h3>
                            <p>Add the features that describe this plan.</p>
                        </div>
                    </div>

                    <div class="plan-form-body">

                        <div id="planFeaturesContainer">

                            @php
                                $oldFeatures = old('features', ['']);
                            @endphp

                            @foreach($oldFeatures as $index => $feature)

                                <div class="plan-feature-input">
                                    <div class="feature-number">
                                        {{ $index + 1 }}
                                    </div>

                                    <input
                                        type="text"
                                        name="features[]"
                                        value="{{ $feature }}"
                                        placeholder="Enter a plan feature..."
                                        maxlength="255"
                                    >

                                    <button
                                        type="button"
                                        class="remove-feature-btn"
                                        onclick="removeFeature(this)"
                                        title="Remove Feature"
                                    >
                                        ×
                                    </button>
                                </div>

                            @endforeach

                        </div>

                        <button
                            type="button"
                            class="add-feature-btn"
                            onclick="addFeature()"
                        >
                            + Add Another Feature
                        </button>

                        <small class="feature-help">
                            You can add up to 30 features.
                        </small>

                    </div>

                </div>

            </div>

            {{-- Sidebar Settings --}}
            <div class="plan-create-sidebar">

                {{-- Status --}}
                <div class="plan-form-card">

                    <div class="plan-form-card-header">
                        <div class="plan-form-icon">◉</div>
                        <div>
                            <h3>Plan Status</h3>
                            <p>Control plan visibility.</p>
                        </div>
                    </div>

                    <div class="plan-form-body">

                        <div class="plan-form-group">
                            <label for="status">Status <span>*</span></label>

                            <select name="status" id="status" required>
                                <option value="active"
                                    {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="inactive"
                                    {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>

                            <small>
                                Active plans can be displayed on the platform.
                            </small>
                        </div>

                        <div class="plan-setting-divider"></div>

                        <label class="plan-checkbox-option">
                            <input
                                type="checkbox"
                                name="is_featured"
                                value="1"
                                {{ old('is_featured') ? 'checked' : '' }}
                            >

                            <span class="custom-checkbox"></span>

                            <span>
                                <strong>Featured Plan</strong>
                                <small>Highlight this plan on the platform.</small>
                            </span>
                        </label>

                    </div>

                </div>

                {{-- Display Order --}}
                <div class="plan-form-card">

                    <div class="plan-form-card-header">
                        <div class="plan-form-icon">↕</div>
                        <div>
                            <h3>Display Settings</h3>
                            <p>Manage the plan display order.</p>
                        </div>
                    </div>

                    <div class="plan-form-body">

                        <div class="plan-form-group">
                            <label for="sort_order">Sort Order</label>

                            <input
                                type="number"
                                name="sort_order"
                                id="sort_order"
                                value="{{ old('sort_order', 0) }}"
                                min="0"
                                max="999999"
                            >

                            <small>
                                Lower numbers will appear first.
                            </small>
                        </div>

                    </div>

                </div>

                {{-- Information --}}
                <div class="plan-info-box">
                    <div class="plan-info-box-icon">ⓘ</div>

                    <div>
                        <strong>Important Information</strong>

                        <p>
                            Plan price and duration are informational fields.
                            This form does not process payments or financial transactions.
                        </p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="plan-form-actions">

                    <button type="submit" class="plan-submit-btn">
                        ✓ Create Plan
                    </button>

                    <a
                        href="{{ route('super-admin.plans.index') }}"
                        class="plan-cancel-btn"
                    >
                        Cancel
                    </a>

                </div>

            </div>

        </div>

    </form>

</div>

<style>

/* Main Layout */

.plan-create-page {
    width: 100%;
}

.plan-create-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.plan-create-header h2 {
    margin: 0 0 7px;
    color: #172033;
    font-size: 23px;
    font-weight: 700;
}

.plan-create-header p {
    margin: 0;
    color: #8490a3;
    font-size: 13px;
}

.plan-back-btn {
    display: inline-flex;
    align-items: center;
    padding: 11px 16px;
    border: 1px solid #e5e9f0;
    border-radius: 8px;
    color: #526078;
    background: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    transition: .2s;
}

.plan-back-btn:hover {
    background: #f5f7fb;
    color: #172d55;
}

.plan-create-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 320px;
    gap: 22px;
    align-items: start;
}

.plan-create-main,
.plan-create-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Cards */

.plan-form-card {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 12px rgba(20, 35, 60, .025);
}

.plan-form-card-header {
    padding: 19px 22px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 1px solid #edf0f5;
}

.plan-form-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: #edf3ff;
    color: #3568c8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
    flex-shrink: 0;
}

.plan-form-card-header h3 {
    margin: 0 0 5px;
    color: #26334a;
    font-size: 15px;
    font-weight: 700;
}

.plan-form-card-header p {
    margin: 0;
    color: #929bad;
    font-size: 11px;
}

.plan-form-body {
    padding: 22px;
}

/* Form Fields */

.plan-form-group {
    margin-bottom: 20px;
}

.plan-form-group:last-child {
    margin-bottom: 0;
}

.plan-form-group label {
    display: block;
    color: #344054;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 9px;
}

.plan-form-group label span {
    color: #e05252;
}

.plan-form-group input,
.plan-form-group textarea,
.plan-form-group select {
    width: 100%;
    padding: 12px 13px;
    border: 1px solid #e2e7ef;
    border-radius: 8px;
    background: #fff;
    color: #26334a;
    font-family: inherit;
    font-size: 12px;
    outline: none;
    transition: .2s;
}

.plan-form-group input,
.plan-form-group select {
    height: 43px;
}

.plan-form-group textarea {
    resize: vertical;
    min-height: 110px;
    line-height: 1.6;
}

.plan-form-group input:focus,
.plan-form-group textarea:focus,
.plan-form-group select:focus {
    border-color: #5279bd;
    box-shadow: 0 0 0 3px rgba(53, 104, 200, .08);
}

.plan-form-group input::placeholder,
.plan-form-group textarea::placeholder {
    color: #a5adbb;
}

.plan-form-group small {
    display: block;
    margin-top: 7px;
    color: #929bad;
    font-size: 10px;
    line-height: 1.5;
}

.plan-form-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

/* Features */

.plan-feature-input {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.feature-number {
    width: 31px;
    height: 31px;
    border-radius: 7px;
    background: #edf3ff;
    color: #3568c8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
}

.plan-feature-input input {
    height: 40px;
    flex: 1;
    min-width: 0;
    padding: 10px 12px;
    border: 1px solid #e2e7ef;
    border-radius: 8px;
    outline: none;
    font-size: 12px;
}

.plan-feature-input input:focus {
    border-color: #5279bd;
}

.remove-feature-btn {
    width: 34px;
    height: 34px;
    border: none;
    border-radius: 7px;
    background: #fff0ef;
    color: #d34c43;
    font-size: 21px;
    cursor: pointer;
    flex-shrink: 0;
}

.remove-feature-btn:hover {
    background: #ffe2df;
}

.add-feature-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 14px;
    margin-top: 5px;
    border: 1px dashed #b9c8df;
    border-radius: 8px;
    background: #f7f9fd;
    color: #345b96;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s;
}

.add-feature-btn:hover {
    background: #edf3ff;
    border-color: #5279bd;
}

.feature-help {
    display: block;
    margin-top: 12px;
    color: #929bad;
    font-size: 10px;
}

/* Settings */

.plan-setting-divider {
    height: 1px;
    background: #edf0f5;
    margin: 20px 0;
}

.plan-checkbox-option {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    cursor: pointer;
}

.plan-checkbox-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.custom-checkbox {
    width: 19px;
    height: 19px;
    border: 1px solid #cbd3df;
    border-radius: 5px;
    flex-shrink: 0;
    position: relative;
}

.plan-checkbox-option input:checked + .custom-checkbox {
    background: #172d55;
    border-color: #172d55;
}

.plan-checkbox-option input:checked + .custom-checkbox::after {
    content: '✓';
    position: absolute;
    top: 0;
    left: 4px;
    color: #fff;
    font-size: 12px;
}

.plan-checkbox-option strong {
    display: block;
    color: #344054;
    font-size: 12px;
    margin-bottom: 5px;
}

.plan-checkbox-option small {
    display: block;
    color: #929bad;
    font-size: 10px;
    line-height: 1.5;
}

/* Information Box */

.plan-info-box {
    display: flex;
    gap: 11px;
    padding: 15px;
    border: 1px solid #dbe8fa;
    background: #f1f7ff;
    border-radius: 10px;
}

.plan-info-box-icon {
    color: #3568c8;
    font-size: 19px;
}

.plan-info-box strong {
    display: block;
    color: #345b96;
    font-size: 11px;
    margin-bottom: 6px;
}

.plan-info-box p {
    margin: 0;
    color: #647087;
    font-size: 10px;
    line-height: 1.6;
}

/* Actions */

.plan-form-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.plan-submit-btn {
    width: 100%;
    height: 45px;
    border: none;
    border-radius: 8px;
    background: #172d55;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s;
}

.plan-submit-btn:hover {
    background: #244477;
    transform: translateY(-2px);
}

.plan-cancel-btn {
    width: 100%;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5e9f0;
    border-radius: 8px;
    background: #fff;
    color: #647087;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
}

.plan-cancel-btn:hover {
    background: #f7f8fa;
    color: #26334a;
}

/* Alerts */

.plan-alert {
    padding: 14px 17px;
    border-radius: 9px;
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    font-size: 13px;
}

.plan-alert.error {
    color: #b42318;
    background: #fff0ef;
    border: 1px solid #ffd5d1;
}

.plan-alert ul {
    margin: 7px 0 0;
    padding-left: 18px;
}

/* Responsive */

@media (max-width: 1000px) {
    .plan-create-layout {
        grid-template-columns: 1fr;
    }

    .plan-create-sidebar {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        align-items: start;
    }

    .plan-info-box,
    .plan-form-actions {
        grid-column: span 2;
    }
}

@media (max-width: 768px) {
    .plan-create-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .plan-form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }

    .plan-create-sidebar {
        display: flex;
    }
}

@media (max-width: 480px) {
    .plan-create-header h2 {
        font-size: 20px;
    }

    .plan-back-btn {
        width: 100%;
        justify-content: center;
    }

    .plan-form-card-header,
    .plan-form-body {
        padding: 16px;
    }
}

</style>

<script>

let featureCount = document.querySelectorAll(
    '#planFeaturesContainer .plan-feature-input'
).length;

function addFeature() {

    if (featureCount >= 30) {
        alert('You can add a maximum of 30 features.');
        return;
    }

    featureCount++;

    const container = document.getElementById('planFeaturesContainer');

    const wrapper = document.createElement('div');
    wrapper.className = 'plan-feature-input';

    wrapper.innerHTML = `
        <div class="feature-number">${featureCount}</div>

        <input
            type="text"
            name="features[]"
            placeholder="Enter a plan feature..."
            maxlength="255"
        >

        <button
            type="button"
            class="remove-feature-btn"
            onclick="removeFeature(this)"
            title="Remove Feature"
        >
            ×
        </button>
    `;

    container.appendChild(wrapper);

    wrapper.querySelector('input').focus();

    updateFeatureNumbers();
}

function removeFeature(button) {

    const container = document.getElementById('planFeaturesContainer');

    if (container.children.length <= 1) {
        const input = container.querySelector('input');
        input.value = '';
        return;
    }

    button.closest('.plan-feature-input').remove();

    featureCount--;

    updateFeatureNumbers();
}

function updateFeatureNumbers() {

    const features = document.querySelectorAll(
        '#planFeaturesContainer .plan-feature-input'
    );

    features.forEach((feature, index) => {
        feature.querySelector('.feature-number').textContent = index + 1;
    });

    featureCount = features.length;
}

</script>

@endsection