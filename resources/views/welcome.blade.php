<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Twitter</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f7f9fa; margin: 0; padding: 0; color: #0f1419; }
        .app-container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; padding-top: 20px; }
        
        .sidebar { width: 30%; position: sticky; top: 20px; height: max-content; padding-right: 25px; box-sizing: border-box; }
        .brand { font-size: 2.2em; font-weight: 900; color: #1da1f2; margin-bottom: 20px; padding-left: 10px; letter-spacing: -1px; display: flex; align-items: center; justify-content: space-between; }
        .bell-icon { font-size: 0.55em; color: #536471; background: #eff3f4; padding: 10px; border-radius: 50%; cursor: pointer; transition: 0.2s; position: relative; }
        .bell-icon:hover { background: #e8f5fe; color: #1da1f2; }
        .bell-dot { position: absolute; top: 5px; right: 5px; width: 8px; height: 8px; background: #f4212e; border-radius: 50%; }
        
        .sidebar-card { background: white; border: 1px solid #eff3f4; border-radius: 20px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .sidebar-profile { text-align: center; border-bottom: 1px solid #eff3f4; padding-bottom: 25px; margin-bottom: 20px; position: relative; }
        
        .profile-avatar-wrapper { position: relative; width: 100px; height: 100px; margin: 0 auto 15px auto; }
        .sidebar-avatar { width: 100%; height: 100%; border-radius: 50%; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold; font-size: 35px; text-transform: uppercase; transition: 0.2s; position: relative; z-index: 2; border: 3px solid white; box-sizing: border-box; }
        .sidebar-avatar:hover { opacity: 0.8; }
        .story-ring { position: absolute; top: -3px; left: -3px; right: -3px; bottom: -3px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); border-radius: 50%; z-index: 1; }
        .status-bubble-sidebar { position: absolute; bottom: 0; right: -10px; background: white; border: 1px solid #eff3f4; padding: 4px 10px; border-radius: 20px; font-size: 11px; color: #0f1419; box-shadow: 0 2px 10px rgba(0,0,0,0.08); font-weight: 600; z-index: 20; max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer; }

        .sidebar-name { font-weight: 800; font-size: 1.3em; color: #0f1419; margin-bottom: 5px; }
        .sidebar-username { color: #536471; font-size: 1em; margin-bottom: 15px; }
        .sidebar-stats { display: flex; justify-content: center; gap: 20px; font-size: 0.9em; color: #536471; }
        .stat-link { cursor: pointer; transition: 0.2s; padding: 5px 10px; border-radius: 10px; }
        .stat-link:hover { background: #f7f9fa; color: #0f1419; }
        .sidebar-stats strong { color: #0f1419; font-size: 1.2em; display: block; }
        
        .btn-view-profile { display: block; width: 100%; background: #0f1419; color: white; padding: 12px 0; border-radius: 30px; text-decoration: none; font-weight: bold; margin-top: 20px; transition: 0.2s; font-size: 1em; }
        .btn-view-profile:hover { background: #272c30; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

        .nav-menu { list-style: none; padding: 0; margin: 0; }
        .nav-menu li { margin-bottom: 5px; }
        .nav-menu a { display: flex; align-items: center; gap: 15px; padding: 12px 15px; text-decoration: none; color: #0f1419; font-size: 1.1em; border-radius: 30px; transition: background 0.2s; font-weight: 600; }
        .nav-menu a:hover { background-color: #e8f5fe; color: #1da1f2; }
        
        .btn-logout { width: 100%; background: white; color: #f4212e; border: 1px solid #f4212e; padding: 12px; border-radius: 30px; font-weight: bold; font-size: 1em; cursor: pointer; margin-top: 15px; transition: 0.2s; }
        .btn-logout:hover { background: #fdeced; }

        .main-feed { width: 68%; background: white; border: 1px solid #eff3f4; border-radius: 20px; min-height: 100vh; padding-bottom: 50px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.01); }
        .feed-header { position: sticky; top: 0; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); z-index: 10; border-bottom: 1px solid #eff3f4; }
        .page-title { font-size: 1.4em; font-weight: 900; padding: 20px 15px 10px 15px; margin: 0; color: #0f1419; text-align: center; } 
        .feed-tabs { display: flex; padding: 0 15px; }
        .tab-item { flex: 1; text-align: center; padding: 15px 0; text-decoration: none; color: #536471; font-weight: 700; transition: 0.2s; position: relative; font-size: 1.05em; }
        .tab-item:hover { background-color: #f7f9fa; color: #0f1419; border-radius: 8px 8px 0 0; }
        .tab-item.active { color: #0f1419; }
        .tab-item.active::after { content: ''; position: absolute; bottom: 0; left: 25%; width: 50%; height: 4px; background-color: #1da1f2; border-radius: 4px 4px 0 0; }

        .compose-area { padding: 25px; background-color: white; border-bottom: 8px solid #eff3f4; display: flex; gap: 20px; }
        .compose-input-wrapper { flex: 1; border: 1px solid #cfd9de; border-radius: 16px; padding: 10px 15px; transition: 0.2s; background: #fcfdfe; }
        .compose-input-wrapper:focus-within { border-color: #1da1f2; box-shadow: 0 0 0 1px #1da1f2; background: white; }
        .compose-input { width: 100%; box-sizing: border-box; border: none; font-size: 1.15em; outline: none; resize: none; background: transparent; color: #0f1419; font-family: inherit; }
        .compose-input::placeholder { color: #8899a6; font-weight: 500; }
        .compose-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; border-top: 1px solid #eff3f4; padding-top: 10px; }
        
        .btn-post { background: #1da1f2; color: white; border: none; padding: 8px 24px; font-weight: bold; border-radius: 30px; cursor: pointer; font-size: 1em; transition: 0.2s; }
        .btn-post:hover { background: #1a91da; }
        .btn-emoji { background: none; border: none; font-size: 1.2em; cursor: pointer; border-radius: 50%; padding: 5px; transition: 0.2s; opacity: 0.7; }
        .btn-emoji:hover { background: #e8f5fe; opacity: 1; }

        .post-card { border-bottom: 8px solid #eff3f4; transition: 0.2s; position: relative; }
        .post-inner { padding: 25px 25px 15px 25px; background: white; }
        .post-card:hover .post-inner { background: #fdfdfe; }
        .my-post { border-left: 4px solid #1da1f2; }
        .my-post-badge { position: absolute; top: 25px; right: 25px; background: #e8f5fe; color: #1da1f2; font-size: 0.75em; font-weight: bold; padding: 4px 10px; border-radius: 12px; }

        .post-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
        .post-name { font-weight: 800; color: #0f1419; text-decoration: none; font-size: 1.1em; display: inline-block; }
        .post-name:hover { text-decoration: underline; }
        .post-username { color: #536471; font-size: 0.9em; }
        .post-time { color: #536471; font-size: 0.9em; margin-left: 5px; }
        
        .btn-feed-follow { background: #1da1f2; color: white; border: none; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 0.85em; cursor: pointer; transition: 0.2s; }
        .btn-feed-follow:hover { background: #1a91da; }

        .post-content { font-size: 1.15em; line-height: 1.6; margin-bottom: 18px; margin-top: 15px; color: #0f1419; }
        
        .post-actions { display: flex; gap: 15px; color: #536471; font-size: 0.95em; align-items: center; border-top: 1px solid #eff3f4; padding-top: 12px; margin-top: 15px; }
        .action-pill { background: #f7f9fa; border: 1px solid #eff3f4; color: inherit; cursor: pointer; font-size: 0.95em; font-weight: 600; display: flex; align-items: center; gap: 7px; padding: 8px 16px; border-radius: 20px; transition: 0.2s; }
        .action-pill:hover { background: #e8f5fe; color: #1da1f2; border-color: #cfd9de; }
        .action-pill.like:hover { background: #fce8f3; color: #f91880; border-color: #f91880; }
        .action-pill.delete:hover { background: #fdeced; color: #f4212e; border-color: #f4212e; }

        .comment-section-wrapper { display: none; background: #fcfdfe; border-top: 1px solid #eff3f4; }
        .comment-list-area { padding: 15px 25px; }
        .comment-item { display: flex; gap: 12px; margin-bottom: 20px; position: relative; }
        .comment-avatar { flex-shrink: 0; margin-top: 5px; }
        .comment-bubble { background: #f7f9fa; padding: 15px 20px; border-radius: 16px; flex: 1; border: 1px solid #eff3f4; }
        
        .reply-thread-wrapper { margin-left: 55px; border-left: 2px solid #e1e8ed; padding-left: 15px; margin-top: -10px; margin-bottom: 15px; display: none; }
        .btn-toggle-replies { background: none; border: none; color: #1da1f2; font-weight: bold; cursor: pointer; font-size: 0.85em; padding: 0; margin-left: 55px; margin-bottom: 20px; display: inline-block; }

        .author-badge { font-size: 0.75em; background: #fce8f3; color: #f91880; padding: 2px 6px; border-radius: 10px; margin-left: 6px; vertical-align: text-top; font-weight: bold; border: 1px solid #f91880; }

        .comment-action-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; width: 100%; }
        .comment-meta-left { flex: 1; }
        .comment-buttons-right { display: flex; gap: 15px; align-items: center; font-size: 0.9em; }
        
        .c-btn { background: none; border: none; cursor: pointer; font-weight: bold; transition: 0.2s; padding: 0; display: flex; align-items: center; gap: 4px; font-size: 1em; }
        .c-btn-reply { color: #1da1f2; }
        .c-btn-reply:hover { text-decoration: underline; }
        .c-btn-like { color: #536471; filter: grayscale(100%); opacity: 0.6; } 
        .c-btn-like.active { filter: none; opacity: 1; color: #f91880; } 
        .c-btn-dislike { transform: scaleY(-1); margin-top: -4px; color: #536471; filter: grayscale(100%); opacity: 0.6; font-size: 1.1em;} 
        .c-btn-dislike.active { filter: none; opacity: 1; color: #8899a6; } 

        .comment-edit-row { display: flex; gap: 15px; margin-top: 10px; font-size: 0.85em; border-top: 1px dashed #cfd9de; padding-top: 8px; }
        .btn-text-action { background: none; border: none; color: #536471; cursor: pointer; padding: 0; font-weight: 600; }
        .btn-text-action:hover { text-decoration: underline; }
        .btn-text-danger { color: #f4212e; }

        .comment-form-container { display: none; gap: 12px; margin-top: 15px; padding: 15px; background: white; border-radius: 16px; border: 1px solid #cfd9de; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        .comment-input { flex: 1; padding: 8px 0; border: none; outline: none; background: transparent; font-size: 1em; color: #0f1419; font-family: inherit;}
        .btn-send { background: #1da1f2; color: white; border: none; padding: 6px 16px; border-radius: 20px; cursor: pointer; font-weight: bold; font-size: 0.9em; transition: 0.2s;}
        .btn-send:hover { background: #1a91da; }

        /* WRITE REPLY BAR AT BOTTOM OF POST */
        .write-reply-bar { padding: 15px 25px; background: white; border-top: 1px solid #eff3f4; display: flex; gap: 12px; align-items: center; }

        .alert-box { background: #fff3e0; border: 1px solid #ffe0b2; padding: 15px; border-radius: 16px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(239,108,0,0.05); }
        .alert-title { color: #ef6c00; font-weight: bold; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; font-size: 1em; }

        .toxic-warning { background: #fdf2f2; border: 1px solid #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 12px; font-size: 0.95em; display: flex; justify-content: space-between; align-items: center; margin-top: 5px; }
        .btn-reveal { background: white; border: 1px solid #f5c6cb; color: #721c24; padding: 5px 12px; border-radius: 20px; cursor: pointer; font-weight: bold; font-size: 0.85em; transition: 0.2s; }
        .btn-reveal:hover { background: #f8d7da; }

        .action-menu-dropdown { display: none; position: absolute; right: 0; top: 30px; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.15); border-radius: 16px; z-index: 100; min-width: 220px; padding: 5px 0; overflow: hidden; border: 1px solid #eff3f4; }
        .action-menu-dropdown a, .action-menu-dropdown button { display: block; width: 100%; padding: 12px 20px; color: #0f1419; text-decoration: none; font-size: 0.95em; font-weight: 600; border: none; background: none; text-align: left; transition: 0.2s; cursor: pointer; font-family: inherit; }
        .action-menu-dropdown a:hover, .action-menu-dropdown button:hover { background: #f7f9fa; }
        .action-menu-dropdown .text-danger { color: #f4212e; }
        .action-menu-dropdown .text-danger:hover { background: #fdeced; }
        .highlight-user { color: #1da1f2; font-weight: 800; }
        
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); }
        .modal-content { background-color: white; margin: 5% auto; padding: 0; border-radius: 24px; width: 90%; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); display: flex; flex-direction: column; min-height: 50vh; }
        .modal-header { font-size: 1.2em; font-weight: 900; padding: 20px; text-align: center; border-bottom: 1px solid #eff3f4; position: relative; flex-shrink: 0; }
        .close-btn { position: absolute; right: 20px; top: 18px; font-size: 1.5em; cursor: pointer; color: #0f1419; font-weight: bold; }
        .modal-user-list { list-style: none; padding: 0; margin: 0; max-height: 60vh; overflow-y: auto; flex: 1; }
        .modal-user-list li { padding: 15px 20px; border-bottom: 1px solid #f7f9fa; display: flex; justify-content: space-between; align-items: center; transition: 0.2s; }
        .modal-user-list li:hover { background: #fcfdfe; }
        .modal-user-list a { text-decoration: none; color: #0f1419; font-weight: bold; font-size: 1.05em; }
    </style>
</head>
<body>

@php
    function getAvatarGradient($id) {
        $gradients = [
            'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)', 'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)',
            'linear-gradient(135deg, #fccb90 0%, #d57eeb 100%)', 'linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%)',
            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)', 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)', 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)'
        ];
        return $gradients[$id % count($gradients)];
    }
    
    $me = Auth::user();
    $myFollowingList = $me ? $me->following()->where('status', 'accepted')->pluck('following_id')->toArray() : [];
    $followersOfMeList = $me ? $me->followers()->where('status', 'accepted')->pluck('follower_id')->toArray() : [];
    
    $hasMyStory = false; 
    $myBubble = $me->active_status ?? null;
@endphp

<div class="app-container">
    <div class="sidebar">
        <div class="brand">
            Twitter 
            <span class="bell-icon" title="Notifications">🔔<div class="bell-dot"></div></span>
        </div>
        
        <div class="sidebar-card">
            @auth
                <div class="sidebar-profile">
                    <div class="profile-avatar-wrapper">
                        @if($hasMyStory) <div class="story-ring"></div> @endif
                        <a href="/accounts/{{ Auth::id() }}" style="text-decoration: none;">
                            <div class="sidebar-avatar" style="background: {{ getAvatarGradient(Auth::id()) }};">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </a>
                        @if($myBubble)
                            <div class="status-bubble-sidebar" onclick="window.location.href='/accounts/{{ Auth::id() }}'">💭 {{ $myBubble }}</div>
                        @endif
                    </div>
                    
                    <div class="sidebar-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-username">@ {{ Auth::user()->username }}</div>
                    
                    <div class="sidebar-stats">
                        <div class="stat-link" onclick="document.getElementById('sidebarFollowersModal').style.display='flex'">
                            <strong>{{ Auth::user()->followers()->where('status', 'accepted')->count() }}</strong> Followers
                        </div>
                        <div class="stat-link" onclick="document.getElementById('sidebarFollowingModal').style.display='flex'">
                            <strong>{{ Auth::user()->following()->where('status', 'accepted')->count() }}</strong> Following
                        </div>
                    </div>
                    <a href="/accounts/{{ Auth::id() }}" class="btn-view-profile">View My Profile</a>
                </div>

                @php $pendingRequests = \App\Models\Follow::where('following_id', Auth::id())->where('status', 'pending')->with('follower')->get(); @endphp
                @if($pendingRequests->count() > 0)
                    <div class="alert-box">
                        <div class="alert-title">🔔 Pending Requests ({{ $pendingRequests->count() }})</div>
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
                <li><a href="/" onclick="if(window.location.pathname=='/') { window.scrollTo({top: 0, behavior: 'smooth'}); return false; }">🏠 Home</a></li>
                <li><a href="/accounts">🔍 Explore Users</a></li>
                <li><a href="/messages">✉️ Messages</a></li>
                <li><a href="/communities">👥 Communities</a></li>
                <li><a href="/settings">⚙️ Settings</a></li>
            </ul>

            @auth
                <form action="/logout" method="POST">
                    @csrf<button type="submit" class="btn-logout">Logout</button>
                </form>
            @endauth
        </div>
    </div>

    <div class="main-feed">
        <div class="feed-header">
            <h2 class="page-title">Home</h2>
            <div class="feed-tabs">
                <a href="/" class="tab-item {{ (!isset($feedType) || $feedType == 'global') ? 'active' : '' }}">For You</a>
                @auth<a href="/?feed=following" class="tab-item {{ (isset($feedType) && $feedType == 'following') ? 'active' : '' }}">Following</a>@endauth
            </div>
        </div>

        @if (session('success')) <div style="background: #e8f5e9; color: #2e7d32; padding: 18px 25px; font-weight: bold; border-bottom: 1px solid #c8e6c9;">✓ {{ session('success') }}</div> @endif
        @if ($errors->any()) <div style="background: #ffebee; color: #c62828; padding: 18px 25px; font-weight: bold; border-bottom: 1px solid #ffcdd2;">✗ {{ $errors->first() }}</div> @endif

        @auth
            @php
                $placeholders = ['What is happening?!', 'Share your thoughts today...', 'Voice your opinion!', 'Tweet your ideas!'];
                $randomPlaceholder = $placeholders[array_rand($placeholders)];
            @endphp
            <div class="compose-area">
                <a href="/accounts/{{ Auth::id() }}">
                    <div class="sidebar-avatar" style="width: 50px; height: 50px; font-size: 18px; margin: 0; background: {{ getAvatarGradient(Auth::id()) }}; border:none;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </a>
                <form action="/posts" method="POST" style="flex: 1;">
                    @csrf
                    <div class="compose-input-wrapper">
                        <textarea name="content" class="compose-input" rows="2" placeholder="{{ $randomPlaceholder }}" maxlength="350" oninput="updateCount(this)" required></textarea>
                        <div class="compose-actions">
                            <button type="button" class="btn-emoji" title="Add Emoji">😀</button>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <span id="charCount" style="color: #536471; font-size: 0.85em; font-weight: bold;">0/350</span>
                                <button type="submit" class="btn-post">Post</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endauth

        @if($posts->isEmpty())
            <div style="text-align: center; color: #536471; padding: 60px 25px;">
                <h2>Welcome</h2>
                <p>Your timeline is empty.</p>
            </div>
        @else
            @foreach($posts as $post)
                @php
                    $isMyPost = Auth::id() === $post->account_id;
                    $amFollowingPost = in_array($post->account_id, $myFollowingList ?? []);
                    $postFollowingMe = in_array($post->account_id, $followersOfMeList ?? []);
                @endphp
                <div class="post-card {{ $isMyPost ? 'my-post' : '' }}" id="post-card-{{$post->id}}">
                    <div class="post-inner">
                        @if($isMyPost) <div class="my-post-badge">Your Post</div> @endif

                        <div class="post-header">
                            <div style="display: flex; gap: 12px; align-items: flex-start;">
                                <a href="/accounts/{{ $post->account->id }}" style="text-decoration: none;">
                                    <div class="sidebar-avatar" style="width: 48px; height: 48px; font-size: 16px; margin: 0; background: {{ getAvatarGradient($post->account_id) }}; border:none;">
                                        {{ substr($post->account->name, 0, 1) }}
                                    </div>
                                </a>
                                <div style="padding-top: 2px;">
                                    <a href="/accounts/{{ $post->account->id }}" class="post-name">{{ $post->account->name }}</a>
                                    <div style="margin-top: 2px;">
                                        <span class="post-username">@ {{ $post->account->username }}</span>
                                        <span class="post-time">• {{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if(!$isMyPost)
                                <div style="display: flex; align-items: center; gap: 12px; padding-top: 5px;">
                                    @if(!$amFollowingPost)
                                        <form action="/follows" method="POST" style="margin: 0;">
                                            @csrf <input type="hidden" name="following_id" value="{{ $post->account_id }}">
                                            <button type="submit" class="btn-feed-follow">{{ $postFollowingMe ? 'Follow Back' : 'Follow' }}</button>
                                        </form>
                                    @endif
                                    <div style="position: relative;">
                                        <button onclick="toggleActionMenu(event, 'drop-post-{{$post->id}}')" style="background: none; border: none; font-size: 1.4em; cursor: pointer; color: #536471; font-weight: bold; line-height: 1; padding: 0 5px;">⋮</button>
                                        <div id="drop-post-{{$post->id}}" class="action-menu-dropdown action-menu-popup">
                                            @if($amFollowingPost)
                                                <form action="/follows/{{ $post->account_id }}" method="POST" style="margin:0;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit">Unfollow <span class="highlight-user">@ {{ $post->account->username }}</span></button>
                                                </form>
                                            @endif
                                            <a href="/messages/{{ $post->account_id }}">💬 Direct Message</a>
                                            <button onclick="document.getElementById('post-card-{{$post->id}}').style.display='none'">👁️‍🗨️ Hide this post</button>
                                            <div style="height: 1px; background: #eff3f4; margin: 5px 0;"></div>
                                            <a href="#" class="text-danger">🚫 Block / Report</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="post-content">{{ $post->content }}</div>
                        
                        <div class="post-actions">
                            <form action="/posts/{{ $post->id }}/like" method="POST" style="margin:0;">
                                @csrf<button type="submit" class="action-pill like">{{ $post->isLikedBy(Auth::id()) ? '❤️' : '🤍' }} {{ $post->likes->count() }}</button>
                            </form>
                            <button class="action-pill" onclick="toggleComments({{ $post->id }})">💬 {{ $post->comments->count() }} Comments</button>
                            @if($isMyPost)
                                <form action="/posts/{{ $post->id }}" method="POST" style="margin:0; margin-left: auto;">
                                    @csrf @method('DELETE')<button type="submit" class="action-pill delete" onclick="return confirm('Delete post?')">🗑️ Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="comment-section-wrapper" id="comment-wrapper-{{ $post->id }}">
                        @auth
                        <div class="write-reply-bar">
                            <a href="/accounts/{{ Auth::id() }}">
                                <div class="sidebar-avatar" style="width: 35px; height: 35px; font-size: 14px; margin: 0; background: {{ getAvatarGradient(Auth::id()) }}; border:none;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </a>
                            <form action="/comments" method="POST" style="flex: 1; display: flex; gap: 10px; background: #f7f9fa; border-radius: 20px; padding: 5px 5px 5px 15px; border: 1px solid #eff3f4;">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <input type="text" name="content" class="comment-input" placeholder="Post your reply..." required autocomplete="off" style="padding: 5px 0;">
                                <button type="button" class="btn-emoji" title="Add Emoji">😀</button>
                                <button type="submit" class="btn-send">Send</button>
                            </form>
                        </div>
                        @endauth

                        <div class="comment-list-area">
                            @if($post->comments->count() > 0)
                                @foreach($post->comments->where('parent_id', null) as $comment)
                                    @php 
                                        $isMyComment = Auth::id() === $comment->account_id;
                                        // TODO: Uncomment ini kalau fungsi likes() di Comment model sudah dibuat
                                        // $isLikedByCreator = $comment->likes()->where('account_id', $post->account_id)->exists();
                                        // $commentLikesCount = $comment->likes()->count();
                                        $isLikedByCreator = false; 
                                        $commentLikesCount = 0; 
                                    @endphp
                                    <div class="comment-item">
                                        <div class="comment-avatar">
                                            <a href="/accounts/{{ $comment->account_id }}" style="text-decoration: none;">
                                                <div class="sidebar-avatar" style="width: 40px; height: 40px; font-size: 15px; margin: 0; background: {{ getAvatarGradient($comment->account_id) }}; border:none;">
                                                    {{ substr($comment->account->name ?? 'A', 0, 1) }}
                                                </div>
                                            </a>
                                        </div>
                                        <div class="comment-bubble">
                                            <div class="comment-action-row">
                                                <div class="comment-meta-left">
                                                    <a href="/accounts/{{ $comment->account_id }}" style="font-weight: bold; color: #0f1419; text-decoration: none; font-size: 1.05em;">{{ $comment->account->name ?? 'Anonymous' }}</a>
                                                    @if($isLikedByCreator)
                                                        <span class="author-badge">❤️ Liked by Author</span>
                                                    @endif
                                                    <div style="color: #536471; font-size: 0.9em; margin-top: 2px;">
                                                        @ {{ $comment->account->username ?? 'user' }} • {{ $comment->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                                
                                                <div class="comment-buttons-right">
                                                    @auth
                                                        <button class="c-btn c-btn-reply" onclick="toggleReplyBox({{ $comment->id }})">Reply</button>
                                                    @endauth
                                                    <button class="c-btn c-btn-like" onclick="toggleSimulateLike(this)">🤍 <span class="c-count">{{ $commentLikesCount }}</span></button>
                                                    @if(!$isMyComment)
                                                        <button class="c-btn c-btn-dislike" onclick="this.classList.toggle('active')" title="Dislike">👍</button>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if($comment->is_toxic)
                                                <div class="toxic-warning" id="warning-{{ $comment->id }}">
                                                    <span>⚠️ Potentially inappropriate.</span>
                                                    <button class="btn-reveal" onclick="revealToxic({{ $comment->id }})">Show</button>
                                                </div>
                                                <div id="comment-text-{{ $comment->id }}" style="font-size: 1.05em; color: #0f1419; display: none; margin-top: 8px;">{{ $comment->content }}</div>
                                            @else
                                                <div id="comment-text-{{ $comment->id }}" style="font-size: 1.05em; color: #0f1419; margin-top: 5px;">{{ $comment->content }}</div>
                                            @endif

                                            @if($isMyComment)
                                                <div class="comment-edit-row">
                                                    <button onclick="toggleEditComment({{ $comment->id }})" class="btn-text-action">Edit</button>
                                                    <form action="/comments/{{ $comment->id }}" method="POST" style="margin: 0;">
                                                        @csrf @method('DELETE')<button type="submit" onclick="return confirm('Delete comment?')" class="btn-text-action btn-text-danger">Delete</button>
                                                    </form>
                                                </div>
                                                <form action="/comments/{{ $comment->id }}" method="POST" id="edit-form-{{ $comment->id }}" style="display: none; margin-top: 10px; gap: 8px;">
                                                    @csrf @method('PUT')
                                                    <input type="text" name="content" value="{{ $comment->content }}" style="flex: 1; padding: 8px 15px; border: 1px solid #1da1f2; border-radius: 20px; outline: none;">
                                                    <button type="submit" style="background: #1da1f2; color: white; border: none; padding: 6px 15px; border-radius: 20px; cursor: pointer; font-weight: bold;">Save</button>
                                                </form>
                                            @endif

                                            @auth
                                                <form action="/comments" method="POST" id="reply-form-{{ $comment->id }}" class="comment-form-container">
                                                    @csrf
                                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                    <button type="button" class="btn-emoji" title="Add Emoji">😀</button>
                                                    <input type="text" name="content" class="comment-input" placeholder="Reply to {{ $comment->account->name }}..." required autocomplete="off">
                                                    <button type="submit" class="btn-send">Reply</button>
                                                </form>
                                            @endauth
                                        </div>
                                    </div>

                                    @if($comment->replies->count() > 0)
                                        <button class="btn-toggle-replies" onclick="toggleRepliesThread({{ $comment->id }}, this)">
                                            — View {{ $comment->replies->count() }} replies
                                        </button>
                                        <div class="reply-thread-wrapper" id="thread-{{ $comment->id }}">
                                            @foreach($comment->replies as $reply)
                                                @php $isMyReply = Auth::id() === $reply->account_id; @endphp
                                                <div class="comment-item" style="margin-bottom: 12px;">
                                                    <div class="comment-avatar">
                                                        <a href="/accounts/{{ $reply->account_id }}" style="text-decoration: none;">
                                                            <div class="sidebar-avatar" style="width: 32px; height: 32px; font-size: 13px; margin: 0; background: {{ getAvatarGradient($reply->account_id) }}; border:none;">
                                                                {{ substr($reply->account->name ?? 'A', 0, 1) }}
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="comment-bubble" style="background: #ffffff; padding: 12px 16px;">
                                                        <div class="comment-action-row">
                                                            <div class="comment-meta-left">
                                                                <a href="/accounts/{{ $reply->account_id }}" style="font-weight: bold; color: #0f1419; text-decoration: none;">{{ $reply->account->name ?? 'Anonymous' }}</a>
                                                                <div style="color: #536471; font-size: 0.8em; margin-top: 2px;">@ {{ $reply->account->username ?? 'user' }} • {{ $reply->created_at->diffForHumans() }}</div>
                                                            </div>
                                                            <div class="comment-buttons-right">
                                                                <button class="c-btn c-btn-like" onclick="toggleSimulateLike(this)">🤍 <span class="c-count">0</span></button>
                                                                @if(!$isMyReply)
                                                                    <button class="c-btn c-btn-dislike" onclick="this.classList.toggle('active')" title="Dislike">👍</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        @if($reply->is_toxic)
                                                            <div class="toxic-warning" id="warning-{{ $reply->id }}" style="padding: 6px 10px;">
                                                                <span>⚠️ Potentially inappropriate.</span>
                                                                <button class="btn-reveal" style="padding: 2px 8px;" onclick="revealToxic({{ $reply->id }})">Show</button>
                                                            </div>
                                                            <div id="comment-text-{{ $reply->id }}" style="font-size: 0.95em; color: #0f1419; display: none; margin-top: 5px;">{{ $reply->content }}</div>
                                                        @else
                                                            <div id="comment-text-{{ $reply->id }}" style="font-size: 0.95em; color: #0f1419; margin-top: 5px;">{{ $reply->content }}</div>
                                                        @endif

                                                        @if($isMyReply)
                                                            <div class="comment-edit-row">
                                                                <button onclick="toggleEditComment({{ $reply->id }})" class="btn-text-action">Edit</button>
                                                                <form action="/comments/{{ $reply->id }}" method="POST" style="margin: 0;">
                                                                    @csrf @method('DELETE')<button type="submit" onclick="return confirm('Delete reply?')" class="btn-text-action btn-text-danger">Delete</button>
                                                                </form>
                                                            </div>
                                                            <form action="/comments/{{ $reply->id }}" method="POST" id="edit-form-{{ $reply->id }}" style="display: none; margin-top: 10px; gap: 8px;">
                                                                @csrf @method('PUT')
                                                                <input type="text" name="content" value="{{ $reply->content }}" style="flex: 1; padding: 6px 12px; border: 1px solid #1da1f2; border-radius: 20px; outline: none; font-size: 0.9em;">
                                                                <button type="submit" style="background: #1da1f2; color: white; border: none; padding: 6px 12px; border-radius: 20px; cursor: pointer; font-size: 0.85em; font-weight: bold;">Save</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@auth
<div id="sidebarFollowersModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-btn" onclick="document.getElementById('sidebarFollowersModal').style.display='none'">×</span>
            Followers
        </div>
        <ul class="modal-user-list">
            @php $myFollowersList = Auth::user()->followers()->where('status', 'accepted')->with('follower')->get(); @endphp
            @if($myFollowersList->isEmpty()) <li style="justify-content: center; color: gray;">No followers yet.</li>
            @else
                @foreach($myFollowersList as $f)
                    <li>
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <a href="/accounts/{{ $f->follower->id }}" style="text-decoration: none;">
                                <div class="sidebar-avatar" style="width: 44px; height: 44px; font-size: 16px; margin: 0; background: {{ getAvatarGradient($f->follower->id) }}; border:none;">
                                    {{ substr($f->follower->name, 0, 1) }}
                                </div>
                            </a>
                            <div style="flex: 1;">
                                <a href="/accounts/{{ $f->follower->id }}" style="display: block; font-weight:bold; color:#0f1419; text-decoration:none;">{{ $f->follower->name }}</a>
                                <div style="color: #536471; font-size: 0.9em;">@ {{ $f->follower->username }}</div>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>

<div id="sidebarFollowingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-btn" onclick="document.getElementById('sidebarFollowingModal').style.display='none'">×</span>
            Following
        </div>
        <ul class="modal-user-list">
            @php $myFollowingData = Auth::user()->following()->where('status', 'accepted')->with('following')->get(); @endphp
            @if($myFollowingData->isEmpty()) <li style="justify-content: center; color: gray;">Not following anyone yet.</li>
            @else
                @foreach($myFollowingData as $g)
                    <li>
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <a href="/accounts/{{ $g->following->id }}" style="text-decoration: none;">
                                <div class="sidebar-avatar" style="width: 44px; height: 44px; font-size: 16px; margin: 0; background: {{ getAvatarGradient($g->following->id) }}; border:none;">
                                    {{ substr($g->following->name, 0, 1) }}
                                </div>
                            </a>
                            <div style="flex: 1;">
                                <a href="/accounts/{{ $g->following->id }}" style="display: block; font-weight:bold; color:#0f1419; text-decoration:none;">{{ $g->following->name }}</a>
                                <div style="color: #536471; font-size: 0.9em;">@ {{ $g->following->username }}</div>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>
@endauth

<script>
    function updateCount(textarea) {
        const count = textarea.value.length;
        const display = textarea.parentElement.querySelector('#charCount') || document.getElementById('charCount');
        if(display) {
            display.innerText = count + "/350";
            display.style.color = count > 339 ? '#f4212e' : '#536471';
        }
    }

    function toggleComments(postId) {
        const wrapper = document.getElementById('comment-wrapper-' + postId);
        wrapper.style.display = (wrapper.style.display === 'block') ? 'none' : 'block';
    }

    function toggleRepliesThread(commentId, btnElement) {
        const thread = document.getElementById('thread-' + commentId);
        if(thread.style.display === 'block') {
            thread.style.display = 'none';
            btnElement.innerText = "— View replies";
        } else {
            thread.style.display = 'block';
            btnElement.innerText = "— Hide replies";
        }
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

    function toggleActionMenu(event, menuId) {
        event.stopPropagation(); 
        document.querySelectorAll('.action-menu-popup').forEach(menu => {
            if(menu.id !== menuId) menu.style.display = 'none';
        });
        const menu = document.getElementById(menuId);
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    }

    window.onclick = function(e) { 
        if (e.target.classList.contains('modal')) e.target.style.display = "none";
        if (!e.target.matches('.action-menu-popup') && !e.target.closest('button')) {
            document.querySelectorAll('.action-menu-popup').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    }

    function toggleSimulateLike(btn) {
        const isActive = btn.classList.contains('active');
        let countSpan = btn.querySelector('.c-count');
        let current = parseInt(countSpan.innerText);
        
        if (isActive) {
            btn.classList.remove('active');
            btn.innerHTML = `🤍 <span class="c-count">${current - 1}</span>`;
        } else {
            btn.classList.add('active');
            btn.innerHTML = `❤️ <span class="c-count">${current + 1}</span>`;
        }
    }
</script>
</body>
</html>