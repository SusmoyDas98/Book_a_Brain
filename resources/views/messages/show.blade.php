@extends('layouts.app')

@section('content')

<div style="min-height:100vh;padding:0;margin-top:60px;display:flex;flex-direction:column;">

    {{-- CHAT HEADER --}}
    <div style="background:white;border-bottom:2px solid #e2e8f0;padding:0.85rem 1.5rem;position:sticky;top:60px;z-index:10;">
        <div class="container" style="max-width:800px;">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('messages.index') }}" style="color:#64748b;text-decoration:none;font-size:1.2rem;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:1rem;flex-shrink:0;">
                    {{ strtoupper(substr($otherUser->name ?? '?', 0, 1)) }}
                </div>
                <div>
                    <p style="font-weight:700;color:#0f172a;margin:0;font-size:0.92rem;">{{ $otherUser->name }}</p>
                    <p style="color:#64748b;font-size:0.75rem;margin:0;">
                        <i class="bi bi-book" style="font-size:0.65rem;"></i>
                        {{ $contract->subject }} · ৳{{ number_format($contract->salary) }}/mo
                    </p>
                </div>
                <a href="{{ route('contracts.show', $contract) }}" class="ms-auto"
                   style="background:rgba(99,102,241,0.08);color:#6366f1;border-radius:10px;padding:5px 12px;font-size:0.75rem;font-weight:700;text-decoration:none;">
                    View Contract →
                </a>
            </div>
        </div>
    </div>

    {{-- MESSAGES AREA --}}
    <div class="container" style="max-width:800px;flex:1;overflow-y:auto;padding:1.5rem 1rem 1rem;" id="chat-messages">

        @forelse($messages as $msg)
            <div class="d-flex mb-3 {{ $msg->sender_id === Auth::id() ? 'justify-content-end' : 'justify-content-start' }}" data-msg-id="{{ $msg->id }}">
                @if($msg->sender_id !== Auth::id())
                    <div style="width:32px;height:32px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.7rem;color:#64748b;flex-shrink:0;margin-right:8px;margin-top:4px;">
                        {{ strtoupper(substr($msg->sender->name ?? '?', 0, 1)) }}
                    </div>
                @endif

                <div style="max-width:70%;{{ $msg->sender_id === Auth::id()
                    ? 'background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border-radius:20px 20px 4px 20px;'
                    : 'background:white;color:#0f172a;border:1px solid #e2e8f0;border-radius:20px 20px 20px 4px;' }}padding:0.75rem 1rem;box-shadow:0 2px 8px rgba(0,0,0,0.06);">

                    {{-- Attachment --}}
                    @if($msg->hasAttachment())
                        @if($msg->isImageAttachment())
                            <img src="{{ asset('storage/' . $msg->attachment_path) }}"
                                 alt="Shared image"
                                 style="max-width:100%;border-radius:12px;margin-bottom:{{ $msg->body ? '0.5rem' : '0' }};cursor:pointer;"
                                 onclick="window.open(this.src, '_blank')">
                        @else
                            <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank"
                               style="display:flex;align-items:center;gap:8px;{{ $msg->sender_id === Auth::id() ? 'color:rgba(255,255,255,0.9);' : 'color:#6366f1;' }}text-decoration:none;padding:0.4rem;border-radius:10px;{{ $msg->sender_id === Auth::id() ? 'background:rgba(255,255,255,0.15);' : 'background:rgba(99,102,241,0.06);' }}margin-bottom:{{ $msg->body ? '0.5rem' : '0' }};">
                                <i class="bi bi-file-earmark-pdf" style="font-size:1.2rem;"></i>
                                <span style="font-size:0.8rem;font-weight:600;">PDF Document</span>
                                <i class="bi bi-download" style="font-size:0.75rem;"></i>
                            </a>
                        @endif
                    @endif

                    {{-- Body --}}
                    @if($msg->body)
                        <p style="margin:0;font-size:0.88rem;line-height:1.5;word-wrap:break-word;">{{ $msg->body }}</p>
                    @endif

                    {{-- Time + Read status --}}
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:4px;margin-top:0.3rem;">
                        <span style="font-size:0.65rem;{{ $msg->sender_id === Auth::id() ? 'color:rgba(255,255,255,0.6);' : 'color:#94a3b8;' }}">
                            {{ $msg->created_at->format('g:i A') }}
                        </span>
                        @if($msg->sender_id === Auth::id())
                            <i class="bi {{ $msg->read_at ? 'bi-check2-all' : 'bi-check2' }}"
                               style="font-size:0.75rem;color:{{ $msg->read_at ? '#86efac' : 'rgba(255,255,255,0.5)' }};"></i>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:3rem 0;" id="empty-state">
                <i class="bi bi-chat-heart" style="font-size:3rem;color:#cbd5e1;"></i>
                <p style="color:#94a3b8;margin-top:1rem;font-size:0.9rem;">Start the conversation! 👋</p>
            </div>
        @endforelse
    </div>

    {{-- MESSAGE INPUT --}}
    <div style="background:white;border-top:2px solid #e2e8f0;padding:0.75rem 1rem;position:sticky;bottom:0;">
        <div class="container" style="max-width:800px;">
            <form action="{{ route('messages.store', $conversation) }}" method="POST" enctype="multipart/form-data"
                  class="d-flex align-items-end gap-2">
                @csrf

                {{-- Attachment --}}
                <label style="cursor:pointer;flex-shrink:0;width:40px;height:40px;background:rgba(99,102,241,0.08);border-radius:12px;display:flex;align-items:center;justify-content:center;transition:0.2s;"
                       onmouseover="this.style.background='rgba(99,102,241,0.15)'"
                       onmouseout="this.style.background='rgba(99,102,241,0.08)'"
                       title="Attach image or PDF">
                    <i class="bi bi-paperclip" style="color:#6366f1;font-size:1.1rem;"></i>
                    <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf" style="display:none;"
                           onchange="document.getElementById('file-name').textContent = this.files[0]?.name || ''">
                </label>

                {{-- Text input --}}
                <div style="flex:1;position:relative;">
                    <span id="file-name" style="font-size:0.7rem;color:#6366f1;font-weight:600;position:absolute;top:-16px;left:4px;"></span>
                    <input type="text" name="body" placeholder="Type a message..."
                           autocomplete="off"
                           style="width:100%;background:#f8fafc;border:2px solid #e2e8f0;border-radius:14px;padding:0.65rem 1rem;color:#0f172a;outline:none;font-family:'Plus Jakarta Sans',sans-serif;font-size:0.88rem;transition:border-color 0.2s;"
                           onfocus="this.style.borderColor='#6366f1'"
                           onblur="this.style.borderColor='#e2e8f0'">
                </div>

                {{-- Send --}}
                <button type="submit"
                        style="flex-shrink:0;width:40px;height:40px;background:linear-gradient(135deg,#6366f1,#4f46e5);border:none;border-radius:12px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 4px 12px rgba(99,102,241,0.3);transition:0.2s;"
                        onmouseover="this.style.transform='scale(1.05)'"
                        onmouseout="this.style.transform='scale(1)'">
                    <i class="bi bi-send-fill" style="color:white;font-size:0.95rem;"></i>
                </button>
            </form>

            @if($errors->any())
                <p style="color:#ef4444;font-size:0.78rem;margin:0.5rem 0 0;">{{ $errors->first() }}</p>
            @endif
            @if(session('error'))
                <p style="color:#ef4444;font-size:0.78rem;margin:0.5rem 0 0;">{{ session('error') }}</p>
            @endif
        </div>
    </div>
</div>

{{-- POLLING SCRIPT --}}
<script>
(function() {
    const conversationId = {{ $conversation->id }};
    const pollUrl = "{{ route('messages.poll', $conversation) }}";
    const chatContainer = document.getElementById('chat-messages');
    const currentUserId = {{ Auth::id() }};
    let lastMessageId = {{ $messages->last()?->id ?? 0 }};

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
    scrollToBottom();

    function createMessageHtml(msg) {
        const avatarInitial = (msg.sender_name || '?')[0].toUpperCase();
        const isMine = msg.is_mine;

        let attachmentHtml = '';
        if (msg.attachment_path) {
            if (msg.attachment_type === 'image') {
                attachmentHtml = `<img src="${msg.attachment_path}" alt="Shared image" style="max-width:100%;border-radius:12px;margin-bottom:${msg.body ? '0.5rem' : '0'};cursor:pointer;" onclick="window.open(this.src,'_blank')">`;
            } else {
                const linkColor = isMine ? 'color:rgba(255,255,255,0.9);' : 'color:#6366f1;';
                const linkBg = isMine ? 'background:rgba(255,255,255,0.15);' : 'background:rgba(99,102,241,0.06);';
                attachmentHtml = `<a href="${msg.attachment_path}" target="_blank" style="display:flex;align-items:center;gap:8px;${linkColor}text-decoration:none;padding:0.4rem;border-radius:10px;${linkBg}margin-bottom:${msg.body ? '0.5rem' : '0'};"><i class="bi bi-file-earmark-pdf" style="font-size:1.2rem;"></i><span style="font-size:0.8rem;font-weight:600;">PDF Document</span><i class="bi bi-download" style="font-size:0.75rem;"></i></a>`;
            }
        }

        const bodyHtml = msg.body ? `<p style="margin:0;font-size:0.88rem;line-height:1.5;word-wrap:break-word;">${msg.body.replace(/</g,'&lt;').replace(/>/g,'&gt;')}</p>` : '';
        const timeColor = isMine ? 'color:rgba(255,255,255,0.6);' : 'color:#94a3b8;';
        const checkIcon = isMine ? `<i class="bi ${msg.read ? 'bi-check2-all' : 'bi-check2'}" style="font-size:0.75rem;color:${msg.read ? '#86efac' : 'rgba(255,255,255,0.5)'};"></i>` : '';

        const bubbleStyle = isMine
            ? 'background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border-radius:20px 20px 4px 20px;'
            : 'background:white;color:#0f172a;border:1px solid #e2e8f0;border-radius:20px 20px 20px 4px;';

        const avatarHtml = !isMine ? `<div style="width:32px;height:32px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.7rem;color:#64748b;flex-shrink:0;margin-right:8px;margin-top:4px;">${avatarInitial}</div>` : '';

        return `<div class="d-flex mb-3 ${isMine ? 'justify-content-end' : 'justify-content-start'}" data-msg-id="${msg.id}">
            ${avatarHtml}
            <div style="max-width:70%;${bubbleStyle}padding:0.75rem 1rem;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                ${attachmentHtml}${bodyHtml}
                <div style="display:flex;align-items:center;justify-content:flex-end;gap:4px;margin-top:0.3rem;">
                    <span style="font-size:0.65rem;${timeColor}">${msg.time}</span>${checkIcon}
                </div>
            </div>
        </div>`;
    }

    function pollMessages() {
        fetch(`${pollUrl}?after_id=${lastMessageId}`)
            .then(r => r.json())
            .then(messages => {
                if (messages.length > 0) {
                    // Remove empty state if it exists
                    const emptyState = document.getElementById('empty-state');
                    if (emptyState) emptyState.remove();

                    messages.forEach(msg => {
                        chatContainer.insertAdjacentHTML('beforeend', createMessageHtml(msg));
                        lastMessageId = msg.id;
                    });
                    scrollToBottom();
                }
            })
            .catch(() => {}); // Silently handle network errors
    }

    setInterval(pollMessages, 5000);
})();
</script>

@endsection
