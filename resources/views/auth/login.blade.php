<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Masuk ke Twitter</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f7f9fa;
            color: #0f1419;
            display: flex; justify-content: center; align-items: center; 
            min-height: 100vh; margin: 0; 
        }

        .login-box { 
            width: 100%; max-width: 380px; padding: 40px; 
            background: #ffffff; 
            border-radius: 16px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05), 0 20px 48px rgba(0,0,0,0.05);
            text-align: center;
        }

        .twitter-logo {
            font-size: 75px;
            font-weight: 900;
            color: #1d9bf0;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        h2 { font-size: 24px; font-weight: 800; margin-bottom: 25px; }
        
        .error-list { color: #f4212e; font-size: 14px; margin-bottom: 20px; text-align: left; padding-left: 20px; }

        .form-group { margin-bottom: 20px; text-align: left; }
        label { display: block; margin-bottom: 8px; font-size: 14px; color: #536471; font-weight: 600; }
        
        input[type="email"], input[type="password"] {
            width: 100%; background: #ffffff; border: 1px solid #cfd9de; border-radius: 8px; 
            padding: 12px; color: #0f1419; font-size: 16px; outline: none; transition: 0.2s;
            box-sizing: border-box;
        }
        input:focus { border: 2px solid #1d9bf0; }

        .checkbox-group { display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 14px; margin-bottom: 25px; cursor: pointer; color: #536471; }
        
        button[type="submit"] {
            width: 100%; background: #0f1419; color: #fff; border: none; padding: 12px; 
            border-radius: 9999px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.2s;
        }
        button[type="submit"]:hover { background: #272c30; }

        p { margin-top: 20px; font-size: 14px; color: #536471; }
        a { color: #1d9bf0; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-box">
    <div class="twitter-logo">Twitter</div>

    <h2>Form Login</h2>

    @if (session('success'))
        <div style="background: #e6fffa; color: #2c7a7b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/login" method="POST">
        @csrf
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        
        <label class="checkbox-group">
            <input type="checkbox" name="remember" id="remember">
            <span>Ingat Saya</span>
        </label>
        
        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="/register">Daftar di sini</a></p>
</div>

</body>
</html>