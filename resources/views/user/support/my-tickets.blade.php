@extends('layouts.app')

@section('page-title', 'My Tickets')

@section('content')

<style>
    .tickets-page {
        padding-bottom: 25px;
    }

    .tickets-hero {
        background: linear-gradient(135deg, #102b26, #087f5b);
        padding: 28px;
        border-radius: 14px;
        color: #fff;
        margin-bottom: 25px;
    }

    .tickets-hero h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .tickets-hero p {
        color: rgba(255,255,255,.8);
        margin: 0;
        font-size: 14px;
    }

    .ticket-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .ticket-stat {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 19px;
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .ticket-stat-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .ticket-stat-icon.total {
        background: #e8f1ff;
        color: #2563eb;
    }

    .ticket-stat-icon.open {
        background: #fff7ed;
        color: #ea580c;
    }

    .ticket-stat-icon.progress {
        background: #eff6ff;
        color: #3b82f6;
    }

    .ticket-stat-icon.closed {
        background: #ecfdf5;
        color: #059669;
    }

    .ticket-stat h3 {
        font-size: 21px;
        font-weight: 700;
        margin: 0;
        color: var(--text);
    }

    .ticket-stat span {
        font-size: 12px;
        color: var(--muted);
    }

    .tickets-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .tickets-card-header {
        padding: 20px 22px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .tickets-card-header h4 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: var(--text);
    }

    .tickets-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .tickets-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        padding: 15px 18px;
        text-align: left;
        white-space: nowrap;
    }

    .tickets-table td {
        padding: 16px 18px;
        border-top: 1px solid var(--border);
        color: var(--text);
        font-size: 13px;
        white-space: nowrap;
    }

    .ticket-id {
        font-weight: 700;
        color: var(--primary);
    }

    .ticket-subject {
        font-weight: 600;
    }

    .ticket-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-open {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-progress {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .status-closed {
        background: #ecfdf5;
        color: #047857;
    }

    .priority-low {
        color: #64748b;
    }

    .priority-medium {
        color: #d97706;
    }

    .priority-high {
        color: #dc2626;
    }

    .tickets-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .tickets-empty i {
        font-size: 43px;
        color: #cbd5e1;
        margin-bottom: 15px;
    }

    .tickets-empty h5 {
        color: var(--text);
        font-weight: 700;
        margin-bottom: 8px;
    }

    .tickets-empty p {
        color: var(--muted);
        font-size: 14px;
        margin: 0;
    }

    .ticket-notice {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1d4ed8;
        border-radius: 10px;
        padding: 15px 18px;
        margin-top: 20px;
        font-size: 13px;
        line-height: 1.6;
    }

    @media(max-width: 992px) {
        .ticket-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 576px) {
        .ticket-stats {
            grid-template-columns: 1fr;
        }

        .tickets-hero {
            padding: 22px;
        }

        .tickets-hero h2 {
            font-size: 21px;
        }

        .tickets-card-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="tickets-page">

    {{-- Page Header --}}
    <div class="tickets-hero">
        <h2>
            <i class="fa-solid fa-list-check me-2"></i>
            My Support Tickets
        </h2>

        <p>
            Track and review your support requests in one place.
        </p>
    </div>

    {{-- Statistics --}}
    <div class="ticket-stats">

        <div class="ticket-stat">
            <div class="ticket-stat-icon total">
                <i class="fa-solid fa-ticket"></i>
            </div>

            <div>
                <h3>{{ $summary['total'] ?? 0 }}</h3>
                <span>Total Tickets</span>
            </div>
        </div>

        <div class="ticket-stat">
            <div class="ticket-stat-icon open">
                <i class="fa-solid fa-folder-open"></i>
            </div>

            <div>
                <h3>{{ $summary['open'] ?? 0 }}</h3>
                <span>Open Tickets</span>
            </div>
        </div>

        <div class="ticket-stat">
            <div class="ticket-stat-icon progress">
                <i class="fa-solid fa-spinner"></i>
            </div>

            <div>
                <h3>{{ $summary['in_progress'] ?? 0 }}</h3>
                <span>In Progress</span>
            </div>
        </div>

        <div class="ticket-stat">
            <div class="ticket-stat-icon closed">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div>
                <h3>{{ $summary['closed'] ?? 0 }}</h3>
                <span>Closed Tickets</span>
            </div>
        </div>

    </div>

    {{-- Tickets Table --}}
    <div class="tickets-card">

        <div class="tickets-card-header">
            <h4>
                <i class="fa-solid fa-table-list me-2"></i>
                Ticket History
            </h4>
        </div>

        @if($tickets->isNotEmpty())

            <div class="table-responsive">

                <table class="tickets-table">

                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Subject</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Submitted Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($tickets as $ticket)

                            @php
                                $status = strtolower(data_get($ticket, 'status', 'open'));
                                $priority = strtolower(data_get($ticket, 'priority', 'medium'));

                                $statusClass = match($status) {
                                    'open' => 'status-open',
                                    'in_progress' => 'status-progress',
                                    'closed' => 'status-closed',
                                    default => 'status-open',
                                };

                                $priorityClass = match($priority) {
                                    'low' => 'priority-low',
                                    'high' => 'priority-high',
                                    default => 'priority-medium',
                                };
                            @endphp

                            <tr>

                                <td>
                                    <span class="ticket-id">
                                        #{{ data_get($ticket, 'ticket_number', data_get($ticket, 'id', 'N/A')) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="ticket-subject">
                                        {{ data_get($ticket, 'subject', 'Support Request') }}
                                    </span>
                                </td>

                                <td>
                                    {{ data_get($ticket, 'category', 'General') }}
                                </td>

                                <td>
                                    <span class="{{ $priorityClass }}">
                                        {{ ucfirst($priority) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="ticket-status {{ $statusClass }}">
                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>

                                <td>
                                    @if(data_get($ticket, 'created_at'))
                                        {{ \Carbon\Carbon::parse(data_get($ticket, 'created_at'))->format('d M Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="tickets-empty">

                <i class="fa-solid fa-ticket"></i>

                <h5>No Support Tickets Found</h5>

                <p>
                    You haven't submitted any support tickets yet.
                    Your submitted tickets will appear here.
                </p>

            </div>

        @endif

    </div>

    {{-- Notice --}}
    <div class="ticket-notice">
        <i class="fa-solid fa-circle-info me-2"></i>

        <strong>Information:</strong>
        Ticket tracking is currently a display-only interface.
        Ticket submission and support responses are not activated.
    </div>

</div>

@endsection