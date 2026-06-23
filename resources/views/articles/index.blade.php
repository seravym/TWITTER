<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Articles</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f9fa; margin: 0; color: #0f1419; }
        .container { max-width: 900px; margin: 30px auto; background: white; border: 1px solid #eff3f4; border-radius: 20px; overflow: hidden; }
        .header { padding: 25px; border-bottom: 1px solid #eff3f4; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; }
        .btn { background: #1da1f2; color: white; padding: 10px 18px; border-radius: 30px; text-decoration: none; font-weight: bold; border: none; cursor: pointer; }
        .btn.secondary { background: #0f1419; }
        .message { padding: 15px 25px; background: #e8f5e9; color: #2e7d32; font-weight: bold; }
        .section-title { padding: 20px 25px 5px; margin: 0; font-size: 18px; }
        .article-card { padding: 25px; border-bottom: 1px solid #eff3f4; display: flex; gap: 18px; }
        .cover { width: 160px; height: 100px; object-fit: cover; border-radius: 16px; background: #eff3f4; flex-shrink: 0; }
        .title { color: #0f1419; text-decoration: none; font-size: 22px; font-weight: 800; }
        .meta { color: #536471; font-size: 14px; margin: 6px 0 10px; }
        .excerpt { line-height: 1.5; margin: 0; color: #263238; }
        .draft-box { padding: 15px 25px; border-bottom: 8px solid #eff3f4; }
        .draft-item { display: flex; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px solid #eff3f4; }
        .draft-item:last-child { border-bottom: none; }
        .empty { padding: 35px 25px; text-align: center; color: #536471; }
        .back { color: #1da1f2; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <a href="/" class="back">← Back to Home</a>
            <h1>Articles</h1>
        </div>
        <a href="{{ route('articles.create') }}" class="btn">Write Article</a>
    </div>

    @if (session('success'))
        <div class="message">{{ session('success') }}</div>
    @endif

    @if($myArticles->count() > 0)
        <h2 class="section-title">My Articles</h2>
        <div class="draft-box">
            @foreach($myArticles as $item)
                <div class="draft-item">
                    <div>
                        <strong>{{ $item->title }}</strong>
                        <span class="meta">• {{ ucfirst($item->status) }}</span>
                    </div>
                    <div>
                        <a href="{{ route('articles.show', $item) }}" class="back">Open</a>
                        <a href="{{ route('articles.edit', $item) }}" class="back" style="margin-left:10px;">Edit</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h2 class="section-title">Published Articles</h2>

    @forelse($articles as $article)
        <div class="article-card">
            <img
                src="{{ $article->cover_image ? asset('storage/' . $article->cover_image) : asset('images/default-article.svg') }}"
                onerror="this.onerror=null;this.src='{{ asset('images/default-article.svg') }}';"
                class="cover"
                alt="Cover"
            >

            <div>
                <a href="{{ route('articles.show', $article) }}" class="title">{{ $article->title }}</a>
                <div class="meta">By @ {{ $article->account->username }} • {{ optional($article->published_at)->diffForHumans() }}</div>
                <p class="excerpt">{{ $article->excerpt }}</p>
            </div>
        </div>
    @empty
        <div class="empty">No published articles yet.</div>
    @endforelse

    <div style="padding: 25px;">
        {{ $articles->links() }}
    </div>
</div>
</body>
</html>
