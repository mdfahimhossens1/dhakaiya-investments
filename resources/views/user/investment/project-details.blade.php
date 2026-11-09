@extends('layouts.app')

@section('title', 'Project Details')

@section('content')

<style>
    .project-details-page {
        padding: 5px 0 25px;
    }

    .project-details-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .project-details-heading h2 {
        font-size: 23px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 5px;
    }

    .project-details-heading p {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        background: var(--white);
    }

    .back-btn:hover {
        color: var(--primary);
        border-color: var(--primary);
    }

    .project-main-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .project-banner {
        min-height: 190px;
        background: linear-gradient(135deg, #102b26, #087f5b);
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        color: white;
        text-align: center;
        padding: 30px 20px;
    }

    .project-banner i {
        font-size: 42px;
        margin-bottom: 15px;
        opacity: .9;
    }

    .project-banner h3 {
        font-size: 21px;
        font-weight: 700;
        margin: 0 0 8px;
    }

    .project-banner p {
        font-size: 12px;
        margin: 0;
        opacity: .8;
    }

    .project-content {
        padding: 25px;
    }

    .project-content h4 {
        color: var(--text);
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 12px;
    }

    .project-content p {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.8;
        margin-bottom: 20px;
    }

    .project-information-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 20px;
    }

    .information-item {
        border: 1px solid var(--border);
        border-radius: 9px;
        padding: 16px;
        background: #fafcfc;
    }

    .information-item span {
        display: block;
        color: var(--muted);
        font-size: 11px;
        margin-bottom: 8px;
    }

    .information-item strong {
        color: var(--text);
        font-size: 13px;
        font-weight: 600;
    }

    .project-status {
        display: inline-block;
        background: #fff7e6;
        color: #b7791f;
        padding: 7px 12px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 600;
    }

    .project-empty {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 65px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 75px;
        height: 75px;
        border-radius: 50%;
        background: #e8f5ef;
        color: var(--primary);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 30px;
        margin: 0 auto 20px;
    }

    .project-empty h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 10px;
    }

    .project-empty p {
        color: var(--muted);
        font-size: 12px;
        line-height: 1.8;
        max-width: 430px;
        margin: 0 auto 22px;
    }

    .browse-btn {
        display: inline-block;
        background: var(--primary);
        color: white;
        text-decoration: none;
        padding: 11px 20px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .browse-btn:hover {
        background: var(--primary-dark);
        color: white;
    }

    .project-notice {
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

    @media(max-width: 576px) {
        .project-information-grid {
            grid-template-columns: 1fr;
        }

        .project-content {
            padding: 18px;
        }

        .back-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="project-details-page">

    {{-- Heading --}}
    <div class="project-details-heading">

        <div>
            <h2>Project Details</h2>
            <p>View detailed information about a project.</p>
        </div>

        <a href="{{ route('user.available-projects.index') }}"
           class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Available Projects
        </a>

    </div>


    @if($project)

        {{-- Future Project Details --}}
        <div class="project-main-card">

            <div class="project-banner">
                <i class="fas fa-building"></i>
                <h3>{{ $project->name }}</h3>
                <p>Project Information</p>
            </div>

            <div class="project-content">

                <span class="project-status">
                    {{ ucfirst($project->status) }}
                </span>

                <h4 style="margin-top: 20px;">About This Project</h4>

                <p>
                    {{ $project->description }}
                </p>

                <div class="project-information-grid">

                    <div class="information-item">
                        <span>Project Category</span>
                        <strong>{{ $project->category }}</strong>
                    </div>

                    <div class="information-item">
                        <span>Project Status</span>
                        <strong>{{ ucfirst($project->status) }}</strong>
                    </div>

                </div>

            </div>

        </div>

    @else

        {{-- Empty State --}}
        <div class="project-empty">

            <div class="empty-icon">
                <i class="fas fa-folder-open"></i>
            </div>

            <h3>Project Details Not Available</h3>

            <p>
                @if(isset($slug))
                    The requested project has not been published
                    or its details are not available yet.
                @else
                    Select a published project from the Available
                    Projects section to view its details.
                @endif
            </p>

            <a href="{{ route('user.available-projects.index') }}"
               class="browse-btn">
                <i class="fas fa-search"></i>
                Browse Available Projects
            </a>

        </div>

    @endif


    {{-- Notice --}}
    <div class="project-notice" style="margin-top: 20px;">

        <i class="fas fa-shield-alt"></i>

        <div>
            <h5>Important Information</h5>

            <p>
                Project descriptions and other information must be
                verified before publication. Displaying project
                information does not guarantee financial returns.
                Real investment and share-related activities must
                remain disabled until applicable legal and regulatory
                approvals are confirmed.
            </p>
        </div>

    </div>

</div>

@endsection