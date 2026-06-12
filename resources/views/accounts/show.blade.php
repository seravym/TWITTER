<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil {{ $account->name }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f7f9fa; margin: 0; padding: 40px 20px; color: #0f1419; }
        .profile-container { max-width: 650px; margin: 0 auto; background: white; border: 1px solid #eff3f4; border-radius: 24px; box-sizing: border-box; box-shadow: 0 10px 40px rgba(0,0,0,0.06); overflow: hidden; position: relative; }
        
        .profile-banner { height: 160px; background: linear-gradient(120deg, #89f7fe 0%, #66a6ff 100%); position: relative; }
        .btn-back { position: absolute; top: 20px; left: 20px; background: rgba(0,0,0,0.4); color: white; padding: 8px 16px; border-radius: 30px; text-decoration: none; font-weight: bold; font-size: 0.9em; backdrop-filter: blur(5px); transition: 0.2s; z-index: 10; }
        .btn-back:hover { background: rgba(0,0,0,0.6); }

        .profile-content { padding: 20px 30px 30px 30px; position: relative; }
        .threads-header { position: relative; min-height: 60px; margin-bottom: 10px; }
        .threads-info { padding-right: 130px; padding-top: 5px; }
        .threads-name { font-size: 1.8em; font-weight: 900; margin: 0 0 5px 0; color: #0f1419; letter-spacing: -0.5px; }
        .threads-username { color: #536471; font-size: 1em; background: #f7f9fa; padding: 4px 12px; border-radius: 20px; display: inline-block; font-weight: 600; border: 1px solid #eff3f4; }
        
        /* CSS Background dihapus agar bisa dimanipulasi dengan PHP */
        .profile-avatar { position: absolute; right: 0; top: -70px; width: 110px; height: 110px; border-radius: 50%; border: 4px solid white; flex-shrink: 0; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .feed-avatar { border-radius: 50%; flex-shrink: 0; margin: 0; }
        
        .bio-text { font-size: 1.1em; line-height: 1.5; margin: 15px 0; color: #0f1419; }
        .meta-stats { display: flex; gap: 15px; margin: 20px 0 25px 0; font-size: 0.95em; color: #536471; border-bottom: none; }
        .stat-link { background: #fdfdfe; padding: 10px 18px; border-radius: 30px; cursor: pointer; user-select: none; transition: all 0.3s ease; border: 1px solid #eff3f4; }
        .stat-link:hover { background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-3px); color: #0f1419; border-color: #cfd9de; }
        .stat-link strong { color: #0f1419; font-weight: 800; font-size: 1.15em; margin-right: 4px; }

        .btn-follow { background: #0f1419; color: white; border: none; padding: 12px 25px; font-weight: bold; border-radius: 30px; cursor: pointer; width: 100%; font-size: 1.05em; transition: 0.2s; }
        .btn-unfollow { background: white; color: #0f1419; border: 1px solid #cfd9de; padding: 12px 25px; font-weight: bold; border-radius: 30px; cursor: pointer; width: 100%; font-size: 1.05em; transition: 0.2s; }
        .btn-unfollow:hover { border-color: #f4212e; color: #f4212e; background: #fdeced; }
        .btn-edit { background: white; color: #0f1419; border: 1px solid #cfd9de; padding: 12px 25px; font-weight: bold; border-radius: 30px; cursor: pointer; width: 100%; font-size: 1.05em; transition: 0.2s; }
        .btn-edit:hover { background: #f7f9fa; border-color: #cfd9de; box-shadow: inset 0 2px 5px rgba(0,0,0,0.03); }

        .profile-tabs { display: flex; margin-top: 15px; border-top: 1px solid #eff3f4; padding-top: 5px; }
        .tab-item { flex: 1; text-align: center; padding: 15px 0; cursor: pointer; font-weight: 700; color: #536471; border-bottom: 2px solid transparent; transition: 0.2s; }
        .tab-item:hover { color: #0f1419; background-color: #fcfdfe; border-radius: 10px 10px 0 0; }
        .tab-item.active { color: #0f1419; border-bottom: 3px solid #0f1419; }
        .tab-content { display: none; padding-top: 20px; }
        .tab-content.active { display: block; animation: fadeIn 0.3s; }
        .feed-card { border-bottom: 1px solid #eff3f4; padding-bottom: 20px; margin-bottom: 20px; }
        .feed-card:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); }
        .modal-content { background-color: white; margin: 10% auto; padding: 0; border-radius: 24px; width: 90%; max-width: 420px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; }
        .modal-header { font-size: 1.2em; font-weight: 900; padding: 20px; text-align: center; border-bottom: 1px solid #eff3f4; position: relative; }
        .close-btn { position: absolute; left: 20px; top: 18px; font-size: 1.5em; cursor: pointer; color: #0f1419; font-weight: bold; }
        .modal-user-list { list-style: none; padding: 0; margin: 0; max-height: 400px; overflow-y: auto; }
        .modal-user-list li { padding: 15px 20px; border-bottom: 1px solid #f7f9fa; display: flex; justify-content: space-between; align-items: center; transition: 0.2s; }
        .modal-user-list li:hover { background: #fdfdfe; }
        .modal-user-list a { text-decoration: none; color: #0f1419; font-weight: bold; font-size: 1.05em; }
    </style>
</head>
<body>

@php
    function getAvatarGradient($id) {
        $gradients = [
            'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)',
            'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)',
            'linear-gradient(135deg, #fccb90 0%, #d57eeb 100%)',
            'linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%)',
            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)',
            'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)'
        ];
        return $gradients[$id % count($gradients)];
    }
@endphp

<div class="profile-container">
    <div class="profile-banner">
        <a href="/" class="btn-back">← Kembali</a>
    </div>

    <div class="profile-content">
        <div class="threads-header">
            <div class="threads-info">
                <h1 class="threads-name">{{ $account->name }}</h1>
                <div class="threads-username">@ {{ $account->username }}</div>
            </div>
            <div class="profile-avatar" style="background: {{ getAvatarGradient($account->id) }};"></div>
        </div>

        @if(isset($isLocked) && $isLocked)
            <div style="background-color: #f8d7da; padding: 20px; border-radius: 16px; text-align: center; margin-top: 20px;">
                <h2 style="margin: 0; color: #721c24;">🔒 Akun Privat</h2>
                <p style="color: #721c24;">Ikuti akun ini untuk melihat postingannya.</p>
                @auth
                    @php $checkPending = \App\Models\Follow::where('follower_id', Auth::id())->where('following_id', $account->id)->first(); @endphp
                    @if($checkPending && $checkPending->status === 'pending')
                        <button disabled class="btn-edit" style="margin-top: 15px;">⏳ Menunggu Persetujuan</button>
                    @else
                        <form action="/follows" method="POST" style="margin-top: 15px;">
                            @csrf <input type="hidden" name="following_id" value="{{ $account->id }}">
                            <button type="submit" class="btn-follow">Request Follow</button>
                        </form>
                    @endif
                @endauth
            </div>
        @else
            <div class="bio-text">{{ $account->bio ?? 'Belum ada bio.' }}</div>
            
            <div class="meta-stats">
                <span class="stat-link" onclick="openModal('followersModal')">
                    <strong>{{ $account->followers()->where('status', 'accepted')->count() }}</strong> Pengikut
                </span>
                <span class="stat-link" onclick="openModal('followingModal')">
                    <strong>{{ $account->following()->where('status', 'accepted')->count() }}</strong> Mengikuti
                </span>
            </div>

            <div style="margin-bottom: 25px;">
                @auth
                    @if(Auth::id() !== $account->id)
                        @if(Auth::user()->isFollowing($account->id))
                            <form action="/follows/{{ $account->id }}" method="POST" style="display: inline-block; width: 100%;">
                                @csrf @method('DELETE') <button type="submit" class="btn-unfollow">Berhenti Mengikuti</button>
                            </form>
                        @else
                            <form action="/follows" method="POST" style="display: inline-block; width: 100%;">
                                @csrf <input type="hidden" name="following_id" value="{{ $account->id }}">
                                <button type="submit" class="btn-follow">Ikuti Balik</button>
                            </form>
                        @endif
                    @else
                        <a href="/accounts/{{ $account->id }}/edit"><button class="btn-edit">Edit profil</button></a>
                    @endif
                @endauth
            </div>

            <div class="profile-tabs">
                <div class="tab-item active" onclick="switchTab('postsArea', this)">Postingan</div>
                <div class="tab-item" onclick="switchTab('commentsArea', this)">Komentar</div>
            </div>

            <div id="postsArea" class="tab-content active">
                @php $userPosts = $account->posts()->latest()->get(); @endphp
                @if($userPosts->isEmpty())
                    <div style="text-align: center; color: #536471; padding: 40px 0;">Belum ada postingan.</div>
                @else
                    @foreach($userPosts as $post)
                        <div class="feed-card">
                            <div style="display: flex; gap: 15px;">
                                <div class="feed-avatar" style="width: 45px; height: 45px; background: {{ getAvatarGradient($account->id) }};"></div>
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
                    <div style="text-align: center; color: #536471; padding: 40px 0;">Belum ada komentar.</div>
                @else
                    @foreach($userComments as $comment)
                        <div class="feed-card">
                            <div style="display: flex; gap: 15px;">
                                <div class="feed-avatar" style="width: 45px; height: 45px; background: {{ getAvatarGradient($account->id) }};"></div>
                                <div>
                                    <div style="font-weight: bold; color: #0f1419;">{{ $account->name }} <span style="color: #536471; font-weight: normal; font-size: 0.9em;">• berkomentar</span></div>
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

<div id="followersModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close-btn" onclick="closeModal('followersModal')">×</span>
            Pengikut
        </div>
        <ul class="modal-user-list">
            @php $myFollowers = $account->followers()->where('status', 'accepted')->with('follower')->get(); @endphp
            @if($myFollowers->isEmpty()) <li style="justify-content: center; color: gray;">Belum ada pengikut.</li>
            @else
                @foreach($myFollowers as $f)
                    <li>
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <div class="feed-avatar" style="width: 35px; height: 35px; background: {{ getAvatarGradient($f->follower->id) }};"></div>
                            <div>
                                <a href="/accounts/{{ $f->follower->id }}">{{ $f->follower->name }}</a>
                                <div style="color: #536471; font-size: 0.9em;">@ {{ $f->follower->username }}</div>
                            </div>
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
            Mengikuti
        </div>
        <ul class="modal-user-list">
            @php $myFollowing = $account->following()->where('status', 'accepted')->with('following')->get(); @endphp
            @if($myFollowing->isEmpty()) <li style="justify-content: center; color: gray;">Belum mengikuti siapapun.</li>
            @else
                @foreach($myFollowing as $g)
                    <li>
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <div class="feed-avatar" style="width: 35px; height: 35px; background: {{ getAvatarGradient($g->following->id) }};"></div>
                            <div>
                                <a href="/accounts/{{ $g->following->id }}">{{ $g->following->name }}</a>
                                <div style="color: #536471; font-size: 0.9em;">@ {{ $g->following->username }}</div>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).style.display = "block"; }
    function closeModal(id) { document.getElementById(id).style.display = "none"; }
    window.onclick = function(e) { if (e.target.classList.contains('modal')) e.target.style.display = "none"; }
    function switchTab(tabId, element) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-item').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        element.classList.add('active');
    }
</script>
</body>
</html>