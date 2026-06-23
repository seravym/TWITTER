<div style="max-width: 600px; margin: 20px auto; font-family: sans-serif;">
    <h2>Antrean Menfess Baru</h2>
    
    @forelse($menfesses as $menfess)
        <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; border-radius: 8px;">
            <p style="color: gray; font-size: 12px;">Dari: Pengirim Rahasia (ID: {{ $menfess->sender_id }})</p>
            <p>{{ $menfess->message }}</p>
            
            <form action="{{ route('menfess.approve', $menfess->id) }}" method="POST">
                @csrf
                <button type="submit" style="background: green; color: white; padding: 5px 15px; border: none;">Approve & Publish</button>
            </form>
        </div>
    @empty
        <p>Belum ada menfess baru.</p>
    @endforelse
</div>