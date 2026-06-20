<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmark Saya - Twitaw</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #f7f9fa; color: #0f1419; min-height: 100vh;
        }

        .page-header {
            background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
            padding: 30px 20px 80px; text-align: center; color: white; position: relative;
        }
        .back-link {
            position: absolute; top: 20px; left: 20px;
            color: rgba(255,255,255,0.7); text-decoration: none; font-weight: 600; transition: 0.2s;
        }
        .back-link:hover { color: #fff; }

        .page-title-hero { font-size: 2.5em; font-weight: 900; letter-spacing: -1.5px; margin-bottom: 10px; }
        .bookmark-count-badge {
            display: inline-block;
            background: rgba(245,158,11,0.2); border: 1px solid rgba(245,158,11,0.4);
            color: #fde68a; padding: 6px 18px; border-radius: 30px;
            font-size: 0.9em; font-weight: 700;
        }

        .feed-container { max-width: 600px; margin: -30px auto 0; padding: 0 15px 60px; }

        .post-card {
            background: white; border-radius: 20px; border: 1px solid #eff3f4;
            margin-bottom: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.25s ease; overflow: hidden;
        }
        .post-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.08); transform: translateY(-2px); }
        .post-inner { padding: 20px; }

        .bookmark-label {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 0.78em; font-weight: 700; color: #f59e0b;
            background: #fffbeb; border: 1px solid #fde68a;
            padding: 3px 10px; border-radius: 20px; margin-bottom: 12px;
        }

        .post-avatar {
            width: 46px; height: 46px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 900; font-size: 16px;
            text-transform: uppercase; text-decoration: none; flex-shrink: 0;
        }
        .post-header-row { display: flex; gap: 12px; align-items: flex-start; margin-bottom: 14px; }
        .post-meta a { font-weight: 800; color: #0f1419; text-decoration: none; font-size: 1.05em; }
        .post-meta a:hover { text-decoration: underline; }
        .post-meta .sub { color: #536471; font-size: 0.88em; margin-top: 2px; }

        .post-content {
            font-size: 1.08em; line-height: 1.65; color: #0f1419; margin-bottom: 15px;
        }
        .post-content .tag-link { color: #8b5cf6; font-weight: 700; text-decoration: none; }
        .post-content .tag-link:hover { text-decoration: underline; }

        .hashtag-chips { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }
        .hashtag-chip {
            background: rgba(139,92,246,0.1); color: #7c3aed;
            padding: 3px 12px; border-radius: 20px; font-size: 0.8em; font-weight: 700;
            text-decoration: none; transition: 0.2s;
        }
        .hashtag-chip:hover { background: rgba(139,92,246,0.2); }

        .post-actions { display: flex; gap: 10px; border-top: 1px solid #eff3f4; padding-top: 14px; flex-wrap: wrap; }
        .action-btn {
            background: #f7f9fa; border: 1px solid #eff3f4;
            padding: 7px 16px; border-radius: 20px;
            font-size: 0.88em; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; gap: 6px; transition: 0.2s; color: #536471;
            text-decoration: none;
        }
        .action-btn:hover { background: #e8f5fe; color: #1da1f2; border-color: #c8e6ff; }
        .action-btn.like:hover { background: #fce8f3; color: #f91880; border-color: #f9c8e7; }
        .action-btn.remove:hover { background: #fef2f2; color: #ef4444; border-color: #fecaca; }

        .saved-at { font-size: 0.78em; color: #b0b7c0; margin-left: auto; padding-top: 2px; }

        .empty-state { text-align: center; padding: 80px 20px; }
        .empty-state .emoji { font-size: 4em; margin-bottom: 20px; }
        .empty-state h3 { font-size: 1.3em; font-weight: 800; color: #0f3460; margin-bottom: 10px; }
        .empty-state p { color: #536471; }
        .empty-state a {
            display: inline-block; margin-top: 20px;
            background: #1da1f2; color: white;
            padding: 12px 28px; border-radius: 30px;
            text-decoration: none; font-weight: 700; transition: 0.2s;
        }
        .empty-state a:hover { background: #1a91da; }

        @media (max-width: 600px) {
            .page-title-hero { font-size: 1.8em; }
        }
    </style>
</head>
<body>

@php
function bmkAvatarGradient($id) {
    $g = ['linear-gradient(135deg,#a18cd1,#fbc2eb)','linear-gradient(135deg,#84fab0,#8fd3f4)',
          'linear-gradient(135deg,#fccb90,#d57eeb)','linear-gradient(135deg,#e0c3fc,#8ec5fc)',
          'linear-gradient(135deg,#f093fb,#f5576c)','linear-gradient(135deg,#4facfe,#00f2fe)',
          'linear-gradient(135deg,#ff9a9e,#fecfef)','linear-gradient(135deg,#a8edea,#fed6e3)'];
    return $g[$id % count($g)];
}
function bmkParseHashtags($text) {
    return preg_replace('/#(\w+)/u','<a href="/hashtags/$1" class="tag-link">#$1</a>', e($text));
}
@endphp

<div class="page-header">
    <a href="/" class="back-link">← Kembali ke Home</a>
    <div class="page-title-hero">🔖 Bookmark Saya</div>
    <div class="bookmark-count-badge">{{ $bookmarks->count() }} post tersimpan</div>
</div>

<div class="feed-container">
    @if(session('success'))
        <div style="background:#eafff0;color:#008000;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-weight:700;">
            ✓ {{ session('success') }}
        </div>
    @endif

    @forelse($bookmarks as $bm)
        @php $post = $bm->post; @endphp
        @if($post)
        <div class="post-card">
            <div class="post-inner">
                <div class="bookmark-label">🔖 Disimpan {{ $bm->created_at->diffForHumans() }}</div>

                <div class="post-header-row">
                    <a href="/accounts/{{ $post->account->id }}" class="post-avatar"
                       style="background: {{ bmkAvatarGradient($post->account_id) }};">
                        {{ strtoupper(substr($post->account->name, 0, 1)) }}
                    </a>
                    <div class="post-meta">
                        <a href="/accounts/{{ $post->account->id }}">{{ $post->account->name }}</a>
                        <div class="sub">@{{ $post->account->username }} · {{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                @if($post->hashtags && $post->hashtags->count())
                    <div class="hashtag-chips">
                        @foreach($post->hashtags as $tag)
                            <a href="{{ route('hashtags.show', $tag->name) }}" class="hashtag-chip">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="post-content">{!! bmkParseHashtags($post->content) !!}</div>

                <div class="post-actions">
                    <form action="/posts/{{ $post->id }}/like" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="action-btn like">
                            {{ $post->isLikedBy(Auth::id()) ? '❤️' : '🤍' }} {{ $post->likes->count() }}
                        </button>
                    </form>
                    <span class="action-btn">💬 {{ $post->comments->count() }}</span>

                    {{-- Hapus bookmark --}}
                    <form action="{{ route('bookmarks.destroy', $bm->id) }}" method="POST"
                          style="margin:0;" onsubmit="return confirm('Hapus dari bookmark?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn remove">🗑️ Hapus</button>
                    </form>

                    <div class="saved-at">Disimpan: {{ $bm->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>
        @endif
    @empty
        <div class="empty-state">
            <div class="emoji">🔖</div>
            <h3>Belum ada yang tersimpan</h3>
            <p>Simpan post menarik dengan menekan tombol Bookmark di setiap post.</p>
            <a href="/">Jelajahi Feed</a>
        </div>
    @endforelse
</div>

</body>
</html>