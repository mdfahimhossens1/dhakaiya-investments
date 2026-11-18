<div class="ag-chat" id="chatBox">
    @forelse($messages as $m)
        <div class="ag-msg {{ (int) $m->sender_id === (int) $meId ? 'mine' : '' }}">
            <div class="ag-bubble">
                @if($m->research_request_id)
                    <span class="ag-tag">Request #{{ $m->research_request_id }}</span><br>
                @endif
                {!! nl2br(e($m->body)) !!}
                <small>{{ $m->created_at->format('d M, h:i A') }}</small>
            </div>
        </div>
    @empty
        <div class="ag-empty"><i class="fa-regular fa-comments"></i>No messages yet. Say hello!</div>
    @endforelse
</div>

@if($canSend ?? true)
    <form method="POST" action="{{ $sendRoute }}" class="ag-chat-form">
        @csrf
        <textarea name="body" rows="2" maxlength="2000" required placeholder="Write a message..."></textarea>
        <button type="submit" class="ag-btn"><i class="fa-solid fa-paper-plane"></i> Send</button>
    </form>
@endif

<p style="font-size:11.5px;color:var(--muted);margin:10px 0 0;">
    <i class="fa-solid fa-shield-halved"></i>
    Never share passwords or OTP codes. Deposits to your investment wallet are made only from the platform's Deposit page.
</p>

<script>
    (function () { const b = document.getElementById('chatBox'); if (b) b.scrollTop = b.scrollHeight; })();
</script>