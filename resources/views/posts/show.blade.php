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
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        h1 {
            margin-top: 0;
            font-size: 1.6em;
            font-weight: 900;
        }
        p {
            font-size: 1.15em;
            line-height: 1.5;
            color: #0f1419;
        }
        a {
            color: #1da1f2;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 15px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <h1>{{ $post->title }}</h1>

        <p>{{ $post->content }}</p>

        <a href="{{ route('posts.index') }}">Kembali</a>

    </div>

</body>
</html>