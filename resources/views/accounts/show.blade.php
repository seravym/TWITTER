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
        .threads-info { padding-right: 150px; padding-top: 5px; }
        .threads-name { font-size: 1.8em; font-weight: 900; margin: 0 0 5px 0; color: #0f1419; letter-spacing: -0.5px; }
        .threads-username { color: #536471; font-size: 1em; background: #f7f9fa; padding: 4px 12px; border-radius: 20px; display: inline-block; font-weight: 600; border: 1px solid #eff3f4; }
        
        .profile-avatar-wrapper { position: absolute; right: 0; top: -85px; width: 130px; height: 130px; z-index: 5; }
        .profile-avatar { box-sizing: border-box; width: 100%; height: 100%; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; font-size: 55px; color: white; font-weight: bold; text-transform: uppercase; background-color: #fff; position: relative; z-index: 2; }
        
        .story-ring {
            position: absolute; top: -4px; left: -4px; right: -4px; bottom: -4px;
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            border-radius: 50%; z-index: 1;
        }
        
        .status-badge { 
            position: absolute; bottom: 5px; left: -5px; width: 36px; height: 36px; 
            background: #1d9bf0; color: white; border: 3px solid white; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; font-size: 22px; 
            font-weight: bold; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            transition: 0.2s; padding: 0; line-height: 1; z-index: 25;
        }
        .status-badge:hover { transform: scale(1.1); background: #1a8cd8; }

        .status-bubble {
            position: absolute; top: 140px; right: 0; background: white; border: 1px solid #eff3f4;
            padding: 8px 16px; border-radius: 20px; font-size: 13px; color: #0f1419;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); font-weight: 600; z-index: 20;
            animation: float 3s ease-in-out infinite; max-width: 180px; width: max-content;
            word-wrap: break-word; text-align: center; cursor: pointer; transition: 0.2s;
        }
        .status-bubble.empty-bubble { color: #536471; font-weight: normal; font-style: italic; }
        .status-bubble:hover { background: #f7f9fa; transform: translateY(-2px); }
        .status-bubble::after {
            content: ''; position: absolute; top: -6px; right: 40px; width: 12px; height: 12px;
            background: white; border-top: 1px solid #eff3f4; border-left: 1px solid #eff3f4;
            transform: rotate(45deg);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .feed-avatar { border-radius: 50%; flex-shrink: 0; margin: 0; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; text-transform: uppercase; }
        .bio-text { font-size: 1.1em; line-height: 1.5; margin: 30px 0 15px 0; color: #0f1419; } 
        .meta-stats { display: flex; gap: 15px; margin: 20px 0 25px 0; font-size: 0.95em; color: #536471; border-bottom: none; }
        .stat-link { background: #fdfdfe; padding: 10px 18px; border-radius: 30px; cursor: pointer; user-select: none; transition: all 0.3s ease; border: 1px solid #eff3f4; }
        .stat-link:hover { background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-3px); color: #0f1419; border-color: #cfd9de; }
        .stat-link strong { color: #0f1419; font-weight: 800; font-size: 1.15em; margin-right: 4px; }

        .btn-follow, .btn-unfollow, .btn-edit { padding: 12px 25px; font-weight: bold; border-radius: 30px; cursor: pointer; width: 100%; font-size: 1.05em; transition: 0.2s; }
        .btn-follow { background: #0f1419; color: white; border: none; }
        .btn-unfollow { background: white; color: #0f1419; border: 1px solid #cfd9de; }
        .btn-unfollow:hover { border-color: #f4212e; color: #f4212e; background: #fdeced; }
        .btn-edit { background: white; color: #0f1419; border: 1px solid #cfd9de; }
        .btn-edit:hover { background: #f7f9fa; box-shadow: inset 0 2px 5px rgba(0,0,0,0.03); }

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

        .story-input, .status-input, .status-select { width: 100%; padding: 12px 15px; border: 1px solid #cfd9de; border-radius: 12px; margin-bottom: 15px; font-size: 1em; box-sizing: border-box; outline: none; background: white; }
        .status-input:focus { border-color: #1d9bf0; }
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

    $prompts = ['Apa mood mu hari ini?', 'Lagi dengerin lagu apa?', 'Pengen ngunyah makanan?', 'Lagi sibuk apa nih?'];
    $randomPrompt = $prompts[array_rand($prompts)];
    
    $hasActiveStory = $account->stories()->where('expires_at', '>', now())->exists();
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
            
            <div class="profile-avatar-wrapper">
                
                @if($hasActiveStory)
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
                
                @if($account->active_status)
                    <div class="status-bubble" onclick="openModal('statusModal')">
                        💭 {{ $account->active_status }}
                    </div>
                @elseif(Auth::id() === $account->id)
                    <div class="status-bubble empty-bubble" onclick="openModal('statusModal')">
                        ➕ {{ $randomPrompt }}
                    </div>
                @endif

                @auth
                    @if(Auth::id() === $account->id)
                        <button class="status-badge" onclick="openModal('storyModal')">+</button>
                    @endif
                @endauth
            </div>
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
                    <div style="text-align: center; color: #536471; padding: 40px 0;">Belum ada komentar.</div>
                @else
                    @foreach($userComments as $comment)
                        <div class="feed-card">
                            <div style="display: flex; gap: 15px;">
                                <div class="feed-avatar" style="width: 45px; height: 45px; font-size: 18px; background: {{ getAvatarGradient($account->id) }};">
                                    {{ substr($account->name, 0, 1) }}
                                </div>
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

<div id="statusModal" class="modal">
    <div class="modal-content" style="padding: 25px;">
        <div class="modal-header" style="border: none; padding: 0 0 15px 0;">
            <span class="close-btn" onclick="closeModal('statusModal')" style="top: 0; right: 0; left: auto;">×</span>
            <h3>Set Notes Hari Ini</h3>
        </div>
        
        <form action="{{ route('accounts.status') }}" method="POST">
            @csrf
            <input type="text" name="status_text" class="status-input" placeholder="Tulis sesuatu..." maxlength="50" required autocomplete="off">
            <label style="font-size: 14px; font-weight: bold; color: #536471; display: block; margin-bottom: 8px;">Tampilkan notes selama:</label>
            <select name="duration" class="status-select">
                <option value="24_hours">24 Jam</option>
                <option value="3_days">3 Hari</option>
                <option value="1_week">1 Minggu</option>
            </select>
            <button type="submit" class="btn-follow" style="width: 100%; border-radius: 12px; padding: 14px;">Simpan Notes</button>
        </form>
    </div>
</div>

<div id="storyModal" class="modal">
    <div class="modal-content" style="padding: 25px;">
        <div class="modal-header" style="border: none; padding: 0 0 15px 0;">
            <span class="close-btn" onclick="closeModal('storyModal')" style="top: 0; right: 0; left: auto;">×</span>
            <h3>Unggah Story Baru</h3>
        </div>
        
        <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label style="font-size: 14px; font-weight: bold; color: #536471; display: block; margin-bottom: 8px;">Pilih Gambar/Video (maks. 2MB):</label>
            <input type="file" name="media" class="story-input" accept="image/*" required>
            <button type="submit" class="btn-follow" style="width: 100%; border-radius: 12px; padding: 14px;">Unggah Story</button>
        </form>
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
                            <div class="feed-avatar" style="width: 35px; height: 35px; font-size: 14px; background: {{ getAvatarGradient($f->follower->id) }};">
                                {{ substr($f->follower->name, 0, 1) }}
                            </div>
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
                            <div class="feed-avatar" style="width: 35px; height: 35px; font-size: 14px; background: {{ getAvatarGradient($g->following->id) }};">
                                {{ substr($g->following->name, 0, 1) }}
                            </div>
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