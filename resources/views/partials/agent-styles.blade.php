<style>
    .ag-wrap { display: flex; flex-direction: column; gap: 20px; }
    .ag-grid { display: grid; gap: 16px; }
    .ag-g2 { grid-template-columns: repeat(2, 1fr); }
    .ag-g3 { grid-template-columns: repeat(3, 1fr); }
    .ag-g4 { grid-template-columns: repeat(4, 1fr); }

    .ag-card, .ag-panel { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 22px; box-shadow: 0 1px 2px rgba(15,23,42,.04); }
    .ag-stat { display: flex; align-items: center; gap: 14px; }
    .ag-stat h3 { font-size: 22px; margin: 0 0 2px; color: var(--text); line-height: 1.2; }
    .ag-stat p { margin: 0; font-size: 13px; color: var(--muted); }
    .ag-ico { width: 46px; height: 46px; flex-shrink: 0; border-radius: 13px; display: grid; place-items: center; font-size: 19px; background: #e9f7f1; color: var(--primary); }
    .ag-ico.amber { background: #fef3c7; color: #b45309; } .ag-ico.blue { background: #dbeafe; color: #1d4ed8; }
    .ag-ico.green { background: #dcfce7; color: #15803d; } .ag-ico.purple { background: #ede9fe; color: #6d28d9; }

    .ag-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
    .ag-head h3 { font-size: 17px; font-weight: 700; color: var(--text); margin: 0 0 4px; }
    .ag-head p { font-size: 13px; color: var(--muted); margin: 0; }

    .ag-badge { display: inline-block; padding: 4px 11px; border-radius: 999px; font-size: 11.5px; font-weight: 600; white-space: nowrap; }
    .ag-badge.amber { background: #fef3c7; color: #92400e; } .ag-badge.blue { background: #dbeafe; color: #1e40af; }
    .ag-badge.purple { background: #ede9fe; color: #5b21b6; } .ag-badge.teal { background: #ccfbf1; color: #115e59; }
    .ag-badge.green { background: #dcfce7; color: #166534; } .ag-badge.red { background: #fee2e2; color: #991b1b; }
    .ag-badge.gray { background: #f1f5f9; color: #475569; }

    .ag-btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; border: 1px solid var(--primary); background: var(--primary); color: #fff; padding: 10px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; transition: .15s; }
    .ag-btn:hover { background: var(--primary-dark); border-color: var(--primary-dark); color: #fff; }
    .ag-btn.sm { padding: 6px 12px; font-size: 12px; border-radius: 8px; }
    .ag-btn.ghost { background: #fff; color: var(--primary); } .ag-btn.ghost:hover { background: #f0fdf4; color: var(--primary); }
    .ag-btn.danger { background: #fff; color: #b91c1c; border-color: #fecaca; } .ag-btn.danger:hover { background: #fef2f2; color: #b91c1c; }
    .ag-btn.warn { background: #fff; color: #b45309; border-color: #fde68a; } .ag-btn.warn:hover { background: #fffbeb; color: #b45309; }
    .ag-actions { display: flex; flex-wrap: wrap; gap: 8px; }

    .ag-field { margin-bottom: 15px; }
    .ag-field label { display: block; font-size: 12.5px; font-weight: 600; color: var(--text); margin-bottom: 7px; }
    .ag-field input[type=text], .ag-field input[type=email], .ag-field input[type=password], .ag-field input[type=number],
    .ag-field input[type=file], .ag-field select, .ag-field textarea, .ag-input {
        width: 100%; padding: 11px 13px; border: 1px solid var(--border); border-radius: 10px; background: #f8fafc; color: var(--text); font-size: 13.5px; font-family: inherit;
    }
    .ag-field textarea { resize: vertical; min-height: 100px; }
    .ag-field input:focus, .ag-field select:focus, .ag-field textarea:focus { outline: none; border-color: var(--primary); background: #fff; }
    .ag-field small { display: block; margin-top: 5px; color: var(--muted); font-size: 11.5px; }
    .ag-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .ag-row3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .ag-filter { display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end; }
    .ag-filter .ag-field { margin: 0; flex: 1; min-width: 160px; }

    .ag-check { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text); cursor: pointer; }
    .ag-geo { border: 1px solid var(--border); border-radius: 12px; padding: 14px; margin-bottom: 12px; background: #fcfdfd; }
    .ag-geo h4 { margin: 0 0 10px; font-size: 13px; color: var(--text); }
    .ag-geo-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 8px; }

    .ag-tabs { display: flex; gap: 8px; flex-wrap: wrap; }
    .ag-tab { padding: 7px 15px; border-radius: 999px; font-size: 12.5px; font-weight: 600; background: #f1f5f9; color: var(--muted); text-decoration: none; }
    .ag-tab:hover { background: #e2e8f0; } .ag-tab.active { background: var(--primary); color: #fff; }

    .ag-table-wrap { overflow-x: auto; }
    .ag-table { width: 100%; border-collapse: collapse; min-width: 720px; }
    .ag-table th { text-align: left; font-size: 11.5px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); padding: 11px 14px; background: #f8fafc; border-bottom: 1px solid var(--border); }
    .ag-table td { padding: 13px 14px; font-size: 13px; color: var(--text); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .ag-table tr:last-child td { border-bottom: none; }
    .ag-table tbody tr:hover { background: #fafcfb; }
    .ag-table small { display: block; color: var(--muted); font-size: 11.5px; margin-top: 2px; }
    .ag-table .num { text-align: right; white-space: nowrap; }

    .ag-notice { padding: 14px 18px; border-radius: 12px; font-size: 13px; line-height: 1.7; background: #eff6ff; border: 1px solid #dbeafe; color: #1e40af; margin-bottom: 14px; }
    .ag-wrap .ag-notice { margin-bottom: 0; }
    .ag-notice.warn { background: #fffbeb; border-color: #fde68a; color: #92400e; }
    .ag-notice.ok { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
    .ag-notice.err { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
    .ag-notice ul { margin: 0; padding-left: 18px; }

    .ag-empty { text-align: center; padding: 40px 20px; color: var(--muted); font-size: 13px; }
    .ag-empty i { display: block; font-size: 34px; color: #cbd5e1; margin-bottom: 12px; }

    .ag-avatar { width: 46px; height: 46px; border-radius: 50%; flex-shrink: 0; background: #dcfce7; color: var(--primary); display: grid; place-items: center; font-weight: 800; font-size: 17px; }
    .ag-person { display: flex; align-items: center; gap: 12px; }
    .ag-person strong { display: block; font-size: 14px; color: var(--text); } .ag-person small { color: var(--muted); font-size: 12px; }
    .ag-agent-card { display: flex; flex-direction: column; gap: 12px; }
    .ag-agent-card p { font-size: 12.5px; color: var(--muted); margin: 0; line-height: 1.6; }

    .ag-kv { display: grid; grid-template-columns: 150px 1fr; gap: 10px 14px; font-size: 13px; }
    .ag-kv dt { color: var(--muted); } .ag-kv dd { margin: 0; color: var(--text); word-break: break-word; }

    .ag-steps { display: flex; gap: 6px; }
    .ag-step { flex: 1; text-align: center; font-size: 11.5px; color: var(--muted); }
    .ag-step i { display: block; height: 6px; border-radius: 6px; background: #e2e8f0; margin-bottom: 7px; }
    .ag-step.done { color: var(--primary); font-weight: 600; } .ag-step.done i { background: var(--primary); }

    .ag-text { white-space: pre-wrap; line-height: 1.75; font-size: 13.5px; color: var(--text); background: #f8fafc; border: 1px solid #eef2f6; border-radius: 12px; padding: 16px; }

    .ag-chat { height: 420px; overflow-y: auto; padding: 16px; background: #f8fafc; border: 1px solid var(--border); border-radius: 14px; display: flex; flex-direction: column; gap: 10px; }
    .ag-msg { display: flex; } .ag-msg.mine { justify-content: flex-end; }
    .ag-bubble { max-width: 76%; padding: 10px 14px; border-radius: 14px; font-size: 13.5px; line-height: 1.6; background: #fff; border: 1px solid var(--border); color: var(--text); word-break: break-word; }
    .ag-msg.mine .ag-bubble { background: var(--primary); border-color: var(--primary); color: #fff; }
    .ag-bubble small { display: block; margin-top: 4px; font-size: 10.5px; opacity: .7; }
    .ag-tag { display: inline-block; font-size: 10.5px; font-weight: 700; background: rgba(0,0,0,.08); padding: 1px 8px; border-radius: 999px; margin-bottom: 4px; }
    .ag-chat-form { display: flex; gap: 10px; margin-top: 12px; }
    .ag-chat-form textarea { flex: 1; padding: 11px 13px; border: 1px solid var(--border); border-radius: 10px; font-family: inherit; font-size: 13.5px; resize: none; }
    .ag-two { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }

    @media (max-width: 1000px) { .ag-g4 { grid-template-columns: repeat(2, 1fr); } .ag-two { grid-template-columns: 1fr; } }
    @media (max-width: 750px) { .ag-g3, .ag-g2, .ag-row, .ag-row3 { grid-template-columns: 1fr; } .ag-kv { grid-template-columns: 1fr; gap: 2px 0; } .ag-kv dd { margin-bottom: 8px; } }
    @media (max-width: 550px) { .ag-g4 { grid-template-columns: 1fr; } .ag-card, .ag-panel { padding: 17px; } .ag-bubble { max-width: 90%; } }
</style>