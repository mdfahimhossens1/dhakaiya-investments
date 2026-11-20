@if($bellRoutes)
<div class="nb-wrap" id="nbWrap">

    <button type="button" class="nb-btn" id="nbToggle" aria-label="Notifications">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        @if($bellUnread > 0)
            <span class="nb-count">{{ $bellUnread > 99 ? '99+' : $bellUnread }}</span>
        @endif
    </button>

    <div class="nb-dropdown" id="nbDropdown">

        <div class="nb-head">
            <strong>Notifications</strong>
            @if($bellUnread > 0)
                <form action="{{ route($bellRoutes['read_all']) }}" method="POST">
                    @csrf
                    <button type="submit" class="nb-link">Mark all read</button>
                </form>
            @endif
        </div>

        <div class="nb-list">
            @forelse($bellItems as $n)
                @php
                    $d = $n->data;
                    $t = in_array(($d['type'] ?? 'info'), ['success','warning','danger','info']) ? $d['type'] : 'info';
                    $target = !empty($d['url']) ? route($bellRoutes['open'], $n->id) : route($bellRoutes['index']);
                @endphp
                <a href="{{ $target }}" class="nb-item {{ $n->read_at ? '' : 'unread' }}">
                    <span class="nb-dot {{ $t }}"></span>
                    <span class="nb-body">
                        <b>{{ \Illuminate\Support\Str::limit($d['title'] ?? 'Notification', 50) }}</b>
                        <small>{{ \Illuminate\Support\Str::limit($d['message'] ?? '', 70) }}</small>
                        <em>{{ $n->created_at->diffForHumans() }}</em>
                    </span>
                </a>
            @empty
                <div class="nb-empty">No notifications yet</div>
            @endforelse
        </div>

        <a href="{{ route($bellRoutes['index']) }}" class="nb-footer">View all notifications</a>
    </div>
</div>

<style>
    .nb-wrap{position:relative;display:inline-block}
    .nb-btn{position:relative;width:40px;height:40px;border-radius:50%;border:1px solid #e2e8f0;background:#fff;color:#475569;display:grid;place-items:center;cursor:pointer}
    .nb-btn:hover{background:#f1f5f9}
    .nb-count{position:absolute;top:-4px;right:-4px;min-width:18px;height:18px;padding:0 5px;border-radius:9px;background:#e11d48;color:#fff;font-size:10px;font-weight:700;display:grid;place-items:center}
    .nb-dropdown{display:none;position:absolute;right:0;top:48px;width:340px;max-width:92vw;background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 12px 32px rgba(15,23,42,.14);z-index:1000;overflow:hidden}
    .nb-dropdown.open{display:block}
    .nb-head{display:flex;justify-content:space-between;align-items:center;padding:13px 16px;border-bottom:1px solid #f1f5f9;font-size:13px;color:#1e293b}
    .nb-link{border:0;background:none;color:var(--primary,#16a34a);font-size:11px;font-weight:650;cursor:pointer}
    .nb-list{max-height:340px;overflow-y:auto}
    .nb-item{display:flex;gap:11px;padding:12px 16px;text-decoration:none;border-bottom:1px solid #f8fafc}
    .nb-item:hover{background:#f8fafc}
    .nb-item.unread{background:#f8fffb}
    .nb-dot{width:9px;height:9px;border-radius:50%;margin-top:5px;flex-shrink:0;background:#2563eb}
    .nb-dot.success{background:#16a34a}.nb-dot.warning{background:#ea580c}.nb-dot.danger{background:#dc2626}
    .nb-body{display:flex;flex-direction:column;gap:2px;min-width:0}
    .nb-body b{font-size:12px;color:#334155}
    .nb-body small{font-size:11px;color:#64748b;line-height:1.5}
    .nb-body em{font-size:10px;color:#94a3b8;font-style:normal}
    .nb-empty{padding:28px 16px;text-align:center;font-size:12px;color:#94a3b8}
    .nb-footer{display:block;text-align:center;padding:12px;font-size:12px;font-weight:650;color:var(--primary,#16a34a);text-decoration:none;border-top:1px solid #f1f5f9}
    .nb-footer:hover{background:#f8fafc}
</style>

<script>
    (function () {
        const toggle = document.getElementById('nbToggle');
        const box = document.getElementById('nbDropdown');
        if (!toggle || !box) return;
        toggle.addEventListener('click', function (e) { e.stopPropagation(); box.classList.toggle('open'); });
        document.addEventListener('click', function (e) { if (!box.contains(e.target)) box.classList.remove('open'); });
    })();
</script>
@endif