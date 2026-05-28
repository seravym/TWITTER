<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Form Login</h2>

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/login" method="POST">
        @csrf
        <div>
            <label>Email:</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <br>
        <div>
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <div>
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Ingat Saya</label>
        </div>
        <br>
        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="/register">Daftar di sini</a></p>
</body>
</html>