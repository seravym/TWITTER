<h1>Quote Tweet</h1>

<div style="border:1px solid #ccc; padding:10px; margin-bottom:15px;">
    <strong>{{ $post->account->name }}</strong>
    <br>
    {{ $post->content }}
</div>

<form method="POST" action="{{ route('posts.store') }}">
    @csrf

    <input
        type="hidden"
        name="quote_post_id"
        value="{{ $post->id }}"
    >

    <textarea
        name="content"
        rows="4"
        placeholder="Tambahkan komentar..."
        required></textarea>

    <br><br>

    <button type="submit">
        Quote Tweet
    </button>
</form>