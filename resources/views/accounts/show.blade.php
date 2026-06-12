<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $account->name }}'s Profile</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f7f9fa; margin: 0; padding: 40px 20px; color: #0f1419; }
        .profile-container { max-width: 650px; margin: 0 auto; background: white; border: 1px solid #eff3f4; border-radius: 24px; box-sizing: border-box; box-shadow: 0 10px 40px rgba(0,0,0,0.06); overflow: hidden; position: relative; }
        
        .profile-banner { height: 160px; background: linear-gradient(120deg, #89f7fe 0%, #66a6ff 100%); position: relative; }
        .btn-back { position: absolute; top: 20px; left: 20px; background: rgba(0,0,0,0.4); color: white; padding: 8px 16px; border-radius: 30px; text-decoration: none; font-weight: bold; font-size: 0.9em; backdrop-filter: blur(5px); transition: 0.2s; z-index: 10; }
        .btn-back:hover { background: rgba(0,0,0,0.6); }

        .profile-content { padding: 20px 30px 30px 30px; position: relative; }
        .threads-header { position: relative; min-height: 60px; margin-bottom: 10px; }
        .threads-info { padding-right: 150px; padding-top: 5px; }
        .threads-name { font-size: 1.8em; font-weight: 900; margin: 0 0 5px 0; color: #0f1419; letter-spacing: -0.5px; }
        .threads-username { color: #536471; font-size: 1em; background: #f7f9fa; padding: 4px 12px; border-radius: 20px; display: inline-block; font-weight: 600; border: 1px solid #eff3f4; }
        
        .profile-avatar-wrapper { position: absolute; right: 0; top: -85px; width: 130px; height: 130px; z-index: 5; }
        .profile-avatar { box-sizing: border-box; width: 100%; height: 100%; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; font-size: 55px; color: white; font-weight: bold; text-transform: uppercase; background-color: #fff; position: relative; z-index: 2; }
        
        .story-ring { position: absolute; top: -4px; left: -4px; right: -4px; bottom: -4px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); border-radius: 50%; z-index: 1; }
        
        .status-badge { position: absolute; bottom: 5px; left: -5px; width: 36px; height: 36px; background: #1d9bf0; color: white; border: 3px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: bold; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: 0.2s; padding: 0; line-height: 1; z-index: 25; }
        .status-badge:hover { transform: scale(1.1); background: #1a8cd8; }

        .status-bubble { position: absolute; top: 140px; right: 0; background: white; border: 1px solid #eff3f4; padding: 8px 16px; border-radius: 20px; font-size: 13px; color: #0f1419; box-shadow: 0 4px 15px rgba(0,0,0,0.08); font-weight: 600; z-index: 20; animation: float 3s ease-in-out infinite; max-width: 180px; width: max-content; word-wrap: break-word; text-align: center; cursor: pointer; transition: 0.2s; }
        .status-bubble.empty-bubble { color: #536471; font-weight: normal; font-style: italic; }
        .status-bubble:hover { background: #f7f9fa; transform: translateY(-2px); }
        .status-bubble::after { content: ''; position: absolute; top: -6px; right: 40px; width: 12px; height: 12px; background: white; border-top: 1px solid #eff3f4; border-left: 1px solid #eff3f4; transform: rotate(45deg); }

        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }

        .feed-avatar { border-radius: 50%; flex-shrink: 0; margin: 0; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; text-transform: uppercase; transition: 0.2s; }
        .feed-avatar:hover { opacity: 0.8; }
        
        .bio-text { font-size: 1.1em; line-height: 1.5; margin: 25px 0 5px 0; color: #0f1419; } 
        
        .mutual-text { font-size: 0.9em; color: #536471; margin-bottom: 15px; }
        .mutual-text strong { color: #0f1419; }

        .meta-stats { display: flex; gap: 15px; margin: 20px 0 25px 0; font-size: 0.95em; color: #536471; border-bottom: none; }
        .stat-link { background: #fdfdfe; padding: 10px 18px; border-radius: 30px; cursor: pointer; user-select: none; transition: all 0.3s ease; border: 1px solid #eff3f4; }
        .stat-link:hover:not(.locked) { background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-3px); color: #0f1419; border-color: #cfd9de; }
        .stat-link.locked { cursor: default; opacity: 0.6; background: transparent; border-color: transparent; padding: 10px 0; }
        .stat-link strong { color: #0f1419; font-weight: 800; font-size: 1.15em; margin-right: 4px; }

        .btn-follow, .btn-edit { padding: 12px 25px; font-weight: bold; border-radius: 30px; cursor: pointer; width: 100%; font-size: 1.05em; transition: 0.2s; }
        .btn-follow { background: #0f1419; color: white; border: none; }
        .btn-edit { background: white; color: #0f1419; border: 1px solid #cfd9de; }
        .btn-edit:hover { background: #f7f9fa; box-shadow: inset 0 2px 5px rgba(0,0,0,0.03); }
        .btn-edit:disabled { background: #f7f9fa; color: #8899a6; cursor: not-allowed; border-color: #eff3f4; }

        .btn-list-action { width: 120px; text-align: center; box-sizing: border-box; display: inline-block; padding: 8px 0; border-radius: 20px; font-weight: bold; font-size: 0.85em; cursor: pointer; transition: 0.2s; }
        .btn-list-follow { background: #0f1419; color: white; border: none; }
        .btn-list-follow:hover { background: #272c30; }
        
        .btn-following { background: white; color: #0f1419; border: 1px solid #cfd9de; }
        .btn-following .text-hover { display: none; }
        .btn-following:hover { background: #fdeced; color: #f4212e; border-color: #fdeced; }
        .btn-following:hover .text-default { display: none; }
        .btn-following:hover .text-hover { display: inline; }
        
        .btn-list-pending { background: #f7f9fa; color: #536471; border: 1px solid #cfd9de; cursor: not-allowed; }

        .action-menu-dropdown { display: none; position: absolute; right: 0; top: 35px; background: white; box-shadow: 0 4px 25px rgba(0,0,0,0.12); border-radius: 16px; z-index: 100; min-width: 220px; padding: 8px 0; overflow: hidden; border: 1px solid #eff3f4; }
        .action-menu-dropdown a { display: block; padding: 14px 20px; color: #0f1419; text-decoration: none; font-size: 0.95em; font-weight: 600; border-bottom: 1px solid #f7f9fa; transition: 0.2s; text-align: left; }
        .action-menu-dropdown a:last-child { border-bottom: none; }
        .action-menu-dropdown a:hover { background: #f7f9fa; }
        .action-menu-dropdown a.text-danger { color: #f4212e; }
        .action-menu-dropdown a.text-danger:hover { background: #fdeced; }

        .profile-tabs { display: flex; margin-top: 25px; border-top: 1px solid #eff3f4; padding-top: 5px; }
        .tab-item { flex: 1; text-align: center; padding: 15px 0; cursor: pointer; font-weight: 700; color: #536471; border-bottom: 2px solid transparent; transition: 0.2s; }
        .tab-item:hover { color: #0f1419; background-color: #fcfdfe; border-radius: 10px 10px 0 0; }
        .tab-item.active { color: #0f1419; border-bottom: 3px solid #0f1419; }
        .tab-content { display: none; padding-top: 20px; }
        .tab-content.active { display: block; animation: fadeIn 0.3s; }
        .feed-card { border-bottom: 1px solid #eff3f4; padding-bottom: 20px; margin-bottom: 20px; }
        .feed-card:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); }
        .modal-content { background-color: white; margin: 4% auto; padding: 0; border-radius: 24px; width: 90%; max-width: 600px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; position: relative; display: flex; flex-direction: column; min-height: 60vh; }
        .modal-header { font-size: 1.2em; font-weight: 900; padding: 20px; text-align: center; border-bottom: 1px solid #eff3f4; position: relative; flex-shrink: 0; }
        
        .close-btn { position: absolute; right: 20px; top: 18px; font-size: 1.5em; cursor: pointer; color: #0f1419; font-weight: bold; }
        .sort-select { position: absolute; left: 20px; top: 22px; font-size: 0.85em; padding: 6px 10px; border-radius: 12px; border: 1px solid #cfd9de; outline: none; background-color: #f7f9fa; cursor: pointer; font-weight: bold; }
        
        .modal-user-list { list-style: none; padding: 0; margin: 0; max-height: 65vh; overflow-y: auto; flex: 1; }
        .modal-user-list li { padding: 15px 20px; border-bottom: 1px solid #f7f9fa; display: flex; justify-content: space-between; align-items: center; transition: 0.2s; }
        .modal-user-list li:hover { background: #fcfdfe; }
        .modal-user-list a { text-decoration: none; color: #0f1419; font-weight: bold; font-size: 1.05em; }
        .modal-user-list a:hover { text-decoration: underline; }

        .story-input, .status-input, .status-select { width: 100%; padding: 12px 15px; border: 1px solid #cfd9de; border-radius: 12px; margin-bottom: 15px; font-size: 1em; box-sizing: border-box; outline: none; background: white; }
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

    $prompts = ['How are you feeling today?', 'What song is on your mind?', 'Craving some food?', 'What are you busy with?'];
    $randomPrompt = $prompts[array_rand($prompts)];
    $hasActiveStory = $account->stories()->where('expires_at', '>', now())->exists();

    $isMyProfile = Auth::id() === $account->id;
    $amFollowing = false;
    $isPending = false;
    $isFollowingMe = false;
    $isLocked = false;
    $mutuals = collect();
    
    $myFollowingIdsList = [];
    $myPendingIdsList = [];
    $followersOfMeList = [];

    if (Auth::check()) {
        $myFollowingIdsList = Auth::user()->following()->where('status', 'accepted')->pluck('following_id')->toArray();
        $myPendingIdsList = Auth::user()->following()->where('status', 'pending')->pluck('following_id')->toArray();
        $followersOfMeList = Auth::user()->followers()->where('status', 'accepted')->pluck('follower_id')->toArray();
        
        if(!$isMyProfile) {
            $amFollowing = in_array($account->id, $myFollowingIdsList);
            $isPending = in_array($account->id, $myPendingIdsList);
            $isFollowingMe = in_array($account->id, $followersOfMeList);
            
            $targetSetting = \App\Models\Setting::where('account_id', $account->id)->first();
            $isPrivate = $targetSetting && $targetSetting->isPrivateAccount;
            $isLocked = $isPrivate && !$amFollowing;

            $mutuals = $account->followers()->whereIn('follower_id', $myFollowingIdsList)->where('status', 'accepted')->with('follower')->take(2)->get();
        }
    }
@endphp

<div class="profile-container">
    <div class="profile-banner">
        <a href="/" class="btn-back">← Back</a>
    </div>

    <div class="profile-content">
        <div class="threads-header">
            <div class="threads-info">
                <h1 class="threads-name">{{ $account->name }}</h1>
                <div class="threads-username">@ {{ $account->username }}</div>
            </div>
            
            <div class="profile-avatar-wrapper">
                @if(!$isLocked && $hasActiveStory)
                    <a href="{{ route('stories.show', $account->id) }}" style="text-decoration: none; display: block; width: 100%; height: 100%;">
                        <div class="story-ring"></div>
                        <div class="profile-avatar" style="background: {{ getAvatarGradient($account->id) }};">
                            {{ substr($account->name, 0, 1) }}
                        </div>
                    </a>
                @else
                    <div class="profile-avatar" style="background: {{ getAvatarGradient($account->id) }};">
                        {{ substr($account->name, 0, 1) }}
                    </div>
                @endif
                
                @if(!$isLocked && $account->active_status)
                    <div class="status-bubble" onclick="{{ $isMyProfile ? "openModal('statusModal')" : "" }}">
                        💭 {{ $account->active_status }}
                    </div>
                @elseif($isMyProfile)
                    <div class="status-bubble empty-bubble" onclick="openModal('statusModal')">
                        ➕ {{ $randomPrompt }}
                    </div>
                @endif

                @if($isMyProfile)
                    <button class="status-badge" onclick="openModal('storyModal')">+</button>
                @endif
            </div>
        </div>

        <div class="bio-text">{{ $account->bio ?? 'No bio yet.' }}</div>
        
        @if(!$isLocked && !$isMyProfile && $mutuals->count() > 0)
            <div class="mutual-text">
                Followed by 
                @foreach($mutuals as $index => $mutual)
                    <a href="/accounts/{{ $mutual->follower->id }}" style="color: #0f1419; text-decoration: none; font-weight: bold;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                        {{ '@' . $mutual->follower->username }}
                    </a>{{ $index < $mutuals->count() - 1 ? ', ' : '' }}
                @endforeach
                @if($account->followers()->whereIn('follower_id', $myFollowingIdsList)->where('status', 'accepted')->count() > 2)
                    and others
                @endif
            </div>
        @endif
        
        <div class="meta-stats">
            <span class="stat-link {{ $isLocked ? 'locked' : '' }}" onclick="{{ $isLocked ? '' : "openModal('followersModal')" }}">
                <strong>{{ $account->followers()->where('status', 'accepted')->count() }}</strong> Followers
            </span>
            <span class="stat-link {{ $isLocked ? 'locked' : '' }}" onclick="{{ $isLocked ? '' : "openModal('followingModal')" }}">
                <strong>{{ $account->following()->where('status', 'accepted')->count() }}</strong> Following
            </span>
        </div>

        <div style="margin-bottom: 10px;">
            @auth
                @if(!$isMyProfile)
                    @if($amFollowing)
                        <form id="form-unfollow-main" action="/follows/{{ $account->id }}" method="POST">
                            @csrf @method('DELETE') 
                            <button type="submit" class="btn-edit btn-following" style="width:100%; border-radius: 30px; padding: 12px 25px;" onclick="attemptUnfollow(event, 'form-unfollow-main', {{ $isPrivate ? 'true' : 'false' }}, '{{ $account->username }}')">
                                <span class="text-default">Following</span>
                                <span class="text-hover">Unfollow</span>
                            </button>
                        </form>
                    @elseif($isPending)
                        <button class="btn-edit" disabled>⏳ Pending</button>
                    @else
                        <form action="/follows" method="POST">
                            @csrf <input type="hidden" name="following_id" value="{{ $account->id }}">
                            <button type="submit" class="btn-follow">{{ $isFollowingMe ? 'Follow Back' : 'Follow' }}</button>
                        </form>
                    @endif
                @else
                    <a href="/accounts/{{ $account->id }}/edit" style="display: block; width: 100%;">
                        <button class="btn-edit">Edit Profile</button>
                    </a>
                @endif
            @endauth
        </div>

        @if($isLocked)
            <div style="background-color: #f8d7da; padding: 40px 20px; border-radius: 16px; text-align: center; margin-top: 30px; border: 1px solid #f5c6cb;">
                <h2 style="margin: 0 0 10px 0; color: #721c24;">🔒 Private Account</h2>
                <p style="color: #721c24; margin: 0;">Follow this account to see their posts and comments.</p>
            </div>
        @else
            <div class="profile-tabs">
                <div class="tab-item active" onclick="switchTab('postsArea', this)">Posts</div>
                <div class="tab-item" onclick="switchTab('commentsArea', this)">Comments</div>
            </div>

            <div id="postsArea" class="tab-content active">
                @php $userPosts = $account->posts()->latest()->get(); @endphp
                @if($userPosts->isEmpty())
                    <div style="text-align: center; color: #536471; padding: 40px 0;">No posts yet.</div>
                @else
                    @foreach($userPosts as $post)
                        <div class="feed-card">
                            <div style="display: flex; gap: 15px;">
                                <div class="feed-avatar" style="width: 45px; height: 45px; font-size: 18px; background: {{ getAvatarGradient($account->id) }};">
                                    {{ substr($account->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: bold; color: #0f1419;">{{ $account->name }} <span style="color: #536471; font-weight: normal; font-size: 0.9em;">• {{ $post->created_at->diffForHumans() }}</span></div>
                                    <div style="margin-top: 5px; font-size: 1.05em; line-height: 1.5;">{{ $post->content }}</div>
                                    <div style="margin-top: 10px; color: #536471; font-size: 0.9em;">❤️ {{ $post->likes()->count() }} • 💬 {{ $post->comments()->count() }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div id="commentsArea" class="tab-content">
                @php $userComments = $account->comments()->latest()->get(); @endphp
                @if($userComments->isEmpty())
                    <div style="text-align: center; color: #536471; padding: 40px 0;">No comments yet.</div>
                @else
                    @foreach($userComments as $comment)
                        <div class="feed-card">
                            <div style="display: flex; gap: 15px;">
                                <div class="feed-avatar" style="width: 45px; height: 45px; font-size: 18px; background: {{ getAvatarGradient($account->id) }};">
                                    {{ substr($account->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: bold; color: #0f1419;">{{ $account->name }} <span style="color: #536471; font-weight: normal; font-size: 0.9em;">• commented</span></div>
                                    <div style="margin-top: 5px; font-size: 1.05em; line-height: 1.5;">{{ $comment->content }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @endif
    </div>
</div>

<div id="statusModal" class="modal">
    <div class="modal-content" style="padding: 25px; min-height: auto;">
        <div class="modal-header" style="border: none; padding: 0 0 15px 0;">
            <span class="close-btn" onclick="closeModal('statusModal')">×</span>
            <h3>Set Today's Note</h3>
        </div>
        <form action="{{ route('accounts.status') }}" method="POST">
            @csrf
            <input type="text" name="status_text" class="status-input" placeholder="Write something..." maxlength="50" required autocomplete="off">
            <label style="font-size: 14px; font-weight: bold; color: #536471; display: block; margin-bottom: 8px;">Show note for:</label>
            <select name="duration" class="status-select">
                <option value="24_hours">24 Hours</option>
                <option value="3_days">3 Days</option>
                <option value="1_week">1 Week</option>
            </select>
            <button type="submit" class="btn-follow" style="width: 100%; border-radius: 12px; padding: 14px;">Save Note</button>
        </form>
    </div>
</div>

<div id="storyModal" class="modal">
    <div class="modal-content" style="padding: 25px; min-height: auto;">
        <div class="modal-header" style="border: none; padding: 0 0 15px 0;">
            <span class="close-btn" onclick="closeModal('storyModal')">×</span>
            <h3>Upload New Story</h3>
        </div>
        <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label style="font-size: 14px; font-weight: bold; color: #536471; display: block; margin-bottom: 8px;">Select Image/Video (max. 2MB):</label>
            <input type="file" name="media" class="story-input" accept="image/*" required>
            <button type="submit" class="btn-follow" style="width: 100%; border-radius: 12px; padding: 14px;">Upload Story</button>
        </form>
    </div>
</div>

<div id="unfollowConfirmModal" class="modal">
    <div class="modal-content" style="padding: 30px; text-align: center; max-width: 400px; margin: 15% auto; min-height: auto;">
        <h3 style="margin-top: 0; font-size: 1.4em;">Unfollow @<span id="unfollow-username"></span>?</h3>
        <p style="color: #536471; margin-bottom: 25px; line-height: 1.5;">Their Tweets will no longer show up in your home timeline. You will need to request to follow them again since their account is private.</p>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <button id="confirmUnfollowBtn" style="background: #f4212e; color: white; border: none; padding: 14px; border-radius: 30px; font-weight: bold; cursor: pointer; font-size: 1.05em; transition: 0.2s;">Unfollow</button>
            <button onclick="closeModal('unfollowConfirmModal')" style="background: white; color: #0f1419; border: 1px solid #cfd9de; padding: 14px; border-radius: 30px; font-weight: bold; cursor: pointer; font-size: 1.05em; transition: 0.2s;">Cancel</button>
        </div>
    </div>
</div>

<div id="followersModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-btn" onclick="closeModal('followersModal')">×</span>
            Followers
            <select class="sort-select" onchange="window.location.href='?sort=' + this.value + '&modal=followersModal'">
                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
            </select>
        </div>
        <ul class="modal-user-list">
            @php 
                $fSort = request('sort') === 'oldest' ? 'asc' : 'desc';
                $myFollowers = $account->followers()->where('status', 'accepted')->orderBy('created_at', $fSort)->with('follower')->get(); 
            @endphp
            @if($myFollowers->isEmpty()) <li style="justify-content: center; color: gray;">No followers yet.</li>
            @else
                @foreach($myFollowers as $f)
                    @php 
                        $uid = $f->follower->id; 
                        $isUserPrivate = \App\Models\Setting::where('account_id', $uid)->value('isPrivateAccount') ?? false;
                    @endphp
                    <li>
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <a href="/accounts/{{ $uid }}" style="text-decoration: none;">
                                <div class="feed-avatar" style="width: 44px; height: 44px; font-size: 16px; background: {{ getAvatarGradient($uid) }};">
                                    {{ substr($f->follower->name, 0, 1) }}
                                </div>
                            </a>
                            <div style="flex: 1;">
                                <a href="/accounts/{{ $uid }}" style="display: block;">{{ $f->follower->name }}</a>
                                <div style="color: #536471; font-size: 0.9em; line-height: 1.2; margin-top: 2px;">@ {{ $f->follower->username }}</div>
                            </div>
                            
                            @auth
                                @if(Auth::id() !== $uid)
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        @if(in_array($uid, $myFollowingIdsList))
                                            <form id="form-unf-f-{{$uid}}" action="/follows/{{ $uid }}" method="POST" style="margin: 0;">
                                                @csrf @method('DELETE') 
                                                <button type="submit" class="btn-list-action btn-following" onclick="attemptUnfollow(event, 'form-unf-f-{{$uid}}', {{ $isUserPrivate ? 'true' : 'false' }}, '{{ $f->follower->username }}')">
                                                    <span class="text-default">Following</span>
                                                    <span class="text-hover">Unfollow</span>
                                                </button>
                                            </form>
                                        @elseif(in_array($uid, $myPendingIdsList))
                                            <button class="btn-list-action btn-list-pending" disabled>Pending</button>
                                        @else
                                            <form action="/follows" method="POST" style="margin: 0;">
                                                @csrf <input type="hidden" name="following_id" value="{{ $uid }}">
                                                <button type="submit" class="btn-list-action btn-list-follow">{{ in_array($uid, $followersOfMeList) ? 'Follow Back' : 'Follow' }}</button>
                                            </form>
                                        @endif
                                        
                                        <div style="position: relative; display: inline-block;">
                                            <button onclick="toggleActionMenu(event, 'drop-f-{{$uid}}')" style="background: none; border: none; color: #536471; font-weight: bold; font-size: 1.4em; padding: 0 5px; cursor: pointer; line-height: 1;">⋮</button>
                                            <div id="drop-f-{{$uid}}" class="action-menu-dropdown action-menu-popup">
                                                <a href="/messages/{{ $uid }}">💬 Direct Message</a>
                                                <a href="#" class="text-danger">🚫 Block / Report</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>

<div id="followingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-btn" onclick="closeModal('followingModal')">×</span>
            Following
            <select class="sort-select" onchange="window.location.href='?sort=' + this.value + '&modal=followingModal'">
                <option value="auto" {{ request('sort', 'auto') == 'auto' ? 'selected' : '' }}>Default</option>
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
            </select>
        </div>
        <ul class="modal-user-list">
            @php 
                $currentSort = request('sort', 'auto');
                $gSort = $currentSort === 'oldest' ? 'asc' : 'desc';
                $myFollowing = $account->following()->where('status', 'accepted')->orderBy('created_at', $gSort)->with('following')->get();
                
                // SISTEM FILTERISASI AUTO: Dahulukan akun sendiri, lalu yang di-follow, lalu yang belum.
                if(Auth::check() && $myFollowing->isNotEmpty() && $currentSort === 'auto') {
                    $me = $myFollowing->filter(fn($i) => $i->following->id === Auth::id());
                    $followedByMe = $myFollowing->filter(fn($i) => $i->following->id !== Auth::id() && in_array($i->following->id, $myFollowingIdsList));
                    $notFollowedByMe = $myFollowing->filter(fn($i) => $i->following->id !== Auth::id() && !in_array($i->following->id, $myFollowingIdsList));
                    
                    $myFollowing = $me->merge($followedByMe)->merge($notFollowedByMe);
                }
            @endphp
            @if($myFollowing->isEmpty()) <li style="justify-content: center; color: gray;">Not following anyone yet.</li>
            @else
                @foreach($myFollowing as $g)
                    @php 
                        $uid = $g->following->id; 
                        $isUserPrivate = \App\Models\Setting::where('account_id', $uid)->value('isPrivateAccount') ?? false;
                    @endphp
                    <li>
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <a href="/accounts/{{ $uid }}" style="text-decoration: none;">
                                <div class="feed-avatar" style="width: 44px; height: 44px; font-size: 16px; background: {{ getAvatarGradient($uid) }};">
                                    {{ substr($g->following->name, 0, 1) }}
                                </div>
                            </a>
                            <div style="flex: 1;">
                                <a href="/accounts/{{ $uid }}" style="display: block;">{{ $g->following->name }}</a>
                                <div style="color: #536471; font-size: 0.9em; line-height: 1.2; margin-top: 2px;">@ {{ $g->following->username }}</div>
                            </div>

                            @auth
                                @if(Auth::id() !== $uid)
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        @if(in_array($uid, $myFollowingIdsList))
                                            <form id="form-unf-g-{{$uid}}" action="/follows/{{ $uid }}" method="POST" style="margin: 0;">
                                                @csrf @method('DELETE') 
                                                <button type="submit" class="btn-list-action btn-following" onclick="attemptUnfollow(event, 'form-unf-g-{{$uid}}', {{ $isUserPrivate ? 'true' : 'false' }}, '{{ $g->following->username }}')">
                                                    <span class="text-default">Following</span>
                                                    <span class="text-hover">Unfollow</span>
                                                </button>
                                            </form>
                                        @elseif(in_array($uid, $myPendingIdsList))
                                            <button class="btn-list-action btn-list-pending" disabled>Pending</button>
                                        @else
                                            <form action="/follows" method="POST" style="margin: 0;">
                                                @csrf <input type="hidden" name="following_id" value="{{ $uid }}">
                                                <button type="submit" class="btn-list-action btn-list-follow">{{ in_array($uid, $followersOfMeList) ? 'Follow Back' : 'Follow' }}</button>
                                            </form>
                                        @endif
                                        
                                        <div style="position: relative; display: inline-block;">
                                            <button onclick="toggleActionMenu(event, 'drop-g-{{$uid}}')" style="background: none; border: none; color: #536471; font-weight: bold; font-size: 1.4em; padding: 0 5px; cursor: pointer; line-height: 1;">⋮</button>
                                            <div id="drop-g-{{$uid}}" class="action-menu-dropdown action-menu-popup">
                                                <a href="/messages/{{ $uid }}">💬 Direct Message</a>
                                                <a href="#" class="text-danger">🚫 Block / Report</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>

<script>
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.has('modal')) {
            openModal(urlParams.get('modal'));
        }
    }

    function openModal(id) { document.getElementById(id).style.display = "flex"; }
    function closeModal(id) { document.getElementById(id).style.display = "none"; }
    
    let pendingUnfollowForm = null;
    function attemptUnfollow(event, formId, isPrivate, username) {
        event.preventDefault();
        pendingUnfollowForm = document.getElementById(formId);
        
        if(isPrivate) {
            document.getElementById('unfollow-username').innerText = username;
            document.querySelectorAll('.modal').forEach(m => m.style.display = 'none');
            openModal('unfollowConfirmModal');
        } else {
            pendingUnfollowForm.submit();
        }
    }
    
    document.getElementById('confirmUnfollowBtn').addEventListener('click', function() {
        if(pendingUnfollowForm) pendingUnfollowForm.submit();
    });

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

    function switchTab(tabId, element) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-item').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        element.classList.add('active');
    }
</script>
</body>
</html>