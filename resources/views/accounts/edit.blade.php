<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
</head>
<body>
    <a href="/accounts/{{ $account->id }}"><- Batal</a>
    <h1>Edit Profil Anda</h1>

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/accounts/{{ $account->id }}" method="POST">
        @csrf
        @method('PUT') <div>
            <label>Nama Lengkap:</label><br>
            <input type="text" name="name" value="{{ old('name', $account->name) }}" required>
        </div>
        <br>
        <div>
            <label>Username:</label><br>
            <input type="text" name="username" value="{{ old('username', $account->username) }}" required>
        </div>
        <br>
        <div>
            <label>Bio:</label><br>
            <textarea name="bio" rows="4" cols="30">{{ old('bio', $account->bio) }}</textarea>
        </div>
        <br>
        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>