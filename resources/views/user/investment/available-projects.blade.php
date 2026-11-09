@extends('layouts.app')

@section('title', 'Available Projects')

@section('content')

<style>
    .projects-page {
        padding: 5px 0 25px;
    }

    .projects-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .projects-heading h2 {
        font-size: 23px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 5px;
    }

    .projects-heading p {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .project-status-badge {
        background: #fff7e6;
        color: #b7791f;
        padding: 9px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .project-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .project-info-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
    }

    .project-info-card .icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e8f5ef;
        color: var(--primary);
        font-size: 18px;
        margin-bottom: 15px;
    }

    .project-info-card h4 {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 7px;
    }

    .project-info-card p {
        color: var(--muted);
        font-size: 12px;
        line-height: 1.6;
        margin: 0;
    }

    .projects-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .projects-card-header {
        padding: 20px;
        border-bottom: 1px solid var(--border);
    }

    .projects-card-header h4 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 5px;
    }

    .projects-card-header p {
        font-size: 12px;
        color: var(--muted);
        margin: 0;
    }

    .project-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding: 18px 20px;
        background: #fafcfc;
        border-bottom: 1px solid var(--border);
    }

    .project-filters select {
        min-width: 180px;
        padding: 10px 13px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: white;
        color: var(--text);
        font-size: 13px;
        outline: none;
    }

    .filter-btn {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .filter-btn:hover {
        background: var(--primary-dark);
    }

    .reset-btn {
        display: inline-flex;
        align-items: center;
        padding: 10px 16px;
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        text-decoration: none;
        font-size: 13px;
    }

    .projects-empty {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        width: 72px;
        height: 72px;
        background: #e8f5ef;
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 28px;
        margin: 0 auto 18px;
    }

    .projects-empty h4 {
        color: var(--text);
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .projects-empty p {
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
        max-width: 420px;
        margin: 0 auto;
    }

    .project-notice {
        margin-top: 20px;
        padding: 17px 20px;
        border: 1px solid #dcecff;
        background: #f0f7ff;
        border-radius: 10px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .project-notice i {
        color: #2563eb;
        font-size: 18px;
        margin-top: 2px;
    }

    .project-notice h5 {
        color: #1e40af;
        font-size: 13px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .project-notice p {
        color: #475569;
        font-size: 12px;
        line-height: 1.7;
        margin: 0;
    }

    @media(max-width: 900px) {
        .project-info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 576px) {
        .project-info-grid {
            grid-template-columns: 1fr;
        }

        .project-filters select,
        .filter-btn,
        .reset-btn {
            width: 100%;
        }
    }
</style>

<div class="projects-page">

    {{-- Page Heading --}}
    <div class="projects-heading">
        <div>
            <h2>Available Projects</h2>
            <p>Explore published project information and details.</p>
        </div>

        <span class="project-status-badge">
            <i class="fas fa-info-circle"></i>
            Project Listing
        </span>
    </div>


    {{-- Information Cards --}}
    <div class="project-info-grid">

        <div class="project-info-card">
            <div class="icon">
                <i class="fa-solid fa-building"></i>
            </div>

            <h4>Project Information</h4>

            <p>
                View project descriptions, business details and
                relevant documentation.
            </p>
        </div>


        <div class="project-info-card">
            <div class="icon">
                <i class="fas fa-chart-pie"></i>
            </div>

            <h4>Project Categories</h4>

            <p>
                Explore projects according to their business
                categories.
            </p>
        </div>


        <div class="project-info-card">
            <div class="icon">
                <i class="fas fa-shield-alt"></i>
            </div>

            <h4>Verified Information</h4>

            <p>
                Project information should be published only
                after appropriate verification and approval.
            </p>
        </div>

    </div>


    {{-- Projects Listing --}}
    <div class="projects-card">

        <div class="projects-card-header">
            <h4>All Projects</h4>

            <p>
                Browse the projects published by the platform.
            </p>
        </div>


        {{-- Filters --}}
        <form method="GET"
              action="{{ route('user.available-projects.index') }}"
              class="project-filters">

            <select name="category">
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}"
                        {{ $category === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>


            <select name="status">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>
                    All Status
                </option>

                <option value="open" {{ $status === 'open' ? 'selected' : '' }}>
                    Open
                </option>

                <option value="upcoming" {{ $status === 'upcoming' ? 'selected' : '' }}>
                    Upcoming
                </option>

                <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>
                    Closed
                </option>
            </select>


            <button type="submit" class="filter-btn">
                <i class="fas fa-filter"></i>
                Apply Filter
            </button>


            <a href="{{ route('user.available-projects.index') }}"
               class="reset-btn">
                Reset
            </a>

        </form>


        {{-- Project List --}}
        @forelse($projects as $project)

            {{-- Project cards will be implemented
                 after the approved project data model is ready. --}}

        @empty

            <div class="projects-empty">

                <div class="empty-icon">
                    <i class="fas fa-folder-open"></i>
                </div>

                <h4>No Projects Available</h4>

                <p>
                    There are currently no published projects.
                    Approved project information will appear here
                    when it becomes available.
                </p>

            </div>

        @endforelse

    </div>


    {{-- Important Notice --}}
    <div class="project-notice">

        <i class="fas fa-shield-alt"></i>

        <div>
            <h5>Important Information</h5>

            <p>
                Project publication does not guarantee financial returns
                or investment safety. Any real share offering, investment
                collection or profit distribution must remain disabled
                until the applicable legal and regulatory requirements
                have been verified.
            </p>
        </div>

    </div>

</div>

@endsection