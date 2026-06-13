<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $feedType = $request->query('feed', 'global');
        $currentUserId = Auth::id();

        $acceptedFollowingIds = Auth::check() 
            ? Auth::user()->following()->where('status', 'accepted')->pluck('following_id')->toArray() 
            : [];

        if ($feedType === 'following' && Auth::check()) {
            $posts = Post::whereIn('account_id', $acceptedFollowingIds)
                         ->orWhere('account_id', $currentUserId)
                         ->with(['account', 'likes', 'comments'])
                         ->latest()
                         ->get();
        } else {
            $privateAccountIds = Setting::where('isPrivateAccount', true)->pluck('account_id')->toArray();

            $posts = Post::with(['account', 'likes', 'comments'])
                         ->where(function($query) use ($currentUserId, $acceptedFollowingIds, $privateAccountIds) {
                             $query->whereNotIn('account_id', $privateAccountIds) // Tampilkan jika BUKAN akun private
                                   ->orWhere('account_id', $currentUserId)       // ATAU postingan milik kita sendiri
                                   ->orWhereIn('account_id', $acceptedFollowingIds); // ATAU akun private yang sudah berhasil kita follow
                         })
                         ->latest()
                         ->get();
        }

        return view('welcome', compact('posts', 'feedType'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        Post::create([
            'account_id' => Auth::id(), 
            'content' => $request->content
        ]);

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
            'content' => 'required|string'
        ]);

        $post->update([
            'content' => $request->content
        ]);

        return redirect()->route('posts.index')->with('success', 'Post berhasil diperbarui!');
    }

    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->account_id) {
            abort(403, 'Anda tidak berhak menghapus postingan ini.');
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
            // unlike
            $like->delete();
        } else {
            // like
            Like::create([
                'account_id' => $accountId,
                'post_id' => $post->id
            ]);
        }
        return back();
    }

    public function show($id)
    {
        $post = \App\Models\Post::findOrFail($id);
        
        return view('posts.show', compact('post'));
    }    
}