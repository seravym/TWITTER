<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Komunitas</title>
</head>
<body>
    <a href="/"><- Kembali ke Home</a>
    <h1>Daftar Komunitas</h1>
    
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="/communities/create"><button>+ Buat Komunitas Baru</button></a>
    <hr>

    @if($communities->isEmpty())
        <p>Belum ada komunitas yang dibuat.</p>
    @else
        <ul>
            @foreach($communities as $community)
                <li>
                    <strong><a href="/communities/{{ $community->id }}">{{ $community->name }}</a></strong> 
                    (Dibuat oleh: {{ $community->creator->name }})
                    <p>{{ $community->description }}</p>
                </li>
                <br>
            @endforeach
        </ul>
    @endif
</body>
</html>