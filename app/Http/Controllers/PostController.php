<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Setting;
use App\Models\Hashtag;
use App\Models\CloseFriend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $feedType      = $request->query('feed', 'global');
        $currentUserId = Auth::id();

        $acceptedFollowingIds = Auth::check()
            ? Auth::user()->following()->where('status', 'accepted')->pluck('following_id')->toArray()
            : [];

        // Daftar ID yang merupakan close friend saya (untuk filter post close_friend)
        $myCloseFriendIds = Auth::check()
            ? CloseFriend::where('account_id', $currentUserId)->pluck('friend_id')->toArray()
            : [];

        // Ambil daftar akun yang di-block oleh user ini
        $mySetting   = Setting::where('account_id', $currentUserId)->first();
        $blockedByMe = $mySetting ? ($mySetting->blocked_accounts ?? []) : [];

        // Ambil daftar akun yang mem-block user ini
        $blockingMe = Setting::whereJsonContains('blocked_accounts', $currentUserId)
            ->pluck('account_id')->toArray();

        $blockedIds = array_unique(array_merge($blockedByMe, $blockingMe));

        if ($feedType === 'following' && Auth::check()) {
            $posts = Post::whereIn('account_id', $acceptedFollowingIds)
                         ->orWhere('account_id', $currentUserId)
                         ->whereNotIn('account_id', $blockedIds)
                         ->with(['account', 'likes', 'comments', 'hashtags', 'bookmarks'])
                         ->latest()
                         ->get()
                         ->filter(function ($post) use ($currentUserId, $myCloseFriendIds) {
                             if ($post->visibility === 'close_friend') {
                                 return $post->account_id === $currentUserId
                                     || in_array($post->account_id, $myCloseFriendIds);
                             }
                             return true;
                         });
        } else {
            $privateAccountIds = Setting::where('isPrivateAccount', true)->pluck('account_id')->toArray();

            $posts = Post::with(['account', 'likes', 'comments', 'hashtags', 'bookmarks'])
                         ->whereNotIn('account_id', $blockedIds)
                         ->where(function ($query) use ($currentUserId, $acceptedFollowingIds, $privateAccountIds) {
                             $query->whereNotIn('account_id', $privateAccountIds)
                                   ->orWhere('account_id', $currentUserId)
                                   ->orWhereIn('account_id', $acceptedFollowingIds);
                         })
                         ->latest()
                         ->get()
                         ->filter(function ($post) use ($currentUserId, $myCloseFriendIds) {
                             if ($post->visibility === 'close_friend') {
                                 return $post->account_id === $currentUserId
                                     || in_array($post->account_id, $myCloseFriendIds);
                             }
                             return true;
                         });
        }

        return view('welcome', compact('posts', 'feedType'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content'    => 'required|string|max:350',
            'media'      => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,webm|max:20480',
            'visibility' => 'nullable|in:public,close_friend',
        ]);

        // Handle upload media
        $mediaPath = null;
        $mediaType = null;
        if ($request->hasFile('media') && $request->file('media')->isValid()) {
            $file      = $request->file('media');
            $mime      = $file->getMimeType();
            $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';
            $mediaPath = $file->store('posts', 'public');
        }

        $post = Post::create([
            'account_id' => Auth::id(),
            'content'    => $request->content,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'visibility' => $request->input('visibility', 'public'),
        ]);

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
            'content'    => 'required|string|max:350',
            'media'      => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,webm|max:20480',
            'visibility' => 'nullable|in:public,close_friend',
        ]);

        // Handle media baru jika di-upload ulang
        $mediaPath = $post->media_path;
        $mediaType = $post->media_type;
        if ($request->hasFile('media') && $request->file('media')->isValid()) {
            // Hapus file lama jika ada
            if ($mediaPath) {
                Storage::disk('public')->delete($mediaPath);
            }
            $file      = $request->file('media');
            $mime      = $file->getMimeType();
            $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';
            $mediaPath = $file->store('posts', 'public');
        }

        $post->update([
            'content'    => $request->content,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'visibility' => $request->input('visibility', $post->visibility),
        ]);

        $this->syncHashtags($post, $request->content);

        return redirect()->route('posts.index')->with('success', 'Post berhasil diperbarui!');
    }

    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->account_id) {
            abort(403, 'Anda tidak berhak menghapus postingan ini.');
        }

        // Hapus media dari storage
        if ($post->media_path) {
            Storage::disk('public')->delete($post->media_path);
        }

        // Kurangi post_count hashtag sebelum dihapus
        foreach ($post->hashtags as $hashtag) {
            $hashtag->decrementPostCount();
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post berhasil dihapus!');
    }

    /**
     * Toggle pin/unpin post di profil.
     * Route: POST /posts/{post}/pin
     */
    public function pin(Post $post)
    {
        if (Auth::id() !== $post->account_id) {
            abort(403, 'Anda tidak berhak menyematkan postingan ini.');
        }

        // Jika sudah pin, unpin dulu semua post milik user ini
        if ($post->is_pinned) {
            $post->update(['is_pinned' => false]);
            return back()->with('success', 'Post tidak lagi disematkan.');
        }

        // Unpin semua post milik user, lalu pin yang dipilih
        Post::where('account_id', Auth::id())->update(['is_pinned' => false]);
        $post->update(['is_pinned' => true]);

        return back()->with('success', 'Post berhasil disematkan ke profil! 📌');
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

        // Cek visibilitas
        if (!$post->isVisibleTo(Auth::id())) {
            abort(403, 'Post ini hanya untuk close friends.');
        }

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

        $post->hashtags()->sync($hashtagIds);
    }
}