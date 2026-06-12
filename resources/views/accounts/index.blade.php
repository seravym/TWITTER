<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelajahi Pengguna</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f7f9fa; color: #0f1419; margin: 0; min-height: 100vh; display: flex; justify-content: center; padding: 20px; }
        .container { width: 100%; max-width: 600px; }

        .back-link { color: #1d9bf0; text-decoration: none; font-weight: 600; font-size: 15px; display: inline-block; margin-bottom: 20px; }
        .back-link:hover { text-decoration: underline; }

        h1 { font-size: 22px; font-weight: 800; margin-bottom: 20px; }

        .list-card { background: #fff; border-radius: 16px; border: 1px solid #eff3f4; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .user-card { padding: 16px; display: flex; align-items: center; gap: 12px; text-decoration: none; color: inherit; transition: 0.2s; border-bottom: 1px solid #eff3f4; }
        .user-card:last-child { border-bottom: none; }
        .user-card:hover { background: #f7f9fa; }

        .avatar { 
            width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 18px; text-transform: uppercase; flex-shrink: 0;
        }
        
        .user-info { flex: 1; }
        .name { font-weight: 800; font-size: 15px; color: #0f1419; }
        .username { font-size: 14px; color: #536471; }
        .bio { font-size: 14px; color: #536471; margin-top: 2px; }
    </style>
</head>
<body>

@php
    function getAvatarGradient($id) {
        $gradients = [
            'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)', 'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)',
            'linear-gradient(135deg, #fccb90 0%, #d57eeb 100%)', 'linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%)',
            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)', 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)', 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)'
        ];
        return $gradients[$id % count($gradients)];
    }
@endphp

<div class="container">
    <a href="/" class="back-link">← Kembali ke Home</a>
    <h1>Jelajahi Pengguna</h1>

    <div class="list-card">
        @foreach($accounts as $acc)
            <a href="/accounts/{{ $acc->id }}" class="user-card">
                <div class="avatar" style="background: {{ getAvatarGradient($acc->id) }};">
                    {{ substr($acc->name, 0, 1) }}
                </div>
                
                <div class="user-info">
                    <div class="name">{{ $acc->name }}</div>
                    <div class="username">{{ '@' . $acc->username }}</div>
                    <div class="bio">{{ $acc->bio ?? 'Belum ada bio.' }}</div>
                </div>
            </a>
        @endforeach
    </div>
</div>

</body>
</html>