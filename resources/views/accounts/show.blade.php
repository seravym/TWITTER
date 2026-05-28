<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil {{ $account->name }}</title>
</head>
<body>
    <a href="/accounts"><- Kembali ke Daftar Pengguna</a> | <a href="/">Home</a>
    
    <h1>Profil: {{ $account->name }}</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <p><strong>Username:</strong> {{ $account->username }}</p>
    <p><strong>Email:</strong> {{ $account->email }}</p>
    <p><strong>Bio:</strong> {{ $account->bio ?? 'Belum ada bio.' }}</p>

    @if(Auth::id() === $account->id)
        <a href="/accounts/{{ $account->id }}/edit"><button>Edit Profil Anda</button></a>
    @endif
</body>
</html>