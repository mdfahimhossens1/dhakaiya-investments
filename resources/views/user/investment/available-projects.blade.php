@extends('layouts.app')

@section('title', 'Available Projects')

@section('content')

<div class="available-projects-page">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <div class="page-breadcrumb">
                <span>User Panel</span>
                <i class="fas fa-chevron-right"></i>
                <span>Projects</span>
            </div>

            <h1>Explore Projects</h1>

            <p>
                Discover and explore the projects published on our platform.
            </p>
        </div>

        <div class="header-icon">
            <i class="fas fa-layer-group"></i>
        </div>
    </div>


    {{-- Statistics --}}
    <div class="project-stats">

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-folder-open"></i>
            </div>

            <div>
                <span>Total Projects</span>
                <h3>{{ $projects->total() }}</h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>

            <div>
                <span>Open Projects</span>
                <h3>
                    {{ \App\Models\Project::where('is_active', true)->where('status', 'active')->where('available_shares', '>', 0)->count() }}
                </h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>

            <div>
                <span>Upcoming Projects</span>
                <h3>
                    {{ \App\Models\Project::where('is_active', true)->where('status', 'upcoming')->count() }}
                </h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-building"></i>
            </div>

            <div>
                <span>Categories</span>
                <h3>{{ count($categories) - 1 }}</h3>
            </div>
        </div>

    </div>

    {{-- Filter Section --}}
    <div class="filter-card">

        <div class="filter-header">
            <div>
                <h3>
                    <i class="fas fa-sliders-h"></i>
                    Explore Projects
                </h3>

                <p>Find projects using the available filters.</p>
            </div>

            <span class="result-count">
                {{ $projects->total() }} Projects Found
            </span>
        </div>


        <form action="{{ route('user.available-projects.index') }}"
              method="GET"
              class="project-filter-form">

            {{-- Search --}}
            <div class="filter-field search-field">
                <label for="projectSearch">Search Projects</label>

                <div class="input-wrapper">
                    <i class="fas fa-search"></i>

                    <input
                        type="text"
                        id="projectSearch"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by project name..."
                    >
                </div>
            </div>


            {{-- Category --}}
            <div class="filter-field">
                <label for="category">Category</label>

                <div class="select-wrapper">
                    <i class="fas fa-layer-group"></i>

                    <select name="category" id="category">
                        @foreach($categories as $slug => $name)
                            <option
                                value="{{ $slug }}"
                                {{ $category === $slug ? 'selected' : '' }}
                            >
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>


            {{-- Status --}}
            <div class="filter-field">
                <label for="status">Project Status</label>

                <div class="select-wrapper">
                    <i class="fas fa-filter"></i>

                    <select name="status" id="status">

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
                </div>
            </div>


            {{-- Buttons --}}
            <div class="filter-actions">

                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i>
                    Search
                </button>

                <a href="{{ route('user.available-projects.index') }}"
                   class="btn-reset">
                    <i class="fas fa-redo-alt"></i>
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- Projects Section --}}
    <div class="projects-section">

        <div class="section-heading">
            <div>
                <h2>Available Projects</h2>
                <p>Browse the latest published projects.</p>
            </div>
        </div>


        <div class="projects-grid">

            @forelse($projects as $project)

                @php

                    $projectStatus = match($project->status) {
                        'active' => 'Open',
                        'upcoming' => 'Upcoming',
                        'completed' => 'Completed',
                        'paused' => 'Paused',
                        default => ucfirst($project->status ?? 'Unknown'),
                    };

                    $statusClass = match($project->status) {
                        'active' => 'status-open',
                        'upcoming' => 'status-upcoming',
                        'completed' => 'status-completed',
                        'paused' => 'status-paused',
                        default => 'status-default',
                    };

                    $categoryName = $project->projectCategory->name
                        ?? $project->category
                        ?? 'General';

                    $companyName = $project->company->name ?? 'Company';

                    $description = $project->description
                        ? \Illuminate\Support\Str::limit(strip_tags($project->description), 120)
                        : 'Project description has not been provided yet.';

                @endphp


                <article class="project-card">

                    {{-- Image --}}
                    <div class="project-image">

                        @if($project->image)
                            <img
                                src="{{ asset('storage/' . $project->image) }}"
                                alt="{{ $project->name }}"
                                loading="lazy"
                            >
                        @else
                            <div class="image-placeholder">
                                <i class="fas fa-building"></i>
                            </div>
                        @endif


                        {{-- Featured --}}
                        @if($project->is_featured)
                            <span class="featured-badge">
                                <i class="fas fa-star"></i>
                                Featured
                            </span>
                        @endif


                        {{-- Status --}}
                        <span class="project-status {{ $statusClass }}">
                            <span class="status-dot"></span>
                            {{ $projectStatus }}
                        </span>

                    </div>


                    {{-- Card Body --}}
                    <div class="project-content">

                        {{-- Category & Company --}}
                        <div class="project-meta">

                            <span class="category-badge">
                                <i class="fas fa-tag"></i>
                                {{ $categoryName }}
                            </span>

                            <span class="company-name">
                                <i class="fas fa-building"></i>
                                {{ $companyName }}
                            </span>

                        </div>


                        {{-- Title --}}
                        <h3 class="project-title">
                            {{ $project->name }}
                        </h3>


                        {{-- Description --}}
                        <p class="project-description">
                            {{ $description }}
                        </p>


                        {{-- Project Information --}}
                        <div class="project-information">

                            <div class="information-item">
                                <div class="information-icon">
                                    <i class="far fa-calendar-alt"></i>
                                </div>

                                <div>
                                    <span>Start Date</span>

                                    <strong>
                                        {{ $project->start_date
                                            ? $project->start_date->format('d M Y')
                                            : 'Not announced' }}
                                    </strong>
                                </div>
                            </div>

                            <div class="information-item">
                                <div class="information-icon">
                                    <i class="far fa-calendar-check"></i>
                                </div>

                                <div>
                                    <span>End Date</span>

                                    <strong>
                                        {{ $project->end_date
                                            ? $project->end_date->format('d M Y')
                                            : 'Not announced' }}
                                    </strong>
                                </div>
                            </div>

                        </div>


                        {{-- Footer --}}
                        <div class="project-footer">

                            <span class="project-reference">
                                Project #{{ $project->id }}
                            </span>

                            <span class="details-label">
                                <a href="{{ route('user.project-details.show', $project->slug) }}"> Project Information </a>
                                <i class="fas fa-arrow-right"></i>
                            </span>

                        </div>

                    </div>

                </article>

            @empty

                <div class="empty-state">

                    <div class="empty-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>

                    <h3>No Projects Found</h3>

                    <p>
                        We couldn't find any projects matching your search.
                        Try changing your filters or check back later.
                    </p>

                    <a href="{{ route('user.available-projects.index') }}"
                       class="empty-reset-btn">
                        <i class="fas fa-redo-alt"></i>
                        View All Projects
                    </a>

                </div>

            @endforelse

        </div>


        {{-- Pagination --}}
        @if($projects->hasPages())
            <div class="pagination-wrapper">
                {{ $projects->links() }}
            </div>
        @endif

    </div>

</div>

@endsection


@push('styles')
<style>

/* =====================================
   AVAILABLE PROJECTS - MAIN DESIGN
===================================== */

.available-projects-page {
    width: 100%;
    padding-bottom: 30px;
    color: #1e293b;
}


/* =====================================
   PAGE HEADER
===================================== */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 28px;
}

.page-breadcrumb {
    display: flex;
    align-items: center;
    gap: 9px;
    color: #94a3b8;
    font-size: 12px;
    margin-bottom: 10px;
}

.page-breadcrumb i {
    font-size: 9px;
}

.page-header h1 {
    font-size: 27px;
    font-weight: 800;
    color: #172033;
    margin: 0 0 7px;
}

.page-header p {
    font-size: 13px;
    color: #8491a5;
    margin: 0;
}

.header-icon {
    width: 58px;
    height: 58px;
    min-width: 58px;
    border-radius: 16px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #eef2ff;
    color: #5145cd;
    font-size: 24px;
}


/* =====================================
   STATISTICS
===================================== */

.project-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 17px;
    margin-bottom: 22px;
}

.stat-card {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 13px;
    padding: 19px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .025);
}

.stat-icon {
    width: 45px;
    height: 45px;
    min-width: 45px;
    border-radius: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 18px;
}

.stat-icon.blue {
    background: #edf3ff;
    color: #4675d5;
}

.stat-icon.green {
    background: #eafaf1;
    color: #16a05d;
}

.stat-icon.orange {
    background: #fff5e8;
    color: #e58b24;
}

.stat-icon.purple {
    background: #f3edff;
    color: #8b5cf6;
}

.stat-card span {
    font-size: 11px;
    color: #8b98aa;
    display: block;
    margin-bottom: 5px;
}

.stat-card h3 {
    font-size: 22px;
    font-weight: 800;
    color: #1e293b;
    margin: 0;
}


/* =====================================
   NOTICE
===================================== */

.project-notice {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 17px 19px;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    border-radius: 12px;
    margin-bottom: 25px;
}

.notice-icon {
    color: #3b82f6;
    font-size: 19px;
    margin-top: 2px;
}

.project-notice strong {
    display: block;
    color: #1e40af;
    font-size: 13px;
    margin-bottom: 5px;
}

.project-notice p {
    color: #526b91;
    font-size: 12px;
    line-height: 1.7;
    margin: 0;
}


/* =====================================
   FILTER CARD
===================================== */

.filter-card {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 15px;
    padding: 22px;
    margin-bottom: 30px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .025);
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 21px;
}

.filter-header h3 {
    color: #1e293b;
    font-size: 16px;
    font-weight: 750;
    margin: 0 0 5px;
}

.filter-header h3 i {
    color: #6475dc;
    margin-right: 7px;
}

.filter-header p {
    font-size: 12px;
    color: #94a3b8;
    margin: 0;
}

.result-count {
    font-size: 11px;
    font-weight: 700;
    color: #5145cd;
    background: #f0efff;
    padding: 8px 12px;
    border-radius: 30px;
    white-space: nowrap;
}

.project-filter-form {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr auto;
    gap: 13px;
    align-items: end;
}

.filter-field label {
    display: block;
    font-size: 12px;
    font-weight: 650;
    color: #475569;
    margin-bottom: 8px;
}

.input-wrapper,
.select-wrapper {
    position: relative;
}

.input-wrapper i,
.select-wrapper i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 13px;
    pointer-events: none;
}

.input-wrapper input,
.select-wrapper select {
    width: 100%;
    height: 44px;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    background: #fff;
    padding: 0 13px 0 38px;
    color: #334155;
    font-size: 12px;
    outline: none;
    transition: .2s;
}

.input-wrapper input:focus,
.select-wrapper select:focus {
    border-color: #818cf8;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, .09);
}

.filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-filter,
.btn-reset {
    height: 44px;
    padding: 0 15px;
    border: none;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    white-space: nowrap;
    transition: .2s;
}

.btn-filter {
    color: #fff;
    background: #5145cd;
}

.btn-filter:hover {
    background: #4338b5;
}

.btn-reset {
    color: #64748b;
    background: #f1f5f9;
}

.btn-reset:hover {
    background: #e2e8f0;
    color: #334155;
}


/* =====================================
   SECTION HEADING
===================================== */

.section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.section-heading h2 {
    font-size: 19px;
    font-weight: 800;
    color: #172033;
    margin: 0 0 5px;
}

.section-heading p {
    color: #94a3b8;
    font-size: 12px;
    margin: 0;
}


/* =====================================
   PROJECT GRID
===================================== */

.projects-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 22px;
}


/* =====================================
   PROJECT CARD
===================================== */

.project-card {
    background: #fff;
    border: 1px solid #e9edf3;
    border-radius: 15px;
    overflow: hidden;
    transition: all .3s ease;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .025);
}

.project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(15, 23, 42, .09);
    border-color: #d9defa;
}


/* =====================================
   PROJECT IMAGE
===================================== */

.project-image {
    width: 100%;
    height: 205px;
    position: relative;
    overflow: hidden;
    background: #eef2ff;
}

.project-image img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: transform .5s ease;
}

.project-card:hover .project-image img {
    transform: scale(1.06);
}

.image-placeholder {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #e8edff, #f5f6ff);
    color: #7c83d8;
    font-size: 53px;
}


/* =====================================
   FEATURED BADGE
===================================== */

.featured-badge {
    position: absolute;
    top: 13px;
    left: 13px;
    padding: 7px 11px;
    background: #fff7df;
    color: #a16207;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 750;
    box-shadow: 0 3px 10px rgba(0,0,0,.06);
}


/* =====================================
   STATUS BADGE
===================================== */

.project-status {
    position: absolute;
    top: 13px;
    right: 13px;
    padding: 7px 11px;
    border-radius: 30px;
    background: #fff;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 10px;
    font-weight: 750;
    box-shadow: 0 3px 10px rgba(0,0,0,.07);
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.status-open {
    color: #15803d;
}

.status-open .status-dot {
    background: #22c55e;
}

.status-upcoming {
    color: #b45309;
}

.status-upcoming .status-dot {
    background: #f59e0b;
}

.status-completed {
    color: #2563eb;
}

.status-completed .status-dot {
    background: #3b82f6;
}

.status-paused {
    color: #64748b;
}

.status-paused .status-dot {
    background: #94a3b8;
}

.status-default {
    color: #64748b;
}

.status-default .status-dot {
    background: #94a3b8;
}


/* =====================================
   PROJECT CONTENT
===================================== */

.project-content {
    padding: 18px;
}

.project-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 13px;
}

.category-badge {
    background: #f0efff;
    color: #5145cd;
    padding: 6px 9px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
}

.company-name {
    font-size: 10px;
    color: #8491a5;
    font-weight: 600;
}

.company-name i {
    margin-right: 4px;
}

.project-title {
    font-size: 17px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.45;
    margin: 0 0 9px;
}

.project-description {
    color: #7c8a9e;
    font-size: 12px;
    line-height: 1.8;
    min-height: 66px;
    margin: 0;
}


/* =====================================
   PROJECT INFORMATION
===================================== */

.project-information {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    padding: 14px 0;
    margin-top: 15px;
    border-top: 1px solid #f0f2f6;
    border-bottom: 1px solid #f0f2f6;
}

.information-item {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 0;
}

.information-icon {
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f3f5ff;
    color: #6575d8;
    font-size: 13px;
}

.information-item span {
    display: block;
    font-size: 9px;
    color: #9aa5b5;
    margin-bottom: 4px;
}

.information-item strong {
    display: block;
    font-size: 10px;
    font-weight: 750;
    color: #475569;
    white-space: nowrap;
}


/* =====================================
   CARD FOOTER
===================================== */

.project-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    padding-top: 14px;
}

.project-reference {
    color: #a0aabb;
    font-size: 10px;
    font-weight: 600;
}

.details-label {
    color: #5145cd;
    font-size: 10px;
    font-weight: 750;
}

.details-label i {
    margin-left: 4px;
}


/* =====================================
   EMPTY STATE
===================================== */

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 65px 20px;
    background: #fff;
    border: 1px dashed #dce3ed;
    border-radius: 15px;
}

.empty-icon {
    width: 75px;
    height: 75px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto 18px;
    border-radius: 50%;
    background: #f1f3ff;
    color: #7582d9;
    font-size: 29px;
}

.empty-state h3 {
    font-size: 18px;
    color: #1e293b;
    font-weight: 800;
    margin: 0 0 8px;
}

.empty-state p {
    color: #94a3b8;
    font-size: 12px;
    line-height: 1.7;
    max-width: 420px;
    margin: 0 auto 18px;
}

.empty-reset-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 17px;
    background: #5145cd;
    color: #fff;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
}

.empty-reset-btn:hover {
    background: #4338b5;
    color: #fff;
}


/* =====================================
   PAGINATION
===================================== */

.pagination-wrapper {
    margin-top: 27px;
    display: flex;
    justify-content: center;
}


/* =====================================
   RESPONSIVE DESIGN
===================================== */

@media(max-width: 1200px) {

    .project-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .project-filter-form {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .projects-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media(max-width: 650px) {

    .page-header {
        align-items: flex-start;
    }

    .page-header h1 {
        font-size: 22px;
    }

    .header-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        font-size: 19px;
    }

    .project-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .stat-card {
        padding: 13px 10px;
        gap: 9px;
    }

    .stat-icon {
        width: 37px;
        height: 37px;
        min-width: 37px;
        font-size: 15px;
    }

    .stat-card span {
        font-size: 10px;
    }

    .stat-card h3 {
        font-size: 19px;
    }

    .filter-card {
        padding: 16px;
    }

    .filter-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .project-filter-form {
        grid-template-columns: 1fr;
    }

    .filter-actions {
        width: 100%;
    }

    .filter-actions button,
    .filter-actions a {
        flex: 1;
    }

    .projects-grid {
        grid-template-columns: 1fr;
        gap: 17px;
    }

    .project-image {
        height: 215px;
    }

    .project-content {
        padding: 16px;
    }

    .project-notice {
        padding: 14px;
    }

}

</style>
@endpush