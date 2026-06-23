<h1>Timeline</h1>

<a href="{{ route('posts.create') }}">Buat Tweet</a>
 |
 <a href="{{ route('explore.index') }}">Explore</a>

<br><br>

@if ($posts->isEmpty())
    <p>Belum ada tweet.</p>
@else

    @foreach ($posts as $post)
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">

            {{-- CONTENT --}}
            <p>{{ $post->content }}</p>

            {{-- USER --}}
            <small>
                dibuat oleh user #{{ $post->accounts->name }}
            </small>

            <br><br>

            {{-- LIKE BUTTON --}}
            <form action="{{ route('posts.like', $post->id) }}" method="POST" style="display:inline;">
                @csrf

                <button type="submit"
                    style="color: {{ $post->isLikedBy(auth()->id()) ? 'red' : 'black' }}">
                    ❤️ {{ $post->likes->count() }}
                </button>
            </form>

            <br><br>

            {{-- EDIT --}}
            <a href="{{ route('posts.edit', $post) }}">Edit</a>

            {{-- DELETE --}}
            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')

                <button type="submit">Hapus</button>
            </form>

        </div>
    @endforeach

@endif