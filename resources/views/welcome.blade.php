<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Utama</title>
</head>
<body>
    <h1>Selamat Datang di Aplikasi</h1>

    @if (session('success'))
        <p style="color: green; font-weight: bold;">{{ session('success') }}</p>
    @endif

    <hr>

    @auth
        <p>Halo, <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->username }})</p>
        <p>Email kamu: {{ Auth::user()->email }}</p>
        
        <ul>
            <li><a href="/accounts/{{ Auth::id() }}">Lihat Profil Saya</a></li>
            <li><a href="/accounts">Lihat Semua Pengguna</a></li>
            <li><a href="/posts">Lihat Semua Postingan</a></li>
            <li><a href="/messages">Direct Messages</a></li>
            <li><a href="/comments">Komentar Saya</a></li>
            <li><a href="/follows">Following Saya</a></li>
        </ul>
        
        <form action="/logout" method="POST" style="display: inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>

        <hr>

        <h2>Buat Postingan Baru</h2>
        @if ($errors->any())
            <ul style="color: red;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="/posts" method="POST">
            @csrf
            <textarea name="content" rows="3" cols="50" placeholder="Apa yang sedang kamu pikirkan?" required></textarea>
            <br>
            <button type="submit">Posting</button>
        </form>

    @else
        <p>Kamu belum login. Silakan pilih opsi di bawah ini:</p>
        <a href="/login"><button>Login</button></a>
        <a href="/register"><button>Register</button></a>
    @endauth

    <hr>

        <h2>Feed Postingan</h2>
        @if($posts->isEmpty())
            <p>Belum ada postingan apapun.</p>
        @else
            @foreach($posts as $post)
                <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
                    <p>
                        <strong><a href="/accounts/{{ $post->account->id }}">{{ $post->account->name }}</a></strong> 
                        ({{ $post->account->username }}) 
                        <span style="color: gray; font-size: 0.8em;">• {{ $post->created_at->diffForHumans() }}</span>
                    </p>
                    <p>{{ $post->content }}</p>
                    
                    <form action="/posts/{{ $post->id }}/like" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit">
                            {{ $post->isLikedBy(Auth::id()) ? '❤️ Unlike' : '🤍 Like' }} ({{ $post->likes->count() }})
                        </button>
                    </form>

                    @if(Auth::id() === $post->account_id)
                        | <a href="/posts/{{ $post->id }}/edit"><button>Edit</button></a> | 
                        <form action="/posts/{{ $post->id }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    @endif

                    @auth
                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #ccc;">
                        <form action="/comments" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <input type="text" name="content" placeholder="Tulis komentar..." required style="width: 60%; padding: 2px;">
                            <button type="submit">Kirim</button>
                        </form>
                    </div>
                    @endauth
                </div>
            @endforeach
        @endif
</body>
</html>