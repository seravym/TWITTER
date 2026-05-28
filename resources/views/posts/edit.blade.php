<h1>Edit Tweet</h1>

<form method="POST" action="{{ route('posts.update', $post) }}">
    @csrf
    @method('PUT')

    <textarea name="content" rows="4" required>{{ $post->content }}</textarea>

    <br><br>

    <button type="submit">
        Update
    </button>
</form>