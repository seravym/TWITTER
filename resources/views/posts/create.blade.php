<h1>Buat Tweet</h1>

<form method="POST" action="{{ route('posts.store') }}">
    @csrf

    <textarea name="content" rows="4" placeholder="Apa yang lagi dipikirin?" required></textarea>

    <br><br>

    <button type="submit">
        Tweet
    </button>
</form>