<style>
.mp-page{display:grid;gap:22px}
.mp-hero{background:linear-gradient(135deg,#102b26,#087f5b);color:#fff;padding:28px;border-radius:15px;position:relative;overflow:hidden}
.mp-hero h2{font-size:24px;font-weight:700;margin:0 0 9px}
.mp-hero p{color:#d5e9e1;font-size:14px;margin:0}
.mp-hero>i{position:absolute;right:35px;top:20px;font-size:75px;opacity:.15}
.mp-flash{padding:13px 16px;border-radius:9px;font-size:13px}
.mp-flash.ok{background:#eaf8f0;color:#137547;border:1px solid #c8ecd7}
.mp-flash.err{background:#fff0ef;color:#b42318;border:1px solid #ffd5d1}
.mp-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px}
.mp-card,.mp-section{background:#fff;border:1px solid #e8edf0;border-radius:13px;padding:22px}
.mp-card-icon{width:43px;height:43px;border-radius:10px;background:#e8f5ef;color:#087f5b;display:flex;align-items:center;justify-content:center;margin-bottom:14px;font-size:18px}
.mp-card h3{margin:0 0 5px;font-size:22px;color:#1e293b;overflow-wrap:anywhere}
.mp-card p{margin:0;font-size:13px;color:#84919f}
.mp-card small{display:block;margin-top:6px;font-size:11px;color:#84919f}
.mp-section h3{font-size:18px;color:#1e293b;margin:0 0 6px;font-weight:700}
.mp-section>.sub{margin:0 0 18px;color:#84919f;font-size:13px}
.mp-details{display:grid;grid-template-columns:repeat(2,1fr);gap:15px}
.mp-detail{background:#f8faf9;border:1px solid #e8edf0;padding:18px;border-radius:10px}
.mp-detail span.l{display:block;color:#84919f;font-size:13px;margin-bottom:8px}
.mp-detail strong{color:#1e293b;font-size:16px}
.mp-badge{display:inline-block;padding:6px 13px;border-radius:30px;font-size:12px;font-weight:700;color:#fff}
.mp-status{display:inline-block;background:#fff4db;color:#946200;padding:6px 12px;border-radius:30px;font-size:12px;font-weight:700}
.mp-status.on{background:#e7f6ef;color:#087f5b}
.mp-req{margin-bottom:16px}
.mp-req-top{display:flex;justify-content:space-between;font-size:12px;color:#475569;margin-bottom:6px;gap:10px;flex-wrap:wrap}
.mp-bar{height:9px;background:#e8edf0;border-radius:20px;overflow:hidden}
.mp-bar i{display:block;height:100%;background:#087f5b;border-radius:20px}
.mp-ladder{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:15px}
.mp-rank{border:2px solid #e8edf0;border-radius:12px;padding:18px;background:#fff}
.mp-rank.cur{border-color:#087f5b;background:#effaf5}
.mp-rank h4{margin:10px 0 4px;font-size:16px;color:#1e293b}
.mp-rank ul{list-style:none;margin:12px 0 0;padding:0;font-size:12px;color:#475569;display:grid;gap:6px}
.mp-rank li b{color:#1e293b}
.mp-table-wrap{overflow-x:auto}
.mp-table{width:100%;border-collapse:collapse;min-width:640px}
.mp-table th{background:#f8faf9;text-align:left;padding:13px;font-size:12px;color:#64748b}
.mp-table td{padding:13px;border-top:1px solid #edf0f2;font-size:13px;color:#1e293b}
.mp-table small{display:block;color:#84919f;font-size:11px;margin-top:3px}
.mp-pos{color:#16814f;font-weight:700}.mp-neg{color:#c0392b;font-weight:700}
.mp-chip{display:inline-block;padding:4px 10px;border-radius:15px;font-size:11px;font-weight:700}
.mp-chip.locked,.mp-chip.promoted,.mp-chip.paid{background:#e7f6ef;color:#087f5b}
.mp-chip.released,.mp-chip.demoted{background:#feecec;color:#dc4545}
.mp-form{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
.mp-form input{width:90px;padding:8px 10px;border:1px solid #dce2ea;border-radius:7px;font-size:12px}
.mp-btn{border:0;padding:8px 14px;border-radius:7px;font-size:12px;font-weight:700;cursor:pointer;background:#087f5b;color:#fff}
.mp-btn.red{background:#fff0ef;color:#d34c43}
.mp-empty{text-align:center;padding:40px 15px;background:#fafbfc;border:1px dashed #dce4e8;border-radius:10px}
.mp-empty i{font-size:35px;color:#b8c3cc;margin-bottom:13px}
.mp-empty h4{margin:0 0 7px;color:#334155}
.mp-empty p{margin:0;color:#84919f;font-size:13px}
.mp-notice{background:#eff6ff;color:#1e40af;border:1px solid #dbeafe;padding:16px;border-radius:10px;font-size:13px;line-height:1.7}
.mp-warn{background:#fffbeb;color:#92400e;border:1px solid #fde68a;padding:14px;border-radius:10px;font-size:12px;line-height:1.7}
.mp-pg{margin-top:16px}
@media(max-width:768px){.mp-details{grid-template-columns:1fr}}
@media(max-width:480px){.mp-hero{padding:22px}.mp-hero h2{font-size:21px}.mp-card,.mp-section{padding:16px}}
</style>
@if(session('success'))<div class="mp-flash ok">✓ {{ session('success') }}</div>@endif
@if(session('error'))<div class="mp-flash err">{{ session('error') }}</div>@endif
@if($errors->any())<div class="mp-flash err">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif