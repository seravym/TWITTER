<!DOCTYPE html>
<html lang="id" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - Twitaw</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:          #f7f9fa;
            --card-bg:     #ffffff;
            --text:        #0f1419;
            --text-muted:  #536471;
            --border:      #eff3f4;
            --input-bg:    #f7f9fa;
            --accent:      #1d9bf0;
            --danger:      #f4212e;
            --success-bg:  #eafff0;
            --success-txt: #008000;
            --shadow:      0 8px 30px rgba(0,0,0,0.06);
            --toggle-bg:   #cfd9de;
            --section-sep: #eff3f4;
        }
        html.dark-mode {
            --bg:          #15202b;
            --card-bg:     #1e2732;
            --text:        #f7f9fa;
            --text-muted:  #8899a6;
            --border:      #2f3b47;
            --input-bg:    #253341;
            --accent:      #1d9bf0;
            --danger:      #f4212e;
            --success-bg:  #1a3028;
            --success-txt: #4caf50;
            --shadow:      0 8px 30px rgba(0,0,0,0.3);
            --toggle-bg:   #2f3b47;
            --section-sep: #2f3b47;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg); color: var(--text);
            min-height: 100vh; transition: background 0.3s, color 0.3s;
        }

        .settings-wrapper { max-width: 640px; margin: 0 auto; padding: 30px 15px 80px; }

        .back-btn {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--accent); text-decoration: none; font-weight: 700; font-size: 15px;
            margin-bottom: 24px; transition: 0.2s;
        }
        .back-btn:hover { opacity: 0.8; }

        .page-title { font-size: 1.9em; font-weight: 900; letter-spacing: -1px; margin-bottom: 28px; }

        .alert { padding: 14px 18px; border-radius: 14px; font-weight: 700; margin-bottom: 20px; font-size: 0.95em; }
        .alert-success { background: var(--success-bg); color: var(--success-txt); }

        .settings-card {
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 20px; padding: 0;
            box-shadow: var(--shadow); margin-bottom: 20px;
            overflow: hidden; transition: background 0.3s, border-color 0.3s;
        }

        .section-header {
            padding: 18px 22px 14px; border-bottom: 1px solid var(--section-sep);
            font-size: 0.78em; font-weight: 800; text-transform: uppercase;
            letter-spacing: 1px; color: var(--text-muted);
        }

        .setting-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 17px 22px; border-bottom: 1px solid var(--section-sep); transition: background 0.15s;
        }
        .setting-row:last-child { border-bottom: none; }
        .setting-row:hover { background: rgba(29,155,240,0.04); }

        .setting-label { display: flex; flex-direction: column; gap: 3px; flex: 1; }
        .setting-label strong { font-size: 1em; font-weight: 700; color: var(--text); }
        .setting-label span { font-size: 0.82em; color: var(--text-muted); }

        .toggle-wrapper { position: relative; width: 50px; height: 27px; flex-shrink: 0; margin-left: 16px; }
        .toggle-wrapper input[type="checkbox"] { opacity: 0; width: 0; height: 0; position: absolute; }
        .toggle-track {
            position: absolute; inset: 0; background: var(--toggle-bg);
            border-radius: 34px; cursor: pointer; transition: 0.3s;
        }
        .toggle-track::before {
            content: ''; position: absolute;
            width: 21px; height: 21px; left: 3px; bottom: 3px;
            background: white; border-radius: 50%; transition: 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        input:checked + .toggle-track { background: var(--accent); }
        input:checked + .toggle-track::before { transform: translateX(23px); }

        .styled-select {
            background: var(--input-bg); border: 1px solid var(--border);
            color: var(--text); padding: 9px 14px; border-radius: 10px;
            font-size: 0.9em; font-weight: 600; outline: none;
            cursor: pointer; transition: 0.2s; min-width: 160px;
            font-family: 'Inter', sans-serif;
        }
        .styled-select:focus { border-color: var(--accent); }

        .theme-pills { display: flex; gap: 10px; margin-top: 4px; }
        .theme-pill {
            flex: 1; padding: 10px; border-radius: 14px;
            border: 2px solid var(--border); cursor: pointer;
            text-align: center; transition: 0.2s; font-size: 0.85em; font-weight: 700;
            background: var(--input-bg); color: var(--text);
        }
        .theme-pill:hover { border-color: var(--accent); }
        .theme-pill.selected { border-color: var(--accent); background: rgba(29,155,240,0.1); color: var(--accent); }
        .theme-pill input { display: none; }
        .theme-pill .theme-icon { font-size: 1.6em; display: block; margin-bottom: 4px; }

        .btn-save {
            width: 100%; background: var(--accent); color: white;
            border: none; padding: 15px; border-radius: 14px;
            font-size: 1em; font-weight: 800; cursor: pointer;
            margin-top: 8px; transition: 0.2s; letter-spacing: 0.2px;
            font-family: 'Inter', sans-serif;
        }
        .btn-save:hover { opacity: 0.88; transform: translateY(-1px); }

        .search-block-row { display: flex; gap: 10px; padding: 16px 22px; border-bottom: 1px solid var(--section-sep); }
        .search-block-input {
            flex: 1; padding: 10px 14px; border: 1px solid var(--border);
            border-radius: 10px; background: var(--input-bg); color: var(--text);
            font-size: 0.9em; outline: none; transition: 0.2s; font-family: 'Inter', sans-serif;
        }
        .search-block-input:focus { border-color: var(--accent); }

        .blocked-user-row {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 22px; border-bottom: 1px solid var(--section-sep);
        }
        .blocked-user-row:last-child { border-bottom: none; }
        .blocked-avatar {
            width: 42px; height: 42px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 900; font-size: 15px;
            text-transform: uppercase; flex-shrink: 0;
        }
        .blocked-info { flex: 1; }
        .blocked-info strong { font-size: 0.95em; color: var(--text); }
        .blocked-info span { font-size: 0.82em; color: var(--text-muted); display: block; }
        .btn-unblock {
            background: transparent; border: 1.5px solid var(--danger);
            color: var(--danger); padding: 6px 14px; border-radius: 20px;
            font-weight: 700; font-size: 0.82em; cursor: pointer;
            transition: 0.2s; font-family: 'Inter', sans-serif;
        }
        .btn-unblock:hover { background: var(--danger); color: white; }

        .empty-blocked { padding: 20px 22px; color: var(--text-muted); font-size: 0.9em; text-align: center; }

        .search-results-area { padding: 0 22px 16px; display: none; }
        .search-result-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 0; border-bottom: 1px solid var(--section-sep);
        }
        .search-result-item:last-child { border-bottom: none; }
        .btn-block-user {
            margin-left: auto; background: var(--danger); color: white;
            border: none; padding: 6px 14px; border-radius: 20px;
            font-weight: 700; font-size: 0.82em; cursor: pointer;
            transition: 0.2s; font-family: 'Inter', sans-serif;
        }
        .btn-block-user:hover { opacity: 0.85; }
    </style>
</head>
<body>

<div class="settings-wrapper">
    <a href="/" class="back-btn">← Kembali ke Home</a>
    <h1 class="page-title">⚙️ Pengaturan</h1>

    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert" style="background:#fdeced;color:#c62828;">✗ {{ $errors->first() }}</div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf

        {{-- TAMPILAN --}}
        <div class="settings-card">
            <div class="section-header">🎨 Tampilan</div>

            <div class="setting-row" style="flex-direction: column; align-items: flex-start; gap: 12px;">
                <div class="setting-label">
                    <strong>Tema Aplikasi</strong>
                    <span>Pilih tampilan yang nyaman untuk mata kamu</span>
                </div>
                <div class="theme-pills" style="width:100%;">
                    <label class="theme-pill {{ $setting->theme === 'light' ? 'selected' : '' }}" id="pill-light" onclick="selectTheme('light')">
                        <input type="radio" name="theme" value="light" {{ $setting->theme === 'light' ? 'checked' : '' }}>
                        <span class="theme-icon">☀️</span> Terang
                    </label>
                    <label class="theme-pill {{ $setting->theme === 'dark' ? 'selected' : '' }}" id="pill-dark" onclick="selectTheme('dark')">
                        <input type="radio" name="theme" value="dark" {{ $setting->theme === 'dark' ? 'checked' : '' }}>
                        <span class="theme-icon">🌙</span> Gelap
                    </label>
                    <label class="theme-pill {{ $setting->theme === 'system' ? 'selected' : '' }}" id="pill-system" onclick="selectTheme('system')">
                        <input type="radio" name="theme" value="system" {{ $setting->theme === 'system' ? 'checked' : '' }}>
                        <span class="theme-icon">💻</span> Sistem
                    </label>
                </div>
            </div>

            <div class="setting-row">
                <div class="setting-label">
                    <strong>Bahasa</strong>
                    <span>Pilih bahasa antarmuka</span>
                </div>
                <select name="language" class="styled-select">
                    <option value="id" {{ $setting->language === 'id' ? 'selected' : '' }}>🇮🇩 Indonesia</option>
                    <option value="en" {{ $setting->language === 'en' ? 'selected' : '' }}>🇺🇸 English</option>
                </select>
            </div>
        </div>

        {{-- PRIVASI & KEAMANAN --}}
        <div class="settings-card">
            <div class="section-header">🔒 Privasi & Keamanan</div>

            <div class="setting-row">
                <div class="setting-label">
                    <strong>Akun Privat</strong>
                    <span>Hanya pengikut yang disetujui bisa melihat postinganmu</span>
                </div>
                <label class="toggle-wrapper">
                    <input type="checkbox" name="isPrivateAccount" value="1"
                           {{ $setting->isPrivateAccount ? 'checked' : '' }}>
                    <span class="toggle-track"></span>
                </label>
            </div>

            <div class="setting-row">
                <div class="setting-label">
                    <strong>Tampilkan Status Online</strong>
                    <span>Orang lain bisa melihat kapan kamu aktif</span>
                </div>
                <label class="toggle-wrapper">
                    <input type="checkbox" name="showOnlineStatus" value="1"
                           {{ $setting->showOnlineStatus ? 'checked' : '' }}>
                    <span class="toggle-track"></span>
                </label>
            </div>

            <div class="setting-row">
                <div class="setting-label">
                    <strong>Izinkan DM dari</strong>
                    <span>Siapa yang bisa mengirim pesan langsung kepadamu</span>
                </div>
                <select name="allowDmFrom" class="styled-select">
                    <option value="everyone" {{ $setting->allowDmFrom === 'everyone' ? 'selected' : '' }}>Semua Orang</option>
                    <option value="following" {{ $setting->allowDmFrom === 'following' ? 'selected' : '' }}>Yang Diikuti Saja</option>
                </select>
            </div>
        </div>

        {{-- NOTIFIKASI --}}
        <div class="settings-card">
            <div class="section-header">🔔 Notifikasi</div>

            <div class="setting-row">
                <div class="setting-label">
                    <strong>Pesan Masuk (DM)</strong>
                    <span>Notifikasi saat ada pesan masuk</span>
                </div>
                <label class="toggle-wrapper">
                    <input type="checkbox" name="notificationMessage" value="1"
                           {{ $setting->notificationMessage ? 'checked' : '' }}>
                    <span class="toggle-track"></span>
                </label>
            </div>

            <div class="setting-row">
                <div class="setting-label">
                    <strong>Pengikut Baru</strong>
                    <span>Notifikasi saat ada yang mengikutimu</span>
                </div>
                <label class="toggle-wrapper">
                    <input type="checkbox" name="notificationFollow" value="1"
                           {{ $setting->notificationFollow ? 'checked' : '' }}>
                    <span class="toggle-track"></span>
                </label>
            </div>

            <div class="setting-row">
                <div class="setting-label">
                    <strong>Suka (Like) pada Post</strong>
                    <span>Notifikasi saat postinganmu disukai</span>
                </div>
                <label class="toggle-wrapper">
                    <input type="checkbox" name="notificationLike" value="1"
                           {{ $setting->notificationLike ? 'checked' : '' }}>
                    <span class="toggle-track"></span>
                </label>
            </div>
        </div>

        <button type="submit" class="btn-save">Simpan Pengaturan</button>
    </form>

    {{-- BLOCK ACCOUNT --}}
    <div class="settings-card" style="margin-top: 24px;">
        <div class="section-header">🚫 Blokir Akun</div>

        <div class="search-block-row">
            <input type="text" id="blockSearchInput" class="search-block-input"
                   placeholder="Cari username untuk diblokir..."
                   oninput="searchUsers(this.value)">
        </div>

        <div id="searchResultsArea" class="search-results-area"></div>

        @if($blockedAccounts->count())
            @php
            $bGradients = ['linear-gradient(135deg,#a18cd1,#fbc2eb)','linear-gradient(135deg,#84fab0,#8fd3f4)',
                           'linear-gradient(135deg,#fccb90,#d57eeb)','linear-gradient(135deg,#e0c3fc,#8ec5fc)',
                           'linear-gradient(135deg,#f093fb,#f5576c)'];
            @endphp
            @foreach($blockedAccounts as $ba)
                <div class="blocked-user-row">
                    <div class="blocked-avatar" style="background: {{ $bGradients[$ba->id % count($bGradients)] }};">
                        {{ strtoupper(substr($ba->name, 0, 1)) }}
                    </div>
                    <div class="blocked-info">
                        <strong>{{ $ba->name }}</strong>
                        <span>@{{ $ba->username }}</span>
                    </div>
                    <form action="{{ route('settings.unblock', $ba->id) }}" method="POST" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-unblock">Batalkan Blokir</button>
                    </form>
                </div>
            @endforeach
        @else
            <div class="empty-blocked">Belum ada akun yang diblokir.</div>
        @endif
    </div>
</div>

<script>
(function() {
    const savedTheme = '{{ $setting->theme }}';
    applyTheme(savedTheme);
})();

function applyTheme(theme) {
    const html = document.getElementById('html-root');
    if (theme === 'dark') {
        html.classList.add('dark-mode');
    } else if (theme === 'system') {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        html.classList.toggle('dark-mode', prefersDark);
    } else {
        html.classList.remove('dark-mode');
    }
}

function selectTheme(theme) {
    ['light','dark','system'].forEach(t => {
        document.getElementById('pill-' + t).classList.toggle('selected', t === theme);
    });
    applyTheme(theme);
}

let searchTimeout = null;

function searchUsers(query) {
    const area = document.getElementById('searchResultsArea');
    clearTimeout(searchTimeout);

    if (!query || query.trim().length < 2) {
        area.style.display = 'none';
        area.innerHTML = '';
        return;
    }

    searchTimeout = setTimeout(() => {
        area.style.display = 'block';
        area.innerHTML = renderSearchHint(query.trim());
    }, 400);
}

function renderSearchHint(q) {
    return `<div class="search-result-item" style="padding: 14px 0;">
        <div style="flex:1; font-size:0.9em; color: var(--text-muted);">
            Tekan tombol blokir setelah mengetik username yang tepat:
        </div>
    </div>
    <div class="search-result-item">
        <div style="flex:1;">
            <strong style="color:var(--text);">@${q}</strong>
        </div>
        <button type="button" class="btn-block-user" onclick="blockByUsername('${q}')">
            🚫 Blokir
        </button>
    </div>`;
}

function blockByUsername(username) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/settings/block-by-username';
    form.innerHTML = `
        <input name="_token" value="{{ csrf_token() }}">
        <input name="username" value="${username}">
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>
</body>
</html>