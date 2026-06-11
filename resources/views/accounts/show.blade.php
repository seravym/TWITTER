<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil {{ $account->name }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f7f9fa; margin: 0; padding: 20px; color: #0f1419; }
        .profile-container { max-width: 650px; margin: 0 auto; background: white; border: 1px solid #e1e8ed; border-radius: 12px; padding: 25px; box-sizing: border-box; }
        .btn-back { display: inline-block; text-decoration: none; color: #1da1f2; font-weight: bold; margin-bottom: 20px; }
        .meta-stats { background: #f7f9fa; padding: 12px; border-radius: 8px; margin: 15px 0; display: inline-block; font-size: 0.95em; }
        .btn-follow { background: #0f1419; color: white; border: none; padding: 8px 20px; font-weight: bold; border-radius: 30px; cursor: pointer; }
        .btn-unfollow { background: #ef3b2c; color: white; border: none; padding: 8px 20px; font-weight: bold; border-radius: 30px; cursor: pointer; }
        .section-title { border-bottom: 2px solid #eff3f4; padding-bottom: 8px; margin-top: 30px; color: #536471; font-size: 1.1em; }
        .user-list { list-style: none; padding: 0; margin: 10px 0; }
        .user-list li { padding: 10px; background: #f7f9fa; border-radius: 8px; margin-bottom: 6px; font-size: 0.95em; }
    </style>
</head>
<body>

<div class="profile-container">
    <a href="/" class="btn-back"><- Kembali ke Halaman Utama</a>
    
    <h1 style="margin: 0 0 5px 0;">{{ $account->name }}</h1>
    <div style="color: #536471; font-size: 1.1em;">@ {{ $account->username }}</div>

    @if(session('success'))
        <p style="color: green; font-weight: bold; margin-top: 15px;">{{ session('success') }}</p>
    @endif

    @if(isset($isLocked) && $isLocked)
        <div style="background-color: #f8d7da; padding: 20px; border: 1px solid #f5c6cb; border-radius: 12px; margin-top: 20px; text-align: center;">
            <h2 style="margin: 0; color: #721c24;">🔒 Akun ini diprivat</h2>
            <p style="color: #721c24;">Kamu harus mem-follow akun ini dan menunggu persetujuan mereka untuk berinteraksi.</p>
            
            @auth
                @php
                    $checkPending = \App\Models\Follow::where('follower_id', Auth::id())->where('following_id', $account->id)->first();
                @endphp

                @if($checkPending && $checkPending->status === 'pending')
                    <button disabled style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 30px; font-weight: bold;">⏳ Menunggu Persetujuan...</button>
                @else
                    <form action="/follows" method="POST" style="margin-top: 15px;">
                        @csrf
                        <input type="hidden" name="following_id" value="{{ $account->id }}">
                        <button type="submit" class="btn-follow">Request Follow</button>
                    </form>
                @endif
            @endauth
        </div>
    @else
        <p style="font-size: 1.05em; line-height: 1.4; margin-top: 15px;">{{ $account->bio ?? 'Belum menulis bio.' }}</p>
        <p style="margin: 5px 0; color: #536471;">✉️ {{ $account->email }}</p>

        <div class="meta-stats">
            <strong>Followers:</strong> {{ $account->followers()->where('status', 'accepted')->count() }} | 
            <strong>Following:</strong> {{ $account->following()->where('status', 'accepted')->count() }}
        </div>

        @auth
            @if(Auth::id() !== $account->id)
                <div style="margin-top: 5px;">
                    @if(Auth::user()->isFollowing($account->id))
                        <form action="/follows/{{ $account->id }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-unfollow">Unfollow</button>
                        </form>
                        <a href="/messages/{{ $account->username }}"><button style="background: #1da1f2; color: white; border: none; padding: 8px 20px; font-weight: bold; border-radius: 30px; cursor: pointer; margin-left: 5px;">Kirim DM ✉️</button></a>
                    @else
                        <form action="/follows" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="following_id" value="{{ $account->id }}">
                            <button type="submit" class="btn-follow">Follow</button>
                        </form>
                    @endif
                </div>
            @else
                <div style="margin-top: 10px;">
                    <a href="/accounts/{{ $account->id }}/edit"><button style="background:#e1e8ed; border:none; padding:8px 20px; border-radius:30px; font-weight:bold; cursor:pointer;">Edit Profil Anda</button></a>
                </div>
            @endif
        @endauth

        <div class="section-title">👥 Daftar Pengikut (Followers)</div>
        <ul class="user-list">
            @php
                $myFollowers = $account->followers()->where('status', 'accepted')->with('follower')->get();
            @endphp
            @if($myFollowers->isEmpty())
                <li style="color: gray; background:none; padding-left:0;">Belum ada pengikut.</li>
            @else
                @foreach($myFollowers as $f)
                    <li>
                        <a href="/accounts/{{ $f->follower->id }}" style="text-decoration:none; color:#0f1419; font-weight:bold;">{{ $f->follower->name }}</a> 
                        <span style="color:gray;">(@ {{ $f->follower->username }})</span>
                    </li>
                @endforeach
            @endif
        </ul>

        <div class="section-title">🌖 Mengikuti (Following)</div>
        <ul class="user-list">
            @php
                $myFollowing = $account->following()->where('status', 'accepted')->with('following')->get();
            @endphp
            @if($myFollowing->isEmpty())
                <li style="color: gray; background:none; padding-left:0;">Belum mengikuti siapapun.</li>
            @else
                @foreach($myFollowing as $g)
                    <li>
                        <a href="/accounts/{{ $g->following->id }}" style="text-decoration:none; color:#0f1419; font-weight:bold;">{{ $g->following->name }}</a> 
                        <span style="color:gray;">(@ {{ $g->following->username }})</span>
                    </li>
                @endforeach
            @endif
        </ul>
    @endif
</div>

</body>
</html>