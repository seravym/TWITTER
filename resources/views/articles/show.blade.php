<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $article->title }}</title>
    <style>
        body { font-family: Georgia, 'Times New Roman', serif; background: #f7f9fa; margin: 0; color: #0f1419; }
        .article { max-width: 820px; margin: 30px auto; background: white; border: 1px solid #eff3f4; border-radius: 20px; overflow: hidden; }
        .top { padding: 25px 30px 10px; font-family: Arial, sans-serif; }
        .back { color: #1da1f2; text-decoration: none; font-weight: bold; }
        .cover { width: 100%; max-height: 380px; object-fit: cover; display: block; }
        .content { padding: 10px 45px 45px; }
        h1 { font-family: Arial, sans-serif; font-size: 42px; line-height: 1.15; margin-bottom: 10px; }
        .meta { font-family: Arial, sans-serif; color: #536471; margin-bottom: 30px; }
        .body { font-size: 20px; line-height: 1.8; }
        .body img { max-width: 100%; border-radius: 14px; }
        .body blockquote { border-left: 4px solid #1da1f2; padding-left: 16px; color: #536471; }
        .actions { font-family: Arial, sans-serif; display: flex; gap: 12px; margin-top: 25px; }
        .btn { background: #0f1419; color: white; padding: 9px 16px; border-radius: 30px; text-decoration: none; font-weight: bold; }
        .badge { background: #fff4cc; color: #8a6500; padding: 5px 10px; border-radius: 20px; font-size: 13px; font-weight: bold; }
    </style>
</head>
<body>
<div class="article">
    <div class="top">
        <a href="{{ route('articles.index') }}" class="back">← Back to Articles</a>
    </div>

    @if($article->cover_image)
        <img
            src="{{ asset('storage/' . $article->cover_image) }}"
            onerror="this.onerror=null;this.src='{{ asset('images/default-article.svg') }}';"
            class="cover"
            alt="Cover"
        >
    @endif

    <div class="content">
        <h1>{{ $article->title }}</h1>
        <div class="meta">
            By @ {{ $article->account->username }}
            @if($article->status === 'published')
                • {{ optional($article->published_at)->format('d M Y') }}
            @else
                • <span class="badge">Draft</span>
            @endif
        </div>

        <div class="body">
            {!! $article->body !!}
        </div>

        @if(Auth::id() === $article->account_id)
            <div class="actions">
                <a href="{{ route('articles.edit', $article) }}" class="btn">Edit Article</a>
            </div>
        @endif
    </div>
</div>
</body>
</html>
