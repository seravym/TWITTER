<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengguna</title>
</head>
<body>
    <a href="/"><- Kembali ke Home</a>
    <h1>Daftar Pengguna Aplikasi</h1>
    <hr>

    <ul>
        @foreach($accounts as $acc)
            <li>
                <strong><a href="/accounts/{{ $acc->id }}">{{ $acc->name }}</a></strong> 
                ({{ $acc->username }})
                <p>Bio: {{ $acc->bio ?? '-' }}</p>
            </li>
            <br>
        @endforeach
    </ul>
</body>
</html>