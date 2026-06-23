<!DOCTYPE html>
<html lang="en" class="{{ (Auth::check() && Auth::user()->setting && Auth::user()->setting->theme === 'dark') ? 'dark-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <title>Archive - Twitter</title>
    <style>
        :root { --bg:#f7f9fa; --card:#fff; --text:#0f1419; --muted:#536471; --border:#eff3f4; --hover:#e8f5fe; --accent:#1da1f2; }
        html.dark-mode { --bg:#15202b; --card:#1e2732; --text:#f7f9fa; --muted:#8899a6; --border:#2f3b47; --hover:#253341; --accent:#1d9bf0; }
        body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; background:var(--bg); color:var(--text); margin:0; padding:24px; }
        .container { max-width:760px; margin:0 auto; background:var(--card); border:1px solid var(--border); border-radius:22px; overflow:hidden; }
        .header { padding:22px 26px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
        .header h1 { margin:0; font-size:1.5em; }
        .back { color:var(--accent); text-decoration:none; font-weight:800; }
        .empty { padding:55px 25px; text-align:center; color:var(--muted); }
        .post-card { padding:22px 26px; border-bottom:8px solid var(--border); }
        .post-card:last-child { border-bottom:none; }
        .meta { color:var(--muted); font-size:0.92em; margin-bottom:12px; }
        .content { font-size:1.08em; line-height:1.6; margin-bottom:14px; white-space:pre-wrap; }
        .media { margin:12px 0 15px; border-radius:16px; overflow:hidden; }
        .media img, .media video { width:100%; max-height:420px; object-fit:cover; border-radius:16px; display:block; background:#000; }
        .actions { display:flex; flex-wrap:wrap; gap:10px; border-top:1px solid var(--border); padding-top:12px; }
        .btn { background:var(--hover); color:var(--text); border:1px solid var(--border); padding:9px 15px; border-radius:999px; font-weight:800; cursor:pointer; text-decoration:none; font-size:0.95em; }
        .btn.primary { background:var(--accent); color:white; border-color:var(--accent); }
        .btn.danger { background:#fdeced; color:#f4212e; border-color:#ffcdd2; }
    </style>
</head>
<body>
@php
    function archiveTextLinks($text) {
        $escaped = e($text);
        $escaped = preg_replace('/@([A-Za-z0-9_]+)/', '<a href="/accounts/$1" style="color:#1da1f2;text-decoration:none;font-weight:bold;">@$1</a>', $escaped);
        return preg_replace('/#([A-Za-z0-9_]+)/', '<a href="/hashtags/$1" style="color:#1da1f2;text-decoration:none;font-weight:bold;">#$1</a>', $escaped);
    }
@endphp
<div class="container">
    <div class="header">
        <div>
            <a href="/" class="back">← Back to Home</a>
            <h1>🗄️ Archive</h1>
        </div>
    </div>

    @if (session('success'))
        <div style="background:#e8f5e9;color:#2e7d32;padding:15px 26px;font-weight:bold;">✓ {{ session('success') }}</div>
    @endif

    @if($posts->isEmpty())
        <div class="empty">
            <h2>Belum ada post archive.</h2>
            <p>Post yang kamu archive akan hilang dari timeline dan masuk ke halaman ini.</p>
        </div>
    @else
        @foreach($posts as $post)
            <div class="post-card">
                <div class="meta">Archived {{ optional($post->archived_at)->diffForHumans() }} • Posted {{ $post->created_at->diffForHumans() }}</div>
                <div class="content">{!! archiveTextLinks($post->content) !!}</div>

                @if($post->media_path)
                    <div class="media">
                        @if($post->media_type === 'video')
                            <video controls>
                                <source src="{{ asset('storage/' . $post->media_path) }}">
                            </video>
                        @else
                            <img src="{{ asset('storage/' . $post->media_path) }}" alt="Post media">
                        @endif
                    </div>
                @endif

                <div class="actions">
                    <form action="{{ route('posts.restore', $post) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn primary">Restore</button>
                    </form>

                    @if($post->media_path)
                        <a href="{{ route('posts.downloadMedia', $post) }}" class="btn">⬇️ Download Media</a>
                    @endif

                    <form action="/posts/{{ $post->id }}" method="POST" style="margin:0; margin-left:auto;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn danger" onclick="return confirm('Delete post permanently?')">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif
</div>
</body>
</html>
