{{-- Shared styles for all user/investment pages --}}
<style>
    .inv-page { padding: 5px 0 25px; }

    .inv-heading { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:25px; }
    .inv-heading h2 { font-size:23px; font-weight:700; color:var(--text); margin:0 0 5px; }
    .inv-heading p { color:var(--muted); font-size:13px; margin:0; }

    .inv-badge { background:#e8f5ef; color:var(--primary); padding:9px 14px; border-radius:8px; font-size:12px; font-weight:600; }

    .inv-btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:10px 18px; border-radius:8px; font-size:12px; font-weight:600; border:1px solid transparent; cursor:pointer; text-decoration:none; }
    .inv-btn.primary { background:var(--primary); color:#fff; }
    .inv-btn.primary:hover { background:var(--primary-dark); color:#fff; }
    .inv-btn.outline { background:var(--white); color:var(--text); border-color:var(--border); }
    .inv-btn.outline:hover { color:var(--primary); border-color:var(--primary); }
    .inv-btn.small { padding:7px 12px; font-size:11px; }
    .inv-btn:disabled { opacity:.55; cursor:not-allowed; }

    /* alerts */
    .inv-alert { padding:13px 16px; border-radius:8px; margin-bottom:20px; font-size:13px; }
    .inv-alert.success { background:#dcfce7; color:#166534; }
    .inv-alert.error { background:#fee2e2; color:#991b1b; }
    .inv-alert ul { margin:6px 0 0; padding-left:20px; }

    /* summary */
    .inv-summary { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:25px; }
    .inv-stat { background:var(--white); border:1px solid var(--border); border-radius:12px; padding:20px; }
    .inv-stat .icon { width:40px; height:40px; display:flex; align-items:center; justify-content:center; border-radius:9px; background:#e8f5ef; color:var(--primary); font-size:17px; margin-bottom:14px; }
    .inv-stat .label { color:var(--muted); font-size:12px; margin-bottom:8px; }
    .inv-stat h3 { color:var(--text); font-size:23px; font-weight:700; margin:0; word-break:break-word; }
    .inv-stat small { display:block; color:var(--muted); font-size:11px; margin-top:7px; }

    /* panel */
    .inv-panel { background:var(--white); border:1px solid var(--border); border-radius:12px; overflow:hidden; margin-bottom:20px; }
    .inv-panel-header { padding:20px; border-bottom:1px solid var(--border); }
    .inv-panel-header h4 { font-size:16px; font-weight:700; color:var(--text); margin:0 0 5px; }
    .inv-panel-header p { font-size:12px; color:var(--muted); margin:0; }
    .inv-panel-body { padding:22px; }

    .inv-filter { display:flex; flex-wrap:wrap; gap:12px; padding:18px 20px; background:#fafcfc; border-bottom:1px solid var(--border); }
    .inv-filter select { min-width:180px; padding:10px 13px; border:1px solid var(--border); border-radius:8px; background:#fff; color:var(--text); font-size:13px; outline:none; }

    /* table */
    .inv-table-wrap { overflow-x:auto; }
    .inv-table { width:100%; min-width:800px; border-collapse:collapse; }
    .inv-table th { background:#f8fafb; color:var(--muted); font-size:11px; font-weight:600; padding:15px 18px; text-align:left; white-space:nowrap; }
    .inv-table td { padding:16px 18px; border-top:1px solid var(--border); font-size:12px; color:var(--text); vertical-align:middle; }
    .inv-table td small { display:block; color:var(--muted); font-size:10px; margin-top:3px; }

    .st-badge { display:inline-block; padding:5px 10px; border-radius:20px; font-size:11px; font-weight:600; background:#f1f5f9; color:#475569; }
    .st-badge.active, .st-badge.approved, .st-badge.completed { background:#dcfce7; color:#15803d; }
    .st-badge.pending { background:#fef3c7; color:#b45309; }
    .st-badge.rejected { background:#fee2e2; color:#b91c1c; }
    .pl-pos { color:#16a34a; font-weight:600; }
    .pl-neg { color:#dc2626; font-weight:600; }

    /* empty */
    .inv-empty { text-align:center; padding:55px 20px; }
    .inv-empty .icon { width:70px; height:70px; border-radius:50%; background:#e8f5ef; color:var(--primary); display:flex; justify-content:center; align-items:center; font-size:28px; margin:0 auto 17px; }
    .inv-empty h4 { font-size:17px; font-weight:700; color:var(--text); margin-bottom:8px; }
    .inv-empty p { color:var(--muted); font-size:12px; line-height:1.7; max-width:420px; margin:0 auto 18px; }

    /* notice */
    .inv-notice { margin-top:20px; padding:17px 20px; border:1px solid #dcecff; background:#f0f7ff; border-radius:10px; display:flex; gap:12px; align-items:flex-start; }
    .inv-notice i { color:#2563eb; font-size:18px; margin-top:2px; }
    .inv-notice h5 { color:#1e40af; font-size:13px; font-weight:700; margin:0 0 5px; }
    .inv-notice p { color:#475569; font-size:12px; line-height:1.7; margin:0; }

    .inv-pagination { padding:18px 20px; border-top:1px solid var(--border); }

    /* form */
    .inv-field { margin-bottom:16px; }
    .inv-field label { display:block; font-size:12px; font-weight:600; color:var(--text); margin-bottom:7px; }
    .inv-field input, .inv-field select { width:100%; padding:11px 12px; border:1px solid var(--border); border-radius:8px; font-size:13px; outline:none; background:#fff; box-sizing:border-box; }
    .inv-field input:focus, .inv-field select:focus { border-color:var(--primary); }
    .inv-help { display:block; color:var(--muted); font-size:11px; margin-top:5px; }

    /* modal */
    .inv-modal { display:none; position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,.6); padding:20px; align-items:center; justify-content:center; }
    .inv-modal.active { display:flex; }
    .inv-modal-box { width:100%; max-width:460px; background:#fff; border-radius:12px; overflow:hidden; max-height:92vh; overflow-y:auto; }
    .inv-modal-head { display:flex; justify-content:space-between; align-items:center; padding:18px 20px; border-bottom:1px solid var(--border); }
    .inv-modal-head h3 { margin:0; font-size:16px; color:var(--text); }
    .inv-modal-close { border:none; background:transparent; font-size:26px; cursor:pointer; color:var(--muted); line-height:1; }
    .inv-modal-body { padding:20px; }
    .inv-modal-foot { display:flex; justify-content:flex-end; gap:10px; padding:14px 20px; border-top:1px solid var(--border); }

    @media(max-width:1000px) { .inv-summary { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:576px) {
        .inv-summary { gap:10px; }
        .inv-stat { padding:15px; }
        .inv-stat h3 { font-size:19px; }
        .inv-filter select, .inv-filter .inv-btn { width:100%; }
    }
</style>