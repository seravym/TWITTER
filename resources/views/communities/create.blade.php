<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Komunitas Baru</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f7f9fa; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        
        .container { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); width: 100%; max-width: 450px; }
        
        .back-link { display: inline-block; margin-bottom: 20px; color: #1da1f2; text-decoration: none; font-weight: 600; font-size: 14px; }
        .back-link:hover { text-decoration: underline; }
        
        h1 { font-size: 24px; font-weight: 800; color: #0f1419; margin-bottom: 25px; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 14px; font-weight: 600; color: #536471; margin-bottom: 8px; }
        input, textarea { width: 100%; padding: 12px; border: 1px solid #cfd9de; border-radius: 8px; box-sizing: border-box; font-size: 16px; outline: none; font-family: inherit; }
        input:focus, textarea:focus { border-color: #1d9bf0; }
        
        .btn-save { width: 100%; background: #0f1419; color: white; border: none; padding: 14px; border-radius: 9999px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.2s; margin-top: 10px; }
        .btn-save:hover { background: #272c30; }
    </style>
</head>
<body>

    <div class="container">
        <a href="/communities" class="back-link">← Kembali</a>
        <h1>Buat Komunitas Baru</h1>

        <form action="/communities" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Komunitas</label>
                <input type="text" name="name" required placeholder="Contoh: Pecinta Kopi">
            </div>
            
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" rows="4" required placeholder="Ceritakan tentang komunitasmu..."></textarea>
            </div>
            
            <button type="submit" class="btn-save">Simpan Komunitas</button>
        </form>
    </div>

</body>
</html>