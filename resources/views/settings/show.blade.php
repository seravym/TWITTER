<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
</head>
<body>
    <h1>Pengaturan Akun</h1>
    
    @if(session('success'))
        <div style="color: green; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        
        <div>
            <label>
                <input type="checkbox" name="isPrivateAccount" value="1" {{ $setting->isPrivateAccount ? 'checked' : '' }}>
                Akun Privat
            </label>
        </div>
        <br>

        <div>
            <label>Izinkan DM dari:</label>
            <select name="allowDmFrom">
                <option value="everyone" {{ $setting->allowDmFrom == 'everyone' ? 'selected' : '' }}>Semua Orang</option>
                <option value="following" {{ $setting->allowDmFrom == 'following' ? 'selected' : '' }}>Orang yang Diikuti</option>
            </select>
        </div>
        <br>

        <div>
            <label>
                <input type="checkbox" name="showOnlineStatus" value="1" {{ $setting->showOnlineStatus ? 'checked' : '' }}>
                Tampilkan Status Online
            </label>
        </div>
        <br>

        <h3>Notifikasi</h3>
        <div>
            <label>
                <input type="checkbox" name="notificationMessage" value="1" {{ $setting->notificationMessage ? 'checked' : '' }}>
                Pesan Masuk (DM)
            </label>
        </div>
        <div>
            <label>
                <input type="checkbox" name="notificationFollow" value="1" {{ $setting->notificationFollow ? 'checked' : '' }}>
                Pengikut Baru
            </label>
        </div>
        <div>
            <label>
                <input type="checkbox" name="notificationLike" value="1" {{ $setting->notificationLike ? 'checked' : '' }}>
                Suka (Like) pada Tweet
            </label>
        </div>
        <br>

        <div>
            <label>Pilihan Tema:</label>
            <select name="theme">
                <option value="light" {{ $setting->theme == 'light' ? 'selected' : '' }}>Terang (Light)</option>
                <option value="dark" {{ $setting->theme == 'dark' ? 'selected' : '' }}>Gelap (Dark)</option>
                <option value="system" {{ $setting->theme == 'system' ? 'selected' : '' }}>Sistem</option>
            </select>
        </div>
        <br>

        <div>
            <label>Bahasa:</label>
            <select name="language">
                <option value="id" {{ $setting->language == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                <option value="en" {{ $setting->language == 'en' ? 'selected' : '' }}>English</option>
            </select>
        </div>
        <br>

        <button type="submit">Simpan Pengaturan</button>
    </form>
</body>
</html>