@extends('layouts.app')

@section('title', 'Plan Details')

@section('page-title', 'Plan Details')

@section('content')

<div class="plan-details-page">

    {{-- Page Header --}}
    <div class="plan-details-header">
        <div>
            <h2>Plan Details</h2>
            <p>View complete information about this platform plan.</p>
        </div>

        <div class="plan-header-actions">
            <a href="{{ route('super-admin.plans.index') }}" class="plan-btn plan-btn-light">
                <i class="fas fa-arrow-left"></i>
                Back to Plans
            </a>

            <a href="{{ route('super-admin.plans.edit', $plan) }}" class="plan-btn plan-btn-primary">
                <i class="fas fa-edit"></i>
                Edit Plan
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="plan-alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="plan-details-grid">

        {{-- Main Content --}}
        <div class="plan-details-main">

            {{-- Plan Overview --}}
            <div class="plan-details-card">

                <div class="plan-overview">
                    <div class="plan-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>

                    <div class="plan-overview-info">
                        <div class="plan-name-row">
                            <h2>{{ $plan->name }}</h2>

                            @if($plan->is_featured)
                                <span class="featured-badge">
                                    <i class="fas fa-star"></i>
                                    Featured
                                </span>
                            @endif
                        </div>

                        <p>{{ $plan->slug }}</p>

                        <div class="plan-status">
                            @if($plan->status === 'active')
                                <span class="status-badge active">
                                    <i class="fas fa-check-circle"></i>
                                    Active
                                </span>
                            @else
                                <span class="status-badge inactive">
                                    <i class="fas fa-times-circle"></i>
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="plan-overview-description">
                    <h4>Description</h4>

                    <p>
                        {{ $plan->description ?: 'No description has been added for this plan.' }}
                    </p>
                </div>

            </div>

            {{-- Plan Information --}}
            <div class="plan-details-card">

                <div class="plan-section-header">
                    <div>
                        <h3>Plan Information</h3>
                        <p>Basic plan configuration and display information.</p>
                    </div>
                </div>

                <div class="plan-info-grid">

                    <div class="plan-info-item">
                        <span>Plan Name</span>
                        <strong>{{ $plan->name }}</strong>
                    </div>

                    <div class="plan-info-item">
                        <span>Plan Duration</span>
                        <strong>{{ number_format($plan->duration_days) }} Days</strong>
                    </div>

                    <div class="plan-info-item">
                        <span>Display Price</span>
                        <strong>৳{{ number_format((float) $plan->display_price, 2) }}</strong>
                    </div>

                    <div class="plan-info-item">
                        <span>Display Order</span>
                        <strong>{{ $plan->sort_order }}</strong>
                    </div>

                    <div class="plan-info-item">
                        <span>Featured Plan</span>
                        <strong>{{ $plan->is_featured ? 'Yes' : 'No' }}</strong>
                    </div>

                    <div class="plan-info-item">
                        <span>Plan Status</span>
                        <strong>{{ ucfirst($plan->status) }}</strong>
                    </div>

                </div>

                <div class="plan-display-notice">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Display Information Only</strong>
                        <p>
                            The price and duration shown here are informational.
                            This page does not process payments or financial transactions.
                        </p>
                    </div>
                </div>

            </div>

            {{-- Features --}}
            <div class="plan-details-card">

                <div class="plan-section-header">
                    <div>
                        <h3>Plan Features</h3>
                        <p>Features configured for this plan.</p>
                    </div>

                    <span class="feature-count">
                        {{ count($plan->features ?? []) }} Features
                    </span>
                </div>

                @if(!empty($plan->features))

                    <div class="plan-features-grid">

                        @foreach($plan->features as $feature)

                            <div class="plan-feature-item">
                                <span class="feature-check">
                                    <i class="fas fa-check"></i>
                                </span>

                                <span>{{ $feature }}</span>
                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="plan-empty-features">
                        <i class="fas fa-list-ul"></i>
                        <p>No features have been added yet.</p>
                    </div>

                @endif

            </div>

        </div>

        {{-- Sidebar --}}
        <div class="plan-details-sidebar">

            {{-- Summary --}}
            <div class="plan-details-card">

                <div class="plan-section-header">
                    <h3>Plan Summary</h3>
                </div>

                <div class="plan-summary-item">
                    <div class="summary-icon">
                        <i class="fas fa-hashtag"></i>
                    </div>

                    <div>
                        <span>Plan ID</span>
                        <strong>#{{ $plan->id }}</strong>
                    </div>
                </div>

                <div class="plan-summary-item">
                    <div class="summary-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>

                    <div>
                        <span>Created At</span>
                        <strong>{{ $plan->created_at->format('d M Y, h:i A') }}</strong>
                    </div>
                </div>

                <div class="plan-summary-item">
                    <div class="summary-icon">
                        <i class="fas fa-clock"></i>
                    </div>

                    <div>
                        <span>Last Updated</span>
                        <strong>{{ $plan->updated_at->format('d M Y, h:i A') }}</strong>
                    </div>
                </div>

            </div>

            {{-- Actions --}}
            <div class="plan-details-card">

                <div class="plan-section-header">
                    <h3>Quick Actions</h3>
                </div>

                <a href="{{ route('super-admin.plans.edit', $plan) }}"
                   class="plan-action-link">
                    <i class="fas fa-edit"></i>
                    Edit Plan
                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="{{ route('super-admin.plans.index') }}"
                   class="plan-action-link">
                    <i class="fas fa-list"></i>
                    All Plans
                    <i class="fas fa-chevron-right"></i>
                </a>

            </div>

        </div>

    </div>

</div>

<style>
.plan-details-page {
    padding: 24px;
    color: #1e293b;
}

.plan-details-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.plan-details-header h2 {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 6px;
}

.plan-details-header p {
    color: #64748b;
    font-size: 13px;
    margin: 0;
}

.plan-header-actions {
    display: flex;
    gap: 10px;
}

.plan-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 7px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: .2s;
}

.plan-btn-light {
    background: #fff;
    color: #334155;
    border: 1px solid #e2e8f0;
}

.plan-btn-light:hover {
    background: #f8fafc;
}

.plan-btn-primary {
    background: #172554;
    color: #fff;
}

.plan-btn-primary:hover {
    background: #1e3a8a;
    color: #fff;
}

.plan-alert-success {
    padding: 13px 16px;
    background: #dcfce7;
    color: #166534;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 13px;
}

.plan-details-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 320px;
    gap: 22px;
    align-items: start;
}

.plan-details-main,
.plan-details-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
    min-width: 0;
}

.plan-details-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 22px;
}

.plan-overview {
    display: flex;
    align-items: center;
    gap: 18px;
}

.plan-icon {
    width: 65px;
    height: 65px;
    border-radius: 12px;
    background: #eff6ff;
    color: #172554;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 25px;
    flex-shrink: 0;
}

.plan-overview-info {
    min-width: 0;
}

.plan-name-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.plan-name-row h2 {
    font-size: 21px;
    font-weight: 700;
    margin: 0;
}

.plan-overview-info > p {
    color: #94a3b8;
    font-size: 12px;
    margin: 5px 0 10px;
}

.featured-badge {
    background: #fef3c7;
    color: #92400e;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
}

.plan-status {
    display: flex;
    gap: 8px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.status-badge.inactive {
    background: #fee2e2;
    color: #991b1b;
}

.plan-overview-description {
    border-top: 1px solid #edf0f4;
    margin-top: 22px;
    padding-top: 18px;
}

.plan-overview-description h4 {
    font-size: 13px;
    font-weight: 700;
    margin: 0 0 8px;
}

.plan-overview-description p {
    font-size: 13px;
    color: #64748b;
    line-height: 1.8;
    margin: 0;
}

.plan-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.plan-section-header h3 {
    font-size: 15px;
    font-weight: 700;
    margin: 0 0 5px;
}

.plan-section-header p {
    color: #94a3b8;
    font-size: 12px;
    margin: 0;
}

.plan-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
}

.plan-info-item {
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.plan-info-item span {
    color: #94a3b8;
    font-size: 12px;
}

.plan-info-item strong {
    color: #334155;
    font-size: 14px;
}

.plan-display-notice {
    display: flex;
    gap: 12px;
    padding: 14px;
    margin-top: 22px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    border-radius: 8px;
}

.plan-display-notice > i {
    margin-top: 2px;
}

.plan-display-notice strong {
    font-size: 12px;
}

.plan-display-notice p {
    font-size: 11px;
    line-height: 1.6;
    margin: 5px 0 0;
}

.feature-count {
    background: #eff6ff;
    color: #1e40af;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.plan-features-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 13px;
}

.plan-feature-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #475569;
}

.feature-check {
    width: 22px;
    height: 22px;
    background: #dcfce7;
    color: #16a34a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    flex-shrink: 0;
}

.plan-empty-features {
    text-align: center;
    padding: 25px;
    color: #94a3b8;
}

.plan-empty-features i {
    font-size: 25px;
}

.plan-empty-features p {
    font-size: 12px;
}

.plan-summary-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 13px 0;
    border-bottom: 1px solid #f1f5f9;
}

.plan-summary-item:first-of-type {
    padding-top: 0;
}

.plan-summary-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.summary-icon {
    width: 37px;
    height: 37px;
    background: #f1f5f9;
    color: #475569;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.plan-summary-item span,
.plan-summary-item strong {
    display: block;
}

.plan-summary-item span {
    color: #94a3b8;
    font-size: 11px;
    margin-bottom: 4px;
}

.plan-summary-item strong {
    font-size: 12px;
    overflow-wrap: anywhere;
}

.plan-action-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 0;
    border-bottom: 1px solid #f1f5f9;
    color: #475569;
    text-decoration: none;
    font-size: 12px;
}

.plan-action-link:first-of-type {
    padding-top: 0;
}

.plan-action-link:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.plan-action-link > i:first-child {
    color: #64748b;
}

.plan-action-link > i:last-child {
    margin-left: auto;
    color: #94a3b8;
}

.plan-action-link:hover {
    color: #172554;
}

@media(max-width: 1000px) {
    .plan-details-grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 600px) {
    .plan-details-page {
        padding: 15px;
    }

    .plan-details-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .plan-header-actions {
        width: 100%;
    }

    .plan-btn {
        justify-content: center;
        flex: 1;
    }

    .plan-info-grid,
    .plan-features-grid {
        grid-template-columns: 1fr;
    }

    .plan-details-card {
        padding: 16px;
    }

    .plan-overview {
        align-items: flex-start;
    }

    .plan-name-row h2 {
        font-size: 18px;
    }
}
</style>

@endsection