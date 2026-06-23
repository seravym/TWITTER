<!DOCTYPE html>
<html lang="en" class="{{ (Auth::check() && Auth::user()->setting && Auth::user()->setting->theme === 'dark') ? 'dark-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <title>Home - Twitter</title>
    <style>
        :root {
            --bg: #f7f9fa; --card-bg: #ffffff; --text: #0f1419; --text-muted: #536471;
            --border: #eff3f4; --hover-bg: #e8f5fe; --compose-bg: #fcfdfe;
            --accent: #1da1f2; --shadow: 0 4px 15px rgba(0,0,0,0.03);
            --feed-sep: #eff3f4; --comment-bg: #fafbfc;
        }
        html.dark-mode {
            --bg: #15202b; --card-bg: #1e2732; --text: #f7f9fa; --text-muted: #8899a6;
            --border: #2f3b47; --hover-bg: #253341; --compose-bg: #253341;
            --accent: #1d9bf0; --shadow: 0 4px 15px rgba(0,0,0,0.3);
            --feed-sep: #2f3b47; --comment-bg: #192734;
        }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: var(--bg); margin: 0; padding: 0; color: var(--text); transition: background 0.3s, color 0.3s; }
        .app-container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; padding-top: 20px; }
        
        .sidebar { width: 30%; position: sticky; top: 20px; height: max-content; padding-right: 25px; box-sizing: border-box; }
        .brand { font-size: 2.2em; font-weight: 900; color: #1da1f2; margin-bottom: 20px; padding-left: 10px; letter-spacing: -1px; display: flex; align-items: center; justify-content: space-between; }
        .bell-icon { font-size: 0.55em; color: #536471; background: #eff3f4; padding: 10px; border-radius: 50%; cursor: pointer; transition: 0.2s; position: relative; }
        .bell-icon:hover { background: #e8f5fe; color: #1da1f2; }
        .bell-dot { position: absolute; top: 5px; right: 5px; width: 8px; height: 8px; background: #f4212e; border-radius: 50%; }
        
        .sidebar-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 20px; padding: 25px; box-shadow: var(--shadow); }
        .sidebar-profile { text-align: center; border-bottom: 1px solid var(--border); padding-bottom: 25px; margin-bottom: 20px; position: relative; }
        
        .profile-avatar-wrapper { position: relative; width: 100px; height: 100px; margin: 0 auto 15px auto; }
        .sidebar-avatar { width: 100%; height: 100%; border-radius: 50%; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold; font-size: 35px; text-transform: uppercase; transition: 0.2s; position: relative; z-index: 2; border: 3px solid white; box-sizing: border-box; }
        .sidebar-avatar:hover { opacity: 0.8; }
        .story-ring { position: absolute; top: -3px; left: -3px; right: -3px; bottom: -3px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); border-radius: 50%; z-index: 1; }
        
        .status-bubble-sidebar { position: absolute; top: -5px; right: -15px; background: white; border: 1px solid #eff3f4; padding: 4px 10px; border-radius: 20px; font-size: 11px; color: #0f1419; box-shadow: 0 2px 10px rgba(0,0,0,0.08); font-weight: 600; z-index: 20; max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: default; transition: transform 0.1s; }
        .status-bubble-feed { position: absolute; top: -4px; right: -6px; background: white; border: 1px solid #eff3f4; padding: 2px 6px; border-radius: 12px; font-size: 10px; color: #0f1419; box-shadow: 0 2px 5px rgba(0,0,0,0.1); font-weight: 700; z-index: 10; cursor: default; }
        .status-bubble-sidebar:active { transform: scale(0.9); }

        .sidebar-name { font-weight: 800; font-size: 1.3em; color: var(--text); margin-bottom: 5px; }
        .sidebar-username { color: var(--text-muted); font-size: 1em; margin-bottom: 15px; }
        .sidebar-stats { display: flex; justify-content: center; gap: 20px; font-size: 0.9em; color: var(--text-muted); }
        .stat-link { cursor: pointer; transition: 0.2s; padding: 5px 10px; border-radius: 10px; }
        .stat-link:hover { background: var(--hover-bg); color: var(--text); }
        .sidebar-stats strong { color: var(--text); font-size: 1.2em; display: block; }
        
        .btn-view-profile { display: block; width: 100%; background: #0f1419; color: white; padding: 12px 0; border-radius: 30px; text-decoration: none; font-weight: bold; margin-top: 20px; transition: 0.2s; font-size: 1em; }
        .btn-view-profile:hover { background: #272c30; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

        .nav-menu { list-style: none; padding: 0; margin: 0; }
        .nav-menu li { margin-bottom: 5px; }
        .nav-menu a { display: flex; align-items: center; gap: 15px; padding: 12px 15px; text-decoration: none; color: var(--text); font-size: 1.1em; border-radius: 30px; transition: background 0.2s; font-weight: 600; }
        .nav-menu a:hover { background-color: var(--hover-bg); color: var(--accent); }
        
        .btn-logout { width: 100%; background: white; color: #f4212e; border: 1px solid #f4212e; padding: 12px; border-radius: 30px; font-weight: bold; font-size: 1em; cursor: pointer; margin-top: 15px; transition: 0.2s; }
        .btn-logout:hover { background: #f4212e; color: white; border-color: #f4212e; }

        .main-feed { width: 68%; background: var(--card-bg); border: 1px solid var(--border); border-radius: 20px; min-height: 100vh; padding-bottom: 50px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.01); }
        .feed-header { position: sticky; top: 0; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); z-index: 10; border-bottom: 1px solid var(--border); }
        html.dark-mode .feed-header { background: rgba(30,39,50,0.95); }
        .page-title { font-size: 1.4em; font-weight: 900; padding: 20px 15px 10px 15px; margin: 0; color: var(--text); text-align: center; } 
        .feed-tabs { display: flex; padding: 0 15px; }
        .tab-item { flex: 1; text-align: center; padding: 15px 0; text-decoration: none; color: var(--text-muted); font-weight: 700; transition: 0.2s; position: relative; font-size: 1.05em; }
        .tab-item:hover { background-color: var(--hover-bg); color: var(--text); border-radius: 8px 8px 0 0; }
        .tab-item.active { color: var(--text); }
        .tab-item.active::after { content: ''; position: absolute; bottom: 0; left: 25%; width: 50%; height: 4px; background-color: #1da1f2; border-radius: 4px 4px 0 0; }

        .compose-area { padding: 25px; background-color: var(--card-bg); border-bottom: 8px solid var(--feed-sep); display: flex; gap: 20px; }
        .compose-input-wrapper { flex: 1; border: 1px solid var(--border); border-radius: 16px; padding: 10px 15px; transition: 0.2s; background: var(--compose-bg); position: relative;}
        .compose-input-wrapper:focus-within { border-color: var(--accent); box-shadow: 0 0 0 1px var(--accent); background: var(--card-bg); }
        .compose-input { width: 100%; box-sizing: border-box; border: none; font-size: 1.15em; outline: none; resize: none; background: transparent; color: var(--text); font-family: inherit; }
        .compose-input::placeholder { color: var(--text-muted); font-weight: 500; }
        .compose-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; border-top: 1px solid var(--border); padding-top: 10px; }
        
        .btn-post { background: #1da1f2; color: white; border: none; padding: 8px 24px; font-weight: bold; border-radius: 30px; cursor: pointer; font-size: 1em; transition: 0.2s; }
        .btn-post:hover { background: #1a91da; }
        .btn-emoji { background: none; border: none; font-size: 1.2em; cursor: pointer; border-radius: 50%; padding: 5px; transition: 0.2s; opacity: 0.7; }
        .btn-emoji:hover { background: #e8f5fe; opacity: 1; }

        .emoji-picker-popup { display: none; position: absolute; bottom: calc(100% + 15px); right: 0; left: auto; background: white; border: 1px solid #cfd9de; border-radius: 12px; padding: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); z-index: 100; grid-template-columns: repeat(5, 1fr); gap: 8px; }
        .popup-up { top: auto !important; bottom: calc(100% + 15px) !important; right: 0; }
        .emoji-item { cursor: pointer; font-size: 1.2em; text-align: center; padding: 5px; border-radius: 8px; transition: 0.2s; }
        .emoji-item:hover { background: #e8f5fe; }

        .post-card { border-bottom: 8px solid var(--feed-sep); transition: 0.2s; position: relative; }
        .post-inner { padding: 25px 25px 15px 25px; background: var(--card-bg); }
        .post-card:hover .post-inner { background: var(--compose-bg); }
        .my-post { border-left: 4px solid var(--accent); }
        .my-post-badge { position: absolute; top: 25px; right: 25px; background: var(--hover-bg); color: var(--accent); font-size: 0.75em; font-weight: bold; padding: 4px 10px; border-radius: 12px; }

        .post-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
        .post-name { font-weight: 800; color: var(--text); text-decoration: none; font-size: 1.1em; display: inline-block; }
        .post-name:hover { text-decoration: underline; }
        .post-username { color: var(--text-muted); font-size: 0.9em; }
        .post-time { color: var(--text-muted); font-size: 0.9em; margin-left: 5px; }
        
        .btn-feed-follow { background: #1da1f2; color: white; border: none; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 0.85em; cursor: pointer; transition: 0.2s; }
        .btn-feed-follow:hover { background: #1a91da; }

        .post-content { font-size: 1.15em; line-height: 1.6; margin-bottom: 18px; margin-top: 15px; color: var(--text); }
        
        .post-actions { display: flex; gap: 15px; color: var(--text-muted); font-size: 0.95em; align-items: center; border-top: 1px solid var(--border); padding-top: 12px; margin-top: 15px; }
        .action-pill { background: var(--compose-bg); border: 1px solid var(--border); color: inherit; cursor: pointer; font-size: 0.95em; font-weight: 600; display: flex; align-items: center; gap: 7px; padding: 8px 16px; border-radius: 20px; transition: 0.2s; }
        .action-pill:hover { background: var(--hover-bg); color: var(--accent); border-color: var(--border); }
        .action-pill.like:hover { background: #fce8f3; color: #f91880; border-color: #f91880; }
        .action-pill.delete:hover { background: #fdeced; color: #f4212e; border-color: #f4212e; }

        .comment-section-wrapper { display: none; background: var(--comment-bg); border-top: 1px solid var(--border); box-shadow: inset 0px 5px 10px rgba(0,0,0,0.01); }
        .comment-list-area { padding: 15px 25px; }
        
        .sort-bar { display: flex; gap: 15px; border-bottom: 1px solid #eff3f4; padding-bottom: 10px; margin-bottom: 20px; font-size: 0.85em; color: #536471; }
        .sort-option { cursor: pointer; font-weight: 600; transition: 0.2s; }
        .sort-option:hover { color: #1da1f2; }
        .sort-option.active { color: #0f1419; font-weight: 800; }
        
        .comment-item { display: flex; gap: 12px; margin-bottom: 20px; position: relative; }
        .comment-avatar { flex-shrink: 0; margin-top: 5px; position: relative; width: 40px; height: 40px; }
        .comment-bubble { background: #ffffff; padding: 15px 20px; border-radius: 16px; flex: 1; border: 1px solid #eff3f4; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        
        .reply-thread-wrapper { margin-left: 55px; border-left: 2px solid #e1e8ed; padding-left: 15px; margin-top: -15px; margin-bottom: 15px; display: none; }
        .btn-toggle-replies { background: none; border: none; color: #1da1f2; font-weight: bold; cursor: pointer; font-size: 0.85em; padding: 0; margin-left: 55px; margin-bottom: 20px; margin-top: -5px; display: inline-block; }

        .comment-action-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; width: 100%; }
        .comment-meta-left { flex: 1; display: flex; align-items: center; gap: 10px;}
        
        .comment-bottom-row { display: flex; justify-content: flex-end; align-items: center; margin-top: 10px; border-top: 1px dashed #eff3f4; padding-top: 8px; }
        
        .c-actions-right { display: flex; gap: 12px; align-items: center; font-size: 0.85em; position: relative;}
        
        .c-btn { background: none; border: none; cursor: pointer; font-weight: bold; transition: 0.2s; padding: 0; display: flex; align-items: center; gap: 4px; font-size: 1em; }
        .c-btn-reply { color: #1da1f2; }
        .c-btn-reply:hover { text-decoration: underline; }
        .c-btn-like { color: #536471; filter: grayscale(100%); opacity: 0.6; position: relative;} 
        .c-btn-like.active { filter: none; opacity: 1; color: #f91880; } 
        
        .c-btn-dislike { transform: scaleY(-1); color: #536471; opacity: 0.5; font-size: 1.1em; cursor: pointer; background: none; border: none; padding: 0; margin-top: 0;} 
        .c-btn-dislike:hover { opacity: 0.8; }
        .c-btn-dislike.active { opacity: 1; color: #8899a6; filter: grayscale(0%); } 

        .btn-text-action { background: none; border: none; color: #536471; cursor: pointer; padding: 0; font-weight: 600; font-size: 1em;}
        .btn-text-action:hover { color: #1da1f2; text-decoration: underline; }
        .btn-text-danger { color: #f4212e; }
        .btn-text-danger:hover { color: #c62828; }
        .btn-cancel-edit { background: #eff3f4; color: #0f1419; border: none; padding: 6px 15px; border-radius: 20px; cursor: pointer; font-weight: bold; }
        .btn-cancel-edit:hover { background: #cfd9de; }

        .reaction-bubble-popup { display: none; position: absolute; bottom: 30px; left: -10px; background: white; border: 1px solid #cfd9de; border-radius: 30px; padding: 5px 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); z-index: 50; flex-direction: row; gap: 8px; animation: popUp 0.2s ease; }
        @keyframes popUp { 0% { transform: scale(0.8); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
        .reaction-emoji { cursor: pointer; font-size: 1.2em; transition: 0.2s; }
        .reaction-emoji:hover { transform: scale(1.2); }
        .reaction-add { background: #eff3f4; border-radius: 50%; width: 24px; height: 24px; display: flex; justify-content: center; align-items: center; font-weight: bold; cursor: pointer; color: #536471; font-size: 0.8em; margin-top: 2px;}

        .comment-form-container { display: none; padding-top: 15px; width: 100%; box-sizing: border-box; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        .comment-input { flex: 1; padding: 8px 15px; border: none; outline: none; background: transparent; font-size: 0.95em; color: #0f1419; font-family: inherit; border-left: 2px solid #1da1f2;}
        .btn-send { background: #1da1f2; color: white; border: none; padding: 6px 16px; border-radius: 20px; cursor: pointer; font-weight: bold; font-size: 0.9em; transition: 0.2s;}
        .btn-send:hover { background: #1a91da; }

        .write-reply-bar { padding: 20px 25px; background: #fcfdfe; border-top: 1px solid #eff3f4; display: flex; gap: 12px; align-items: center; position: relative;}

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
        
        .btn-follow-back { background: #0f1419; color: white; border: none; padding: 6px 16px; border-radius: 20px; font-weight: bold; cursor: pointer; transition: 0.2s; }
        .btn-follow-back:hover { background: #272c30; }
        .btn-message { background: white; color: #0f1419; border: 1px solid #cfd9de; padding: 6px 16px; border-radius: 20px; font-weight: bold; cursor: pointer; transition: 0.2s; }
        .btn-message:hover { background: #f7f9fa; }
    </style>
</head>
<body>

@php
    function parseMentions($text) {
        return preg_replace('/@(\w+)/', '<a href="/accounts/$1" style="color: #1da1f2; text-decoration: none; font-weight: bold;">@$1</a>', $text);
    }
@endphp

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
    
    $myBubble = $me->active_status ?? null;
@endphp

<div class="app-container">
    <div class="sidebar">
        <div class="brand">
            Twitter 
            @php $unreadNotifCount = \App\Http\Controllers\NotificationController::unreadCount(); @endphp
            <a href="/notifications" class="bell-icon" title="Notifications" style="text-decoration:none;">🔔@if($unreadNotifCount > 0)<div class="bell-dot"></div>@endif</a>
        </div>
        
        <div class="sidebar-card">
            @auth
                <div class="sidebar-profile">
                    <div class="profile-avatar-wrapper">
                        <a href="/accounts/{{ Auth::id() }}" style="text-decoration: none;">
                            <div class="sidebar-avatar" style="background: {{ getAvatarGradient(Auth::id()) }}; border: none;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </a>
                        @if($myBubble)
                            <div class="status-bubble-sidebar">💭 {{ $myBubble }}</div>
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
                <li><a href="/hashtags">🔥 Trending</a></li>
                <li><a href="/bookmarks">🔖 Bookmarks</a></li>
                <li><a href="/close-friends">🌟 Close Friends</a></li>
                <li><a href="/notifications">🔔 Notifications @php $nc = \App\Http\Controllers\NotificationController::unreadCount(); @endphp @if($nc > 0)<span style="background:#f4212e;color:white;font-size:0.7em;padding:2px 8px;border-radius:20px;margin-left:5px;">{{ $nc }}</span>@endif</a></li>
                <li><a href="/messages">✉️ Messages</a></li>
                <li><a href="/communities">👥 Community</a></li>
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
                $placeholders = ['What is happening?!', 'Share your thoughts today...','What\'s on your mind?','Drop a thought here...','Tell the world what you think...','Any good news to share?','Spill the tea...','What\'s the highlight of your day?','Got something to say?','Start a new conversation...','Write your story here...'];
                $randomPlaceholder = $placeholders[array_rand($placeholders)];
            @endphp
            <div class="compose-area">
                <div class="sidebar-avatar" style="width: 50px; height: 50px; font-size: 18px; margin: 0; background: {{ getAvatarGradient(Auth::id()) }}; border:none; cursor:default;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <form action="/posts" method="POST" style="flex: 1;" enctype="multipart/form-data">
                    @csrf
                    <div class="compose-input-wrapper">
                        <textarea name="content" class="compose-input" rows="2" placeholder="{{ $randomPlaceholder }}" maxlength="350" oninput="updateCount(this)" required id="main-compose"></textarea>

                        {{-- Preview media yang dipilih --}}
                        <div id="mediaPreviewArea" style="display:none; margin-top: 10px;"></div>

                        <div class="compose-actions" style="justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 10px; position: relative;">
                                <button type="button" class="btn-emoji" title="Add Emoji" onclick="toggleEmojiBox('emoji-box-main')">😀</button>

                                {{-- Tombol upload media --}}
                                <label for="mediaInput" class="btn-emoji" title="Upload Foto/Video" style="cursor:pointer;">📷</label>
                                <input type="file" id="mediaInput" name="media" accept="image/*,video/*" style="display:none;" onchange="previewMedia(this)">

                                {{-- Visibility selector --}}
                                <div style="display:flex; align-items:center; gap:6px; margin-left:4px;">
                                    <button type="button" id="visPublicBtn" onclick="setVisibility('public')"
                                        style="background:#e8f5fe;color:#1da1f2;border:1.5px solid #1da1f2;padding:5px 12px;border-radius:20px;font-size:0.82em;font-weight:700;cursor:pointer;transition:0.2s;"
                                        title="Post ke semua orang">🌍 Public</button>
                                    <button type="button" id="visCFBtn" onclick="setVisibility('close_friend')"
                                        style="background:white;color:#536471;border:1.5px solid #cfd9de;padding:5px 12px;border-radius:20px;font-size:0.82em;font-weight:700;cursor:pointer;transition:0.2s;"
                                        title="Post ke Close Friends saja">🌟 Close Friends</button>
                                    <input type="hidden" name="visibility" id="visibilityInput" value="public">
                                </div>
                            </div>

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
                                
                                <div style="position: relative; width: 48px; height: 48px;">
                                    <a href="/accounts/{{ $post->account->id }}" style="text-decoration: none;">
                                        <div class="sidebar-avatar" style="width: 100%; height: 100%; font-size: 16px; margin: 0; background: {{ getAvatarGradient($post->account_id) }}; border:none;">
                                            {{ substr($post->account->name, 0, 1) }}
                                        </div>
                                    </a>
                                    @if($isMyPost && $myBubble)
                                        <div class="status-bubble-feed">💭 {{ $myBubble }}</div>
                                    @endif
                                </div>

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

                        {{-- Tampilkan media jika ada --}}
                        @if($post->media_path)
                            <div style="margin: 10px 0 15px; border-radius: 16px; overflow: hidden;">
                                @if($post->media_type === 'video')
                                    <video controls style="width:100%; max-height: 400px; border-radius: 16px; background:#000;">
                                        <source src="{{ asset('storage/' . $post->media_path) }}" type="video/mp4">
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $post->media_path) }}" alt="Post media"
                                         style="width:100%; max-height: 450px; object-fit: cover; border-radius: 16px; display:block;">
                                @endif
                            </div>
                        @endif

                        {{-- Badge close friend --}}
                        @if($post->visibility === 'close_friend')
                            <div style="display:inline-flex;align-items:center;gap:5px;background:rgba(19,78,94,0.08);color:#134e5e;font-size:0.78em;font-weight:700;padding:3px 10px;border-radius:20px;margin-bottom:10px;">
                                🌟 Close Friends Only
                            </div>
                        @endif

                        
                        @if($post->poll)
                            @php
                                $userVote = $post->poll->votes->firstWhere('account_id', Auth::id());
                                $totalVotes = $post->poll->votes->count();
                            @endphp
                            <div style="margin: 15px 0; padding: 14px; background: #f7f9fa; border: 1px solid #eff3f4; border-radius: 18px;">
                                <div style="font-weight: 700; margin-bottom: 12px;">{{ $post->poll->question }}</div>
                                <form action="{{ route('polls.vote', $post) }}" method="POST">
                                    @csrf
                                    @foreach($post->poll->options as $option)
                                        @php
                                            $optionVotes = $option->votes->count();
                                            $percent = $totalVotes > 0 ? round(($optionVotes / $totalVotes) * 100) : 0;
                                        @endphp
                                        <div style="margin-bottom: 10px;">
                                            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                                                <input type="radio" name="poll_option_id" value="{{ $option->id }}" {{ ($userVote && $userVote->poll_option_id == $option->id) ? 'checked' : '' }} {{ $post->poll->isClosed() ? 'disabled' : '' }}>
                                                <span style="font-size:0.96em;">{{ $option->text }}</span>
                                            </label>
                                            <div style="background:#e8eef5;height:10px;border-radius:999px;overflow:hidden;margin-top:8px;">
                                                <div style="width:{{ $percent }}%;background:#1da1f2;height:100%;"></div>
                                            </div>
                                            <div style="font-size:0.82em;color:#536471; margin-top: 4px;">{{ $optionVotes }} vote(s) · {{ $percent }}%</div>
                                        </div>
                                    @endforeach
                                    @if(!$post->poll->isClosed())
                                        <button type="submit" class="btn-post" style="padding: 8px 14px; margin-top: 5px;">Vote</button>
                                    @else
                                        <div style="color:#536471;font-size:0.9em;margin-top:4px;">Polling closed</div>
                                    @endif
                                </form>
                            </div>
                        @endif
                        
                        <div class="poll-container">

                        <input
                            type="text"
                            name="poll_options[]"
                            placeholder="Option 1">

                        <input
                            type="text"
                            name="poll_options[]"
                            placeholder="Option 2">

                        <input
                            type="text"
                            name="poll_options[]"
                            placeholder="Option 3">

                        <input
                            type="text"
                            name="poll_options[]"
                            placeholder="Option 4">

                        <select name="poll_duration">
                            <option value="1">1 Day</option>
                            <option value="3">3 Days</option>
                            <option value="7">7 Days</option>
                        </select>

                    </div>

                        <div class="post-actions">
                            <form action="/posts/{{ $post->id }}/like" method="POST" style="margin:0;">
                                @csrf<button type="submit" class="action-pill like">{{ $post->isLikedBy(Auth::id()) ? '❤️' : '🤍' }} {{ $post->likes->count() }}</button>
                            </form>
                            <button class="action-pill" onclick="toggleComments({{ $post->id }})">💬 {{ $post->comments->count() }} Comments</button>
                            {{-- Bookmark toggle --}}
                            <form action="{{ route('bookmarks.toggle', $post->id) }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="action-pill" style="{{ $post->isBookmarkedBy(Auth::id()) ? 'background:#fffbeb;color:#f59e0b;border-color:#fde68a;' : '' }}" title="{{ $post->isBookmarkedBy(Auth::id()) ? 'Hapus Bookmark' : 'Simpan ke Bookmark' }}">
                                    {{ $post->isBookmarkedBy(Auth::id()) ? '🔖' : '🏷️' }}
                                </button>
                            </form>
                            @if($isMyPost)
                                {{-- Pin / Unpin --}}
                                <form action="{{ route('posts.pin', $post->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="action-pill" title="{{ $post->is_pinned ? 'Unpin post' : 'Sematkan post' }}"
                                        style="{{ $post->is_pinned ? 'background:#fff9eb;color:#d97706;border-color:#fcd34d;' : '' }}">
                                        {{ $post->is_pinned ? '📌 Pinned' : '📌' }}
                                    </button>
                                </form>
                                <form action="/posts/{{ $post->id }}" method="POST" style="margin:0; margin-left: auto;">
                                    @csrf @method('DELETE')<button type="submit" class="action-pill delete" onclick="return confirm('Delete post?')">🗑️ Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="comment-section-wrapper" id="comment-wrapper-{{ $post->id }}">
                        
                        <div class="comment-list-area">
                            @if($post->comments->count() > 0)
                            <div class="sort-bar">
                                <span class="sort-option active" onclick="toggleSort(this)">Date ↓</span>
                                <span class="sort-option" onclick="toggleSort(this)">Likes ↓</span>
                                <span class="sort-option" onclick="toggleSort(this)">Comments ↓</span>
                            </div>
                            @endif

                            @if($post->comments->count() > 0)
                                @foreach($post->comments->where('parent_id', null) as $comment)
                                    @php 
                                        $isMyComment = Auth::id() === $comment->account_id;
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
                                                    <div>
                                                        <a href="/accounts/{{ $comment->account_id }}" style="font-weight: bold; color: #0f1419; text-decoration: none; font-size: 1.05em;">{{ $comment->account->name ?? 'Anonymous' }}</a>
                                                        <span style="color: #536471; font-size: 0.9em; margin-left: 4px;">
                                                            @ {{ $comment->account->username ?? 'user' }} 
                                                            • {{ $comment->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div style="display: flex; gap: 15px; align-items: center;">
                                                    <button class="c-btn c-btn-reply" onclick="toggleReplyBox({{ $comment->id }})">Reply</button>
                                                    <div style="position:relative;">
                                                        <button class="c-btn c-btn-like" ondblclick="toggleReactionPopup('react-pop-{{$comment->id}}')" onclick="toggleSimulateLike(this)">
                                                            🤍 <span class="c-count">{{ $commentLikesCount }}</span>
                                                        </button>
                                                        <div class="reaction-bubble-popup" id="react-pop-{{$comment->id}}" style="display:none;">
                                                            <span class="reaction-emoji" onclick="this.parentElement.style.display='none'">👍</span>
                                                            <span class="reaction-emoji" onclick="this.parentElement.style.display='none'">😂</span>
                                                            <span class="reaction-emoji" onclick="this.parentElement.style.display='none'">😢</span>
                                                            <span class="reaction-emoji" onclick="this.parentElement.style.display='none'">🔥</span>
                                                            <span class="reaction-emoji" onclick="this.parentElement.style.display='none'">💯</span>
                                                            <div class="reaction-add">+</div>
                                                        </div>
                                                    </div>
                                                    @if(!$isMyComment)
                                                        <button class="c-btn-dislike" onclick="this.classList.toggle('active')" title="Dislike">👍</button>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div id="comment-text-{{ $comment->id }}" style="font-size: 1.05em; color: #0f1419; margin-top: 5px;">
                                                <p class="comment-text" style="margin: 0;">{!! parseMentions($comment->content) !!}</p>
                                            </div>

                                            @if($isMyComment)
                                        <form action="/comments/{{ $comment->id }}" method="POST" id="edit-form-{{ $comment->id }}" style="display: none; margin-top: 10px; gap: 8px; flex-wrap: wrap;">
                                            @csrf @method('PUT')

                                            <div style="position: relative; flex: 1; display: flex; align-items: center; gap: 8px;">
                                                
                                                <div class="emoji-picker-popup" id="emoji-box-edit-{{$comment->id}}" style="bottom: 45px; left: 0;">
                                                    <span class="emoji-item" onclick="insertEmoji('edit-input-{{$comment->id}}', '😀')">😀</span>
                                                    <span class="emoji-item" onclick="insertEmoji('edit-input-{{$comment->id}}', '😂')">😂</span>
                                                    <span class="emoji-item" onclick="insertEmoji('edit-input-{{$comment->id}}', '🥰')">🥰</span>
                                                    <span class="emoji-item" onclick="insertEmoji('edit-input-{{$comment->id}}', '🙏')">🙏</span>
                                                    <span class="emoji-item" onclick="insertEmoji('edit-input-{{$comment->id}}', '🔥')">🔥</span>
                                                </div>

                                                <input type="text" id="edit-input-{{$comment->id}}" name="content" value="{{ $comment->content }}" style="flex: 1; padding: 8px 15px; border: 1px solid #1da1f2; border-radius: 20px; outline: none;">
                                                
                                                <button type="button" class="btn-emoji" title="Add Emoji" style="background: none; border: none; font-size: 1.2em; cursor: pointer; padding: 0;" onclick="toggleEmojiBox('emoji-box-edit-{{$comment->id}}')">😀</button>
                                            </div>

                                            <div style="display: flex; gap: 8px;">
                                                <button type="submit" style="background: #1da1f2; color: white; border: none; padding: 6px 15px; border-radius: 20px; cursor: pointer; font-weight: bold;">Save</button>
                                                <button type="button" class="btn-cancel-edit" onclick="toggleEditComment({{ $comment->id }})" style="background: white; color: #1da1f2; border: 1px solid #1da1f2; padding: 6px 15px; border-radius: 20px; cursor: pointer; font-weight: bold;">Cancel</button>
                                            </div>
                                        </form>
                                        @endif

                                            <div class="comment-bottom-row">
                                                <div class="c-actions-right">
                                                    @if($isMyComment)
                                                        <div style="display: flex; align-items: center; gap: 8px;">
                                                            <button onclick="toggleEditComment({{ $comment->id }})" class="btn-text-action">Edit</button>
                                                            <span style="color: #cfd9de; font-weight:bold; margin-bottom: 2px;">|</span>
                                                            <form action="/comments/{{ $comment->id }}" method="POST" style="margin: 0; display:flex;">
                                                                @csrf @method('DELETE')<button type="submit" onclick="return confirm('Delete comment?')" class="btn-text-action btn-text-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                    
                                                    <div style="position: relative;">
                                                        <button onclick="toggleActionMenu(event, 'drop-comment-{{$comment->id}}')" class="btn-text-action" style="font-weight: bold; font-size: 1.2em; display: inline-flex; align-items: center; justify-content: center;">⋯</button>
                                                        <div id="drop-comment-{{$comment->id}}" class="action-menu-dropdown action-menu-popup" style="top: 25px;">
                                                            <button type="button">🌐 Translate</button>
                                                            <button type="button">➡️ Forward</button>
                                                            <button type="button">📋 Copy Text</button>
                                                            <button type="button">🔗 Copy Link Text</button>
                                                            <button type="button" class="text-danger">🚫 Report Message</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @auth
                                                <div id="reply-form-{{ $comment->id }}" class="comment-form-container">
                                                    <div style="display: flex; gap: 12px; align-items: center; margin-top: 5px; width: 100%; box-sizing: border-box;">
                                                        
                                                        <a href="/accounts/{{ Auth::id() }}" style="flex-shrink: 0; text-decoration: none;">
                                                            <div class="sidebar-avatar" style="width: 32px; height: 32px; font-size: 13px; margin: 0; background: {{ getAvatarGradient(Auth::id()) }}; border:none;">
                                                                {{ substr(Auth::user()->name, 0, 1) }}
                                                            </div>
                                                        </a>

                                                        <form action="/comments" method="POST" style="flex: 1; display: flex; gap: 8px; background: #f7f9fa; border-radius: 30px; padding: 6px 6px 6px 15px; border: 1px solid #eff3f4; align-items: center; margin: 0;">
                                                            @csrf
                                                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                            <input type="text" name="content" class="comment-input" placeholder="Reply to {{ $comment->account->name }}..." required autocomplete="off" style="padding: 5px 0; border: none; flex: 1; width: 100%; background: transparent;" id="reply-input-{{$comment->id}}">
                                                            
                                                            <div style="position: relative; display: flex; align-items: center;">
                                                                <div class="emoji-picker-popup popup-up" id="emoji-box-reply-{{$comment->id}}" style="right: 0; left: auto;">
                                                                    <span class="emoji-item" onclick="insertEmoji('reply-input-{{$comment->id}}', '😀')">😀</span>
                                                                    <span class="emoji-item" onclick="insertEmoji('reply-input-{{$comment->id}}', '😂')">😂</span>
                                                                    <span class="emoji-item" onclick="insertEmoji('reply-input-{{$comment->id}}', '🥰')">🥰</span>
                                                                    <span class="emoji-item" onclick="insertEmoji('reply-input-{{$comment->id}}', '🙏')">🙏</span>
                                                                    <span class="emoji-item" onclick="insertEmoji('reply-input-{{$comment->id}}', '🔥')">🔥</span>
                                                                </div>
                                                                <button type="button" class="btn-emoji" title="Add Emoji" onclick="toggleEmojiBox('emoji-box-reply-{{$comment->id}}')">😀</button>
                                                            </div>

                                                            <button type="submit" class="btn-send">Reply</button>
                                                        </form>
                                                    </div>
                                                </div>
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
                                                <div class="comment-item" style="margin-bottom: 12px; padding: 0;">
                                                    <div class="comment-avatar" style="width: 32px; height: 32px;">
                                                        <a href="/accounts/{{ $reply->account_id }}" style="text-decoration: none;">
                                                            <div class="sidebar-avatar" style="width: 100%; height: 100%; font-size: 13px; margin: 0; background: {{ getAvatarGradient($reply->account_id) }}; border:none;">
                                                                {{ substr($reply->account->name ?? 'A', 0, 1) }}
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="comment-bubble" style="background: #ffffff; padding: 12px 16px;">
                                                        
                                                        <div class="comment-action-row">
                                                            <div class="comment-meta-left">
                                                                <div>
                                                                    <a href="/accounts/{{ $reply->account_id }}" style="font-weight: bold; color: #0f1419; text-decoration: none;">{{ $reply->account->name ?? 'Anonymous' }}</a>
                                                                    <span style="color: #536471; font-size: 0.8em; margin-left: 4px;">
                                                                        @ {{ $reply->account->username ?? 'user' }} 
                                                                        • {{ $reply->created_at->diffForHumans() }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            
                                                            <div style="display: flex; gap: 15px; align-items: center;">
                                                                <button class="c-btn c-btn-like" onclick="toggleSimulateLike(this)">🤍 <span class="c-count">0</span></button>
                                                                @if(!$isMyReply)
                                                                    <button class="c-btn-dislike" onclick="this.classList.toggle('active')" title="Dislike">👍</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <div id="comment-text-{{ $reply->id }}" style="font-size: 0.95em; color: #0f1419; margin-top: 5px;">
                                                            {!! parseMentions($reply->content) !!}
                                                        </div>

                                                        @if($isMyReply)
                                                        <form action="/comments/{{ $reply->id }}" method="POST" id="edit-form-{{ $reply->id }}" style="display: none; margin-top: 10px; gap: 8px;">
                                                            @csrf @method('PUT')
                                                            <input type="text" name="content" value="{{ $reply->content }}" style="flex: 1; padding: 6px 12px; border: 1px solid #1da1f2; border-radius: 20px; outline: none; font-size: 0.9em;">
                                                            <button type="submit" style="background: #1da1f2; color: white; border: none; padding: 6px 12px; border-radius: 20px; cursor: pointer; font-size: 0.85em; font-weight: bold;">Save</button>
                                                            <button type="button" class="btn-cancel-edit" onclick="toggleEditComment({{ $reply->id }})">Cancel</button>
                                                        </form>
                                                        @endif

                                                        <div class="comment-bottom-row">
                                                            <div class="c-actions-right">
                                                                @if($isMyReply)
                                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                                        <button onclick="toggleEditComment({{ $reply->id }})" class="btn-text-action">Edit</button>
                                                                        <span style="color: #cfd9de; font-weight:bold; margin-bottom: 2px;">|</span>
                                                                        <form action="/comments/{{ $reply->id }}" method="POST" style="margin: 0; display:flex;">
                                                                            @csrf @method('DELETE')<button type="submit" onclick="return confirm('Delete reply?')" class="btn-text-action btn-text-danger">Delete</button>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                                
                                                                <div style="position: relative;">
                                                                    <button onclick="toggleActionMenu(event, 'drop-reply-{{$reply->id}}')" class="btn-text-action" style="font-weight: bold; font-size: 1.2em; display: inline-flex; align-items: center; justify-content: center;">⋯</button>
                                                                    <div id="drop-reply-{{$reply->id}}" class="action-menu-dropdown action-menu-popup" style="top: 25px;">
                                                                        <button type="button">🌐 Translate</button>
                                                                        <button type="button">➡️ Forward</button>
                                                                        <button type="button">📋 Copy Text</button>
                                                                        <button type="button">🔗 Copy Link Text</button>
                                                                        <button type="button" class="text-danger">🚫 Report Message</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        
                        @auth
                        <div class="write-reply-bar">
                            <a href="/accounts/{{ Auth::id() }}">
                                <div class="sidebar-avatar" style="width: 35px; height: 35px; font-size: 14px; margin: 0; background: {{ getAvatarGradient(Auth::id()) }}; border:none;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </a>
                            <form action="/comments" method="POST" style="flex: 1; display: flex; gap: 10px; background: #f7f9fa; border-radius: 20px; padding: 5px 5px 5px 15px; border: 1px solid #eff3f4; position: relative;">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <input type="text" name="content" class="comment-input" placeholder="Add your comment..." required autocomplete="off" style="padding: 5px 0; border:none;" id="reply-input-{{$post->id}}">
                                
                                <div class="emoji-picker-popup" id="emoji-box-reply-{{$post->id}}" style="bottom: 45px;">
                                    <span class="emoji-item" onclick="insertEmoji('reply-input-{{$post->id}}', '😀')">😀</span>
                                    <span class="emoji-item" onclick="insertEmoji('reply-input-{{$post->id}}', '😂')">😂</span>
                                    <span class="emoji-item" onclick="insertEmoji('reply-input-{{$post->id}}', '🥰')">🥰</span>
                                    <span class="emoji-item" onclick="insertEmoji('reply-input-{{$post->id}}', '🙏')">🙏</span>
                                    <span class="emoji-item" onclick="insertEmoji('reply-input-{{$post->id}}', '🔥')">🔥</span>
                                </div>
                                
                                <button type="button" class="btn-emoji" title="Add Emoji" onclick="toggleEmojiBox('emoji-box-reply-{{$post->id}}')">😀</button>
                                <button type="submit" class="btn-send">Send</button>
                            </form>
                        </div>
                        @endauth
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
            @php 
                $myFollowersList = Auth::user()->followers()->where('status', 'accepted')->with('follower')->get(); 
                $closeFriendIds = \App\Models\CloseFriend::where('account_id', Auth::id())->pluck('friend_id')->toArray();
            @endphp
            
            @if($myFollowersList->isEmpty()) 
                <li style="justify-content: center; color: var(--text-muted); font-weight: 600; padding: 40px 0;">No followers yet.</li>
            @else
                @foreach($myFollowersList as $f)
                    @php 
                        $isFollowingBack = in_array($f->follower->id, $myFollowingList);
                        $isCF = in_array($f->follower->id, $closeFriendIds);
                    @endphp
                    <li>
                        <div style="display: flex; gap: 14px; align-items: center; width: 100%;">
                            <a href="/accounts/{{ $f->follower->id }}" style="text-decoration: none; flex-shrink: 0;">
                                <div class="sidebar-avatar" style="width: 48px; height: 48px; font-size: 18px; margin: 0; background: {{ getAvatarGradient($f->follower->id) }}; border:none;">
                                    {{ substr($f->follower->name, 0, 1) }}
                                </div>
                            </a>
                            
                            <div style="flex: 1; min-width: 0;">
                                <a href="/accounts/{{ $f->follower->id }}" style="display: flex; align-items: center; gap: 5px; font-weight: 800; color: var(--text); text-decoration: none; font-size: 1.05em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $f->follower->name }}
                                    @if($isCF)
                                        <span title="Close Friend" style="font-size: 0.85em;">🌟</span>
                                    @endif
                                </a>
                                <div style="color: var(--text-muted); font-size: 0.95em;">@ {{ $f->follower->username }}</div>
                            </div>
                            
                            <div style="display: flex; align-items: center; gap: 8px;">
                                @if(!$isFollowingBack)
                                    <form action="/follows" method="POST" style="margin: 0;">
                                        @csrf <input type="hidden" name="following_id" value="{{ $f->follower->id }}">
                                        <button type="submit" class="btn-follow-back" style="padding: 7px 18px; background: var(--text); color: var(--bg);">Follow Back</button>
                                    </form>
                                @else
                                    <a href="/messages/{{ $f->follower->id }}" class="btn-message" style="text-decoration: none; padding: 7px 18px;">Message</a>
                                @endif

                                <div style="position: relative;">
                                    <button onclick="toggleActionMenu(event, 'drop-flw-{{$f->follower->id}}')" style="background: none; border: none; font-size: 1.3em; cursor: pointer; color: var(--text-muted); padding: 5px; border-radius: 50%; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; transition: 0.2s;" onmouseover="this.style.background='var(--hover-bg)'" onmouseout="this.style.background='none'">⋮</button>
                                    
                                    <div id="drop-flw-{{$f->follower->id}}" class="action-menu-dropdown action-menu-popup" style="right: 0; top: 40px; min-width: 240px;">
                                        @if($isFollowingBack)
                                            @if($isCF)
                                                <form action="{{ route('close-friends.destroy', $f->follower->id) }}" method="POST" style="margin:0;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-danger">✕ Hapus dari Close Friends</button>
                                                </form>
                                            @else
                                                <form action="{{ route('close-friends.store', $f->follower->id) }}" method="POST" style="margin:0;">
                                                    @csrf
                                                    <button type="submit" style="color: #134e5e; font-weight: 700;">🌟 Tambah ke Close Friends</button>
                                                </form>
                                            @endif
                                            <div style="height: 1px; background: var(--border); margin: 5px 0;"></div>
                                        @else
                                            <div style="padding: 12px 20px; font-size: 0.85em; color: var(--text-muted);">Follow akun ini untuk menambahkannya ke Close Friends.</div>
                                            <div style="height: 1px; background: var(--border); margin: 5px 0;"></div>
                                        @endif
                                        <button class="text-danger">🚫 Remove this follower</button>
                                    </div>
                                </div>
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
            @php 
                $myFollowingData = Auth::user()->following()->where('status', 'accepted')->with('following')->get(); 
            @endphp
            
            @if($myFollowingData->isEmpty()) 
                <li style="justify-content: center; color: var(--text-muted); font-weight: 600; padding: 40px 0;">Not following anyone yet.</li>
            @else
                @foreach($myFollowingData as $g)
                    @php 
                        $isCF = in_array($g->following->id, $closeFriendIds);
                    @endphp
                    <li>
                        <div style="display: flex; gap: 14px; align-items: center; width: 100%;">
                            <a href="/accounts/{{ $g->following->id }}" style="text-decoration: none; flex-shrink: 0;">
                                <div class="sidebar-avatar" style="width: 48px; height: 48px; font-size: 18px; margin: 0; background: {{ getAvatarGradient($g->following->id) }}; border:none;">
                                    {{ substr($g->following->name, 0, 1) }}
                                </div>
                            </a>
                            
                            <div style="flex: 1; min-width: 0;">
                                <a href="/accounts/{{ $g->following->id }}" style="display: flex; align-items: center; gap: 5px; font-weight: 800; color: var(--text); text-decoration: none; font-size: 1.05em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $g->following->name }}
                                    @if($isCF)
                                        <span title="Close Friend" style="font-size: 0.85em;">🌟</span>
                                    @endif
                                </a>
                                <div style="color: var(--text-muted); font-size: 0.95em;">@ {{ $g->following->username }}</div>
                            </div>
                            
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <a href="/messages/{{ $g->following->id }}" class="btn-message" style="text-decoration: none; padding: 7px 18px;">Message</a>
                                
                                <div style="position: relative;">
                                    <button onclick="toggleActionMenu(event, 'drop-fwing-{{$g->following->id}}')" style="background: none; border: none; font-size: 1.3em; cursor: pointer; color: var(--text-muted); padding: 5px; border-radius: 50%; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; transition: 0.2s;" onmouseover="this.style.background='var(--hover-bg)'" onmouseout="this.style.background='none'">⋮</button>
                                    
                                    <div id="drop-fwing-{{$g->following->id}}" class="action-menu-dropdown action-menu-popup" style="right: 0; top: 40px; min-width: 240px;">
                                        @if($isCF)
                                            <form action="{{ route('close-friends.destroy', $g->following->id) }}" method="POST" style="margin:0;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-danger">✕ Hapus dari Close Friends</button>
                                            </form>
                                        @else
                                            <form action="{{ route('close-friends.store', $g->following->id) }}" method="POST" style="margin:0;">
                                                @csrf
                                                <button type="submit" style="color: #134e5e; font-weight: 700;">🌟 Tambah ke Close Friends</button>
                                            </form>
                                        @endif
                                        <div style="height: 1px; background: var(--border); margin: 5px 0;"></div>
                                        <form action="/follows/{{ $g->following->id }}" method="POST" style="margin:0;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-danger">🚫 Unfollow <span style="font-weight: 800;">@ {{ $g->following->username }}</span></button>
                                        </form>
                                    </div>
                                </div>
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
        replyForm.style.display = (replyForm.style.display === 'block') ? 'none' : 'block';
        if (replyForm.style.display === 'block') replyForm.querySelector('.comment-input').focus();
    }
    
    function toggleSort(element) {
        let parent = element.parentElement;
        parent.querySelectorAll('.sort-option').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        if(element.innerText.includes('↓')) {
            element.innerText = element.innerText.replace('↓', '↑');
        } else {
            element.innerText = element.innerText.replace('↑', '↓');
        }
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
    
    function toggleEmojiBox(id) {
        let box = document.getElementById(id);
        box.style.display = (box.style.display === 'grid') ? 'none' : 'grid';
    }

    function insertEmoji(inputId, emoji) {
        let input = document.getElementById(inputId);
        input.value += emoji;
        input.focus();
        updateCount(input);
    }
    
    function toggleReactionPopup(id) {
        let popup = document.getElementById(id);
        popup.style.display = 'flex';
    }

    window.onclick = function(e) { 
        if (e.target.classList.contains('modal')) e.target.style.display = "none";
        
        if (!e.target.matches('.action-menu-popup') && !e.target.closest('button')) {
            document.querySelectorAll('.action-menu-popup').forEach(menu => {
                menu.style.display = 'none';
            });
        }
        
        if (!e.target.closest('.emoji-picker-popup') && !e.target.closest('.btn-emoji')) {
            document.querySelectorAll('.emoji-picker-popup').forEach(box => {
                box.style.display = 'none';
            });
        }
        
        if (!e.target.closest('.comment-form-container') && !e.target.closest('.c-btn-reply')) {
            document.querySelectorAll('.comment-form-container').forEach(form => {
                let input = form.querySelector('.comment-input');
                if (input && input.value.trim() === '') {
                    form.style.display = 'none';
                }
            });
        }
        
        if (!e.target.closest('.reaction-bubble-popup') && !e.target.closest('.c-btn-like')) {
            document.querySelectorAll('.reaction-bubble-popup').forEach(pop => pop.style.display = 'none');
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

    function toggleSimulateLike(btn) {
        const isActive = btn.classList.contains('active');
        let countSpan = btn.querySelector('span'); 
        
        if (!countSpan) return; 
        let current = parseInt(countSpan.innerText.replace(/[^0-9]/g, '')) || 0;
        
        if (isActive) {
            btn.classList.remove('active');
            btn.innerHTML = `🤍 <span>${current - 1}</span>`;
            btn.style.color = "#536471";
        } else {
            btn.classList.add('active');
            btn.innerHTML = `❤️ <span>${current + 1}</span>`;
            btn.style.color = "#f91880"; 
        }
    }

    function handleMention(inputElement) {
        const text = inputElement.value;
        const lastChar = text.slice(-1);
        
        if (lastChar === '@') {
            console.log("Mendeteksi mention, ambil data dari API/Database...");
        }
    }


    document.querySelectorAll('.comment-input').forEach(input => {
        input.addEventListener('input', function() {
            handleMention(this);
        });
    });

    // ── Media Preview ──────────────────────────────────────
    function previewMedia(input) {
        const area = document.getElementById('mediaPreviewArea');
        area.innerHTML = '';
        if (!input.files || !input.files[0]) { area.style.display = 'none'; return; }

        const file = input.files[0];
        const url  = URL.createObjectURL(file);
        area.style.display = 'block';

        let el;
        if (file.type.startsWith('video/')) {
            el = document.createElement('video');
            el.controls = true;
            el.style.cssText = 'width:100%;max-height:280px;border-radius:12px;background:#000;';
            const src = document.createElement('source');
            src.src  = url;
            src.type = file.type;
            el.appendChild(src);
        } else {
            el = document.createElement('img');
            el.src = url;
            el.alt = 'Preview';
            el.style.cssText = 'width:100%;max-height:280px;object-fit:cover;border-radius:12px;display:block;';
        }

        const wrap = document.createElement('div');
        wrap.style.cssText = 'position:relative;';
        const removeBtn = document.createElement('button');
        removeBtn.type  = 'button';
        removeBtn.innerHTML = '✕';
        removeBtn.style.cssText = 'position:absolute;top:6px;right:6px;background:rgba(0,0,0,0.6);color:white;border:none;border-radius:50%;width:26px;height:26px;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;';
        removeBtn.onclick = function() {
            input.value = '';
            area.innerHTML = '';
            area.style.display = 'none';
        };
        wrap.appendChild(el);
        wrap.appendChild(removeBtn);
        area.appendChild(wrap);
    }

    // ── Visibility Selector ────────────────────────────────
    function setVisibility(val) {
        document.getElementById('visibilityInput').value = val;
        const btnPublic = document.getElementById('visPublicBtn');
        const btnCF     = document.getElementById('visCFBtn');
        if (val === 'public') {
            btnPublic.style.cssText = 'background:#e8f5fe;color:#1da1f2;border:1.5px solid #1da1f2;padding:5px 12px;border-radius:20px;font-size:0.82em;font-weight:700;cursor:pointer;transition:0.2s;';
            btnCF.style.cssText     = 'background:white;color:#536471;border:1.5px solid #cfd9de;padding:5px 12px;border-radius:20px;font-size:0.82em;font-weight:700;cursor:pointer;transition:0.2s;';
        } else {
            btnCF.style.cssText     = 'background:rgba(19,78,94,0.12);color:#134e5e;border:1.5px solid #134e5e;padding:5px 12px;border-radius:20px;font-size:0.82em;font-weight:700;cursor:pointer;transition:0.2s;';
            btnPublic.style.cssText = 'background:white;color:#536471;border:1.5px solid #cfd9de;padding:5px 12px;border-radius:20px;font-size:0.82em;font-weight:700;cursor:pointer;transition:0.2s;';
        }
    }
</script>
</body>
</html>
