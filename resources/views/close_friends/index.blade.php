<!DOCTYPE html>
<html lang="id" class="{{ (Auth::check() && Auth::user()->setting && Auth::user()->setting->theme === 'dark') ? 'dark-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Close Friends - Twitaw</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f7f9fa; --card-bg: #ffffff; --text: #0f1419; --text-muted: #536471; --border: #eff3f4; }
        html.dark-mode { --bg: #15202b; --card-bg: #1e2732; --text: #f7f9fa; --text-muted: #8899a6; --border: #2f3b47; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg); color: var(--text); min-height: 100vh;
            transition: background 0.3s, color 0.3s;
        }

        /* ── HEADER ── */
        .page-header {
            background: linear-gradient(135deg, #134e5e, #71b280);
            padding: 30px 20px 80px; text-align: center; color: white; position: relative;
        }
        .back-link {
            position: absolute; top: 20px; left: 20px;
            color: rgba(255,255,255,0.8); text-decoration: none; font-weight: 700; transition: 0.2s;
        }
        .back-link:hover { color: #fff; }
        .page-title-hero { font-size: 2.4em; font-weight: 900; letter-spacing: -1px; margin-bottom: 10px; }
        .count-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);
            color: #fff; padding: 6px 20px; border-radius: 30px;
            font-size: 0.9em; font-weight: 700;
        }

        /* ── CONTAINER ── */
        .container { max-width: 640px; margin: -30px auto 0; padding: 0 16px 80px; }

        /* ── ALERT ── */
        .alert {
            padding: 14px 18px; border-radius: 14px; font-weight: 700;
            margin-bottom: 16px; font-size: 0.95em;
        }
        .alert-success { background: #eafff0; color: #166534; }
        .alert-error   { background: #fef2f2; color: #991b1b; }

        /* ── INFO CARD ── */
        .info-card {
            background: var(--card-bg); border-radius: 20px; border: 1px solid var(--border);
            padding: 18px 22px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex; gap: 14px; align-items: flex-start;
        }
        .info-icon { font-size: 1.8em; flex-shrink: 0; }
        .info-text strong { font-size: 1em; font-weight: 800; color: var(--text); display: block; margin-bottom: 4px; }
        .info-text p { font-size: 0.88em; color: var(--text-muted); line-height: 1.5; }

        /* ── SECTION LABEL ── */
        .section-label {
            font-size: 0.78em; font-weight: 800; text-transform: uppercase;
            letter-spacing: 1px; color: #536471; margin-bottom: 12px; padding-left: 4px;
        }

        /* ── FRIEND CARD ── */
        .friends-list { display: flex; flex-direction: column; gap: 10px; }
        .friend-card {
            background: var(--card-bg); border-radius: 18px; border: 1px solid var(--border);
            padding: 16px 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            display: flex; align-items: center; gap: 14px;
            transition: all 0.2s ease;
        }
        .friend-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.07); transform: translateY(-2px); }
        .friend-card.is-close-friend {
            border-color: rgba(19,78,94,0.3);
            background: linear-gradient(135deg, rgba(19,78,94,0.03), rgba(113,178,128,0.05));
        }

        /* Avatar */
        .friend-avatar {
            width: 50px; height: 50px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 900; font-size: 18px; text-transform: uppercase;
            text-decoration: none; position: relative;
        }
        .cf-ring {
            position: absolute; inset: -3px;
            background: linear-gradient(135deg, #134e5e, #71b280);
            border-radius: 50%; z-index: -1;
        }

        .friend-info { flex: 1; min-width: 0; }
        .friend-name { font-weight: 800; font-size: 1em; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .friend-username { font-size: 0.85em; color: var(--text-muted); margin-top: 2px; }
        .cf-badge {
            display: inline-flex; align-items: center; gap: 4px;
            background: rgba(19,78,94,0.1); color: #134e5e;
            font-size: 0.75em; font-weight: 700;
            padding: 3px 10px; border-radius: 20px; margin-top: 4px;
        }

        /* Buttons */
        .btn-add {
            background: linear-gradient(135deg, #134e5e, #71b280);
            color: white; border: none; padding: 9px 18px; border-radius: 20px;
            font-weight: 700; font-size: 0.88em; cursor: pointer; transition: 0.2s;
            font-family: 'Inter', sans-serif; white-space: nowrap;
        }
        .btn-add:hover { opacity: 0.85; transform: scale(1.03); }

        .btn-remove {
            background: white; border: 1.5px solid #ef4444; color: #ef4444;
            padding: 8px 16px; border-radius: 20px; font-weight: 700;
            font-size: 0.88em; cursor: pointer; transition: 0.2s;
            font-family: 'Inter', sans-serif; white-space: nowrap;
        }
        .btn-remove:hover { background: #ef4444; color: white; }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 60px 20px; color: #536471;
        }
        .empty-state .emoji { font-size: 3.5em; margin-bottom: 16px; }
        .empty-state h3 { font-size: 1.2em; font-weight: 800; color: #0f1419; margin-bottom: 8px; }
        .empty-state p { font-size: 0.9em; line-height: 1.6; }
        .empty-state a {
            display: inline-block; margin-top: 18px;
            background: linear-gradient(135deg, #134e5e, #71b280); color: white;
            padding: 12px 28px; border-radius: 30px;
            text-decoration: none; font-weight: 700; transition: 0.2s;
        }
        .empty-state a:hover { opacity: 0.85; }

        @media (max-width: 600px) {
            .page-title-hero { font-size: 1.8em; }
        }
    </style>
</head>
<body>

@php
function cfAvatarGradient($id) {
    $g = [
        'linear-gradient(135deg,#a18cd1,#fbc2eb)',
        'linear-gradient(135deg,#84fab0,#8fd3f4)',
        'linear-gradient(135deg,#fccb90,#d57eeb)',
        'linear-gradient(135deg,#e0c3fc,#8ec5fc)',
        'linear-gradient(135deg,#f093fb,#f5576c)',
        'linear-gradient(135deg,#4facfe,#00f2fe)',
        'linear-gradient(135deg,#ff9a9e,#fecfef)',
        'linear-gradient(135deg,#a8edea,#fed6e3)',
    ];
    return $g[$id % count($g)];
}
@endphp

<div class="page-header">
    <a href="/" class="back-link">← Kembali ke Home</a>
    <a href="{{ route('settings.show') }}" style="position:absolute;top:20px;right:20px;color:rgba(255,255,255,0.8);text-decoration:none;font-weight:700;">⚙️ Settings</a>
    <div class="page-title-hero">🌟 Close Friends</div>
    <div class="count-badge">{{ count($closeFriendIds) }} close friend aktif</div>
</div>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">✗ {{ $errors->first() }}</div>
    @endif

    {{-- Info card --}}
    <div class="info-card">
        <div class="info-icon">💡</div>
        <div class="info-text">
            <strong>Apa itu Close Friends?</strong>
            <p>Post dengan visibilitas <strong>Close Friend</strong> hanya bisa dilihat oleh orang-orang dalam daftar ini. Kamu bisa memilih dari akun yang sudah kamu follow.</p>
        </div>
    </div>

    @if($followingAccounts->isEmpty())
        <div class="empty-state">
            <div class="emoji">👥</div>
            <h3>Belum ada yang difollow</h3>
            <p>Kamu perlu mengikuti akun lain terlebih dahulu sebelum bisa menambahkan mereka ke Close Friends.</p>
            <a href="/accounts">Temukan Pengguna</a>
        </div>
    @else
        <div class="section-label">Pilih dari orang yang kamu follow</div>
        <div class="friends-list">
            @foreach($followingAccounts as $account)
                @php $isCF = in_array($account->id, $closeFriendIds); @endphp
                <div class="friend-card {{ $isCF ? 'is-close-friend' : '' }}">
                    <a href="/accounts/{{ $account->id }}" class="friend-avatar" style="background: {{ cfAvatarGradient($account->id) }};">
                        @if($isCF)<div class="cf-ring"></div>@endif
                        {{ strtoupper(substr($account->name, 0, 1)) }}
                    </a>
                    <div class="friend-info">
                        <div class="friend-name">{{ $account->name }}</div>
                        <div class="friend-username">@{{ $account->username }}</div>
                        @if($isCF)
                            <div class="cf-badge">🌟 Close Friend</div>
                        @endif
                    </div>

                    @if($isCF)
                        {{-- Tombol hapus dari close friend --}}
                        <form action="{{ route('close-friends.destroy', $account->id) }}" method="POST" style="margin:0;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-remove" onclick="return confirm('Hapus dari close friends?')">✕ Hapus</button>
                        </form>
                    @else
                        {{-- Tombol tambah ke close friend --}}
                        <form action="{{ route('close-friends.store', $account->id) }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn-add">🌟 Tambah</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

</body>
</html>
