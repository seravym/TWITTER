<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Komunitas</title>
    
    <style>
        body { font-family: sans-serif; background-color: #f7f9fa; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 12px; }
        .post-card { border: 1px solid #ccc; padding: 15px; border-radius: 8px; margin-bottom: 20px; background: #fafafa; }
        .comment-section { margin-top: 15px; border-top: 1px dashed #ccc; padding-top: 15px; }
        .comment-item { background: #eee; padding: 8px 12px; border-radius: 6px; margin-top: 10px; font-size: 14px; }
        button { cursor: pointer; padding: 6px 12px; border-radius: 4px; border: 1px solid #ccc; }
        .btn-gabung { background: blue; color: white; }
        .btn-keluar { background: red; color: white; }
    </style>
</head>
<body>

    <div class="container">
        
        {{-- INFO KOMUNITAS --}}
        <a href="/communities">← Kembali</a>
        <h1>{{ $community->name }}</h1>
        <p>Dibuat oleh: <strong>{{ $community->creator->name ?? 'Unknown' }}</strong></p>
        <p>{{ $community->description ?? 'Tidak ada deskripsi.' }}</p>

        @if(session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        <hr>

        {{--TOMBOL GABUNG/KELUAR KOMUNITAS--}}
        @if($community->members->contains(Auth::id()))
            <form action="/communities/{{ $community->id }}/leave" method="POST">
                @csrf
                <button type="submit" class="btn-keluar">Keluar dari Komunitas</button>
            </form>
        @else
            <form action="/communities/{{ $community->id }}/join" method="POST">
                @csrf
                <button type="submit" class="btn-gabung">Gabung Komunitas</button>
            </form>
        @endif

        <hr>

        {{--DAFTAR POSTINGAN DAN LIKE--}}
        <h3>Postingan</h3>
        
        @forelse($community->posts ?? [] as $post)
            <div class="post-card">
                
                {{-- Info Postingan --}}
                <strong>{{ $post->account->name ?? 'User' }}</strong>
                <p>{{ $post->content }}</p>
                <small>{{ $post->created_at->diffForHumans() }}</small>

                {{-- Tombol Like --}}
                @php
                    $isLiked = $post->likes ? $post->likes->where('account_id', Auth::id())->count() > 0 : false;
                @endphp
                <form action="/posts/{{ $post->id }}/like" method="POST" style="margin-top: 10px;">
                    @csrf
                    <button type="submit">
                        {{ $isLiked ? '❤️' : '🤍' }} {{ $post->likes ? $post->likes->count() : 0 }} Likes
                    </button>
                </form>

                {{-- Area Komentar --}}
                <div class="comment-section">
                    <strong>Komentar ({{ $post->comments ? $post->comments->count() : 0 }})</strong>

                    {{-- Form Tulis Komentar --}}
                    <form action="/comments" method="POST" style="margin-top: 10px; display: flex; gap: 5px;">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <input type="text" name="content" placeholder="Tulis komentar..." required style="flex: 1;">
                        <button type="submit">Balas</button>
                    </form>

                    {{-- List Komentar --}}
                    @if($post->comments && $post->comments->count() > 0)
                        @foreach($post->comments as $comment)
                            <div class="comment-item">
                                <strong>{{ $comment->account->name ?? 'User' }}:</strong> 
                                <span>{{ $comment->content }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
        @empty
            <p>Belum ada postingan di komunitas ini.</p>
        @endforelse

        <hr>

        {{-- DAFTAR ANGGOTA --}}
        <h3>Daftar Anggota ({{ $community->members->count() }})</h3>
        <ul>
            @foreach($community->members as $member)
                <li>
                    {{ $member->name }} - 
                    <small>({{ strtoupper($member->pivot->role ?? 'MEMBER') }})</small>
                </li>
            @endforeach
        </ul>

    </div>

</body>
</html>