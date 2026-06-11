<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Followers Saya</title>
</head>
<body>
    <a href="/"><- Kembali ke Home</a>
    <h1>Akun yang Mem-follow Saya (Followers)</h1>
    <hr>
    
    @if($followers->isEmpty())
        <p>Kamu belum memiliki follower.</p>
    @else
        <ul>
            @foreach($followers as $follow)
                <li style="margin-bottom: 10px;">
                    <a href="/accounts/{{ $follow->follower->id }}"><strong>{{ $follow->follower->name }}</strong></a>
                    
                    @if(Auth::user()->isFollowing($follow->follower->id))
                        <span style="background-color: #e8f5e9; color: #2e7d32; padding: 2px 5px; font-size: 0.8em; border-radius: 3px; margin-left: 5px;">Mutual / Follback ✓</span>
                    @else
                        <form action="/follows" method="POST" style="display:inline; margin-left: 10px;">
                            @csrf
                            <input type="hidden" name="following_id" value="{{ $follow->follower->id }}">
                            <button type="submit" style="background-color: #007bff; color: white; border: none; padding: 3px 8px; border-radius: 3px; cursor: pointer;">Follback</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>