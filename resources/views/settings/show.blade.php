<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f7f9fa; color: #0f1419; margin: 0; min-height: 100vh; display: flex; justify-content: center; padding: 20px; }
        .settings-container { width: 100%; max-width: 500px; background: white; padding: 30px; border-radius: 16px; border: 1px solid #eff3f4; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        
        .nav-header { display: flex; align-items: center; margin-bottom: 20px; }
        .back-btn { color: #1d9bf0; text-decoration: none; font-weight: 600; font-size: 15px; }
        .back-btn:hover { text-decoration: underline; }

        h1 { font-size: 22px; font-weight: 800; margin: 0 auto 20px; text-align: center; }
        h3 { font-size: 16px; margin: 25px 0 15px; border-bottom: 1px solid #eff3f4; padding-bottom: 10px; }
        
        .success-msg { background: #eafff0; color: #008000; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-size: 14px; color: #536471; font-weight: 600; }
        
        select { width: 100%; padding: 10px; border: 1px solid #cfd9de; border-radius: 8px; background: white; font-size: 15px; outline: none; }
        select:focus { border-color: #1d9bf0; }

        .checkbox-label { display: flex; align-items: center; gap: 10px; font-size: 15px; cursor: pointer; margin-bottom: 12px; }
        
        button[type="submit"] {
            width: 100%; background: #0f1419; color: white; border: none; padding: 14px; 
            border-radius: 9999px; font-weight: 700; font-size: 16px; cursor: pointer; margin-top: 10px; transition: 0.2s;
        }
        button[type="submit"]:hover { background: #272c30; }
    </style>
</head>
<body>

<div class="settings-container">
    <div class="nav-header">
        <a href="/" class="back-btn">← Kembali ke Home</a>
    </div>

    <h1>Pengaturan Akun</h1>
    
    @if(session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        
        <label class="checkbox-label">
            <input type="checkbox" name="isPrivateAccount" value="1" {{ $setting->isPrivateAccount ? 'checked' : '' }}>
            Akun Privat
        </label>

        <div class="form-group">
            <label>Izinkan DM dari:</label>
            <select name="allowDmFrom">
                <option value="everyone" {{ $setting->allowDmFrom == 'everyone' ? 'selected' : '' }}>Semua Orang</option>
                <option value="following" {{ $setting->allowDmFrom == 'following' ? 'selected' : '' }}>Orang yang Diikuti</option>
            </select>
        </div>

        <label class="checkbox-label">
            <input type="checkbox" name="showOnlineStatus" value="1" {{ $setting->showOnlineStatus ? 'checked' : '' }}>
            Tampilkan Status Online
        </label>

        <h3>Notifikasi</h3>
        
        <label class="checkbox-label">
            <input type="checkbox" name="notificationMessage" value="1" {{ $setting->notificationMessage ? 'checked' : '' }}>
            Pesan Masuk (DM)
        </label>
        
        <label class="checkbox-label">
            <input type="checkbox" name="notificationFollow" value="1" {{ $setting->notificationFollow ? 'checked' : '' }}>
            Pengikut Baru
        </label>
        
        <label class="checkbox-label">
            <input type="checkbox" name="notificationLike" value="1" {{ $setting->notificationLike ? 'checked' : '' }}>
            Suka (Like) pada Tweet
        </label>

        <div class="form-group" style="margin-top: 25px;">
            <label>Pilihan Tema:</label>
            <select name="theme">
                <option value="light" {{ $setting->theme == 'light' ? 'selected' : '' }}>Terang (Light)</option>
                <option value="dark" {{ $setting->theme == 'dark' ? 'selected' : '' }}>Gelap (Dark)</option>
                <option value="system" {{ $setting->theme == 'system' ? 'selected' : '' }}>Sistem</option>
            </select>
        </div>

        <div class="form-group">
            <label>Bahasa:</label>
            <select name="language">
                <option value="id" {{ $setting->language == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                <option value="en" {{ $setting->language == 'en' ? 'selected' : '' }}>English</option>
            </select>
        </div>

        <button type="submit">Simpan Pengaturan</button>
    </form>
</div>

</body>
</html>