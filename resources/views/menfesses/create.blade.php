<div style="max-width: 500px; margin: 20px auto; font-family: sans-serif;">
    <h2>Kirim Menfess (Anonim)</h2>
    <form action="{{ route('menfess.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label>Pilih Base Tujuan:</label><br>
            <select name="base_id" required style="width: 100%; padding: 8px;">
                <option value="">-- Pilih Base --</option>
                @foreach($bases as $base)
                    <option value="{{ $base->id }}">{{ $base->username }}</option>
                @endforeach
            </select>
        </div>
        <div style="margin-bottom: 15px;">
            <label>Pesan Rahasiamu:</label><br>
            <textarea name="message" rows="5" required style="width: 100%; padding: 8px;"></textarea>
        </div>
        <button type="submit" style="background: #1DA1F2; color: white; padding: 10px; border: none;">Kirim Menfess</button>
    </form>
</div>