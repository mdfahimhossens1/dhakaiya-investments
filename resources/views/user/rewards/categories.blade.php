@extends('layouts.app')

@section('content')

<style>
    .reward-category-page {
        padding: 5px 0 25px;
    }

    .reward-category-page .page-heading {
        margin-bottom: 25px;
    }

    .reward-category-page .page-heading h2 {
        font-size: 23px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 6px;
    }

    .reward-category-page .page-heading p {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .reward-category-page .category-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 25px;
    }

    .reward-category-page .category-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 23px;
        transition: .2s;
    }

    .reward-category-page .category-card:hover {
        border-color: #a7d8c5;
        transform: translateY(-3px);
    }

    .reward-category-page .category-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 21px;
        margin-bottom: 17px;
    }

    .reward-category-page .gold {
        background: #fff4d8;
        color: #d69e00;
    }

    .reward-category-page .blue {
        background: #eaf2ff;
        color: #2563eb;
    }

    .reward-category-page .green {
        background: #e8f8ef;
        color: #16a34a;
    }

    .reward-category-page .purple {
        background: #f3eaff;
        color: #9333ea;
    }

    .reward-category-page .orange {
        background: #fff3e8;
        color: #ea580c;
    }

    .reward-category-page .category-card h3 {
        color: var(--text);
        font-size: 15px;
        font-weight: 700;
        margin: 0 0 9px;
    }

    .reward-category-page .category-card p {
        color: var(--muted);
        font-size: 12px;
        line-height: 1.8;
        margin: 0 0 17px;
    }

    .reward-category-page .category-status {
        display: inline-block;
        padding: 7px 11px;
        border-radius: 20px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
    }

    .reward-category-page .info-card {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 18px;
        display: flex;
        gap: 12px;
    }

    .reward-category-page .info-card i {
        color: #d97706;
        font-size: 18px;
    }

    .reward-category-page .info-card h4 {
        color: #92400e;
        font-size: 13px;
        margin: 0 0 6px;
        font-weight: 700;
    }

    .reward-category-page .info-card p {
        color: #92400e;
        font-size: 11px;
        line-height: 1.8;
        margin: 0;
    }

    @media(max-width: 900px) {
        .reward-category-page .category-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 550px) {
        .reward-category-page .category-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="reward-category-page">

    <div class="page-heading">
        <h2>Reward Categories</h2>
        <p>Explore the available reward categories and their descriptions.</p>
    </div>

    <div class="category-grid">

        <div class="category-card">
            <div class="category-icon gold">
                <i class="fa-solid fa-star"></i>
            </div>

            <h3>Membership Rewards</h3>

            <p>
                Recognition associated with eligible membership
                milestones and platform participation.
            </p>

            <span class="category-status">Policy Not Configured</span>
        </div>

        <div class="category-card">
            <div class="category-icon blue">
                <i class="fa-solid fa-users"></i>
            </div>

            <h3>Referral Rewards</h3>

            <p>
                Referral-related recognition based on an approved
                and transparent referral policy.
            </p>

            <span class="category-status">Policy Not Configured</span>
        </div>

        <div class="category-card">
            <div class="category-icon green">
                <i class="fa-solid fa-trophy"></i>
            </div>

            <h3>Achievement Rewards</h3>

            <p>
                Recognition for eligible milestones and achievements
                under officially published rules.
            </p>

            <span class="category-status">Policy Not Configured</span>
        </div>

        <div class="category-card">
            <div class="category-icon purple">
                <i class="fa-solid fa-ranking-star"></i>
            </div>

            <h3>Performance Recognition</h3>

            <p>
                Recognition based on transparent and verifiable
                platform activity criteria.
            </p>

            <span class="category-status">Policy Not Configured</span>
        </div>

        <div class="category-card">
            <div class="category-icon orange">
                <i class="fa-solid fa-calendar-check"></i>
            </div>

            <h3>Participation Rewards</h3>

            <p>
                Recognition for eligible participation in
                officially supported platform activities.
            </p>

            <span class="category-status">Policy Not Configured</span>
        </div>

        <div class="category-card">
            <div class="category-icon blue">
                <i class="fa-solid fa-medal"></i>
            </div>

            <h3>Special Recognition</h3>

            <p>
                Special recognition categories that may be
                introduced through an approved platform policy.
            </p>

            <span class="category-status">Policy Not Configured</span>
        </div>

    </div>

    <div class="info-card">
        <i class="fa-solid fa-shield-halved"></i>

        <div>
            <h4>Reward Policy Notice</h4>

            <p>
                These categories are informational placeholders.
                No cash reward, investment return or referral commission
                is currently enabled. Any future reward program requires
                approved rules, verified funding sources and applicable
                legal and regulatory clearance.
            </p>
        </div>
    </div>

</div>

@endsection