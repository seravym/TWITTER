<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
    $posts = Post::with(['account', 'likes'])->latest()->get();
    return view('welcome', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        Post::create([
            'account_id' => Auth::id(), // UBAH ke account_id
            'content' => $request->content
        ]);

        return redirect()->route('posts.index')->with('success', 'Post berhasil dibuat!');
    }

    // ... method show() tetap sama ...

    public function edit(Post $post)
    {
        // UBAH: cek menggunakan account_id
        if (Auth::id() !== $post->account_id) {
            abort(403, 'Anda tidak berhak mengedit postingan ini.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // UBAH: cek menggunakan account_id
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
        // UBAH: cek menggunakan account_id
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
}