<!DOCTYPE html>
<html lang="en" class="{{ (Auth::check() && Auth::user()->setting && Auth::user()->setting->theme === 'dark') ? 'dark-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <title>Post</title>
    <style>
        :root { --bg:#f7f9fa; --card:#fff; --text:#0f1419; --muted:#536471; --border:#eff3f4; --hover:#e8f5fe; --accent:#1da1f2; }
        html.dark-mode { --bg:#15202b; --card:#1e2732; --text:#f7f9fa; --muted:#8899a6; --border:#2f3b47; --hover:#253341; --accent:#1d9bf0; }
        body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; background:var(--bg); color:var(--text); margin:0; padding:40px 20px; }
        .container { max-width:650px; margin:0 auto; background:var(--card); border:1px solid var(--border); border-radius:20px; padding:25px; }
        .meta { color:var(--muted); margin-bottom:15px; }
        .content { font-size:1.2em; line-height:1.6; white-space:pre-wrap; }
        .media { margin:16px 0; border-radius:16px; overflow:hidden; }
        .media img, .media video { width:100%; max-height:430px; object-fit:cover; border-radius:16px; display:block; background:#000; }
        .actions { display:flex; gap:10px; flex-wrap:wrap; border-top:1px solid var(--border); padding-top:14px; margin-top:16px; }
        .btn { background:var(--hover); color:var(--text); border:1px solid var(--border); padding:9px 15px; border-radius:999px; font-weight:800; cursor:pointer; text-decoration:none; }
        .back { color:var(--accent); text-decoration:none; font-weight:800; display:inline-block; margin-top:18px; }
    </style>
</head>
<body>
@php
    function postTextLinks($text) {
        $escaped = e($text);
        $escaped = preg_replace('/@([A-Za-z0-9_]+)/', '<a href="/accounts/$1" style="color:#1da1f2;text-decoration:none;font-weight:bold;">@$1</a>', $escaped);
        return preg_replace('/#([A-Za-z0-9_]+)/', '<a href="/hashtags/$1" style="color:#1da1f2;text-decoration:none;font-weight:bold;">#$1</a>', $escaped);
    }
@endphp
<div class="container">
    <div class="meta">dibuat oleh <strong>{{ $post->account->name }}</strong> • {{ $post->created_at->diffForHumans() }}</div>
    <div class="content">{!! postTextLinks($post->content) !!}</div>

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
        <form action="{{ route('posts.like', $post) }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn">{{ $post->isLikedBy(auth()->id()) ? '❤️' : '🤍' }} {{ $post->likes->count() }}</button>
        </form>

        <form action="{{ route('posts.repost', $post) }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn">🔁 {{ $post->reposts->count() }}</button>
        </form>

        @if($post->media_path)
            <a href="{{ route('posts.downloadMedia', $post) }}" class="btn">⬇️ Download Media</a>
        @endif


        @if(Auth::id() !== $post->account_id)
            <form action="{{ route('reports.posts.store', $post) }}" method="POST" style="margin:0; display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                @csrf
                <select name="reason" required class="btn" style="padding:8px 12px;">
                    <option value="spam">Spam</option>
                    <option value="harassment">Harassment</option>
                    <option value="inappropriate">Inappropriate content</option>
                    <option value="misinformation">Misinformation</option>
                </select>
                <button type="submit" class="btn" onclick="return confirm('Kirim report post ini?')">🚫 Report</button>
            </form>
        @endif

        @if(Auth::id() === $post->account_id && !$post->archived_at)
            <form action="{{ route('posts.archive', $post) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn">🗄️ Archive</button>
            </form>
        @endif
    </div>

    <a href="{{ route('posts.index') }}" class="back">← Kembali</a>
</div>
</body>
</html>
