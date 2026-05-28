<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h2>Form Pendaftaran Akun</h2>

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/register" method="POST">
        @csrf
        <div>
            <label>Nama Lengkap:</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <br>
        <div>
            <label>Username:</label><br>
            <input type="text" name="username" value="{{ old('username') }}" required>
        </div>
        <br>
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
            <label>Konfirmasi Password:</label><br>
            <input type="password" name="password_confirmation" required>
        </div>
        <br>
        <button type="submit">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="/login">Login di sini</a></p>
</body>
</html>