<style>
    /* Shared Super Admin UI (same look as FAQ page) */
    .sa-page { width:100%; color:#263238; }
    .sa-page * { box-sizing:border-box; }
    .sa-header { display:flex; justify-content:space-between; align-items:center; gap:20px; margin-bottom:25px; }
    .sa-breadcrumb { display:flex; align-items:center; gap:9px; color:#89949b; font-size:12px; margin-bottom:10px; }
    .sa-breadcrumb i { font-size:9px; }
    .sa-breadcrumb strong { color:#087f5b; font-weight:600; }
    .sa-header h1 { font-size:25px; font-weight:700; color:#172b26; margin:0 0 6px; }
    .sa-header p { color:#87939a; font-size:13px; margin:0; }

    .sa-btn { border:none; border-radius:8px; padding:11px 17px; font-size:13px; font-weight:600; display:inline-flex; align-items:center; justify-content:center; gap:8px; cursor:pointer; text-decoration:none; transition:.2s ease; font-family:inherit; }
    .sa-btn-primary { background:#087f5b; color:#fff; box-shadow:0 4px 10px rgba(8,127,91,.12); }
    .sa-btn-primary:hover { background:#066b4c; color:#fff; transform:translateY(-1px); }
    .sa-btn-cancel { background:#edf1f2; color:#59666d; border:1px solid #e1e7e9; }
    .sa-btn-cancel:hover { background:#e1e7e9; color:#263238; }
    .sa-btn-danger { background:#dc3545; color:#fff; }
    .sa-btn-danger:hover { background:#c82333; color:#fff; }

    .sa-alert { padding:13px 16px; border-radius:9px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px; font-size:13px; }
    .sa-alert ul { margin:6px 0 0; padding-left:18px; }
    .sa-alert-success { background:#e9f8f0; color:#16834e; border:1px solid #c9edda; align-items:center; }
    .sa-alert-error { background:#fff0f0; color:#b02a37; border:1px solid #f5c6cb; }
    .sa-alert button { margin-left:auto; border:none; background:transparent; color:inherit; cursor:pointer; font-size:15px; }

    .sa-card { background:#fff; border:1px solid #edf0f1; border-radius:12px; box-shadow:0 3px 12px rgba(20,40,35,.025); overflow:hidden; margin-bottom:20px; }
    .sa-card-header { padding:21px 23px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #edf0f1; gap:12px; }
    .sa-card-header h3 { font-size:16px; color:#172b26; font-weight:700; margin:0 0 5px; }
    .sa-card-header p { font-size:12px; color:#929da3; margin:0; }
    .sa-card-body { padding:22px 23px; }
    .sa-badge { background:#eaf5f1; color:#087f5b; padding:7px 12px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; }

    /* Table */
    .sa-table-wrapper { width:100%; overflow-x:auto; }
    .sa-table { width:100%; border-collapse:collapse; min-width:850px; }
    .sa-table thead { background:#f8faf9; }
    .sa-table th { padding:14px 17px; color:#84918c; font-size:10px; font-weight:700; letter-spacing:.5px; text-align:left; white-space:nowrap; }
    .sa-table td { padding:15px 17px; border-top:1px solid #f0f2f2; vertical-align:middle; font-size:12px; color:#34463e; }
    .sa-table tbody tr:hover { background:#fbfdfc; }
    .sa-table .text-right { text-align:right !important; }
    .sa-name-cell { display:flex; align-items:center; gap:12px; min-width:230px; }
    .sa-name-icon { width:36px; height:36px; border-radius:9px; background:#eaf5f1; color:#087f5b; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .sa-name-cell strong { display:block; color:#263b34; font-size:12px; font-weight:600; margin-bottom:4px; }
    .sa-name-cell small { display:block; color:#9aa4a8; font-size:11px; }
    .sa-tag { display:inline-block; background:#f0f4f3; color:#52665e; padding:6px 10px; border-radius:6px; font-size:11px; font-weight:600; }
    .sa-order { display:inline-flex; align-items:center; justify-content:center; min-width:30px; height:27px; padding:0 7px; background:#f2f5f4; border-radius:6px; color:#52645d; font-size:11px; font-weight:600; }
    .sa-status { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:20px; font-size:10px; font-weight:700; }
    .sa-status span { width:6px; height:6px; border-radius:50%; }
    .sa-status-on { background:#e9f8f0; color:#16834e; } .sa-status-on span { background:#16834e; }
    .sa-status-off { background:#fff5e7; color:#c28121; } .sa-status-off span { background:#df912a; }
    .sa-chip { width:14px; height:14px; border-radius:50%; display:inline-block; vertical-align:middle; margin-right:6px; border:1px solid rgba(0,0,0,.08); }

    .sa-actions { display:flex; align-items:center; justify-content:flex-end; gap:6px; }
    .sa-action-btn { width:31px; height:31px; border:none; border-radius:7px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:.2s; font-size:11px; }
    .sa-action-edit { background:#fff5e7; color:#d58a24; }
    .sa-action-delete { background:#fff0f0; color:#dc3545; }
    .sa-action-btn:hover { transform:translateY(-2px); filter:brightness(.96); }

    .sa-empty { padding:55px 20px; text-align:center; }
    .sa-empty > div { width:65px; height:65px; border-radius:50%; background:#edf5f2; color:#087f5b; display:flex; align-items:center; justify-content:center; margin:0 auto 15px; font-size:25px; }
    .sa-empty h4 { color:#34443d; font-size:16px; margin:0 0 7px; }
    .sa-empty p { color:#98a3a2; font-size:12px; margin-bottom:18px; }
    .sa-pagination { padding:17px 22px; border-top:1px solid #edf0f1; }
    .sa-pagination nav { display:flex; justify-content:flex-end; }

    /* Forms */
    .sa-form-group { margin-bottom:17px; }
    .sa-form-group > label { display:block; font-size:12px; font-weight:600; color:#34463e; margin-bottom:8px; }
    .sa-form-group > label span { color:#dc3545; }
    .sa-form-group input[type=text], .sa-form-group input[type=number], .sa-form-group textarea, .sa-form-group select {
        width:100%; border:1px solid #e1e7e4; border-radius:7px; padding:11px 12px; outline:none; color:#34443d; font-family:inherit; font-size:12px; background:#fff; transition:.2s; }
    .sa-form-group input[type=text], .sa-form-group input[type=number], .sa-form-group select { height:42px; }
    .sa-form-group textarea { resize:vertical; min-height:90px; line-height:1.6; }
    .sa-form-group input:focus, .sa-form-group textarea:focus, .sa-form-group select:focus { border-color:#087f5b; box-shadow:0 0 0 3px rgba(8,127,91,.07); }
    .sa-form-group small { display:block; color:#98a39f; font-size:11px; margin-top:6px; }
    .sa-form-group input[type=color] { width:100%; height:42px; border:1px solid #e1e7e4; border-radius:7px; padding:4px; background:#fff; cursor:pointer; }
    .sa-form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .sa-form-row-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }

    /* Toggle switch */
    .sa-switch-row { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:14px 16px; background:#f8faf9; border:1px solid #edf1ef; border-radius:9px; margin-bottom:12px; }
    .sa-switch-row strong { display:block; font-size:13px; color:#263b34; margin-bottom:3px; }
    .sa-switch-row small { color:#8a9690; font-size:11px; }
    .sa-switch { position:relative; width:44px; height:24px; flex-shrink:0; }
    .sa-switch input { opacity:0; width:0; height:0; position:absolute; }
    .sa-switch i { position:absolute; inset:0; background:#cfd8d4; border-radius:24px; cursor:pointer; transition:.2s; }
    .sa-switch i::before { content:""; position:absolute; width:18px; height:18px; left:3px; top:3px; background:#fff; border-radius:50%; transition:.2s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
    .sa-switch input:checked + i { background:#087f5b; }
    .sa-switch input:checked + i::before { transform:translateX(20px); }
    .sa-switch input:focus-visible + i { box-shadow:0 0 0 3px rgba(8,127,91,.25); }

    .sa-save-bar { display:flex; justify-content:flex-end; gap:10px; padding:16px 23px; border-top:1px solid #edf0f1; background:#fcfdfd; }
    .sa-total { display:inline-block; padding:8px 12px; background:#eaf5f1; color:#087f5b; border-radius:8px; font-size:12px; font-weight:700; }

    /* Modals */
    .sa-modal { position:fixed; inset:0; background:rgba(12,30,25,.55); z-index:9999; display:none; align-items:center; justify-content:center; padding:20px; overflow-y:auto; backdrop-filter:blur(3px); }
    .sa-modal.active { display:flex; }
    .sa-modal-box { width:100%; max-width:600px; background:#fff; border-radius:13px; box-shadow:0 15px 60px rgba(0,0,0,.16); overflow:hidden; animation:saModalShow .2s ease; margin:auto; }
    @keyframes saModalShow { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @media (prefers-reduced-motion: reduce) { .sa-modal-box { animation:none; } }
    .sa-modal-header { padding:21px 23px; border-bottom:1px solid #edf0f1; display:flex; align-items:center; justify-content:space-between; }
    .sa-modal-header h3 { color:#19352b; font-size:16px; font-weight:700; margin:0 0 5px; }
    .sa-modal-header h3 i { color:#087f5b; margin-right:6px; }
    .sa-modal-header p { color:#929da3; font-size:11px; margin:0; }
    .sa-modal-close { width:32px; height:32px; border:none; border-radius:7px; background:#f1f4f3; color:#66736e; cursor:pointer; font-size:13px; }
    .sa-modal-close:hover { background:#e7edeb; color:#263b34; }
    .sa-modal-body { padding:22px 23px; max-height:65vh; overflow-y:auto; }
    .sa-modal-footer { padding:16px 23px; border-top:1px solid #edf0f1; display:flex; align-items:center; justify-content:flex-end; gap:9px; background:#fcfdfd; }
    .sa-delete-box { max-width:410px; text-align:center; padding-top:30px; }
    .sa-delete-icon { width:60px; height:60px; margin:0 auto 15px; border-radius:50%; background:#fff0f0; color:#dc3545; display:flex; align-items:center; justify-content:center; font-size:22px; }
    .sa-delete-box h3 { color:#24372f; font-size:18px; margin:0 0 10px; }
    .sa-delete-box > p { color:#87938e; font-size:12px; line-height:1.7; padding:0 25px; margin-bottom:23px; }
    .sa-delete-box > p strong { color:#dc3545; }
    .sa-delete-box .sa-modal-footer { justify-content:center; }

    @media (max-width:700px) {
        .sa-header { align-items:flex-start; flex-direction:column; }
        .sa-header h1 { font-size:21px; }
        .sa-form-row, .sa-form-row-3 { grid-template-columns:1fr; gap:0; }
        .sa-modal { padding:10px; }
        .sa-modal-header, .sa-modal-body, .sa-modal-footer, .sa-card-body, .sa-save-bar { padding-left:16px; padding-right:16px; }
    }
</style>