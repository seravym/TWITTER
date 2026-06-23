<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\CloseFriend;
use App\Models\Hashtag;
use App\Models\Like;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $feedType = $request->query('feed', 'global');
        $currentUserId = Auth::id();

        $acceptedFollowingIds = Auth::check()
            ? Auth::user()->following()->where('status', 'accepted')->pluck('following_id')->toArray()
            : [];

        $myCloseFriendIds = Auth::check()
            ? CloseFriend::where('account_id', $currentUserId)->pluck('friend_id')->toArray()
            : [];

        $mySetting = Setting::where('account_id', $currentUserId)->first();
        $blockedByMe = $mySetting ? ($mySetting->blocked_accounts ?? []) : [];

        $blockingMe = Setting::whereJsonContains('blocked_accounts', $currentUserId)
            ->pluck('account_id')
            ->toArray();

        $blockedIds = array_unique(array_merge($blockedByMe, $blockingMe));

        $relations = [
            'account',
            'likes',
            'reposts',
            'comments.account',
            'hashtags',
            'bookmarks',
            'poll.options.votes',
            'poll.options',
            'quotedPost.account',
        ];

        $postsQuery = Post::with($relations)
            ->whereNull('community_id')
            ->whereNull('archived_at')
            ->whereNotIn('account_id', $blockedIds);

        if ($feedType === 'following' && Auth::check()) {
            $postsQuery->where(function ($query) use ($currentUserId, $acceptedFollowingIds) {
                $query->where('account_id', $currentUserId)
                    ->orWhereIn('account_id', $acceptedFollowingIds);
            });
        } else {
            $privateAccountIds = Setting::where('isPrivateAccount', true)
                ->pluck('account_id')
                ->toArray();

            $postsQuery->where(function ($query) use ($currentUserId, $acceptedFollowingIds, $privateAccountIds) {
                $query->whereNotIn('account_id', $privateAccountIds)
                    ->orWhere('account_id', $currentUserId)
                    ->orWhereIn('account_id', $acceptedFollowingIds);
            });
        }

        $posts = $postsQuery->latest()
            ->get()
            ->filter(function ($post) use ($currentUserId, $myCloseFriendIds) {
                if ($post->visibility === 'close_friend') {
                    return $post->account_id === $currentUserId
                        || in_array($post->account_id, $myCloseFriendIds);
                }

                return true;
            });

        $articles = Article::with('account')
            ->where('status', 'published')
            ->whereNotIn('account_id', $blockedIds)
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('welcome', compact('posts', 'feedType', 'articles'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:350',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,webm|max:20480',
            'visibility' => 'nullable|in:public,close_friend',
            'quote_post_id' => 'nullable|exists:posts,id',
            'poll_duration' => 'nullable|integer|min:1|max:7',
            'poll_options' => 'nullable|array|max:4',
            'poll_options.*' => 'nullable|string|max:100',
        ]);

        $mediaPath = null;
        $mediaType = null;

        if ($request->hasFile('media') && $request->file('media')->isValid()) {
            $file = $request->file('media');
            $mime = $file->getMimeType();
            $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';
            $mediaPath = $file->store('posts', 'public');
        }

        $post = Post::create([
            'account_id' => Auth::id(),
            'content' => $request->content,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'visibility' => $request->input('visibility', 'public'),
            'quote_post_id' => $request->input('quote_post_id'),
        ]);

        $this->syncHashtags($post, $request->content);

        $pollOptions = collect($request->input('poll_options', []))
            ->map(fn ($option) => trim((string) $option))
            ->filter()
            ->take(4)
            ->values();

        if ($pollOptions->count() >= 2) {
            $poll = $post->poll()->create([
                'expires_at' => now()->addDays((int) $request->input('poll_duration', 7)),
            ]);

            foreach ($pollOptions as $option) {
                $poll->options()->create([
                    'option_text' => $option,
                ]);
            }
        }

        return redirect()->route('posts.index')->with('success', 'Post berhasil dibuat!');
    }

    public function show(Post $post)
    {
        $post->load([
            'account',
            'likes',
            'reposts',
            'comments.account',
            'hashtags',
            'bookmarks',
            'poll.options.votes',
            'quotedPost.account',
        ]);

        if (!$post->isVisibleTo(Auth::id())) {
            abort(403, 'Post ini hanya untuk close friends.');
        }

        return view('posts.show', compact('post'));
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
            'content' => 'required|string|max:350',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,webm|max:20480',
            'visibility' => 'nullable|in:public,close_friend',
        ]);

        $mediaPath = $post->media_path;
        $mediaType = $post->media_type;

        if ($request->hasFile('media') && $request->file('media')->isValid()) {
            if ($mediaPath) {
                Storage::disk('public')->delete($mediaPath);
            }

            $file = $request->file('media');
            $mime = $file->getMimeType();
            $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';
            $mediaPath = $file->store('posts', 'public');
        }

        $post->update([
            'content' => $request->content,
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

        if ($post->media_path) {
            Storage::disk('public')->delete($post->media_path);
        }

        foreach ($post->hashtags as $hashtag) {
            $hashtag->decrementPostCount();
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post berhasil dihapus!');
    }

    public function pin(Post $post)
    {
        if (Auth::id() !== $post->account_id) {
            abort(403, 'Anda tidak berhak menyematkan postingan ini.');
        }

        if ($post->is_pinned) {
            $post->update(['is_pinned' => false]);
            return back()->with('success', 'Post tidak lagi disematkan.');
        }

        Post::where('account_id', Auth::id())->update(['is_pinned' => false]);
        $post->update(['is_pinned' => true]);

        return back()->with('success', 'Post berhasil disematkan ke profil! 📌');
    }

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
                'post_id' => $post->id,
            ]);
        }

        return back();
    }

    public function quote(Post $post)
    {
        $post->load('account');
        return view('posts.quote', compact('post'));
    }

    public function downloadMedia(Post $post)
    {
        if (!$post->media_path || !Storage::disk('public')->exists($post->media_path)) {
            abort(404, 'Media tidak ditemukan.');
        }

        $extension = pathinfo($post->media_path, PATHINFO_EXTENSION);
        $filename = 'post-' . $post->id . '-media.' . $extension;

        return Storage::disk('public')->download($post->media_path, $filename);
    }

    public function archiveIndex()
    {
        $posts = Post::with(['account', 'likes', 'reposts', 'comments', 'quotedPost.account'])
            ->where('account_id', Auth::id())
            ->whereNotNull('archived_at')
            ->latest('archived_at')
            ->get();

        return view('posts.archive', compact('posts'));
    }

    public function archive(Post $post)
    {
        if (Auth::id() !== $post->account_id) {
            abort(403, 'Anda tidak berhak mengarsipkan postingan ini.');
        }

        $post->update(['archived_at' => now()]);

        return back()->with('success', 'Post berhasil diarsipkan.');
    }

    public function restore(Post $post)
    {
        if (Auth::id() !== $post->account_id) {
            abort(403, 'Anda tidak berhak mengembalikan postingan ini.');
        }

        $post->update(['archived_at' => null]);

        return back()->with('success', 'Post berhasil dikembalikan.');
    }

    private function syncHashtags(Post $post, string $content): void
    {
        preg_match_all('/#([A-Za-z0-9_]+)/u', $content, $matches);
        $tagNames = array_unique(array_map('strtolower', $matches[1]));

        $hashtagIds = [];
        foreach ($tagNames as $name) {
            $hashtag = Hashtag::firstOrCreate(['name' => $name]);
            $hashtagIds[] = $hashtag->id;
        }

        $post->hashtags()->sync($hashtagIds);
    }
}
