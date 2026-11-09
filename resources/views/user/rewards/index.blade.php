@extends('layouts.app')

@section('content')

<style>
    .rewards-page {
        padding: 5px 0 25px;
    }

    .rewards-page .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .rewards-page .page-heading h2 {
        font-size: 23px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 6px;
    }

    .rewards-page .page-heading p {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .rewards-page .page-badge {
        background: #fff7e6;
        color: #b7791f;
        padding: 9px 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .rewards-page .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .rewards-page .summary-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .rewards-page .summary-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .rewards-page .icon-gold {
        background: #fff4d8;
        color: #d69e00;
    }

    .rewards-page .icon-blue {
        background: #eaf2ff;
        color: #2563eb;
    }

    .rewards-page .icon-orange {
        background: #fff3e8;
        color: #ea580c;
    }

    .rewards-page .icon-green {
        background: #e8f8ef;
        color: #16a34a;
    }

    .rewards-page .summary-card h3 {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 4px;
        color: var(--text);
    }

    .rewards-page .summary-card p {
        font-size: 12px;
        color: var(--muted);
        margin: 0;
    }

    .rewards-page .section-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 22px;
        overflow: hidden;
    }

    .rewards-page .section-header {
        padding: 19px 22px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .rewards-page .section-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    .rewards-page .section-body {
        padding: 22px;
    }

    .rewards-page .reward-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .rewards-page .reward-card {
        border: 1px solid var(--border);
        border-radius: 11px;
        padding: 20px;
        transition: 0.2s ease;
    }

    .rewards-page .reward-card:hover {
        border-color: #b7dfcf;
        transform: translateY(-2px);
    }

    .rewards-page .reward-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        font-size: 19px;
        margin-bottom: 15px;
    }

    .rewards-page .reward-card h4 {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 8px;
    }

    .rewards-page .reward-card p {
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
        margin: 0 0 14px;
    }

    .rewards-page .reward-tag {
        display: inline-block;
        background: #f1f5f9;
        color: #64748b;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .rewards-page .filter-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .rewards-page .filter-form select {
        min-width: 145px;
        height: 38px;
        border: 1px solid var(--border);
        border-radius: 7px;
        padding: 0 10px;
        color: var(--text);
        font-size: 12px;
        background: #fff;
        outline: none;
    }

    .rewards-page .filter-form button {
        height: 38px;
        border: none;
        border-radius: 7px;
        background: var(--primary);
        color: #fff;
        padding: 0 15px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .rewards-page .table-responsive {
        overflow-x: auto;
    }

    .rewards-page table {
        width: 100%;
        border-collapse: collapse;
        min-width: 750px;
    }

    .rewards-page table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 14px 18px;
        text-align: left;
        white-space: nowrap;
        border-bottom: 1px solid var(--border);
    }

    .rewards-page table td {
        padding: 15px 18px;
        border-bottom: 1px solid var(--border);
        font-size: 12px;
        color: var(--text);
        white-space: nowrap;
    }

    .rewards-page .status-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .rewards-page .status-pending {
        background: #fff3e0;
        color: #c77700;
    }

    .rewards-page .status-approved {
        background: #e8f5ef;
        color: #087f5b;
    }

    .rewards-page .status-rejected {
        background: #feecec;
        color: #dc2626;
    }

    .rewards-page .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .rewards-page .empty-icon {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: #fff7e6;
        color: #d69e00;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        margin: 0 auto 15px;
    }

    .rewards-page .empty-state h4 {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 7px;
    }

    .rewards-page .empty-state p {
        font-size: 12px;
        color: var(--muted);
        margin: 0;
    }

    .rewards-page .notice-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 17px 20px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .rewards-page .notice-box i {
        color: #d97706;
        font-size: 17px;
        margin-top: 2px;
    }

    .rewards-page .notice-box h4 {
        color: #92400e;
        font-size: 13px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .rewards-page .notice-box p {
        color: #92400e;
        font-size: 11px;
        line-height: 1.7;
        margin: 0;
    }

    @media(max-width: 1100px) {
        .rewards-page .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .rewards-page .reward-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 768px) {
        .rewards-page .section-body {
            padding: 15px;
        }

        .rewards-page .filter-form {
            width: 100%;
        }

        .rewards-page .filter-form select {
            flex: 1;
            min-width: 0;
        }
    }

    @media(max-width: 480px) {
        .rewards-page .summary-grid,
        .rewards-page .reward-grid {
            grid-template-columns: 1fr;
        }

        .rewards-page .summary-card {
            padding: 15px;
        }
    }
</style>

<div class="rewards-page">

    {{-- Page Heading --}}
    <div class="page-heading">
        <div>
            <h2>My Rewards</h2>
            <p>View your reward categories and reward activity.</p>
        </div>

        <div class="page-badge">
            <i class="fa-solid fa-gift"></i>
            Rewards Center
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-icon icon-gold">
                <i class="fa-solid fa-gift"></i>
            </div>
            <div>
                <h3>{{ $summary['total_rewards'] }}</h3>
                <p>Total Rewards</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-orange">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <h3>{{ $summary['pending'] }}</h3>
                <p>Pending</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-green">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <h3>{{ $summary['approved'] }}</h3>
                <p>Approved</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon icon-blue">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
                <h3>{{ $summary['rejected'] }}</h3>
                <p>Rejected</p>
            </div>
        </div>

    </div>

    {{-- Reward Categories --}}
    <div class="section-card">

        <div class="section-header">
            <h3>
    <a href="{{ route('user.reward-categories.index') }}">
            <i class="fa-solid fa-layer-group"></i>
            Reward Categories
        </a>
            </h3>
        </div>

        <div class="section-body">

            <div class="reward-grid">

                <div class="reward-card">

                    <div class="reward-icon icon-gold">
                        <i class="fa-solid fa-star"></i>
                    </div>

                    <h4>Membership Recognition</h4>

                    <p>
                        Recognition associated with eligible membership
                        milestones, subject to the platform's approved policy.
                    </p>

                    <span class="reward-tag">Policy Not Configured</span>

                </div>

                <div class="reward-card">

                    <div class="reward-icon icon-blue">
                        <i class="fa-solid fa-users"></i>
                    </div>

                    <h4>Referral Recognition</h4>

                    <p>
                        View referral-related recognition information
                        when an approved referral policy is available.
                    </p>

                    <span class="reward-tag">Policy Not Configured</span>

                </div>

                <div class="reward-card">

                    <div class="reward-icon icon-green">
                        <i class="fa-solid fa-trophy"></i>
                    </div>

                    <h4>Activity Recognition</h4>

                    <p>
                        Recognition based on eligible platform activities
                        under the officially published rules.
                    </p>

                    <span class="reward-tag">Policy Not Configured</span>

                </div>

            </div>

        </div>

    </div>

    {{-- Reward History --}}
    <div class="section-card">

        <div class="section-header">

            <h3>
                <i class="fa-solid fa-clock-rotate-left"></i>
                Reward History
            </h3>

            <form method="GET"
                  action="{{ route('user.rewards.index') }}"
                  class="filter-form">

                <select name="status">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>
                        All Status
                    </option>

                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>
                        Approved
                    </option>

                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>
                        Rejected
                    </option>
                </select>

                <button type="submit">
                    <i class="fa-solid fa-filter"></i>
                    Filter
                </button>

            </form>

        </div>

        <div class="table-responsive">

            <table>

                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Reward ID</th>
                        <th>Reward Type</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($rewards as $index => $reward)

                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>{{ $reward->reference_id }}</td>

                            <td>{{ $reward->type }}</td>

                            <td>{{ $reward->description }}</td>

                            <td>{{ $reward->created_at }}</td>

                            <td>
                                <span class="status-badge status-{{ $reward->status }}">
                                    {{ ucfirst($reward->status) }}
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="fa-solid fa-gift"></i>
                                    </div>

                                    <h4>No Reward Records Found</h4>

                                    <p>
                                        Your reward activity will appear here
                                        when an approved reward system is configured.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Important Notice --}}
    <div class="notice-box">

        <i class="fa-solid fa-shield-halved"></i>

        <div>
            <h4>Reward Policy Information</h4>

            <p>
                Reward categories shown here are informational placeholders.
                No reward, cash bonus, referral commission or payment is
                currently enabled. Any future reward program must have
                transparent eligibility rules, verified funding sources,
                and all required legal and regulatory approvals.
            </p>
        </div>

    </div>

</div>

@endsection