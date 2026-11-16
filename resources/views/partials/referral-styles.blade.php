<style>
    .rf-wrap { display: flex; flex-direction: column; gap: 20px; }

    .rf-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

    .rf-card, .rf-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
    }
    .rf-card { display: flex; align-items: center; gap: 16px; transition: transform .15s, box-shadow .15s; }
    .rf-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(15, 23, 42, .07); }

    .rf-icon {
        width: 50px; height: 50px; flex-shrink: 0; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; background: #e9f7f1; color: var(--primary);
    }
    .rf-icon.amber  { background: #fef3c7; color: #b45309; }
    .rf-icon.blue   { background: #dbeafe; color: #1d4ed8; }
    .rf-icon.green  { background: #dcfce7; color: #15803d; }
    .rf-icon.purple { background: #ede9fe; color: #6d28d9; }

    .rf-card h3 { font-size: 22px; font-weight: 700; color: var(--text); margin: 0 0 3px; line-height: 1.2; }
    .rf-card p  { font-size: 13px; color: var(--muted); margin: 0; }
    .rf-card small { display: block; font-size: 11.5px; color: var(--muted); margin-top: 3px; }

    .rf-panel-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    .rf-panel-head h3 { font-size: 18px; font-weight: 700; color: var(--text); margin: 0 0 4px; }
    .rf-panel-head p  { font-size: 13px; color: var(--muted); margin: 0; }

    /* Level tiles */
    .rf-levels { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .rf-level {
        border: 1px solid var(--border); border-radius: 14px; padding: 18px;
        background: linear-gradient(180deg, #fff, #f8fafc); text-align: center;
    }
    .rf-level.off { opacity: .45; }
    .rf-level small { display: block; font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
    .rf-level strong { display: block; font-size: 28px; color: var(--primary); line-height: 1.1; }
    .rf-level span { display: block; font-size: 12px; color: var(--muted); margin-top: 6px; }

    /* Chips */
    .rf-chips { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
    .rf-chip {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 14px; border-radius: 999px;
        background: #f1f5f9; color: var(--text); font-size: 12.5px; font-weight: 500;
    }
    .rf-chip i { color: var(--primary); }

    /* Badges */
    .rf-badge { display: inline-block; padding: 4px 11px; border-radius: 999px; font-size: 11.5px; font-weight: 600; }
    .rf-badge.pending   { background: #fef3c7; color: #92400e; }
    .rf-badge.paid      { background: #dcfce7; color: #166534; }
    .rf-badge.cancelled { background: #fee2e2; color: #991b1b; }
    .rf-badge.lvl       { background: #e0f2fe; color: #075985; }
    .rf-badge.type      { background: #f1f5f9; color: #334155; }

    /* Tabs */
    .rf-tabs { display: flex; gap: 8px; flex-wrap: wrap; }
    .rf-tab {
        padding: 7px 16px; border-radius: 999px; font-size: 12.5px; font-weight: 600;
        background: #f1f5f9; color: var(--muted); text-decoration: none; transition: .15s;
    }
    .rf-tab:hover { background: #e2e8f0; }
    .rf-tab.active { background: var(--primary); color: #fff; }

    /* Progress */
    .rf-progress { height: 10px; background: #e2e8f0; border-radius: 20px; overflow: hidden; margin: 12px 0 8px; }
    .rf-progress span { display: block; height: 100%; background: linear-gradient(90deg, var(--primary), #34d399); border-radius: 20px; }

    /* Table */
    .rf-table-wrap { overflow-x: auto; margin: 0 -4px; }
    .rf-table { width: 100%; border-collapse: collapse; min-width: 720px; }
    .rf-table th {
        text-align: left; font-size: 11.5px; font-weight: 600; text-transform: uppercase;
        letter-spacing: .05em; color: var(--muted); padding: 11px 14px;
        border-bottom: 1px solid var(--border); background: #f8fafc;
    }
    .rf-table td { padding: 14px; font-size: 13px; color: var(--text); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .rf-table tr:last-child td { border-bottom: none; }
    .rf-table tbody tr:hover { background: #fafcfb; }
    .rf-table .num { text-align: right; white-space: nowrap; }
    .rf-table small { display: block; color: var(--muted); font-size: 11.5px; margin-top: 2px; }
    .rf-money { font-weight: 700; color: var(--text); }

    /* Empty */
    .rf-empty { text-align: center; padding: 45px 20px; }
    .rf-empty i { font-size: 36px; color: #cbd5e1; margin-bottom: 14px; display: block; }
    .rf-empty h4 { font-size: 16px; color: var(--text); margin: 0 0 6px; }
    .rf-empty p { max-width: 420px; margin: 0 auto; color: var(--muted); font-size: 13px; line-height: 1.7; }

    /* Notice */
    .rf-notice { padding: 15px 18px; border-radius: 12px; font-size: 13px; line-height: 1.7; background: #eff6ff; border: 1px solid #dbeafe; color: #1e40af; }
    .rf-notice.warn { background: #fffbeb; border-color: #fde68a; color: #92400e; }
    .rf-notice.ok   { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }

    .rf-pagination { margin-top: 18px; }

    @media (max-width: 750px) {
        .rf-grid-3, .rf-levels { grid-template-columns: 1fr; }
    }
    @media (max-width: 550px) {
        .rf-card, .rf-panel { padding: 17px; }
    }
</style>