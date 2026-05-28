<h1>Timeline</h1>

<a href="{{ route('posts.create') }}">Buat Tweet</a>

<br><br>

@if ($posts->isEmpty())
    <p>Belum ada tweet.</p>
@else

    @foreach ($posts as $post)
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">

            <p>{{ $post->content }}</p>

            <small>
                dibuat oleh user #{{ $post->user_id }}
            </small>

            <br><br>

            <a href="{{ route('posts.edit', $post) }}">Edit</a>

            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')

                <button type="submit">Hapus</button>
            </form>

        </div>
    @endforeach

@endif