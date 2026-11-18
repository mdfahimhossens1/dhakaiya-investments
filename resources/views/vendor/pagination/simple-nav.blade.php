@if ($paginator->hasPages())
    <nav class="pg-nav" role="navigation" aria-label="Pagination">

        @if ($paginator->onFirstPage())
            <span class="pg-btn off">‹ Previous</span>
        @else
            <a class="pg-btn"
               href="{{ $paginator->previousPageUrl() }}"
               rel="prev">‹ Previous</a>
        @endif

        <span class="pg-info">
            Page {{ $paginator->currentPage() }}
            of {{ $paginator->lastPage() }}
        </span>

        @if ($paginator->hasMorePages())
            <a class="pg-btn"
               href="{{ $paginator->nextPageUrl() }}"
               rel="next">Next ›</a>
        @else
            <span class="pg-btn off">Next ›</span>
        @endif

    </nav>

    <style>
        .pg-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin: 6px 0;
        }

        .pg-btn {
            padding: 9px 16px;
            border-radius: 8px;
            background: #087f5b;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }

        .pg-btn.off {
            background: #e9eef2;
            color: #9aa5a0;
            cursor: not-allowed;
        }

        .pg-info {
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
        }
    </style>
@endif