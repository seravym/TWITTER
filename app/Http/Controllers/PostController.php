<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Setting;
use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
        {
            $feedType      = $request->query('feed', 'global');
            $currentUserId = Auth::id();

            $acceptedFollowingIds = Auth::check()
                ? Auth::user()->following()->where('status', 'accepted')->pluck('following_id')->toArray()
                : [];

            // Ambil daftar akun yang di-block oleh user ini
            $mySetting      = Setting::where('account_id', $currentUserId)->first();
            $blockedByMe    = $mySetting ? ($mySetting->blocked_accounts ?? []) : [];

            // Ambil daftar akun yang mem-block user ini
            $blockingMe = Setting::whereJsonContains('blocked_accounts', $currentUserId)
                ->pluck('account_id')->toArray();

            // Gabungkan — sembunyikan post dari/ke yang di-block
            $blockedIds = array_unique(array_merge($blockedByMe, $blockingMe));

            // 1. JIKA FEED FOLLOWING
            if ($feedType === 'following' && Auth::check()) {
                $posts = Post::whereNull('community_id') // Sembunyikan post komunitas
                            ->whereNotIn('account_id', $blockedIds) // Filter block list
                            ->where(function($query) use ($currentUserId, $acceptedFollowingIds) {
                                $query->whereIn('account_id', $acceptedFollowingIds)
                                    ->orWhere('account_id', $currentUserId);
                            })
                            ->with(['account', 'likes', 'comments', 'hashtags', 'bookmarks']) // Relasi lengkap Anda
                            ->latest()
                            ->get();
            
            // 2. JIKA FEED GLOBAL
            } else {
                $privateAccountIds = Setting::where('isPrivateAccount', true)->pluck('account_id')->toArray();

                $posts = Post::whereNull('community_id') // Sembunyikan post komunitas
                            ->whereNotIn('account_id', $blockedIds) // Filter block list
                            ->where(function ($query) use ($currentUserId, $acceptedFollowingIds, $privateAccountIds) {
                                $query->whereNotIn('account_id', $privateAccountIds)
                                    ->orWhere('account_id', $currentUserId)
                                    ->orWhereIn('account_id', $acceptedFollowingIds);
                            })
                            ->with(['account', 'likes', 'comments', 'hashtags', 'bookmarks']) // Relasi lengkap Anda
                            ->latest()
                            ->get();
            }

            return view('welcome', compact('posts', 'feedType'));
        }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:350'
        ]);

        $post = Post::create([
            'account_id' => Auth::id(),
            'content'    => $request->content,
        ]);

        // Parse dan simpan hashtags dari konten post
        $this->syncHashtags($post, $request->content);

        return redirect()->route('posts.index')->with('success', 'Post berhasil dibuat!');
    }

    public function edit(Post $post)
    {
        if (Auth::id() !== $post->account_id) {
            abort(403, 'Anda tidak berhak mengedit postingan ini.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if (Auth::id() !== $post->account_id) {
            abort(403, 'Anda tidak berhak mengubah postingan ini.');
        }

        $request->validate([
            'content' => 'required|string|max:350'
        ]);

        $post->update(['content' => $request->content]);

        // Re-sync hashtags setelah edit
        $this->syncHashtags($post, $request->content);

        return redirect()->route('posts.index')->with('success', 'Post berhasil diperbarui!');
    }

    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->account_id) {
            abort(403, 'Anda tidak berhak menghapus postingan ini.');
        }

        // Kurangi post_count hashtag sebelum dihapus
        foreach ($post->hashtags as $hashtag) {
            $hashtag->decrementPostCount();
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post berhasil dihapus!');
    }

    /*
    | LIKE / UNLIKE TOGGLE
    */
    public function like(Post $post)
    {
        $accountId = Auth::id();
        $like = Like::where('account_id', $accountId)
            ->where('post_id', $post->id)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'account_id' => $accountId,
                'post_id'    => $post->id,
            ]);
        }
        return back();
    }

    public function show($id)
    {
        $post = Post::with(['account', 'likes', 'comments', 'hashtags', 'bookmarks'])->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Parse hashtag (#kata) dari konten, buat atau update record di tabel hashtags,
     * dan sync pivot hashtag_post.
     */
    private function syncHashtags(Post $post, string $content): void
    {
        preg_match_all('/#(\w+)/u', $content, $matches);
        $tagNames = array_unique(array_map('strtolower', $matches[1]));

        $hashtagIds = [];
        foreach ($tagNames as $name) {
            $hashtag = Hashtag::firstOrCreate(['name' => $name]);
            $hashtag->incrementPostCount();
            $hashtagIds[] = $hashtag->id;
        }

        // Detach lama, attach baru (sync)
        $post->hashtags()->sync($hashtagIds);
    }
    
}