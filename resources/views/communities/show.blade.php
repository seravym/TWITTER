<!DOCTYPE html>
<html lang="id" class="{{ (Auth::check() && Auth::user()->setting && Auth::user()->setting->theme === 'dark') ? 'dark-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $community->name }}</title>
    <style>
        :root {
            --bg: #f7f9fa;
            --card-bg: #ffffff;
            --text: #0f1419;
            --text-muted: #536471;
            --border: #d9e1e7;
            --hover-bg: #eef6fb;
            --compose-bg: #fcfdfe;
            --accent: #1d9bf0;
            --danger: #f4212e;
            --success: #16a34a;
        }
        html.dark-mode {
            --bg: #15202b;
            --card-bg: #1e2732;
            --text: #f7f9fa;
            --text-muted: #8899a6;
            --border: #2f3b47;
            --hover-bg: #253341;
            --compose-bg: #253341;
            --accent: #1d9bf0;
            --danger: #ff6b8a;
            --success: #71d68b;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text);
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 720px;
            margin: auto;
            background: var(--card-bg);
            padding: 30px;
            border-radius: 18px;
            border: 1px solid var(--border);
        }
        a { color: var(--accent); text-decoration: none; font-weight: 700; }
        a:hover { text-decoration: underline; }
        h1 { margin-bottom: 8px; }
        h3 { margin-top: 22px; margin-bottom: 12px; }
        .muted { color: var(--text-muted); }
        .divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }
        .success-text { color: var(--success); font-weight: 700; }
        .error-text { color: var(--danger); font-weight: 700; }
        .btn {
            border: none;
            border-radius: 9999px;
            padding: 9px 16px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-soft { background: var(--hover-bg); color: var(--text); border: 1px solid var(--border); }
        .compose-box {
            display: block;
            background: var(--compose-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 15px;
            margin-bottom: 24px;
        }
        .compose-box textarea {
            width: 100%;
            min-height: 90px;
            resize: vertical;
            box-sizing: border-box;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px;
            background: var(--card-bg);
            color: var(--text);
            font-family: inherit;
            font-size: 15px;
            outline: none;
        }
        .compose-box textarea:focus { border-color: var(--accent); }
        .compose-actions { display: flex; justify-content: flex-end; margin-top: 10px; }
        .post-card {
            background: var(--compose-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 16px;
        }
        .post-head { display: flex; justify-content: space-between; gap: 12px; align-items: flex-start; }
        .post-name { color: var(--text); font-weight: 900; }
        .post-content { margin: 12px 0; line-height: 1.55; white-space: pre-wrap; }
        .post-actions { display: flex; gap: 10px; flex-wrap: wrap; border-top: 1px solid var(--border); padding-top: 12px; margin-top: 12px; }
        .quoted-card {
            display: block;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 12px;
            margin: 10px 0;
            background: var(--card-bg);
            color: var(--text);
        }
        .comment-section {
            margin-top: 12px;
            border-top: 1px dashed var(--border);
            padding-top: 12px;
        }
        .comment-form {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }
        .comment-form input {
            flex: 1;
            background: var(--card-bg);
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 9999px;
            padding: 9px 12px;
        }
        .comment-item {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 8px 10px;
            margin-top: 8px;
        }
        .empty {
            padding: 24px;
            text-align: center;
            color: var(--text-muted);
            border: 1px dashed var(--border);
            border-radius: 14px;
            background: var(--compose-bg);
        }
        ul { padding-left: 20px; }
        li { margin-bottom: 6px; }
    </style>
</head>
<body>

@php
    function parseCommunityText($text) {
        $escaped = e($text);
        $escaped = preg_replace('/@([A-Za-z0-9_]+)/', '<a href="/accounts/$1">@$1</a>', $escaped);
        return preg_replace('/#([A-Za-z0-9_]+)/', '<a href="/hashtags/$1">#$1</a>', $escaped);
    }

    $isMember = $community->members->contains(Auth::id());
    $isCreator = (int) $community->creator_id === (int) Auth::id();
@endphp

<div class="container">
    <a href="/communities">← Kembali</a>

    <h1>{{ $community->name }}</h1>
    <p class="muted">Dibuat oleh: <strong>{{ $community->creator->name ?? 'Unknown' }}</strong></p>
    <p>{{ $community->description ?? 'Tidak ada deskripsi.' }}</p>

    @if(session('success'))
        <p class="success-text">{{ session('success') }}</p>
    @endif
    @if($errors->any())
        <p class="error-text">{{ $errors->first() }}</p>
    @endif

    <hr class="divider">

    @if($isMember && !$isCreator)
        <form action="/communities/{{ $community->id }}/leave" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Keluar dari Komunitas</button>
        </form>
    @elseif($isCreator)
        <div class="muted" style="font-weight:700;">Kamu adalah admin komunitas ini.</div>
    @else
        <form action="/communities/{{ $community->id }}/join" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Gabung Komunitas</button>
        </form>
    @endif

    <hr class="divider">

    <h3>Buat Post Komunitas</h3>
    <form action="{{ route('communities.posts.store', $community) }}" method="POST" class="compose-box">
        @csrf
        <textarea name="content" placeholder="Tulis sesuatu untuk komunitas ini..." maxlength="350" required></textarea>
        <div class="compose-actions">
                {{-- Tombol upload gambar/video --}}
                <label for="communityMediaInput" style="cursor: pointer; font-size: 1em; display: inline-flex; align-items: center; gap: 5px;" title="Upload Foto/Video">
                    📷 <span style="font-size: 0.9em; font-weight: normal; color: var(--text-muted);">Tambah Media</span>
                </label>
                <input type="file" id="communityMediaInput" name="media" accept="image/*,video/*" style="display: none;">
        </div>
        <div class="compose-actions">
            <button type="submit" class="btn btn-primary">Post ke Komunitas</button>
        </div>
    </form>

    <h3>Postingan Komunitas</h3>

    @forelse($community->posts as $post)
        <div class="post-card">
            <div class="post-head">
                <div>
                    <a href="/accounts/{{ $post->account->id ?? '#' }}" class="post-name">{{ $post->account->name ?? 'User' }}</a>
                    <div class="muted">@ {{ $post->account->username ?? 'user' }} • {{ $post->created_at->diffForHumans() }}</div>
                </div>
            </div>

            <div class="post-content">{!! parseCommunityText($post->content) !!}</div>

            @if($post->quotedPost)
                <a href="{{ route('posts.show', $post->quotedPost) }}" class="quoted-card">
                    <strong>{{ $post->quotedPost->account->name ?? 'User' }}</strong>
                    <div class="muted">@ {{ $post->quotedPost->account->username ?? 'user' }}</div>
                    <div style="margin-top:8px;">{{ $post->quotedPost->content }}</div>
                </a>
            @endif

            <div class="post-actions">
                <form action="/posts/{{ $post->id }}/like" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-soft">
                        {{ $post->isLikedBy(Auth::id()) ? '❤️' : '🤍' }} {{ $post->likes->count() }}
                    </button>
                </form>

                <form action="{{ route('posts.repost', $post) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-soft">
                        {{ $post->isRepostedBy(Auth::id()) ? '🔁 Reposted' : '🔁 Repost' }} {{ $post->reposts->count() }}
                    </button>
                </form>

                <a href="{{ route('posts.quote', $post) }}" class="btn btn-soft">💬 Quote</a>
            </div>

            <div class="comment-section">
                <strong>Komentar ({{ $post->comments->count() }})</strong>

                <form action="/comments" method="POST" class="comment-form">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <input type="text" name="content" placeholder="Tulis komentar..." required>
                    <button type="submit" class="btn btn-primary">Balas</button>
                </form>

                @foreach($post->comments as $comment)
                    <div class="comment-item">
                        <strong>{{ $comment->account->name ?? 'User' }}:</strong>
                        <span>{!! parseCommunityText($comment->content) !!}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="empty">Belum ada postingan di komunitas ini.</div>
    @endforelse

    <hr class="divider">

    <h3>Daftar Anggota ({{ $community->members->count() }})</h3>
    <ul>
        @foreach($community->members as $member)
            <li>
                {{ $member->name }}
                <small class="muted">({{ strtoupper($member->pivot->role ?? 'MEMBER') }})</small>
            </li>
        @endforeach
    </ul>
</div>

</body>
</html>
