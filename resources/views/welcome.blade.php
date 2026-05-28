<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Utama</title>
</head>
<body>
    <h1>Selamat Datang di Aplikasi</h1>

    @if (session('success'))
        <p style="color: green; font-weight: bold;">{{ session('success') }}</p>
    @endif

    <hr>

    @auth
        <p>Halo, <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->username }})</p>
        <p>Email kamu: {{ Auth::user()->email }}</p>
        
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @else
        <p>Kamu belum login. Silakan pilih opsi di bawah ini:</p>
        <a href="/login"><button>Login</button></a>
        <a href="/register"><button>Register</button></a>
    @endauth
</body>
</html>