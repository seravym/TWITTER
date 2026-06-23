<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('account')
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(10);

        $myArticles = Article::where('account_id', Auth::id())
            ->latest()
            ->get();

        return view('articles.index', compact('articles', 'myArticles'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateArticle($request);

        $status = $request->input('status') === 'published' ? 'published' : 'draft';
        $coverPath = null;

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('articles', 'public');
        }

        Article::create([
            'account_id' => Auth::id(),
            'title' => $data['title'],
            'slug' => $this->makeUniqueSlug($data['title']),
            'body' => $this->cleanBody($data['body']),
            'cover_image' => $coverPath,
            'status' => $status,
            'published_at' => $status === 'published' ? now() : null,
        ]);

        $message = $status === 'published'
            ? 'Artikel berhasil dipublikasikan.'
            : 'Artikel berhasil disimpan sebagai draft.';

        return redirect()->route('articles.index')->with('success', $message);
    }

    public function show(Article $article)
    {
        if ($article->status !== 'published' && $article->account_id !== Auth::id()) {
            abort(403, 'Artikel ini masih draft.');
        }

        $article->load('account');

        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        if ($article->account_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengedit artikel ini.');
        }

        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        if ($article->account_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengubah artikel ini.');
        }

        $data = $this->validateArticle($request);
        $status = $request->input('status') === 'published' ? 'published' : 'draft';

        if ($request->hasFile('cover_image')) {
            if ($article->cover_image) {
                Storage::disk('public')->delete($article->cover_image);
            }

            $article->cover_image = $request->file('cover_image')->store('articles', 'public');
        }

        $article->title = $data['title'];
        $article->body = $this->cleanBody($data['body']);
        $article->status = $status;

        if ($status === 'published' && $article->published_at === null) {
            $article->published_at = now();
        }

        if ($status === 'draft') {
            $article->published_at = null;
        }

        $article->save();

        return redirect()->route('articles.show', $article)->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        if ($article->account_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak menghapus artikel ini.');
        }

        if ($article->cover_image) {
            Storage::disk('public')->delete($article->cover_image);
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus.');
    }

    private function validateArticle(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:150',
            'body' => 'required|string|min:1',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'status' => 'required|in:draft,published',
        ]);
    }

    private function cleanBody(string $body): string
    {
        $allowedTags = '<p><br><strong><b><em><i><u><h1><h2><h3><blockquote><ul><ol><li><a><img>';

        return strip_tags($body, $allowedTags);
    }

    private function makeUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (Article::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
