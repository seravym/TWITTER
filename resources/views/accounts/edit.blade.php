<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            background: #f7f9fa; 
            color: #0f1419;
            display: flex; justify-content: center; align-items: center; 
            min-height: 100vh; margin: 0; 
        }

        .edit-box { 
            width: 100%; max-width: 450px; padding: 30px; 
            background: #ffffff; 
            border-radius: 16px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .header-top { display: flex; align-items: center; margin-bottom: 20px; }
        .back-link { color: #1d9bf0; text-decoration: none; font-weight: 600; font-size: 15px; }
        .back-link:hover { text-decoration: underline; }

        h1 { font-size: 22px; font-weight: 800; margin-bottom: 25px; }

        .error-list { color: #f4212e; font-size: 14px; margin-bottom: 20px; padding-left: 20px; }

        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-size: 14px; color: #536471; font-weight: 600; }
        
        input[type="text"], textarea {
            width: 100%; background: #ffffff; border: 1px solid #cfd9de; border-radius: 8px; 
            padding: 12px; color: #0f1419; font-size: 16px; outline: none; transition: 0.2s;
            box-sizing: border-box; font-family: inherit;
        }
        input:focus, textarea:focus { border: 2px solid #1d9bf0; }

        button[type="submit"] {
            width: 100%; background: #0f1419; color: #fff; border: none; padding: 12px; 
            border-radius: 9999px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.2s; margin-top: 10px;
        }
        button[type="submit"]:hover { background: #272c30; }
    </style>
</head>
<body>

<div class="edit-box">
    <div class="header-top">
        <a href="/accounts/{{ $account->id }}" class="back-link">← Batal</a>
    </div>
    
    <h1>Edit Profil Anda</h1>

    @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/accounts/{{ $account->id }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="name" value="{{ old('name', $account->name) }}" required>
        </div>
        
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" value="{{ old('username', $account->username) }}" required>
        </div>
        
        <div class="form-group">
            <label>Bio:</label>
            <textarea name="bio" rows="4">{{ old('bio', $account->bio) }}</textarea>
        </div>
        
        <button type="submit">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>