<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Twitter Clone - Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f7f9fa; margin: 0; padding: 0; color: #0f1419; }
        .container { display: table; width: 100%; max-width: 1100px; margin: 0 auto; padding-top: 20px; }
        .row { display: table-row; }
        .sidebar { display: table-cell; width: 28%; vertical-align: top; padding-right: 20px; }
        .main-content { display: table-cell; width: 72%; vertical-align: top; background: white; border: 1px solid #e1e8ed; border-radius: 12px; padding: 20px; }
        .profile-card { background: white; border: 1px solid #e1e8ed; border-radius: 12px; padding: 15px; text-align: center; margin-bottom: 20px; }
        .profile-card img { width: 70px; height: 70px; border-radius: 50%; background: #ccc; }
        .nav-menu { list-style: none; padding: 0; margin: 0; }
        .nav-menu li { margin-bottom: 10px; }
        .nav-menu a { display: block; padding: 10px 15px; text-decoration: none; color: #0f1419; font-weight: bold; border-radius: 30px; transition: 0.2s; }
        .nav-menu a:hover { background-color: #e8f5fe; color: #1da1f2; }
        .feed-tabs { border-bottom: 1px solid #e1e8ed; margin-bottom: 20px; padding-bottom: 5px; }
        .tab-btn { text-decoration: none; font-weight: bold; padding: 10px 20px; color: #536471; display: inline-block; position: relative; }
        .tab-btn.active { color: #1da1f2; }
        .tab-btn.accepted::after { content: ''; display: block; width: 100%; height: 4px; background: #1da1f2; position: absolute; bottom: -6px; left: 0; border-radius: 2px; }
        .post-card { border-bottom: 1px solid #eff3f4; padding: 15px 0; }
        .post-card:last-child { border-bottom: none; }
        .btn-primary { background: #1da1f2; color: white; border: none; padding: 10px 20px; font-weight: bold; border-radius: 30px; cursor: pointer; }
        .btn-primary:hover { background: #1a91da; }
        .badge-request { background: #ff9800; color: white; padding: 2px 6px; font-size: 0.8em; border-radius: 10px; }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="sidebar">
            @auth
                <div class="profile-card">
                    <div style="font-weight: bold; font-size: 1.2em; margin-top: 10px;">{{ Auth::user()->name }}</div>
                    <div style="color: #536471;">@ {{ Auth::user()->username }}</div>
                    <hr style="border: 0; border-top: 1px solid #eff3f4; margin: 15px 0;">
                    <a href="/accounts/{{ Auth::id() }}" class="btn-primary" style="text-decoration:none; display:block; text-align:center;">Lihat Profil</a>
                </div>
            @endauth

            <ul class="nav-menu">
                <li><a href="/">🏠 Home</a></li>
                <li><a href="/accounts">🔍 Jelajahi Pengguna</a></li>
                <li><a href="/messages">✉️ Direct Messages</a></li>
                <li><a href="/communities">👥 Komunitas</a></li>
                <li><a href="/settings">⚙️ Settings</a></li>
                @auth
                    @php
                        $pendingRequests = \App\Models\Follow::where('following_id', Auth::id())->where('status', 'pending')->with('follower')->get();
                    @endphp
                    @if($pendingRequests->count() > 0)
                        <li style="background: #fff3e0; border-radius: 8px; padding: 10px; margin-top: 15px;">
                            <strong style="font-size: 0.9em; color: #ef6c00;">🔔 Request Follow ({{ $pendingRequests->count() }})</strong>
                            <ul style="padding-left: 15px; margin: 5px 0; font-size: 0.85em;">
                                @foreach($pendingRequests as $req)
                                    <li style="margin-bottom: 8px;">
                                        <strong>{{ $req->follower->name }}</strong> ingin mem-follow kamu.
                                        <div style="margin-top: 4px;">
                                            <form action="/follows/accept/{{ $req->follower_id }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" style="background:#4caf50; color:white; border:none; padding:2px 6px; border-radius:3px; cursor:pointer;">Terima</button>
                                            </form>
                                            <form action="/follows/reject/{{ $req->follower_id }}" method="POST" style="display:inline; margin-left: 4px;">
                                                @csrf
                                                <button type="submit" style="background:#f44336; color:white; border:none; padding:2px 6px; border-radius:3px; cursor:pointer;">Tolak</button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                    
                    <li style="margin-top: 20px;">
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit" style="width:100%; background:#f44336; color:white; border:none; padding:10px; border-radius:3px; font-weight:bold; cursor:pointer;">Logout</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>

        <div class="main-content">
            @if (session('success'))
                <p style="color: #2e7d32; background: #e8f5e9; padding: 10px; border-radius: 8px; font-weight: bold;">{{ session('success') }}</p>
            @endif

            @auth
                <form action="/posts" method="POST" style="margin-bottom: 30px;">
                    @csrf
                    <textarea name="content" rows="3" style="width: 100%; border: 1px solid #e1e8ed; border-radius: 8px; padding: 10px; font-size: 1.1em; resize: none; box-sizing: border-box;" placeholder="Apa yang sedang hangat hari ini?" required></textarea>
                    <div style="text-align: right; margin-top: 10px;">
                        <button type="submit" class="btn-primary">Posting</button>
                    </div>
                </form>
            @endauth

            <div class="feed-tabs">
                <a href="/" class="tab-btn {{ (!isset($feedType) || $feedType == 'global') ? 'active accepted' : '' }}">For You</a>
                @auth
                <a href="/?feed=following" class="tab-btn {{ (isset($feedType) && $feedType == 'following') ? 'active accepted' : '' }}">Following</a>
                @endauth
            </div>

            @if($posts->isEmpty())
                <p style="color: #536471; text-align: center; padding: 40px 0;">Belum ada postingan di timeline ini.</p>
            @else
                @foreach($posts as $post)
                    <div class="post-card">
                        <p style="margin: 0 0 8px 0;">
                            <strong><a href="/accounts/{{ $post->account->id }}" style="text-decoration:none; color:#0f1419;">{{ $post->account->name }}</a></strong> 
                            <span style="color: #536471;">@ {{ $post->account->username }} • {{ $post->created_at->diffForHumans() }}</span>
                        </p>
                        <p style="font-size: 1.05em; margin: 0 0 12px 0; line-height: 1.4;">{{ $post->content }}</p>
                        
                        <div style="font-size: 0.9em; color: #536471;">
                            <form action="/posts/{{ $post->id }}/like" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 1em;">
                                    {{ $post->isLikedBy(Auth::id()) ? '❤️' : '🤍' }} {{ $post->likes->count() }}
                                </button>
                            </form>

                            @if(Auth::id() === $post->account_id)
                                <span style="margin: 0 10px;">•</span>
                                <form action="/posts/{{ $post->id }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus?')" style="background: none; border: none; color: #f44336; cursor: pointer;">Hapus</button>
                                </form>
                            @endif
                        </div>

                        <div style="margin-top: 15px; padding-left: 20px; border-left: 2px solid #eff3f4;">
                            @if($post->comments->count() > 0)
                                @foreach($post->comments as $comment)
                                    <div style="background: #f7f9fa; padding: 8px 12px; border-radius: 8px; margin-bottom: 6px; font-size: 0.9em;">
                                        <strong>{{ $comment->account->name ?? 'Anonim' }}</strong> 
                                        <span style="color:gray; font-size:0.85em;">• {{ $comment->created_at->diffForHumans() }}</span>
                                        <div style="margin-top: 4px;">{{ $comment->content }}</div>
                                    </div>
                                @endforeach
                            @endif

                            @auth
                            <form action="/comments" method="POST" style="margin-top: 8px;">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <input type="text" name="content" placeholder="Balas komentar..." required style="width: 75%; padding: 6px; border: 1px solid #e1e8ed; border-radius: 20px; padding-left: 15px;">
                                <button type="submit" style="background:#1da1f2; color:white; border:none; padding:6px 12px; border-radius:20px; font-weight:bold; cursor:pointer; margin-left:5px;">Kirim</button>
                            </form>
                            @endauth
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

</body>
</html>