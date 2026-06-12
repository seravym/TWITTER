<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Menampilkan timeline dengan opsi filter Following atau Global.
     */
    public function index(Request $request)
    {
        $feedType = $request->query('feed', 'global');

        if ($feedType === 'following' && Auth::check()) {
            // Ambil ID orang yang di-follow oleh user saat ini
            $followingIds = Auth::user()->following()->where('status', 'accepted')->pluck('following_id')->toArray();
            
            // Tampilkan postingan dari orang yang di-follow + postingan sendiri
            $posts = Post::whereIn('account_id', $followingIds)
                         ->orWhere('account_id', Auth::id())
                         ->with(['account', 'likes', 'comments'])
                         ->latest()
                         ->get();
        } else {
            // Default: Tampilkan semua postingan
            $posts = Post::with(['account', 'likes', 'comments'])->latest()->get();
        }

        return view('welcome', compact('posts', 'feedType'));
    }

    /**
     * Menyimpan postingan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        Post::create([
            'account_id' => Auth::id(),
            'content' => $request->content
        ]);

        // Menggunakan redirect('/') karena welcome.blade.php biasanya diakses dari route '/'
        return redirect('/')->with('success', 'Post berhasil dibuat!');
    }

    /**
     * Menghapus postingan.
     */
    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->account_id) {
            abort(403, 'Anda tidak berhak menghapus postingan ini.');
        }

        $post->delete();
        return redirect('/')->with('success', 'Post berhasil dihapus!');
    }

    /**
     * Like / Unlike Toggle.
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
                'post_id' => $post->id
            ]);
        }
        return back();
    }
}