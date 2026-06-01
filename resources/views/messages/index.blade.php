<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Langsung — Twitter Clone</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #000;
            color: #e7e9ea;
            min-height: 100vh;
        }

        .layout {
            display: flex;
            max-width: 700px;
            margin: 0 auto;
            border-left: 1px solid #2f3336;
            border-right: 1px solid #2f3336;
            min-height: 100vh;
        }

        .main {
            flex: 1;
        }

        /* ── Header ── */
        .header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #2f3336;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .header .back-btn {
            color: #e7e9ea;
            text-decoration: none;
            font-size: 20px;
            display: flex;
            align-items: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            justify-content: center;
            transition: background 0.2s;
        }

        .header .back-btn:hover { background: #1a1a1a; }

        /* ── New DM button ── */
        .new-dm-bar {
            padding: 14px 20px;
            border-bottom: 1px solid #2f3336;
        }

        .new-dm-bar form {
            display: flex;
            gap: 10px;
        }

        .new-dm-bar input {
            flex: 1;
            background: #202327;
            border: 1px solid #2f3336;
            border-radius: 9999px;
            padding: 10px 16px;
            color: #e7e9ea;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s;
        }

        .new-dm-bar input:focus { border-color: #1d9bf0; }
        .new-dm-bar input::placeholder { color: #71767b; }

        .btn-primary {
            background: #1d9bf0;
            color: #fff;
            border: none;
            border-radius: 9999px;
            padding: 10px 20px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
            white-space: nowrap;
        }
        .btn-primary:hover { background: #1a8cd8; }

        /* ── Conversation List ── */
        .convo-list { }

        .convo-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border-bottom: 1px solid #2f3336;
            text-decoration: none;
            color: inherit;
            transition: background 0.15s;
        }

        .convo-item:hover { background: #080808; }

        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1d9bf0, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            text-transform: uppercase;
        }

        .convo-info { flex: 1; min-width: 0; }

        .convo-top {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 3px;
        }

        .convo-name {
            font-weight: 700;
            font-size: 15px;
            color: #e7e9ea;
        }

        .convo-username {
            font-size: 14px;
            color: #71767b;
        }

        .convo-time {
            font-size: 13px;
            color: #71767b;
            flex-shrink: 0;
        }

        .convo-preview {
            font-size: 14px;
            color: #71767b;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .convo-preview.unread {
            color: #e7e9ea;
            font-weight: 500;
        }

        .badge {
            background: #1d9bf0;
            color: #fff;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            padding: 2px 7px;
            min-width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #71767b;
        }

        .empty-state .icon { font-size: 48px; margin-bottom: 12px; }
        .empty-state h3 { font-size: 24px; font-weight: 800; color: #e7e9ea; margin-bottom: 8px; }
        .empty-state p { font-size: 15px; line-height: 1.5; }

        /* ── Alert ── */
        .alert-success {
            background: #1a2a1a;
            border: 1px solid #2d6a2d;
            color: #4ade80;
            padding: 12px 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="layout">
    <div class="main">

        {{-- HEADER --}}
        <div class="header">
            <a href="/posts" class="back-btn">←</a>
            <h1>Pesan</h1>
        </div>

        {{-- SUCCESS ALERT --}}
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        {{-- KIRIM DM BARU --}}
        <div class="new-dm-bar">
            <form action="{{ route('messages.store', '__placeholder__') }}" method="POST" id="new-dm-form">
                @csrf
                <input
                    type="text"
                    id="new-dm-username"
                    placeholder="Ketik username untuk mulai percakapan baru..."
                    autocomplete="off"
                >
                <button type="button" class="btn-primary" onclick="startConvo()">Buka Chat</button>
            </form>
        </div>

        {{-- DAFTAR PERCAKAPAN --}}
        @if($conversations->isEmpty())
            <div class="empty-state">
                <div class="icon">✉️</div>
                <h3>Belum ada pesan</h3>
                <p>Mulai percakapan baru dengan mengetik username di atas.</p>
            </div>
        @else
            <div class="convo-list">
                @foreach($conversations as $convo)
                    <a href="{{ route('messages.show', $convo['account']->username) }}" class="convo-item">

                        {{-- AVATAR --}}
                        <div class="avatar">{{ substr($convo['account']->name, 0, 1) }}</div>

                        {{-- INFO --}}
                        <div class="convo-info">
                            <div class="convo-top">
                                <div>
                                    <span class="convo-name">{{ $convo['account']->name }}</span>
                                    <span class="convo-username"> {{ '@' . $convo['account']->username }}</span>
                                </div>
                                <span class="convo-time">{{ $convo['last_message']->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="convo-preview {{ $convo['unread_count'] > 0 ? 'unread' : '' }}">
                                @if($convo['last_message']->sender_id === Auth::id())
                                    Kamu: 
                                @endif
                                {{ Str::limit($convo['last_message']->body, 60) }}
                            </div>
                        </div>

                        {{-- UNREAD BADGE --}}
                        @if($convo['unread_count'] > 0)
                            <span class="badge">{{ $convo['unread_count'] }}</span>
                        @endif

                    </a>
                @endforeach
            </div>
        @endif

    </div>
</div>

<script>
function startConvo() {
    const username = document.getElementById('new-dm-username').value.trim().replace(/^@/, '');
    if (!username) {
        alert('Masukkan username terlebih dahulu.');
        return;
    }
    window.location.href = '/messages/' + encodeURIComponent(username);
}

// Enter juga bisa trigger
document.getElementById('new-dm-username').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') startConvo();
});
</script>
</body>
</html>
