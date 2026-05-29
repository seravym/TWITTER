<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Komentar Saya</title>
</head>
<body>
    <a href="/"><- Kembali ke Home</a>
    <h1>Komentar Saya</h1>
    <hr>
    @if($comments->isEmpty())
        <p>Belum ada komentar.</p>
    @else
        <ul>
            @foreach($comments as $comment)
                <li>
                    <p>Di postingan ID {{ $comment->post_id }}: "{{ $comment->content }}"</p>
                    <form action="/comments/{{ $comment->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>