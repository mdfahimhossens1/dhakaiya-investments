@push('styles')
<style>
    .welcome-banner {
        background: linear-gradient(110deg, #087f5b, #10b981);
        padding: 32px;
        border-radius: 15px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        overflow: hidden;
    }

    .welcome-label {
        font-size: 10px;
        letter-spacing: 2px;
        font-weight: 700;
        opacity: .8;
    }

    .welcome-banner h1 {
        font-size: 25px;
        margin: 10px 0;
    }

    .welcome-banner p {
        font-size: 13px;
        opacity: .85;
    }

    .welcome-icon {
        font-size: 80px;
        opacity: .2;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 25px;
    }

    .stat-card,
    .dashboard-card {
        background: white;
        border: 1px solid #edf0f2;
        border-radius: 12px;
        padding: 22px;
    }
.stats-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
}

.dashboard-grid {
    grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr);
}

@media(max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media(max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: minmax(0, 1fr);
    }
}

@media(max-width: 480px) {
    .stats-grid {
        grid-template-columns: minmax(0, 1fr);
    }
}
    .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-top > span {
        color: #718096;
        font-size: 12px;
        font-weight: 600;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        border-radius: 10px;
        font-size: 19px;
    }

    .green {
        background: #dcfce7;
        color: #15803d;
    }

    .blue {
        background: #dbeafe;
        color: #2563eb;
    }

    .purple {
        background: #ede9fe;
        color: #7c3aed;
    }

    .orange {
        background: #ffedd5;
        color: #ea580c;
    }

    .stat-card h2 {
        font-size: 27px;
        margin: 18px 0 6px;
        color: #1e293b;
    }

    .stat-card p {
        font-size: 11px;
        color: #94a3b8;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }

    .dashboard-card h3 {
        font-size: 16px;
        margin-bottom: 7px;
    }

    .dashboard-card > p {
        color: #94a3b8;
        font-size: 11px;
        margin-bottom: 20px;
    }

    .quick-actions {
        margin-top: 15px;
    }

    .action-item {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
    }

    .action-item:last-child {
        border-bottom: 0;
    }

    .action-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        font-size: 18px;
    }

    .action-item div:nth-child(2) {
        flex: 1;
    }

    .action-item h4 {
        font-size: 12px;
        margin-bottom: 5px;
    }

    .action-item p {
        font-size: 10px;
        color: #94a3b8;
    }

    .action-item > span {
        font-size: 20px;
        color: #94a3b8;
    }

    .account-card {
        text-align: center;
    }

    .account-avatar {
        width: 65px;
        height: 65px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: #dcfce7;
        color: #087f5b;
        font-size: 26px;
        font-weight: 700;
        margin: 22px auto 12px;
    }

    .account-card .account-name {
        margin-bottom: 10px;
    }

    .role-badge {
        display: inline-block;
        background: #dcfce7;
        color: #087f5b;
        padding: 6px 13px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 700;
    }

    .account-details {
        margin-top: 22px;
        text-align: left;
    }

    .account-details div {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .account-details span {
        font-size: 11px;
        color: #94a3b8;
    }

    .account-details strong {
        font-size: 11px;
        text-align: right;
        word-break: break-word;
    }

    .status-active {
        color: #16a34a;
    }

    .activity-card {
        margin-bottom: 25px;
    }

    .card-heading {
        margin-bottom: 18px;
    }

    .card-heading h3 {
        margin-bottom: 6px;
    }

    .card-heading p {
        font-size: 11px;
        color: #94a3b8;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 550px;
    }

    .dashboard-table th {
        text-align: left;
        padding: 13px;
        font-size: 10px;
        color: #94a3b8;
        background: #f8fafc;
    }

    .dashboard-table td {
        padding: 14px 13px;
        font-size: 11px;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #dcfce7;
        color: #087f5b;
        display: grid;
        place-items: center;
        font-weight: 700;
    }

    .table-role {
        display: inline-block;
        background: #f1f5f9;
        padding: 5px 9px;
        border-radius: 5px;
        font-size: 10px;
    }

    .referral-card {
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .referral-icon {
        width: 55px;
        height: 55px;
        display: grid;
        place-items: center;
        background: #dcfce7;
        color: #087f5b;
        border-radius: 50%;
        font-size: 25px;
        margin-bottom: 18px;
    }

    .referral-card p {
        color: #94a3b8;
        font-size: 11px;
        margin: 10px 0 20px;
    }

    .referral-code {
        width: 100%;
        padding: 14px;
        border: 1px dashed #087f5b;
        background: #f0fdf4;
        color: #087f5b;
        font-size: 17px;
        font-weight: 700;
        letter-spacing: 2px;
        border-radius: 8px;
    }

    .copy-btn {
        width: 100%;
        padding: 12px;
        margin-top: 15px;
        border: 0;
        background: #087f5b;
        color: white;
        border-radius: 7px;
        font-weight: 600;
        cursor: pointer;
    }

    #copyMessage {
        margin-top: 10px;
        color: #16a34a;
        font-size: 11px;
    }

    @media(max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .welcome-banner {
            padding: 24px;
        }

        .welcome-banner h1 {
            font-size: 20px;
        }

        .welcome-icon {
            font-size: 55px;
        }
    }

    @media(max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .stat-card {
            padding: 18px;
        }

        .welcome-banner {
            padding: 20px;
        }
    }
</style>
@endpush