<style>
    .sa-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:23px; }
    .sa-stat-card { background:#fff; border:1px solid #edf0f1; border-radius:12px; padding:19px; display:flex; align-items:center; gap:15px; box-shadow:0 3px 12px rgba(20,40,35,.025); }
    .sa-stat-icon { width:47px; height:47px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:19px; flex-shrink:0; }
    .sa-ic-blue { color:#3478d4; background:#edf4ff; } .sa-ic-green { color:#15965b; background:#e9f8f0; }
    .sa-ic-orange { color:#df912a; background:#fff5e7; } .sa-ic-purple { color:#8258c9; background:#f3edff; }
    .sa-ic-red { color:#dc3545; background:#fff0f0; }
    .sa-stat-card span { display:block; color:#89949b; font-size:12px; margin-bottom:5px; }
    .sa-stat-card h3 { color:#172b26; font-size:22px; font-weight:700; margin:0; }
    .sa-stat-card small { display:block; color:#9aa5aa; font-size:11px; margin-top:4px; }
    .sa-stat-card.is-missing h3 { color:#a3abad; font-size:14px; font-weight:600; }

    .sa-filter-form { padding:17px 22px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #edf0f1; flex-wrap:wrap; }
    .sa-search-box { position:relative; flex:1; min-width:220px; }
    .sa-search-box i { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#9aa5aa; font-size:13px; }
    .sa-search-box input { width:100%; height:40px; border:1px solid #e5e9eb; border-radius:7px; padding:0 12px 0 36px; outline:none; font-size:12px; font-family:inherit; color:#34433e; }
    .sa-filter-select, .sa-filter-date { height:40px; min-width:130px; border:1px solid #e5e9eb; border-radius:7px; padding:0 12px; outline:none; font-size:12px; background:#fff; color:#53615e; font-family:inherit; }
    .sa-search-box input:focus, .sa-filter-select:focus, .sa-filter-date:focus { border-color:#087f5b; box-shadow:0 0 0 3px rgba(8,127,91,.07); }
    .sa-date-field { display:flex; align-items:center; gap:7px; font-size:12px; color:#7b8892; }
    .sa-btn-filter { background:#102b26; color:#fff; } .sa-btn-filter:hover { background:#1c4037; color:#fff; }
    .sa-btn-reset { background:#edf1f2; color:#58666d; } .sa-btn-reset:hover { background:#e1e7e9; color:#263238; }

    .sa-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .sa-grid-2 > .sa-card { margin-bottom:20px; }

    /* Horizontal bars */
    .sa-bar-row { display:grid; grid-template-columns:130px 1fr 54px; align-items:center; gap:12px; margin-bottom:13px; font-size:12px; color:#43544c; }
    .sa-bar-row:last-child { margin-bottom:0; }
    .sa-bar-label { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .sa-bar-track { height:9px; background:#f0f4f3; border-radius:6px; overflow:hidden; }
    .sa-bar-fill { height:100%; background:#087f5b; border-radius:6px; min-width:3px; }
    .sa-bar-val { text-align:right; font-weight:700; color:#263b34; }
    .sa-muted-note { color:#98a39f; font-size:12px; padding:10px 0; }

    /* Column chart */
    .sa-columns { display:flex; align-items:flex-end; gap:4px; height:150px; overflow-x:auto; padding-bottom:4px; }
    .sa-col { flex:1; min-width:14px; height:100%; display:flex; flex-direction:column; justify-content:flex-end; align-items:center; }
    .sa-col div { width:100%; background:#087f5b; border-radius:4px 4px 0 0; min-height:2px; opacity:.85; }
    .sa-col div:hover { opacity:1; }
    .sa-col-axis { display:flex; justify-content:space-between; font-size:10px; color:#9aa5aa; margin-top:8px; }

    /* Log specifics */
    .sa-method { display:inline-block; padding:5px 9px; border-radius:6px; font-size:10px; font-weight:700; }
    .sa-m-post { background:#e9f8f0; color:#16834e; } .sa-m-put { background:#fff5e7; color:#c28121; }
    .sa-m-delete { background:#fff0f0; color:#dc3545; } .sa-m-auth { background:#edf4ff; color:#3478d4; }
    .sa-user-line small { display:block; color:#9aa4a8; font-size:11px; margin-top:3px; }
    .sa-mono { font-family:ui-monospace, Menlo, Consolas, monospace; font-size:11px; }
    .sa-json { background:#f8faf9; border:1px solid #edf1ef; border-radius:8px; padding:12px; font-size:11px; line-height:1.7; white-space:pre-wrap; overflow-wrap:anywhere; margin:0; }
    .sa-detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px; }
    .sa-detail-grid div { background:#f8faf9; padding:11px 12px; border-radius:8px; font-size:12px; color:#34463e; font-weight:600; overflow-wrap:anywhere; }
    .sa-detail-grid label { display:block; font-size:10px; color:#89958f; text-transform:uppercase; letter-spacing:.4px; margin-bottom:5px; font-weight:600; }
    .sa-modal-body .sa-section-label { font-size:11px; color:#89958f; font-weight:600; margin:0 0 8px; }

    @media (max-width:1100px) { .sa-stats { grid-template-columns:repeat(2,1fr); } .sa-grid-2 { grid-template-columns:1fr; } }
    @media (max-width:700px) {
        .sa-stats { gap:10px; } .sa-stat-card { padding:13px; gap:10px; } .sa-stat-card h3 { font-size:18px; }
        .sa-filter-form { flex-direction:column; align-items:stretch; }
        .sa-filter-select, .sa-filter-date, .sa-filter-form .sa-btn { width:100%; }
        .sa-detail-grid { grid-template-columns:1fr; }
        .sa-bar-row { grid-template-columns:90px 1fr 44px; }
    }
</style>