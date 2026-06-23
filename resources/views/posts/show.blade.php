<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Show Post</title>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f7f9fa;
            color: #0f1419;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border: 1px solid #eff3f4;
            border-radius: 16px;
            padding: 25px;
        }

        h1 {
            margin-top: 0;
            font-size: 1.6em;
            font-weight: 900;
        }

        p {
            font-size: 1.15em;
            line-height: 1.5;
        }

        .user {
            font-size: 0.9em;
            color: gray;
            margin-bottom: 10px;
        }

        a {
            color: #1da1f2;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">

    {{-- USER --}}
    <div class="user">
        dibuat oleh {{ $post->account->name }}
    </div>

    {{-- CONTENT --}}
    <h1>{{ $post->content }}</h1>

    <p>{{ $post->content }}</p>

    {{-- LIKE --}}
    <form action="{{ route('posts.like', $post) }}" method="POST" style="display:inline;">
        @csrf

        <button type="submit"
            style="color: {{ $post->isLikedBy(auth()->id()) ? 'red' : 'black' }}">
            ❤️ {{ $post->likes->count() }}
        </button>
    </form>

    <form action="{{ route('posts.repost', $post) }}" method="POST">
        @csrf

        <button type="submit"
            style="color: {{ $post->isRepostedBy(auth()->id()) ? 'green' : 'black' }}">
            🔁 {{ $post->reposts->count() }}
        </button>
    </form>

    <br><br>

    <a href="{{ route('posts.index') }}">Kembali</a>

</div>

</body>
</html>