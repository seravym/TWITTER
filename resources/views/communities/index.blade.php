<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Komunitas</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f7f9fa; color: #0f1419; margin: 0; min-height: 100vh; display: flex; justify-content: center; }
        .container { width: 100%; max-width: 600px; padding: 20px; }
        .back-link { color: #1d9bf0; text-decoration: none; font-weight: 600; font-size: 15px; margin-bottom: 20px; display: inline-block; }
        .back-link:hover { text-decoration: underline; }
        
        h1 { font-size: 22px; font-weight: 800; margin-bottom: 20px; }
        
        .btn-create { 
            background: #1d9bf0; color: white; border: none; padding: 12px 20px; 
            border-radius: 9999px; font-weight: 700; cursor: pointer; display: inline-block; text-decoration: none; margin-bottom: 20px;
        }
        .btn-create:hover { background: #1a8cd8; }

        .list-card { background: #fff; border-radius: 16px; border: 1px solid #cfd9de; overflow: hidden; }
        .item { padding: 20px; border-bottom: 1px solid #cfd9de; text-decoration: none; color: inherit; display: block; transition: 0.2s; }
        .item:last-child { border-bottom: none; }
        .item:hover { background: #f7f9fa; }
        
        .name { font-weight: 800; font-size: 16px; margin-bottom: 4px; }
        .meta { font-size: 13px; color: #536471; margin-bottom: 8px; }
        .desc { font-size: 14px; color: #0f1419; }
    </style>
</head>
<body>

<div class="container">
    <a href="/" class="back-link">← Kembali ke Home</a>
    <h1>Daftar Komunitas</h1>

    @if(session('success'))
        <div style="background: #eafff0; color: #008000; padding: 10px; border-radius: 8px; margin-bottom: 20px;">{{ session('success') }}</div>
    @endif

    <a href="/communities/create" class="btn-create">+ Buat Komunitas Baru</a>

    <div class="list-card">
        @if($communities->isEmpty())
            <div style="padding: 40px; text-align: center; color: #536471;">Belum ada komunitas yang dibuat.</div>
        @else
            @foreach($communities as $community)
                <a href="/communities/{{ $community->id }}" class="item">
                    <div class="name">{{ $community->name }}</div>
                    <div class="meta">Dibuat oleh: {{ $community->creator->name }}</div>
                    <div class="desc">{{ $community->description }}</div>
                </a>
            @endforeach
        @endif
    </div>
</div>

</body>
</html>