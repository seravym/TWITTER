<h1>Explore</h1>

<form method="GET" action="{{ route('explore.index') }}">
    <input type="text" name="q" value="{{ old('q', $query) }}" placeholder="Cari tweet atau pengguna" style="width:300px;">
    <button type="submit">Cari</button>
</form>

@if($query === '')
    <p>Masukkan kata kunci untuk mencari tweet atau akun.</p>
@else
    <h2>Hasil untuk "{{ $query }}"</h2>

    <h3>Akun</h3>
    @if($results['accounts']->isEmpty())
        <p>Tidak ada akun yang cocok.</p>
    @else
        @foreach($results['accounts'] as $account)
            <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                <strong>{{ $account->name }}</strong>
                <br>
                <small>@{{ $account->username }}</small>
                <p>{{ $account->bio }}</p>
            </div>
        @endforeach
    @endif

    <h3>Tweet</h3>
    @if($results['posts']->isEmpty())
        <p>Tidak ada tweet yang cocok.</p>
    @else
        @foreach($results['posts'] as $post)
            <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                <p>{{ $post->content }}</p>
                <small>Dibuat oleh: {{ optional($post->user)->name ?? 'User #' . $post->user_id }}</small>
            </div>
        @endforeach
    @endif
@endif