<!DOCTYPE html>
<html lang="id" class="{{ (Auth::check() && Auth::user()->setting && Auth::user()->setting->theme === 'dark') ? 'dark-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trending Hashtags - Twitaw</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --bg-body: linear-gradient(135deg, #0f0c29, #302b63, #24243e); }
        html.dark-mode { --bg-body: linear-gradient(135deg, #0a0a1a, #1a1a3e, #15152e); }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg-body);
            min-height: 100vh;
            color: #fff;
            padding: 40px 20px;
        }
        .page-wrapper { max-width: 700px; margin: 0 auto; }

        .back-btn {
            display: inline-flex; align-items: center; gap: 8px;
            color: #a78bfa; text-decoration: none; font-weight: 700; font-size: 15px;
            margin-bottom: 30px; transition: 0.2s;
        }
        .back-btn:hover { color: #c4b5fd; }

        .page-header { margin-bottom: 35px; }
        .page-header h1 { font-size: 2.2em; font-weight: 900; letter-spacing: -1px; }
        .page-header p { color: #a78bfa; margin-top: 8px; font-size: 1em; }

        .trending-grid { display: flex; flex-direction: column; gap: 14px; }

        .hashtag-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(167, 139, 250, 0.2);
            border-radius: 18px;
            padding: 20px 25px;
            display: flex; align-items: center; justify-content: space-between;
            text-decoration: none; color: #fff;
            transition: all 0.25s ease;
            position: relative; overflow: hidden;
        }
        .hashtag-card::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(167,139,250,0.15) 0%, transparent 60%);
            opacity: 0; transition: 0.3s;
        }
        .hashtag-card:hover { transform: translateY(-3px); border-color: rgba(167,139,250,0.5); }
        .hashtag-card:hover::before { opacity: 1; }

        .rank-badge {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 0.9em; margin-right: 15px; flex-shrink: 0;
        }
        .rank-1 { background: linear-gradient(135deg, #ffd700, #ff8c00); color: #000; }
        .rank-2 { background: linear-gradient(135deg, #e0e0e0, #9e9e9e); color: #000; }
        .rank-3 { background: linear-gradient(135deg, #cd7f32, #8d4c21); color: #fff; }
        .rank-other { background: rgba(255,255,255,0.1); color: #a78bfa; }

        .hashtag-info { flex: 1; }
        .hashtag-name { font-size: 1.25em; font-weight: 800; letter-spacing: -0.5px; }
        .hashtag-name span { color: #a78bfa; }
        .hashtag-count { font-size: 0.85em; color: #8b7fb8; margin-top: 4px; }

        .hashtag-arrow { font-size: 1.5em; color: #a78bfa; opacity: 0.5; transition: 0.2s; }
        .hashtag-card:hover .hashtag-arrow { opacity: 1; transform: translateX(4px); }

        .empty-state {
            text-align: center; padding: 80px 20px;
            color: #8b7fb8;
        }
        .empty-state .emoji { font-size: 4em; margin-bottom: 15px; }
        .empty-state h3 { font-size: 1.3em; color: #a78bfa; }
        .empty-state p { margin-top: 10px; }
    </style>
</head>
<body>
<div class="page-wrapper">
    <a href="/" class="back-btn">← Kembali ke Home</a>

    <div class="page-header">
        <h1>🔥 Trending Hashtags</h1>
        <p>Topik yang paling banyak dibicarakan saat ini</p>
    </div>

    <div class="trending-grid">
        @forelse($hashtags as $index => $hashtag)
            @php $rank = $index + 1; @endphp
            <a href="{{ route('hashtags.show', $hashtag->name) }}" class="hashtag-card">
                <div class="rank-badge {{ $rank <= 3 ? 'rank-'.$rank : 'rank-other' }}">
                    {{ $rank <= 3 ? ['🥇','🥈','🥉'][$rank - 1] : '#'.$rank }}
                </div>
                <div class="hashtag-info">
                    <div class="hashtag-name"><span>#</span>{{ $hashtag->name }}</div>
                    <div class="hashtag-count">{{ number_format($hashtag->post_count) }} post</div>
                </div>
                <div class="hashtag-arrow">→</div>
            </a>
        @empty
            <div class="empty-state">
                <div class="emoji">🏷️</div>
                <h3>Belum ada hashtag</h3>
                <p>Mulai posting dengan #hashtag dan jadilah yang pertama trending!</p>
            </div>
        @endforelse
    </div>
</div>
</body>
</html>