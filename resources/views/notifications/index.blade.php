<!DOCTYPE html>
<html lang="id" class="{{ (Auth::check() && Auth::user()->setting && Auth::user()->setting->theme === 'dark') ? 'dark-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Twitaw</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f7f9fa; --card-bg: #ffffff; --text: #0f1419; --text-muted: #536471; --border: #eff3f4; --accent: #1d9bf0; }
        html.dark-mode { --bg: #15202b; --card-bg: #1e2732; --text: #f7f9fa; --text-muted: #8899a6; --border: #2f3b47; --accent: #1d9bf0; }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg); color: var(--text); min-height: 100vh;
            transition: background 0.3s, color 0.3s;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 30px 20px 80px; text-align: center; color: white; position: relative;
        }
        .back-link {
            position: absolute; top: 20px; left: 20px;
            color: rgba(255,255,255,0.8); text-decoration: none; font-weight: 700; transition: 0.2s;
        }
        .back-link:hover { color: #fff; }
        .page-title-hero { font-size: 2.2em; font-weight: 900; letter-spacing: -1px; margin-bottom: 8px; }
        .notif-count-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);
            color: #fff; padding: 6px 18px; border-radius: 30px;
            font-size: 0.9em; font-weight: 700;
        }

        .container { max-width: 640px; margin: -30px auto 0; padding: 0 16px 80px; }

        .alert {
            padding: 14px 18px; border-radius: 14px; font-weight: 700;
            margin-bottom: 16px; font-size: 0.95em;
        }
        .alert-success { background: #eafff0; color: #166534; }

        /* SECTION */
        .section-card {
            background: var(--card-bg); border-radius: 20px; border: 1px solid var(--border);
            margin-bottom: 20px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .section-header {
            padding: 16px 22px; border-bottom: 1px solid var(--border);
            font-size: 0.78em; font-weight: 800; text-transform: uppercase;
            letter-spacing: 1px; color: var(--text-muted);
        }

        /* FOLLOW REQUEST CARD */
        .request-row {
            display: flex; align-items: center; gap: 14px;
            padding: 16px 22px; border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }
        .request-row:last-child { border-bottom: none; }
        .request-row:hover { background: rgba(29,155,240,0.04); }
        .req-avatar {
            width: 48px; height: 48px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 900; font-size: 17px; text-transform: uppercase;
        }
        .req-info { flex: 1; min-width: 0; }
        .req-name { font-weight: 800; font-size: 1em; color: var(--text); }
        .req-username { font-size: 0.85em; color: var(--text-muted); }
        .req-actions { display: flex; gap: 8px; }
        .btn-accept {
            background: var(--accent); color: white; border: none;
            padding: 8px 18px; border-radius: 20px; font-weight: 700;
            font-size: 0.85em; cursor: pointer; transition: 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-accept:hover { opacity: 0.85; }
        .btn-reject {
            background: transparent; border: 1.5px solid #ef4444; color: #ef4444;
            padding: 7px 16px; border-radius: 20px; font-weight: 700;
            font-size: 0.85em; cursor: pointer; transition: 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-reject:hover { background: #ef4444; color: white; }

        /* NOTIFICATION ROW */
        .notif-row {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 16px 22px; border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }
        .notif-row:last-child { border-bottom: none; }
        .notif-row:hover { background: rgba(29,155,240,0.04); }
        .notif-row.unread { background: rgba(29,155,240,0.06); }
        .notif-icon {
            width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3em;
        }
        .notif-icon.follow_request { background: rgba(245,158,11,0.15); }
        .notif-icon.follow_accepted { background: rgba(29,155,240,0.15); }
        .notif-icon.like { background: rgba(249,24,128,0.15); }
        .notif-icon.comment { background: rgba(0,186,124,0.15); }
        .notif-icon.mention { background: rgba(29,155,240,0.15); }
        .notif-icon.general { background: rgba(139,92,246,0.15); }

        .notif-content { flex: 1; }
        .notif-text { font-size: 0.95em; line-height: 1.5; color: var(--text); }
        .notif-text strong { font-weight: 800; }
        .notif-time { font-size: 0.8em; color: var(--text-muted); margin-top: 4px; }

        .notif-delete {
            background: none; border: none; color: var(--text-muted);
            cursor: pointer; font-size: 1.1em; padding: 4px; transition: 0.2s;
        }
        .notif-delete:hover { color: #ef4444; }

        .empty-state {
            text-align: center; padding: 60px 20px; color: var(--text-muted);
        }
        .empty-state .emoji { font-size: 3.5em; margin-bottom: 16px; }
        .empty-state h3 { font-size: 1.2em; font-weight: 800; color: var(--text); margin-bottom: 8px; }

        .mark-all-btn {
            display: block; width: 100%; text-align: center;
            padding: 14px; background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 14px; color: var(--accent); font-weight: 700;
            font-size: 0.95em; cursor: pointer; transition: 0.2s;
            font-family: 'Inter', sans-serif; margin-bottom: 20px;
        }
        .mark-all-btn:hover { background: rgba(29,155,240,0.08); }
    </style>
</head>
<body>

@php
function notifAvatarGradient($id) {
    $g = ['linear-gradient(135deg,#a18cd1,#fbc2eb)','linear-gradient(135deg,#84fab0,#8fd3f4)',
          'linear-gradient(135deg,#fccb90,#d57eeb)','linear-gradient(135deg,#e0c3fc,#8ec5fc)',
          'linear-gradient(135deg,#f093fb,#f5576c)','linear-gradient(135deg,#4facfe,#00f2fe)'];
    return $g[$id % count($g)];
}
function notifIcon($type) {
    return match($type) {
        'follow_request' => '🔔',
        'follow_accepted' => '✅',
        'like' => '❤️',
        'comment' => '💬',
        'mention' => '🏷️',
        default => '🔔',
    };
}
@endphp

<div class="page-header">
    <a href="/" class="back-link">← Kembali ke Home</a>
    <div class="page-title-hero">🔔 Notifikasi</div>
    <div class="notif-count-badge">{{ $notifications->count() }} notifikasi</div>
</div>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif

    {{-- FOLLOW REQUESTS SECTION --}}
    @if($followRequests->count() > 0)
    <div class="section-card">
        <div class="section-header">📩 Permintaan Follow ({{ $followRequests->count() }})</div>
        @foreach($followRequests as $req)
            <div class="request-row">
                <div class="req-avatar" style="background: {{ notifAvatarGradient($req->follower->id) }};">
                    {{ strtoupper(substr($req->follower->name, 0, 1)) }}
                </div>
                <div class="req-info">
                    <div class="req-name">{{ $req->follower->name }}</div>
                    <div class="req-username">@{{ $req->follower->username }}</div>
                </div>
                <div class="req-actions">
                    <form action="{{ route('follows.accept', $req->follower->id) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-accept">✓ Terima</button>
                    </form>
                    <form action="{{ route('follows.reject', $req->follower->id) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-reject">✕ Tolak</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    {{-- MARK ALL READ --}}
    @if($notifications->where('is_read', false)->count() > 0)
        <form action="{{ route('notifications.markAllRead') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="mark-all-btn">✓ Tandai Semua Sudah Dibaca</button>
        </form>
    @endif

    {{-- ALL NOTIFICATIONS --}}
    <div class="section-card">
        <div class="section-header">📋 Semua Notifikasi</div>
        @forelse($notifications as $notif)
            <div class="notif-row {{ !$notif->is_read ? 'unread' : '' }}">
                <div class="notif-icon {{ $notif->type }}">
                    {{ notifIcon($notif->type) }}
                </div>
                <div class="notif-content">
                    <div class="notif-text">
                        @if($notif->sender)
                            <strong>{{ $notif->sender->name }}</strong>
                        @endif
                        {{ $notif->message }}
                    </div>
                    <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                    @if(in_array($notif->type, ['mention', 'comment', 'like']) && $notif->reference_id)
                        <a href="/posts/{{ $notif->reference_id }}" style="color:var(--accent);font-weight:700;text-decoration:none;font-size:0.85em;margin-top:6px;display:inline-block;">Open related post</a>
                    @endif
                </div>
                <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="notif-delete" title="Hapus notifikasi">🗑️</button>
                </form>
            </div>
        @empty
            <div class="empty-state">
                <div class="emoji">🔔</div>
                <h3>Belum ada notifikasi</h3>
                <p>Notifikasi akan muncul saat ada yang mem-follow, menyukai post, atau mengomentari postinganmu.</p>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>