<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Komunitas</title>
</head>
<body>
    <a href="/communities"><- Kembali ke Daftar Komunitas</a>
    
    <h1>Komunitas: {{ $community->name }}</h1>
    <p><strong>Deskripsi:</strong> {{ $community->description ?? 'Tidak ada deskripsi.' }}</p>
    <p><strong>Pembuat:</strong> {{ $community->creator->name }}</p>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    @if($errors->any())
        <p style="color: red;">{{ $errors->first() }}</p>
    @endif

    <div>
        @if($community->members->contains(Auth::id()))
            <form action="/communities/{{ $community->id }}/leave" method="POST">
                @csrf
                <button type="submit" style="color: red;">Keluar dari Komunitas</button>
            </form>
        @else
            <form action="/communities/{{ $community->id }}/join" method="POST">
                @csrf
                <button type="submit" style="color: blue;">Gabung Komunitas</button>
            </form>
        @endif
    </div>

    <hr>
    <h3>Daftar Anggota ({{ $community->members->count() }} Orang):</h3>
    <ul>
        @foreach($community->members as $member)
            <li>
                {{ $member->name }} - <strong>Role: {{ $member->pivot->role }}</strong>
            </li>
        @endforeach
    </ul>
</body>
</html>