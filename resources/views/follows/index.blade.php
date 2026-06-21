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
                <li style="margin-bottom: 10px;">
                    <a href="/accounts/{{ $follow->following->id }}"><strong>{{ $follow->following->name }}</strong></a>
                    
                    @if(Auth::user()->isFollowedBy($follow->following->id))
                        <span style="background-color: #e0f7fa; color: #00796b; padding: 2px 5px; font-size: 0.8em; border-radius: 3px; margin-left: 5px;">Follows you</span>
                    @endif

                    <form action="/follows/{{ $follow->following->id }}" method="POST" style="display:inline; margin-left: 10px;">
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