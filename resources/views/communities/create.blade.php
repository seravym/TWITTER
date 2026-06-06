<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Komunitas</title>
</head>
<body>
    <a href="/communities"><- Kembali</a>
    <h1>Buat Komunitas Baru</h1>

    <form action="/communities" method="POST">
        @csrf
        <div>
            <label>Nama Komunitas:</label><br>
            <input type="text" name="name" required>
        </div>
        <br>
        <div>
            <label>Deskripsi:</label><br>
            <textarea name="description" rows="4" cols="30"></textarea>
        </div>
        <br>
        <button type="submit">Simpan Komunitas</button>
    </form>
</body>
</html>