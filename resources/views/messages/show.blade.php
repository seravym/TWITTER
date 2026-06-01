<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat dengan {{ $other->name }} — Twitter Clone</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #000;
            color: #e7e9ea;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .layout {
            display: flex;
            flex-direction: column;
            max-width: 700px;
            margin: 0 auto;
            border-left: 1px solid #2f3336;
            border-right: 1px solid #2f3336;
            width: 100%;
            height: 100vh;
        }

        /* ── Header ── */
        .header {
            flex-shrink: 0;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #2f3336;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .back-btn {
            color: #e7e9ea;
            text-decoration: none;
            font-size: 20px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            flex-shrink: 0;
        }
        .back-btn:hover { background: #1a1a1a; }

        .header-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1d9bf0, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .header-info { }
        .header-name { font-size: 16px; font-weight: 700; line-height: 1.2; }
        .header-username { font-size: 13px; color: #71767b; }

        /* ── Messages Area ── */
        .messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            scroll-behavior: smooth;
        }

        /* Scrollbar styling */
        .messages-area::-webkit-scrollbar { width: 6px; }
        .messages-area::-webkit-scrollbar-track { background: transparent; }
        .messages-area::-webkit-scrollbar-thumb { background: #2f3336; border-radius: 3px; }

        /* Date separator */
        .date-separator {
            text-align: center;
            margin: 16px 0 8px;
            font-size: 12px;
            color: #71767b;
        }

        /* ── Bubble ── */
        .bubble-row {
            display: flex;
            margin-bottom: 2px;
        }

        .bubble-row.mine {
            justify-content: flex-end;
        }

        .bubble-row.theirs {
            justify-content: flex-start;
        }

        .bubble {
            padding: 10px 16px;
            border-radius: 20px;
            font-size: 15px;
            line-height: 1.4;
            word-break: break-word;
            position: relative;
        }

        /* Pesan saya — biru di kanan */
        .bubble-row.mine .bubble {
            background: #1d9bf0;
            color: #fff;
            border-bottom-right-radius: 6px;
        }

        /* Pesan lawan — abu di kiri */
        .bubble-row.theirs .bubble {
            background: #202327;
            color: #e7e9ea;
            border-bottom-left-radius: 6px;
        }

        /* Gelembung pertama dalam kelompok */
        .bubble-row.mine.first .bubble { border-top-right-radius: 20px; }
        .bubble-row.theirs.first .bubble { border-top-left-radius: 20px; }

        .bubble-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
        }

        .bubble-time {
            font-size: 11px;
            color: #71767b;
        }

        .bubble-row.mine .bubble-meta { justify-content: flex-end; }
        .bubble-row.theirs .bubble-meta { justify-content: flex-start; }

        /* Delete button — muncul saat hover */
        .bubble-wrapper {
            display: flex;
            flex-direction: column;
            max-width: 70%;
        }

        .bubble-row.mine .bubble-wrapper {
            align-items: flex-end;
        }

        .bubble-row.theirs .bubble-wrapper {
            align-items: flex-start;
        }

        .delete-btn {
            opacity: 0;
            transition: opacity 0.15s;
            background: none;
            border: none;
            color: #71767b;
            cursor: pointer;
            font-size: 13px;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .delete-btn:hover { color: #f4212e; background: #1a0a0a; }

        .bubble-row:hover .delete-btn { opacity: 1; }

        /* Read receipt */
        .read-receipt {
            font-size: 11px;
            color: #71767b;
        }

        /* ── Input Area ── */
        .input-area {
            flex-shrink: 0;
            border-top: 1px solid #2f3336;
            padding: 12px 16px;
            background: #000;
        }

        .input-area form {
            display: flex;
            align-items: flex-end;
            gap: 10px;
        }

        .input-wrap {
            flex: 1;
            background: #202327;
            border: 1px solid #2f3336;
            border-radius: 20px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            transition: border-color 0.2s;
        }

        .input-wrap:focus-within { border-color: #1d9bf0; }

        .input-wrap textarea {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: #e7e9ea;
            font-size: 15px;
            resize: none;
            max-height: 120px;
            line-height: 1.4;
            font-family: inherit;
        }

        .input-wrap textarea::placeholder { color: #71767b; }

        .send-btn {
            background: #1d9bf0;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.2s, transform 0.1s;
            flex-shrink: 0;
        }

        .send-btn:hover { background: #1a8cd8; transform: scale(1.05); }
        .send-btn:active { transform: scale(0.95); }
        .send-btn:disabled { background: #333; cursor: not-allowed; transform: none; }

        /* ── Error / Success ── */
        .alert-error {
            background: #1a0a0a;
            border: 1px solid #6a1a1a;
            color: #f4212e;
            padding: 10px 16px;
            font-size: 14px;
            flex-shrink: 0;
        }

        .alert-success {
            background: #1a2a1a;
            border: 1px solid #2d6a2d;
            color: #4ade80;
            padding: 10px 16px;
            font-size: 14px;
            flex-shrink: 0;
        }

        /* ── Empty state ── */
        .empty-chat {
            text-align: center;
            padding: 40px 20px;
            color: #71767b;
        }

        .empty-chat .icon { font-size: 40px; margin-bottom: 10px; }
        .empty-chat p { font-size: 14px; }
    </style>
</head>
<body>
<div class="layout">

    {{-- HEADER --}}
    <div class="header">
        <a href="{{ route('messages.index') }}" class="back-btn">←</a>

        <div class="header-avatar">{{ substr($other->name, 0, 1) }}</div>

        <div class="header-info">
            <div class="header-name">{{ $other->name }}</div>
            <div class="header-username">{{ '@' . $other->username }}</div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $err){{ $err }}@endforeach
        </div>
    @endif

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- MESSAGES --}}
    <div class="messages-area" id="messages-area">

        @if($messages->isEmpty())
            <div class="empty-chat">
                <div class="icon">💬</div>
                <p>Belum ada pesan. Mulai percakapan sekarang!</p>
            </div>
        @else

            @php
                $prevDate   = null;
                $prevSender = null;
            @endphp

            @foreach($messages as $msg)

                @php
                    $isMe        = $msg->sender_id === Auth::id();
                    $msgDate     = $msg->created_at->format('Y-m-d');
                    $isFirst     = ($prevSender !== $msg->sender_id);
                    $prevSender  = $msg->sender_id;
                @endphp

                {{-- DATE SEPARATOR --}}
                @if($msgDate !== $prevDate)
                    <div class="date-separator">
                        {{ $msg->created_at->isToday() ? 'Hari ini' : ($msg->created_at->isYesterday() ? 'Kemarin' : $msg->created_at->translatedFormat('d M Y')) }}
                    </div>
                    @php $prevDate = $msgDate; $prevSender = null; @endphp
                @endif

                <div class="bubble-row {{ $isMe ? 'mine' : 'theirs' }} {{ $isFirst ? 'first' : '' }}">
                    <div class="bubble-wrapper">

                        {{-- BUBBLE --}}
                        <div class="bubble">{{ $msg->body }}</div>

                        {{-- META --}}
                        <div class="bubble-meta">

                            {{-- WAKTU --}}
                            <span class="bubble-time">{{ $msg->created_at->format('H:i') }}</span>

                            {{-- READ RECEIPT (hanya untuk pesan saya) --}}
                            @if($isMe)
                                <span class="read-receipt">
                                    {{ $msg->read_at ? '✓✓' : '✓' }}
                                </span>
                            @endif

                            {{-- TOMBOL HAPUS (hanya untuk pesan saya) --}}
                            @if($isMe)
                                <form action="{{ route('messages.destroy', $msg->id) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Hapus pesan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" title="Hapus pesan">🗑</button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>

            @endforeach
        @endif
    </div>

    {{-- INPUT AREA --}}
    <div class="input-area">
        <form action="{{ route('messages.store', $other->username) }}" method="POST" id="chat-form">
            @csrf
            <div class="input-wrap">
                <textarea
                    id="msg-input"
                    name="body"
                    placeholder="Tulis pesan..."
                    rows="1"
                    maxlength="1000"
                ></textarea>
            </div>
            <button type="submit" class="send-btn" id="send-btn" disabled title="Kirim">
                ➤
            </button>
        </form>
    </div>

</div>

<script>
    // Auto scroll ke bawah saat halaman load
    const area = document.getElementById('messages-area');
    area.scrollTop = area.scrollHeight;

    // Auto resize textarea & toggle send button
    const textarea = document.getElementById('msg-input');
    const sendBtn  = document.getElementById('send-btn');

    textarea.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        sendBtn.disabled = this.value.trim() === '';
    });

    // Kirim dengan Enter (Shift+Enter = newline)
    textarea.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (this.value.trim()) {
                document.getElementById('chat-form').submit();
            }
        }
    });
</script>
</body>
</html>
