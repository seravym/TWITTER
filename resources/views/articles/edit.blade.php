<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Article</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f9fa; margin: 0; color: #0f1419; }
        .container { max-width: 900px; margin: 30px auto; background: white; border: 1px solid #eff3f4; border-radius: 20px; padding: 30px; }
        .back { color: #1da1f2; text-decoration: none; font-weight: bold; }
        .form-group { margin-top: 18px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; }
        input[type="text"], input[type="file"] { width: 100%; box-sizing: border-box; padding: 13px; border: 1px solid #cfd9de; border-radius: 12px; font-size: 16px; }
        .actions { margin-top: 22px; display: flex; gap: 12px; align-items: center; }
        .btn { background: #1da1f2; color: white; border: none; padding: 11px 20px; border-radius: 30px; font-weight: bold; cursor: pointer; text-decoration: none; }
        .btn.dark { background: #0f1419; }
        .btn.danger { background: #f4212e; }
        .error { background: #ffebee; color: #c62828; padding: 14px; border-radius: 12px; margin-top: 15px; }
        .ck-editor__editable { min-height: 360px; }
        .current-cover { width: 180px; height: 110px; object-fit: cover; border-radius: 14px; margin-top: 10px; }
    </style>
</head>
<body>
<div class="container">
    <a href="{{ route('articles.show', $article) }}" class="back">← Back to Article</a>
    <h1>Edit Article</h1>

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title', $article->title) }}" maxlength="150" required>
        </div>

        <div class="form-group">
            <label>Cover Image</label>
            <input type="file" name="cover_image" accept="image/*">
            @if($article->cover_image)
                <img
                    src="{{ asset('storage/' . $article->cover_image) }}"
                    onerror="this.onerror=null;this.src='{{ asset('images/default-article.svg') }}';"
                    class="current-cover"
                    alt="Current cover"
                >
            @endif
        </div>

        <div class="form-group">
            <label>Body</label>
            <textarea name="body" id="editor">{{ old('body', $article->body) }}</textarea>
        </div>

        <div class="actions">
            <button type="submit" name="status" value="draft" class="btn dark">Save as Draft</button>
            <button type="submit" name="status" value="published" class="btn">Publish</button>
        </div>
    </form>

    <form action="{{ route('articles.destroy', $article) }}" method="POST" style="margin-top: 15px;" onsubmit="return confirm('Delete this article?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn danger">Delete Article</button>
    </form>
</div>

<script>
    ClassicEditor.create(document.querySelector('#editor')).catch(error => console.error(error));
</script>
</body>
</html>
