<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar ke Twitter</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f7f9fa; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        
        .auth-card { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); width: 100%; max-width: 400px; text-align: center; }
        
        .brand-logo { font-size: 75px; font-weight: 900; color: #1da1f2; margin-bottom: 20px; }
        h2 { font-size: 24px; font-weight: 800; margin-bottom: 30px; color: #0f1419; }

        .form-group { text-align: left; margin-bottom: 20px; }
        label { display: block; font-size: 14px; font-weight: 600; color: #536471; margin-bottom: 8px; }
        input { width: 100%; padding: 12px; border: 1px solid #cfd9de; border-radius: 8px; box-sizing: border-box; font-size: 16px; outline: none; }
        input:focus { border-color: #1d9bf0; }

        .btn-submit { width: 100%; background: #0f1419; color: white; border: none; padding: 14px; border-radius: 9999px; font-weight: 700; font-size: 16px; cursor: pointer; margin-top: 10px; transition: 0.2s; }
        .btn-submit:hover { background: #272c30; }

        .footer-link { margin-top: 25px; font-size: 14px; color: #536471; }
        .footer-link a { color: #1da1f2; text-decoration: none; font-weight: 600; }
        .footer-link a:hover { text-decoration: underline; }

        .errors { background: #fdeced; color: #f4212e; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: left; }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="brand-logo">Twitter</div>
        <h2>Buat Akun Anda</h2>

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="/register" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required>
            </div>
            
            <button type="submit" class="btn-submit">Daftar</button>
        </form>

        <p class="footer-link">Sudah punya akun? <a href="/login">Login di sini</a></p>
    </div>

</body>
</html>