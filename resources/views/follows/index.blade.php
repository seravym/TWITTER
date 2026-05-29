<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Following</title>
</head>
<body>
    <a href="/"><- Kembali ke Home</a>
    <h1>Akun yang Saya Follow</h1>
    <hr>
    @if($follows->isEmpty())
        <p>Kamu belum mem-follow siapapun.</p>
    @else
        <ul>
            @foreach($follows as $follow)
                <li>
                    <a href="/accounts/{{ $follow->following->id }}">{{ $follow->following->name }}</a>
                    <form action="/follows/{{ $follow->following->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Unfollow</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>