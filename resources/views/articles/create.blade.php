<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Write Article</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f9fa; margin: 0; color: #0f1419; }
        .container { max-width: 900px; margin: 30px auto; background: white; border: 1px solid #eff3f4; border-radius: 20px; padding: 30px; }
        .back { color: #1da1f2; text-decoration: none; font-weight: bold; }
        .form-group { margin-top: 18px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; }
        input[type="text"], input[type="file"] { width: 100%; box-sizing: border-box; padding: 13px; border: 1px solid #cfd9de; border-radius: 12px; font-size: 16px; }
        .actions { margin-top: 22px; display: flex; gap: 12px; }
        .btn { background: #1da1f2; color: white; border: none; padding: 11px 20px; border-radius: 30px; font-weight: bold; cursor: pointer; }
        .btn.dark { background: #0f1419; }
        .error { background: #ffebee; color: #c62828; padding: 14px; border-radius: 12px; margin-top: 15px; }
        .ck-editor__editable { min-height: 360px; }
    </style>
</head>
<body>
<div class="container">
    <a href="{{ route('articles.index') }}" class="back">← Back to Articles</a>
    <h1>Write Long-form Article</h1>

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title') }}" maxlength="150" required>
        </div>

        <div class="form-group">
            <label>Cover Image</label>
            <input type="file" name="cover_image" accept="image/*">
        </div>

        <div class="form-group">
            <label>Body</label>
            <textarea name="body" id="editor">{{ old('body') }}</textarea>
        </div>

        <div class="actions">
            <button type="submit" name="status" value="draft" class="btn dark">Save as Draft</button>
            <button type="submit" name="status" value="published" class="btn">Publish</button>
        </div>
    </form>
</div>

<script>
    ClassicEditor.create(document.querySelector('#editor')).catch(error => console.error(error));
</script>
</body>
</html>
