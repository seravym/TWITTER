<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Setting;
use App\Models\Hashtag;
use App\Models\CloseFriend;
use App\Models\Notification;
use App\Models\Account;
use App\Models\Article;
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

        $myCloseFriendIds = Auth::check()
            ? CloseFriend::where('account_id', $currentUserId)->pluck('friend_id')->toArray()
            : [];

        $mySetting   = Setting::where('account_id', $currentUserId)->first();
        $blockedByMe = $mySetting ? ($mySetting->blocked_accounts ?? []) : [];

        $blockingMe = Setting::whereJsonContains('blocked_accounts', $currentUserId)
            ->pluck('account_id')
            ->toArray();

        $blockedIds = array_unique(array_merge($blockedByMe, $blockingMe));

        $baseQuery = Post::with([
                'account',
                'likes',
                'comments.account',
                'comments.replies.account',
                'hashtags',
                'bookmarks',
                'reposts',
                'poll.options.votes',
            ])
            ->whereNull('archived_at')
            ->whereNotIn('account_id', $blockedIds);

        if ($feedType === 'following' && Auth::check()) {
            $posts = $baseQuery
                ->where(function ($query) use ($acceptedFollowingIds, $currentUserId) {
                    $query->whereIn('account_id', $acceptedFollowingIds)
                          ->orWhere('account_id', $currentUserId);
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
        } else {
            $privateAccountIds = Setting::where('isPrivateAccount', true)->pluck('account_id')->toArray();

            $posts = $baseQuery
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

        $articles = Article::with('account')
            ->where('status', 'published')
            ->whereNotIn('account_id', $blockedIds)
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('welcome', compact('posts', 'feedType', 'articles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content'          => 'required|string|max:350',
            'media'            => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,webm|max:20480',
            'visibility'       => 'nullable|in:public,close_friend',
            'poll_question'    => 'nullable|string|max:180',
            'poll_options'     => 'nullable|array|max:4',
            'poll_options.*'   => 'nullable|string|max:100',
            'poll_duration'    => 'nullable|integer|in:1,3,7',
        ]);


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

        $pollOptions = collect($request->input('poll_options', []))
            ->map(fn ($option) => trim((string) $option))
            ->filter()
            ->values();

        if ($pollOptions->count() >= 2) {
            $poll = $post->poll()->create([
                'question'   => $request->filled('poll_question') ? $request->poll_question : $request->content,
                'expires_at' => now()->addDays((int) $request->input('poll_duration', 7)),
            ]);

            $pollOptions->each(function ($option) use ($poll) {
                $poll->options()->create([
                    'option_text' => $option,
                ]);
            });
        }

        $this->syncHashtags($post, $request->content);
        $this->notifyMentions($post, $request->content);

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

        $mediaPath = $post->media_path;
        $mediaType = $post->media_type;

        if ($request->hasFile('media') && $request->file('media')->isValid()) {
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
        $this->notifyMentions($post, $request->content);

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
                'post_id'    => $post->id,
            ]);
        }

        return back();
    }

    public function show($id)
    {
        $post = Post::with(['account', 'likes', 'comments.account', 'comments.replies.account', 'hashtags', 'bookmarks', 'poll.options.votes'])->findOrFail($id);

        if ($post->archived_at !== null && $post->account_id !== Auth::id()) {
            abort(403, 'Post ini sudah diarsipkan.');
        }

        if (!$post->isVisibleTo(Auth::id())) {
            abort(403, 'Post ini hanya untuk close friends.');
        }

        return view('posts.show', compact('post'));
    }

    public function archiveIndex()
    {
        $posts = Post::with([
                'account',
                'likes',
                'comments.account',
                'comments.replies.account',
                'hashtags',
                'bookmarks',
                'reposts',
                'poll.options.votes',
            ])
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

        $post->update([
            'archived_at' => now(),
            'is_pinned' => false,
        ]);

        return back()->with('success', 'Post berhasil dimasukkan ke archive.');
    }

    public function restore(Post $post)
    {
        if (Auth::id() !== $post->account_id) {
            abort(403, 'Anda tidak berhak mengembalikan postingan ini.');
        }

        $post->update(['archived_at' => null]);

        return back()->with('success', 'Post berhasil dikembalikan ke timeline.');
    }

    public function downloadMedia(Post $post)
    {
        if (!$post->media_path) {
            abort(404, 'Media tidak ditemukan.');
        }

        if ($post->archived_at !== null && $post->account_id !== Auth::id()) {
            abort(403, 'Media dari post archive hanya bisa diunduh oleh pemilik post.');
        }

        if (!$post->isVisibleTo(Auth::id())) {
            abort(403, 'Kamu tidak punya akses ke media ini.');
        }

        if (!Storage::disk('public')->exists($post->media_path)) {
            abort(404, 'File media tidak ditemukan di storage.');
        }

        $extension = pathinfo($post->media_path, PATHINFO_EXTENSION);
        $filename = 'post-' . $post->id . '-media' . ($extension ? '.' . $extension : '');

        return response()->download(Storage::disk('public')->path($post->media_path), $filename);
    }

    private function syncHashtags(Post $post, string $content): void
    {
        preg_match_all('/#([A-Za-z0-9_]+)/', $content, $matches);
        $names = collect($matches[1] ?? [])
            ->map(fn ($name) => strtolower($name))
            ->unique()
            ->values();

        $hashtagIds = [];

        foreach ($names as $name) {
            $hashtag = Hashtag::firstOrCreate(
                ['name' => $name],
                ['post_count' => 0]
            );

            $hashtagIds[] = $hashtag->id;
        }

        $oldHashtags = $post->hashtags()->pluck('hashtags.id')->toArray();
        $post->hashtags()->sync($hashtagIds);

        foreach (array_diff($hashtagIds, $oldHashtags) as $id) {
            Hashtag::find($id)?->incrementPostCount();
        }

        foreach (array_diff($oldHashtags, $hashtagIds) as $id) {
            Hashtag::find($id)?->decrementPostCount();
        }
    }

    private function notifyMentions(Post $post, string $content): void
    {
        preg_match_all('/@([A-Za-z0-9_]+)/', $content, $matches);

        $usernames = collect($matches[1] ?? [])
            ->map(fn ($username) => strtolower($username))
            ->unique()
            ->values();

        if ($usernames->isEmpty()) {
            return;
        }

        $mentionedAccounts = Account::whereIn('username', $usernames)->get();

        foreach ($mentionedAccounts as $account) {
            if ($account->id === Auth::id()) {
                continue;
            }

            Notification::create([
                'account_id' => $account->id,
                'sender_id' => Auth::id(),
                'type' => 'mention',
                'message' => '@' . Auth::user()->username . ' mentioned you in a post.',
                'reference_id' => $post->id,
                'is_read' => false,
            ]);
        }
    }
}
