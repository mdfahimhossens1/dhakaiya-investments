<div class="wl-ov" id="wlModal">
    <div class="wl-modal">
        <div class="wl-mh"><h3 id="wlTitle">Details</h3><button type="button" onclick="wlClose()">&times;</button></div>
        <div class="wl-mb" id="wlBody"></div>
    </div>
</div>
<script>
function wlOpen(btn) {
    var d = JSON.parse(btn.dataset.d);
    document.getElementById('wlTitle').textContent = d.title;
    var body = document.getElementById('wlBody');
    body.innerHTML = '';
    d.rows.forEach(function (r) {
        var row = document.createElement('div'); row.className = 'wl-dl';
        var a = document.createElement('span'); a.textContent = r[0];
        var b = document.createElement('strong'); b.textContent = r[1] || '—';
        row.appendChild(a); row.appendChild(b); body.appendChild(row);
    });
    document.getElementById('wlModal').classList.add('show');
}
function wlClose() { document.getElementById('wlModal').classList.remove('show'); }
document.getElementById('wlModal').addEventListener('click', function (e) { if (e.target === this) wlClose(); });
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') wlClose(); });
</script>