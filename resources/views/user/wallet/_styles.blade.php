<style>
.wl-page{width:100%}
.wl-head{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;margin-bottom:24px}
.wl-head h2{font-size:23px;font-weight:700;color:var(--text);margin:0 0 6px}
.wl-head p{color:var(--muted);font-size:13px;margin:0}
.wl-crumb{display:flex;align-items:center;flex-wrap:wrap;gap:9px;font-size:12px;color:var(--muted)}
.wl-crumb a{color:var(--primary);text-decoration:none}
.wl-flash{padding:13px 16px;border-radius:9px;margin-bottom:16px;font-size:13px}
.wl-flash.ok{background:#eaf8f0;color:#137547;border:1px solid #c8ecd7}
.wl-flash.err{background:#fff0ef;color:#b42318;border:1px solid #ffd5d1}
.wl-hero{background:linear-gradient(120deg,#087f5b,#065f46);color:#fff;border-radius:14px;padding:26px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;gap:18px;flex-wrap:wrap}
.wl-hero h2{color:#fff;font-size:22px;margin:0 0 6px}
.wl-hero p{margin:0;font-size:12px;color:rgba(255,255,255,.85)}
.wl-hero-actions{display:flex;gap:10px;flex-wrap:wrap}
.wl-hero-actions a{padding:10px 17px;border-radius:9px;font-size:12px;font-weight:700;text-decoration:none;background:#fff;color:#087f5b}
.wl-hero-actions a.alt{background:rgba(255,255,255,.15);color:#fff}
.wl-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:16px;margin-bottom:22px}
.wl-stat{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:18px;display:flex;align-items:center;gap:13px}
.wl-stat .ic{min-width:43px;width:43px;height:43px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px;background:#e7f6ef;color:#087f5b}
.wl-stat .ic.blue{background:#eaf2ff;color:#3478db}.wl-stat .ic.orange{background:#fff2e6;color:#e58b32}.wl-stat .ic.red{background:#feecec;color:#dc4545}.wl-stat .ic.purple{background:#f2ebff;color:#8255c7}
.wl-stat p{color:var(--muted);font-size:11px;margin:0 0 5px}
.wl-stat h3{font-size:17px;margin:0 0 3px;color:var(--text);overflow-wrap:anywhere}
.wl-stat small{color:var(--muted);font-size:10px;display:block}
.wl-card{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:22px;margin-bottom:20px}
.wl-card-h{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:20px;flex-wrap:wrap}
.wl-card-h h3{color:var(--text);font-size:16px;margin:0 0 5px}
.wl-card-h p{color:var(--muted);font-size:12px;margin:0}
.wl-pill{background:#e7f6ef;color:#087f5b;padding:7px 12px;border-radius:20px;font-size:11px;font-weight:700;text-decoration:none}
.wl-filter{display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;background:#f8fafc;padding:16px;border-radius:10px;margin-bottom:20px}
.wl-field{display:flex;flex-direction:column;gap:7px;min-width:170px}
.wl-field label{font-size:11px;font-weight:600;color:var(--text)}
.wl-field select,.wl-field input{height:40px;padding:0 12px;border:1px solid var(--border);border-radius:7px;background:#fff;color:var(--text);font-size:12px;outline:none}
.wl-btn{height:40px;padding:0 16px;border:none;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;gap:7px;font-size:12px;font-weight:600;text-decoration:none;cursor:pointer}
.wl-btn.primary{background:var(--primary);color:#fff}.wl-btn.ghost{background:#e9eef2;color:#475569}
.wl-table-wrap{width:100%;overflow-x:auto}
.wl-table{width:100%;min-width:760px;border-collapse:collapse}
.wl-table th,.wl-table td{padding:14px;text-align:left;border-bottom:1px solid var(--border);font-size:12px;color:var(--text)}
.wl-table th{background:#f8fafc;color:#64748b;font-weight:600;white-space:nowrap}
.wl-table small{display:block;color:var(--muted);font-size:10px;margin-top:3px}
.wl-pos{color:#16814f;font-weight:700}.wl-neg{color:#c0392b;font-weight:700}
.wl-badge{display:inline-block;padding:5px 10px;border-radius:15px;font-size:11px;font-weight:700}
.wl-badge.pending{background:#fff4db;color:#946200}
.wl-badge.approved,.wl-badge.completed{background:#e7f6ef;color:#087f5b}
.wl-badge.rejected{background:#feecec;color:#dc4545}
.wl-view{border:0;background:#e7f6ef;color:#087f5b;padding:5px 11px;border-radius:15px;font-size:11px;font-weight:700;cursor:pointer}
.wl-empty{text-align:center;padding:40px 15px}
.wl-empty .ic{width:55px;height:55px;border-radius:50%;background:#f1f5f9;color:#94a3b8;display:flex;align-items:center;justify-content:center;font-size:21px;margin:0 auto 13px}
.wl-empty h4{font-size:14px;color:var(--text);margin:0 0 7px}
.wl-empty p{color:var(--muted);font-size:12px;margin:0}
.wl-empty a{color:var(--primary);font-weight:700}
.wl-notice{display:flex;gap:14px;padding:18px;background:#eff6ff;border:1px solid #dbeafe;border-radius:11px;margin-bottom:20px}
.wl-notice i{color:#2563eb;font-size:20px;margin-top:2px}
.wl-notice h4{font-size:13px;color:#1e40af;margin:0 0 6px}
.wl-notice p{font-size:11px;line-height:1.7;color:#475569;margin:0}
.wl-chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px}
.wl-chip{background:#e8f5ef;color:var(--primary);padding:5px 10px;border-radius:20px;font-size:11px;font-weight:600}
.wl-tabs{display:flex;gap:8px;margin-bottom:18px;flex-wrap:wrap}
.wl-tabs a{padding:9px 16px;border-radius:20px;background:#f1f5f9;color:#475569;text-decoration:none;font-size:12px;font-weight:700}
.wl-tabs a.on{background:var(--primary);color:#fff}
.wl-pg{padding-top:16px}
/* wallet cards */
.wl-wallets{display:grid;grid-template-columns:repeat(auto-fit,minmax(310px,1fr));gap:18px;margin-bottom:25px}
.wl-wcard{background:var(--white);border:1px solid var(--border);border-radius:13px;padding:22px}
.wl-wcard-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
.wl-wicon{width:48px;height:48px;background:#effaf5;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:25px}
.wl-wcard h3{color:var(--text);font-size:16px;margin:0 0 4px}
.wl-wcard>p{color:var(--muted);font-size:11px;margin:0 0 18px}
.wl-bal-l{color:var(--muted);font-size:10px;letter-spacing:.7px;font-weight:700;margin-bottom:7px}
.wl-bal{color:var(--text);font-size:26px;font-weight:700;margin-bottom:18px}
.wl-bal span{font-size:19px;font-weight:600;margin-right:5px}
.wl-mini{border-top:1px solid var(--border);padding-top:12px;display:grid;gap:7px}
.wl-mini div{display:flex;justify-content:space-between;font-size:11px;color:var(--muted)}
.wl-mini strong{color:var(--text)}
.wl-wact{display:flex;gap:8px;margin-top:16px}
.wl-wact a{flex:1;text-align:center;padding:9px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;background:var(--primary);color:#fff}
.wl-wact a.alt{background:#e9eef2;color:#475569}
/* methods */
.wl-mgrid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px}
.wl-mcard{background:var(--white);border:1px solid var(--border);border-radius:13px;padding:22px}
.wl-mtop{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:16px}
.wl-mic{width:50px;height:50px;border-radius:12px;background:#e8f5ef;color:var(--primary);display:flex;justify-content:center;align-items:center;font-size:21px}
.wl-mcard h4{font-size:16px;color:var(--text);margin:0 0 7px}
.wl-mcard>p{font-size:13px;color:var(--muted);margin:0 0 12px;line-height:1.6}
.wl-acc{display:flex;justify-content:space-between;gap:8px;padding:9px 0;border-top:1px solid var(--border);font-size:12px;color:var(--text)}
/* forms */
.wl-form label{display:block;margin:14px 0 6px;font-size:12px;font-weight:700;color:var(--text)}
.wl-form select,.wl-form input{width:100%;padding:11px 12px;border:1px solid var(--border);border-radius:8px;font-size:13px;box-sizing:border-box;background:#fff}
.wl-form .wl-btn{width:100%;margin-top:20px;height:44px;font-size:14px}
.wl-preview{margin-top:12px;padding:12px 14px;background:#effaf5;border:1px solid #b7dfcf;border-radius:9px;font-size:12px;color:#065f46;display:none}
/* modal */
.wl-ov{position:fixed;inset:0;background:rgba(15,23,42,.6);z-index:99999;display:none;align-items:center;justify-content:center;padding:16px}
.wl-ov.show{display:flex}
.wl-modal{width:100%;max-width:480px;background:#fff;border-radius:14px;overflow:hidden}
.wl-mh{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--border)}
.wl-mh h3{margin:0;font-size:16px;color:var(--text)}
.wl-mh button{border:0;background:#f1f5f9;width:32px;height:32px;border-radius:8px;font-size:20px;cursor:pointer}
.wl-mb{padding:6px 20px 18px;max-height:65vh;overflow-y:auto}
.wl-dl{display:flex;justify-content:space-between;gap:14px;padding:11px 0;border-bottom:1px solid var(--border);font-size:12px}
.wl-dl span{color:var(--muted)}.wl-dl strong{color:var(--text);text-align:right;overflow-wrap:anywhere}
@media(max-width:600px){.wl-card{padding:16px}.wl-field{min-width:100%}.wl-btn{flex:1}}
</style>