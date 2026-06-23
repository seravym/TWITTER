<!DOCTYPE html>
<html lang="id" class="{{ (Auth::check() && Auth::user()->setting && Auth::user()->setting->theme === 'dark') ? 'dark-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>#{{ $hashtag->name }} - Twitaw</title>
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

        .page-header {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            padding: 30px 20px 80px;
            text-align: center; color: white; position: relative;
        }
        .back-link {
            position: absolute; top: 20px; left: 20px;
            color: rgba(255,255,255,0.7); text-decoration: none; font-weight: 600; transition: 0.2s;
        }
        .back-link:hover { color: #fff; }

        .hashtag-hero {
            font-size: 3em; font-weight: 900;
            letter-spacing: -2px; margin-bottom: 10px;
        }
        .hashtag-hero span { color: #a78bfa; }
        .post-count-badge {
            display: inline-block;
            background: rgba(167,139,250,0.2); border: 1px solid rgba(167,139,250,0.4);
            color: #c4b5fd; padding: 6px 18px; border-radius: 30px;
            font-size: 0.9em; font-weight: 700;
        }

        .feed-container { max-width: 600px; margin: -30px auto 0; padding: 0 15px 60px; }

        .post-card {
            background: var(--card-bg); border-radius: 20px; border: 1px solid var(--border);
            margin-bottom: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.04);
            transition: 0.2s; overflow: hidden;
        }
        .post-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.08); transform: translateY(-2px); }
        .post-inner { padding: 20px; }

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

        .post-content { font-size: 1.1em; line-height: 1.65; color: var(--text); margin-bottom: 15px; }
        .post-content .tag-link { color: #8b5cf6; font-weight: 700; text-decoration: none; }
        .post-content .tag-link:hover { text-decoration: underline; }

        .post-actions { display: flex; gap: 10px; border-top: 1px solid var(--border); padding-top: 14px; }
        .action-btn {
            background: var(--bg); border: 1px solid var(--border);
            padding: 7px 16px; border-radius: 20px;
            font-size: 0.9em; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; gap: 6px; transition: 0.2s; color: #536471;
            text-decoration: none;
        }
        .action-btn:hover { background: #e8f5fe; color: #1da1f2; border-color: #c8e6ff; }
        .action-btn.like:hover { background: #fce8f3; color: #f91880; border-color: #f9c8e7; }
        .action-btn.bookmark:hover { background: #fffbeb; color: #f59e0b; border-color: #fde68a; }
        .action-btn.bookmarked { background: #fffbeb; color: #f59e0b; border-color: #fde68a; }

        .hashtag-chips { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px; }
        .hashtag-chip {
            background: rgba(139, 92, 246, 0.1); color: #7c3aed;
            padding: 3px 12px; border-radius: 20px; font-size: 0.8em; font-weight: 700;
            text-decoration: none; transition: 0.2s;
        }
        .hashtag-chip:hover { background: rgba(139, 92, 246, 0.2); }
        .hashtag-chip.active { background: #8b5cf6; color: white; }

        .empty-state { text-align: center; padding: 80px 20px; color: #536471; }
        .empty-state .emoji { font-size: 3.5em; margin-bottom: 15px; }
    </style>
</head>
<body>

@php
function hashtagAvatarGradient($id) {
    $g = ['linear-gradient(135deg,#a18cd1,#fbc2eb)','linear-gradient(135deg,#84fab0,#8fd3f4)',
          'linear-gradient(135deg,#fccb90,#d57eeb)','linear-gradient(135deg,#e0c3fc,#8ec5fc)',
          'linear-gradient(135deg,#f093fb,#f5576c)','linear-gradient(135deg,#4facfe,#00f2fe)',
          'linear-gradient(135deg,#ff9a9e,#fecfef)','linear-gradient(135deg,#a8edea,#fed6e3)'];
    return $g[$id % count($g)];
}
function parseHashtags($text) {
    return preg_replace('/#(\w+)/u','<a href="/hashtags/$1" class="tag-link">#$1</a>', e($text));
}
@endphp

<div class="page-header">
    <a href="{{ route('hashtags.index') }}" class="back-link">← Trending</a>
    <a href="/" style="position:absolute;top:20px;right:20px;color:rgba(255,255,255,0.7);text-decoration:none;font-weight:600;" class="back-link">🏠 Home</a>
    <div class="hashtag-hero"><span>#</span>{{ $hashtag->name }}</div>
    <div class="post-count-badge">{{ number_format($hashtag->post_count) }} post</div>
</div>

<div class="feed-container">
    @if(session('success'))
        <div style="background:#eafff0;color:#008000;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-weight:700;">
            ✓ {{ session('success') }}
        </div>
    @endif

    @forelse($posts as $post)
        <div class="post-card">
            <div class="post-inner">
                <div class="post-header-row">
                    <a href="/accounts/{{ $post->account->id }}" class="post-avatar"
                       style="background: {{ hashtagAvatarGradient($post->account_id) }};">
                        {{ strtoupper(substr($post->account->name, 0, 1)) }}
                    </a>
                    <div class="post-meta">
                        <a href="/accounts/{{ $post->account->id }}">{{ $post->account->name }}</a>
                        <div class="sub">@{{ $post->account->username }} · {{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                @if($post->hashtags->count())
                    <div class="hashtag-chips">
                        @foreach($post->hashtags as $tag)
                            <a href="{{ route('hashtags.show', $tag->name) }}"
                               class="hashtag-chip {{ $tag->name === $hashtag->name ? 'active' : '' }}">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="post-content">{!! parseHashtags($post->content) !!}</div>

                <div class="post-actions">
                    <form action="/posts/{{ $post->id }}/like" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="action-btn like">
                            {{ $post->isLikedBy(Auth::id()) ? '❤️' : '🤍' }} {{ $post->likes->count() }}
                        </button>
                    </form>
                    <a href="/" class="action-btn">💬 {{ $post->comments->count() }}</a>
                    <form action="{{ route('bookmarks.toggle', $post->id) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="action-btn bookmark {{ $post->isBookmarkedBy(Auth::id()) ? 'bookmarked' : '' }}">
                            {{ $post->isBookmarkedBy(Auth::id()) ? '🔖' : '🏷️' }} Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="emoji">🔍</div>
            <p style="font-size:1.1em;font-weight:700;color:#302b63;">Belum ada post untuk hashtag ini.</p>
        </div>
    @endforelse
</div>

</body>
</html>
