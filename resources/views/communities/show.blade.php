<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Komunitas</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f7f9fa; margin: 0; padding: 40px 20px; color: #0f1419; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        
        .back-link { color: #1da1f2; text-decoration: none; font-weight: 600; font-size: 14px; margin-bottom: 20px; display: inline-block; }
        .back-link:hover { text-decoration: underline; }
        
        h1 { font-size: 28px; margin: 10px 0 5px 0; color: #0f1419; }
        .meta-info { font-size: 14px; color: #536471; margin-bottom: 20px; }
        
        .description-text { font-size: 16px; line-height: 1.6; color: #0f1419; margin-bottom: 25px; }

        .btn { padding: 12px 24px; border-radius: 9999px; font-weight: 700; cursor: pointer; border: none; transition: 0.2s; display: inline-block; text-align: center; }
        .btn-join { background: #1da1f2; color: white; width: 100%; }
        .btn-join:hover { background: #1a91da; }
        .btn-leave { background: white; color: #f4212e; border: 1px solid #cfd9de; width: 100%; }
        .btn-leave:hover { background: #fdeced; border-color: #f4212e; }

        .member-list { list-style: none; padding: 0; margin-top: 20px; }
        .member-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #eff3f4; }
        .role-badge { background: #eff3f4; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 700; color: #536471; }

        .post-section { margin-top: 40px; border-top: 1px solid #eff3f4; padding-top: 20px; }
        .post-input { width: 100%; padding: 16px; border: 1px solid #cfd9de; border-radius: 12px; font-size: 16px; outline: none; font-family: inherit; resize: vertical; min-height: 80px; box-sizing: border-box; margin-bottom: 12px; }
        .post-input:focus { border-color: #1da1f2; }
        .post-card { background: #fff; border: 1px solid #eff3f4; border-radius: 12px; padding: 16px; margin-bottom: 16px; transition: 0.2s; }
        .post-card:hover { background: #f7f9fa; }
        .post-author { font-weight: 800; font-size: 15px; margin-bottom: 6px; }
        .post-content { font-size: 15px; line-height: 1.5; color: #0f1419; margin-bottom: 10px; }
        .post-date { font-size: 13px; color: #536471; }
        .alert-info { background: #eff3f4; color: #536471; padding: 16px; border-radius: 12px; text-align: center; font-size: 14px; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="container">
        <a href="/communities" class="back-link">← Kembali ke Daftar Komunitas</a>
        
        <h1>{{ $community->name }}</h1>
        <div class="meta-info">Dibuat oleh <strong>{{ $community->creator->name ?? 'Unknown' }}</strong></div>
        
        <div class="description-text">
            {{ $community->description ?? 'Tidak ada deskripsi komunitas ini.' }}
        </div>

        @if(session('success'))
            <p style="color: #17bf63; font-weight: 600; margin-bottom: 20px;">{{ session('success') }}</p>
        @endif

        {{-- Logika Tombol Gabung / Keluar --}}
        <div>
            @if($community->members->contains(Auth::id()))
                <form action="/communities/{{ $community->id }}/leave" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-leave">Keluar dari Komunitas</button>
                </form>
            @else
                <form action="/communities/{{ $community->id }}/join" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-join">Gabung Komunitas</button>
                </form>
            @endif
        </div>

        {{-- --- AREA POSTINGAN KOMUNITAS --- --}}
        <div class="post-section">
            <h3 style="margin-bottom: 20px;">Postingan Komunitas</h3>

            {{-- Form Buat Post (Hanya tampil jika user adalah member atau pembuat komunitas) --}}
            @if($community->members->contains(Auth::id()) || $community->creator_id === Auth::id())
                <form action="/communities/{{ $community->id }}/posts" method="POST" style="margin-bottom: 30px;">
                    @csrf
                    <textarea name="content" class="post-input" placeholder="Bagikan sesuatu ke komunitas ini..." required></textarea>
                    <button type="submit" class="btn btn-join" style="width: auto; padding: 10px 24px;">Kirim Post</button>
                </form>
            @else
                <div class="alert-info">
                    Anda harus <strong>bergabung</strong> dengan komunitas ini untuk dapat membagikan postingan.
                </div>
            @endif

            {{-- Daftar Postingan --}}
            <div>
                @forelse($community->posts ?? [] as $post)
                    <div class="post-card">
                        <div class="post-author">{{ $post->account->name ?? 'User' }}</div>
                        <div class="post-content">{{ $post->content }}</div>
                        <div class="post-date">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="alert-info" style="background: transparent; border: 1px dashed #cfd9de;">
                        Belum ada postingan di komunitas ini. Jadilah yang pertama memposting!
                    </div>
                @endforelse
            </div>
        </div>

        {{-- --- DAFTAR ANGGOTA --- --}}
        <h3 style="margin-top: 40px; font-size: 18px; border-top: 1px solid #eff3f4; padding-top: 20px;">
            Daftar Anggota ({{ $community->members->count() }})
        </h3>
        <ul class="member-list">
            @foreach($community->members as $member)
                <li class="member-item">
                    {{ $member->name }}
                    <span class="role-badge">{{ strtoupper($member->pivot->role ?? 'MEMBER') }}</span>
                </li>
            @endforeach
        </ul>
    </div>

</body>
</html>