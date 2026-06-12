<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - Twitter</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f7f9fa; margin: 0; padding: 0; color: #0f1419; }
        .app-container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; padding-top: 20px; }
        
        .sidebar { width: 30%; position: sticky; top: 20px; height: max-content; padding-right: 25px; box-sizing: border-box; }
        .brand { font-size: 2.2em; font-weight: 900; color: #1da1f2; margin-bottom: 20px; padding-left: 10px; letter-spacing: -1px; display: flex; align-items: center; gap: 10px; }
        
        .sidebar-card { background: white; border: 1px solid #eff3f4; border-radius: 20px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .sidebar-profile { text-align: center; border-bottom: 1px solid #eff3f4; padding-bottom: 25px; margin-bottom: 20px; }
        
        /* STYLE AVATAR - Disesuaikan agar isi hurufnya berada di tengah dengan warna putih */
        .sidebar-avatar { border-radius: 50%; margin: 0 auto 15px auto; flex-shrink: 0; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold; text-transform: uppercase; }
        
        .sidebar-name { font-weight: 800; font-size: 1.3em; color: #0f1419; margin-bottom: 5px; }
        .sidebar-username { color: #536471; font-size: 1em; margin-bottom: 15px; }
        .sidebar-stats { display: flex; justify-content: center; gap: 20px; font-size: 0.9em; color: #536471; }
        .sidebar-stats strong { color: #0f1419; font-size: 1.2em; display: block; }
        .btn-view-profile { display: block; width: 100%; background: #0f1419; color: white; padding: 10px 0; border-radius: 30px; text-decoration: none; font-weight: bold; margin-top: 20px; transition: 0.2s; font-size: 1em; }
        .btn-view-profile:hover { background: #272c30; }

        .nav-menu { list-style: none; padding: 0; margin: 0; }
        .nav-menu li { margin-bottom: 5px; }
        .nav-menu a { display: flex; align-items: center; gap: 15px; padding: 12px 15px; text-decoration: none; color: #0f1419; font-size: 1.1em; border-radius: 30px; transition: background 0.2s; font-weight: 600; }
        .nav-menu a:hover { background-color: #e8f5fe; color: #1da1f2; }
        
        .btn-logout { width: 100%; background: transparent; color: #f4212e; border: 1px solid #fdeced; padding: 12px; border-radius: 30px; font-weight: bold; font-size: 1em; cursor: pointer; margin-top: 15px; transition: 0.2s; }
        .btn-logout:hover { background: #f4212e; color: white; border-color: #f4212e; }

        .main-feed { width: 68%; background: white; border: 1px solid #eff3f4; border-radius: 20px; min-height: 100vh; padding-bottom: 50px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.01); }
        .feed-header { position: sticky; top: 0; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); z-index: 10; border-bottom: 1px solid #eff3f4; }
        /* PAGE TITLE - text-align: center ditambahkan di sini */
        .page-title { font-size: 1.4em; font-weight: 900; padding: 20px 15px 10px 15px; margin: 0; color: #0f1419; text-align: center; } 
        .feed-tabs { display: flex; padding: 0 15px; }
        .tab-item { flex: 1; text-align: center; padding: 15px 0; text-decoration: none; color: #536471; font-weight: 700; transition: 0.2s; position: relative; font-size: 1.05em; }
        .tab-item:hover { background-color: #f7f9fa; color: #0f1419; border-radius: 8px 8px 0 0; }
        .tab-item.active { color: #0f1419; }
        .tab-item.active::after { content: ''; position: absolute; bottom: 0; left: 25%; width: 50%; height: 4px; background-color: #1da1f2; border-radius: 4px 4px 0 0; }

        .compose-area { padding: 25px; background-color: #f8f9fa; border-bottom: 1px solid #e1e8ed; display: flex; gap: 20px; box-shadow: inset 0 3px 8px rgba(0,0,0,0.03); }
        .compose-input { flex: 1; border: none; font-size: 1.25em; outline: none; resize: none; margin-top: 10px; background: transparent; color: #0f1419; }
        .compose-input::placeholder { color: #8899a6; font-weight: 500; }
        .btn-post { background: #1da1f2; color: white; border: none; padding: 10px 28px; font-weight: bold; border-radius: 30px; cursor: pointer; font-size: 1.05em; box-shadow: 0 4px 6px rgba(29, 161, 242, 0.2); }
        .btn-post:hover { background: #1a91da; box-shadow: 0 4px 8px rgba(29, 161, 242, 0.3); }

        .post-card { padding: 25px; border-bottom: 1px solid #eff3f4; transition: 0.2s; position: relative; }
        .post-card:hover { background: #fdfdfe; }
        .my-post { border-left: 4px solid #1da1f2; }
        .my-post-badge { position: absolute; top: 25px; right: 25px; background: #e8f5fe; color: #1da1f2; font-size: 0.8em; font-weight: bold; padding: 4px 10px; border-radius: 12px; }

        .post-header { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .post-name { font-weight: 800; color: #0f1419; text-decoration: none; font-size: 1.1em; }
        .post-name:hover { text-decoration: underline; }
        .post-meta { color: #536471; font-size: 0.95em; }
        .post-content { font-size: 1.15em; line-height: 1.6; margin-bottom: 18px; margin-top: 10px; color: #0f1419; }
        
        .post-actions { display: flex; gap: 45px; color: #536471; font-size: 0.95em; align-items: center; margin-top: 15px; }
        .action-btn { background: none; border: none; color: inherit; cursor: pointer; font-size: 1.05em; display: flex; align-items: center; gap: 7px; padding: 6px; border-radius: 20px; transition: 0.2s; }
        .action-btn:hover { background: #e8f5fe; color: #1da1f2; }
        .action-btn.like:hover { background: #fce8f3; color: #f91880; }

        .comment-section { margin-top: 18px; padding-top: 18px; border-top: 1px dashed #eff3f4; }
        .comment-item { display: flex; gap: 12px; margin-bottom: 12px; position: relative; }
        .comment-bubble { background: #f7f9fa; padding: 12px 18px; border-radius: 16px; border-top-left-radius: 4px; flex: 1; border: 1px solid #eff3f4; }
        .reply-thread-wrapper { margin-left: 45px; border-left: 2px solid #e1e8ed; padding-left: 15px; margin-top: -5px; margin-bottom: 15px; }

        .comment-form-container { display: none; gap: 12px; margin-top: 18px; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        .comment-input { flex: 1; padding: 12px 18px; border: 1px solid #cfd9de; border-radius: 30px; outline: none; background: #f7f9fa; transition: 0.2s; font-size: 1em; }
        .comment-input:focus { border-color: #1da1f2; background: white; box-shadow: 0 0 0 2px rgba(29,161,242,0.15); }

        .alert-box { background: #fff3e0; border: 1px solid #ffe0b2; padding: 15px; border-radius: 16px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(239,108,0,0.05); }
        .alert-title { color: #ef6c00; font-weight: bold; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; font-size: 1em; }

        /* DESAIN KOTAK SENSOR TOXIC AI */
        .toxic-warning { background: #fdf2f2; border: 1px solid #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 12px; font-size: 0.95em; display: flex; justify-content: space-between; align-items: center; margin-top: 5px; }
        .btn-reveal { background: white; border: 1px solid #f5c6cb; color: #721c24; padding: 5px 12px; border-radius: 20px; cursor: pointer; font-weight: bold; font-size: 0.85em; transition: 0.2s; }
        .btn-reveal:hover { background: #f8d7da; }
    </style>
</head>
<body>

@php
    function getAvatarGradient($id) {
        $gradients = [
            'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)', // Ungu-Pink
            'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)', // Hijau-Biru
            'linear-gradient(135deg, #fccb90 0%, #d57eeb 100%)', // Orange-Ungu
            'linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%)', // Biru Muda-Ungu
            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)', // Pink-Merah
            'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)', // Biru Terang
            'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)', // Pink Pastel
            'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)'  // Mint-Pink
        ];
        return $gradients[$id % count($gradients)];
    }
@endphp

<div class="app-container">
    <div class="sidebar">
        <div class="brand">Twitter</div>
        <div class="sidebar-card">
            @auth
                <div class="sidebar-profile">
                    <div class="sidebar-avatar" style="width: 90px; height: 90px; font-size: 32px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); background: {{ getAvatarGradient(Auth::id()) }};">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="sidebar-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-username">@ {{ Auth::user()->username }}</div>
                    
                    <div class="sidebar-stats">
                        <div><strong>{{ Auth::user()->followers()->where('status', 'accepted')->count() }}</strong> Pengikut</div>
                        <div><strong>{{ Auth::user()->following()->where('status', 'accepted')->count() }}</strong> Mengikuti</div>
                    </div>
                    <a href="/accounts/{{ Auth::id() }}" class="btn-view-profile">Lihat Profil Saya</a>
                </div>

                @php $pendingRequests = \App\Models\Follow::where('following_id', Auth::id())->where('status', 'pending')->with('follower')->get(); @endphp
                @if($pendingRequests->count() > 0)
                    <div class="alert-box">
                        <div class="alert-title">🔔 Menunggu Persetujuan ({{ $pendingRequests->count() }})</div>
                        @foreach($pendingRequests as $req)
                            <div style="font-size: 0.95em; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                                <strong>{{ $req->follower->name }}</strong>
                                <div style="display: flex; gap: 8px;">
                                    <form action="/follows/accept/{{ $req->follower_id }}" method="POST">@csrf<button type="submit" style="background:#4caf50;color:white;border:none;padding:5px 10px;border-radius:15px;cursor:pointer;font-weight:bold;">✓</button></form>
                                    <form action="/follows/reject/{{ $req->follower_id }}" method="POST">@csrf<button type="submit" style="background:#f44336;color:white;border:none;padding:5px 10px;border-radius:15px;cursor:pointer;font-weight:bold;">✕</button></form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endauth

            <ul class="nav-menu">
                <li><a href="/" onclick="if(window.location.pathname=='/') { window.scrollTo({top: 0, behavior: 'smooth'}); return false; }">🏠 Beranda</a></li>
                <li><a href="/accounts">🔍 Jelajahi Pengguna</a></li>
                <li><a href="/messages">✉️ Direct Messages</a></li>
                <li><a href="/communities">👥 Komunitas</a></li>
                <li><a href="/settings">⚙️ Settings</a></li>
            </ul>

            @auth
                <form action="/logout" method="POST" style="border-top: 1px dashed #eff3f4; margin-top: 20px; padding-top: 5px;">
                    @csrf<button type="submit" class="btn-logout">Logout</button>
                </form>
            @endauth
        </div>
    </div>

    <div class="main-feed">
        <div class="feed-header">
            <h2 class="page-title">Beranda</h2>
            <div class="feed-tabs">
                <a href="/" class="tab-item {{ (!isset($feedType) || $feedType == 'global') ? 'active' : '' }}">For You</a>
                @auth<a href="/?feed=following" class="tab-item {{ (isset($feedType) && $feedType == 'following') ? 'active' : '' }}">Following</a>@endauth
            </div>
        </div>

        @if (session('success')) <div style="background: #e8f5e9; color: #2e7d32; padding: 18px 25px; font-weight: bold; border-bottom: 1px solid #c8e6c9;">✓ {{ session('success') }}</div> @endif
        @if ($errors->any()) <div style="background: #ffebee; color: #c62828; padding: 18px 25px; font-weight: bold; border-bottom: 1px solid #ffcdd2;">✗ {{ $errors->first() }}</div> @endif

        @auth
            @php
                $placeholders = ['Apa yang sedang kamu pikirkan?', 'Berbagi cerita hari ini?', 'Suarakan pendapatmu!', 'Twitter-kan keluh kesahmu!', 'Tuliskan ide brilianmu!'];
                $randomPlaceholder = $placeholders[array_rand($placeholders)];
            @endphp
            <div class="compose-area">
                <div class="sidebar-avatar" style="width: 50px; height: 50px; font-size: 18px; margin-bottom: 0; background: {{ getAvatarGradient(Auth::id()) }};">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <form action="/posts" method="POST" style="flex: 1;">
                    @csrf
                    <textarea name="content" class="compose-input" rows="2" placeholder="{{ $randomPlaceholder }}" required></textarea>
                    <div style="text-align: right; margin-top: 10px;">
                        <button type="submit" class="btn-post">Posting</button>
                    </div>
                </form>
            </div>
        @endauth

        @if($posts->isEmpty())
            <div style="text-align: center; color: #536471; padding: 60px 25px;">
                <h2>Selamat Datang</h2>
                <p>Timeline ini masih kosong.</p>
            </div>
        @else
            @foreach($posts as $post)
                <div class="post-card {{ Auth::id() === $post->account_id ? 'my-post' : '' }}">
                    @if(Auth::id() === $post->account_id) <div class="my-post-badge">Postingan Anda</div> @endif

                    <div class="post-header">
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <div class="sidebar-avatar" style="width: 45px; height: 45px; font-size: 16px; margin: 0; background: {{ getAvatarGradient($post->account_id) }};">
                                {{ substr($post->account->name, 0, 1) }}
                            </div>
                            <div>
                                <a href="/accounts/{{ $post->account->id }}" class="post-name">{{ $post->account->name }}</a>
                                <span class="post-meta">@ {{ $post->account->username }} • {{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="post-content">{{ $post->content }}</div>
                    
                    <div class="post-actions">
                        <form action="/posts/{{ $post->id }}/like" method="POST">
                            @csrf<button type="submit" class="action-btn like">{{ $post->isLikedBy(Auth::id()) ? '❤️' : '🤍' }} {{ $post->likes->count() }}</button>
                        </form>
                        <button class="action-btn" onclick="toggleCommentBox({{ $post->id }})">💬 {{ $post->comments->count() }}</button>
                        @if(Auth::id() === $post->account_id)
                            <form action="/posts/{{ $post->id }}" method="POST">
                                @csrf @method('DELETE')<button type="submit" class="action-btn" style="color:#f4212e;" onclick="return confirm('Hapus postingan?')">🗑️ Hapus</button>
                            </form>
                        @endif
                    </div>

                    <div class="comment-section">
                        @if($post->comments->count() > 0)
                            @foreach($post->comments->where('parent_id', null) as $comment)
                                <div class="comment-item">
                                    <div class="sidebar-avatar" style="width: 35px; height: 35px; font-size: 14px; margin: 0; flex-shrink: 0; background: {{ getAvatarGradient($comment->account_id) }};">
                                        {{ substr($comment->account->name ?? 'A', 0, 1) }}
                                    </div>
                                    <div class="comment-bubble">
                                        <div style="margin-bottom: 4px; display: flex; justify-content: space-between;">
                                            <div>
                                                <strong style="font-size: 0.95em; color: #0f1419;">{{ $comment->account->name ?? 'Anonim' }}</strong>
                                                <span class="post-meta" style="font-size: 0.85em;">• {{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            
                                            <div style="display: flex; gap: 10px; font-size: 0.85em;">
                                                @auth
                                                    <button onclick="toggleReplyBox({{ $comment->id }})" style="background: none; border: none; color: #1da1f2; cursor: pointer; padding: 0; font-weight: bold;">Balas</button>
                                                @endauth
                                                @if(Auth::id() === $comment->account_id)
                                                    <button onclick="toggleEditComment({{ $comment->id }})" style="background: none; border: none; color: #536471; cursor: pointer; padding: 0;">Edit</button>
                                                    <form action="/comments/{{ $comment->id }}" method="POST" style="margin: 0;">
                                                        @csrf @method('DELETE')<button type="submit" onclick="return confirm('Hapus komentar?')" style="background: none; border: none; color: #f4212e; cursor: pointer; padding: 0;">Hapus</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($comment->is_toxic)
                                            <div class="toxic-warning" id="warning-{{ $comment->id }}">
                                                <span>⚠️ Komentar berpotensi tidak pantas.</span>
                                                <button class="btn-reveal" onclick="revealToxic({{ $comment->id }})">Tampilkan</button>
                                            </div>
                                            <div id="comment-text-{{ $comment->id }}" style="font-size: 1em; color: #0f1419; display: none; margin-top: 8px;">{{ $comment->content }}</div>
                                        @else
                                            <div id="comment-text-{{ $comment->id }}" style="font-size: 1em; color: #0f1419;">{{ $comment->content }}</div>
                                        @endif

                                        @if(Auth::id() === $comment->account_id)
                                            <form action="/comments/{{ $comment->id }}" method="POST" id="edit-form-{{ $comment->id }}" style="display: none; margin-top: 10px; gap: 8px;">
                                                @csrf @method('PUT')
                                                <input type="text" name="content" value="{{ $comment->content }}" style="flex: 1; padding: 8px 15px; border: 1px solid #1da1f2; border-radius: 20px; outline: none;">
                                                <button type="submit" style="background: #1da1f2; color: white; border: none; padding: 6px 15px; border-radius: 20px; cursor: pointer; font-weight: bold;">Simpan</button>
                                            </form>
                                        @endif

                                        @auth
                                            <form action="/comments" method="POST" id="reply-form-{{ $comment->id }}" class="comment-form-container" style="display: none; margin-top: 10px; gap: 8px;">
                                                @csrf
                                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                <input type="text" name="content" class="comment-input" placeholder="Balas komentar {{ $comment->account->name }}..." required autocomplete="off">
                                                <button type="submit" style="background: #1da1f2; color: white; border: none; padding: 6px 15px; border-radius: 20px; cursor: pointer; font-weight: bold;">Kirim</button>
                                            </form>
                                        @endauth
                                    </div>
                                </div>

                                @if($comment->replies->count() > 0)
                                    <div class="reply-thread-wrapper">
                                        @foreach($comment->replies as $reply)
                                            <div class="comment-item" style="margin-bottom: 8px;">
                                                <div class="sidebar-avatar" style="width: 28px; height: 28px; font-size: 12px; margin: 0; flex-shrink: 0; background: {{ getAvatarGradient($reply->account_id) }};">
                                                    {{ substr($reply->account->name ?? 'A', 0, 1) }}
                                                </div>
                                                <div class="comment-bubble" style="background: #ffffff; padding: 10px 15px;">
                                                    <div style="margin-bottom: 4px; display: flex; justify-content: space-between;">
                                                        <div>
                                                            <strong style="font-size: 0.9em; color: #0f1419;">{{ $reply->account->name ?? 'Anonim' }}</strong>
                                                            <span class="post-meta" style="font-size: 0.8em;">• {{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        @if(Auth::id() === $reply->account_id)
                                                            <div style="display: flex; gap: 8px; font-size: 0.8em;">
                                                                <button onclick="toggleEditComment({{ $reply->id }})" style="background: none; border: none; color: #536471; cursor: pointer; padding: 0;">Edit</button>
                                                                <form action="/comments/{{ $reply->id }}" method="POST" style="margin: 0;">
                                                                    @csrf @method('DELETE')<button type="submit" onclick="return confirm('Hapus balasan?')" style="background: none; border: none; color: #f4212e; cursor: pointer; padding: 0;">Hapus</button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($reply->is_toxic)
                                                        <div class="toxic-warning" id="warning-{{ $reply->id }}" style="padding: 6px 10px;">
                                                            <span>⚠️ Potensi tidak pantas.</span>
                                                            <button class="btn-reveal" style="padding: 2px 8px;" onclick="revealToxic({{ $reply->id }})">Lihat</button>
                                                        </div>
                                                        <div id="comment-text-{{ $reply->id }}" style="font-size: 0.95em; color: #0f1419; display: none; margin-top: 5px;">{{ $reply->content }}</div>
                                                    @else
                                                        <div id="comment-text-{{ $reply->id }}" style="font-size: 0.95em; color: #0f1419;">{{ $reply->content }}</div>
                                                    @endif

                                                    @if(Auth::id() === $reply->account_id)
                                                        <form action="/comments/{{ $reply->id }}" method="POST" id="edit-form-{{ $reply->id }}" style="display: none; margin-top: 10px; gap: 8px;">
                                                            @csrf @method('PUT')
                                                            <input type="text" name="content" value="{{ $reply->content }}" style="flex: 1; padding: 6px 12px; border: 1px solid #1da1f2; border-radius: 20px; outline: none; font-size: 0.9em;">
                                                            <button type="submit" style="background: #1da1f2; color: white; border: none; padding: 6px 12px; border-radius: 20px; cursor: pointer; font-size: 0.85em; font-weight: bold;">Simpan</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        @auth
                        <form action="/comments" method="POST" id="comment-form-{{ $post->id }}" class="comment-form-container">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <div class="sidebar-avatar" style="width: 40px; height: 40px; font-size: 16px; margin: 0; flex-shrink: 0; background: {{ getAvatarGradient(Auth::id()) }};">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <input type="text" name="content" class="comment-input" placeholder="Balas postingan ini..." required autocomplete="off">
                            <button type="submit" style="background: none; border: none; color: #1da1f2; font-weight: bold; cursor: pointer; padding: 0 10px; font-size: 1em;">Balas</button>
                        </form>
                        @endauth
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<script>
    function toggleCommentBox(postId) {
        const formBox = document.getElementById('comment-form-' + postId);
        formBox.style.display = (formBox.style.display === 'flex') ? 'none' : 'flex';
        if (formBox.style.display === 'flex') formBox.querySelector('.comment-input').focus();
    }
    function toggleEditComment(commentId) {
        const textElement = document.getElementById('comment-text-' + commentId);
        const formElement = document.getElementById('edit-form-' + commentId);
        if (formElement.style.display === 'flex') {
            formElement.style.display = 'none';
            textElement.style.display = 'block';
        } else {
            formElement.style.display = 'flex';
            textElement.style.display = 'none';
            formElement.querySelector('input').focus();
        }
    }
    function toggleReplyBox(commentId) {
        const replyForm = document.getElementById('reply-form-' + commentId);
        replyForm.style.display = (replyForm.style.display === 'flex') ? 'none' : 'flex';
        if (replyForm.style.display === 'flex') replyForm.querySelector('.comment-input').focus();
    }
    
    function revealToxic(commentId) {
        document.getElementById('warning-' + commentId).style.display = 'none';
        document.getElementById('comment-text-' + commentId).style.display = 'block';
    }
</script>
</body>
</html>