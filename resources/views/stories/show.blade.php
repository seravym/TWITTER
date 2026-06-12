<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Story - {{ $account->name }}</title>
    <style>
        body { margin: 0; padding: 0; background-color: #111; color: white; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; overflow: hidden; }
        .story-container { position: relative; width: 100%; max-width: 400px; height: 100vh; max-height: 850px; background: #000; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; }
        
        .progress-bar-container { position: absolute; top: 15px; left: 10px; right: 10px; display: flex; gap: 5px; z-index: 10; }
        .progress-bar { flex: 1; height: 3px; background: rgba(255,255,255,0.3); border-radius: 2px; overflow: hidden; }
        .progress-fill { height: 100%; background: white; width: 0%; animation: fillProgress 10s linear forwards; }
        
        @keyframes fillProgress {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        .story-header { position: absolute; top: 30px; left: 15px; right: 15px; display: flex; justify-content: space-between; align-items: center; z-index: 10; }
        .user-info { display: flex; align-items: center; gap: 10px; text-decoration: none; color: white; text-shadow: 0 1px 4px rgba(0,0,0,0.8); }

        .story-avatar { width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; text-transform: uppercase; border: 1px solid rgba(255,255,255,0.3); }
        
        .story-name { font-weight: 700; font-size: 15px; }
        .story-time { font-size: 13px; color: rgba(255,255,255,0.8); }
        .close-btn { color: white; text-decoration: none; font-size: 32px; font-weight: 300; text-shadow: 0 1px 4px rgba(0,0,0,0.8); cursor: pointer; line-height: 1; }
        
        .story-image { width: 100%; height: 100%; object-fit: cover; }

        .story-footer { position: absolute; bottom: 0; left: 0; right: 0; padding: 20px 15px 30px 15px; background: linear-gradient(transparent, rgba(0,0,0,0.85)); z-index: 10; display: flex; align-items: center; gap: 15px; }
        
        .reply-input { flex: 1; background: transparent; border: 1px solid rgba(255,255,255,0.6); border-radius: 30px; padding: 12px 20px; color: white; outline: none; font-size: 14px; transition: 0.2s; backdrop-filter: blur(5px); }
        .reply-input:focus { border-color: white; background: rgba(255,255,255,0.15); }
        .reply-input::placeholder { color: white; font-weight: 500; }
        .action-icon { font-size: 26px; cursor: pointer; transition: transform 0.2s; background: none; border: none; color: white; padding: 0; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4)); }
        .action-icon:hover { transform: scale(1.1); }

        .owner-stats { flex: 1; display: flex; justify-content: space-around; color: white; font-size: 14px; font-weight: bold; text-shadow: 0 1px 3px rgba(0,0,0,0.8); }
        .stat-item { display: flex; flex-direction: column; align-items: center; gap: 6px; cursor: pointer; transition: 0.2s; }
        .stat-item:hover { transform: translateY(-3px); }
        .stat-icon { font-size: 22px; }
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

<div class="story-container">
    <div class="progress-bar-container">
        <div class="progress-bar"><div class="progress-fill"></div></div>
    </div>

    <div class="story-header">
        <a href="/accounts/{{ $account->id }}" class="user-info">
            <div class="story-avatar" style="background: {{ getAvatarGradient($account->id) }};">
                {{ substr($account->name, 0, 1) }}
            </div>
            <div>
                <div class="story-name">{{ $account->name }}</div>
                <div class="story-time">{{ $stories->first()->created_at->diffForHumans() }}</div>
            </div>
        </a>
        <a href="/accounts/{{ $account->id }}" class="close-btn">&times;</a>
    </div>

    <img src="{{ asset('storage/' . $stories->first()->media_path) }}" class="story-image" alt="Story">

    <div class="story-footer">
        @if(Auth::id() === $account->id)
            <div class="owner-stats">
                <div class="stat-item" onclick="alert('Fitur lihat penonton coming soon!')">
                    <span class="stat-icon">👁️</span>
                    <span>0 Dilihat</span>
                </div>
                <div class="stat-item" onclick="alert('Fitur lihat siapa yang like coming soon!')">
                    <span class="stat-icon">❤️</span>
                    <span>0 Suka</span>
                </div>
                <div class="stat-item" onclick="alert('Fitur lihat siapa yang share coming soon!')">
                    <span class="stat-icon">📤</span>
                    <span>0 Dibagikan</span>
                </div>
            </div>
        @else
            <input type="text" class="reply-input" placeholder="Balas story {{ $account->name }}..." onclick="pauseStory()" onblur="resumeStory()">
            <button class="action-icon" onclick="alert('Fitur Like Story coming soon!')">❤️</button>
            <button class="action-icon" onclick="alert('Fitur Share Story coming soon!')">📤</button>
        @endif
    </div>
</div>

<script>
    let storyTimer;
    let timeRemaining = 10000; 

    function startTimer() {
        storyTimer = setTimeout(() => {
            window.location.href = '/accounts/{{ $account->id }}';
        }, timeRemaining);
    }

    startTimer();

    function pauseStory() {
        clearTimeout(storyTimer);
        document.querySelector('.progress-fill').style.animationPlayState = 'paused';
    }

    function resumeStory() {
        startTimer(); 
        document.querySelector('.progress-fill').style.animationPlayState = 'running';
    }
</script>

</body>
</html>