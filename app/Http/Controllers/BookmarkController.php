<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Tampilkan semua bookmark milik user yang sedang login.
     * Route: GET /bookmarks
     */
    public function index()
    {
        $bookmarks = Bookmark::where('account_id', Auth::id())
            ->with(['post.account', 'post.likes', 'post.comments', 'post.hashtags', 'post.bookmarks'])
            ->latest()
            ->get();

        return view('bookmarks.index', compact('bookmarks'));
    }

    /**
     * Toggle bookmark — jika belum ada tambahkan, jika sudah ada hapus.
     * Route: POST /bookmarks/{post}
     */
    public function toggle(Post $post)
    {
        $accountId = Auth::id();

        $existing = Bookmark::where('account_id', $accountId)
            ->where('post_id', $post->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Post dihapus dari bookmark.';
        } else {
            Bookmark::create([
                'account_id' => $accountId,
                'post_id'    => $post->id,
            ]);
            $message = 'Post ditambahkan ke bookmark!';
        }

        return back()->with('success', $message);
    }

    /**
     * Hapus satu bookmark berdasarkan ID-nya.
     * Route: DELETE /bookmarks/{bookmark}
     */
    public function destroy(Bookmark $bookmark)
    {
        // Pastikan hanya pemilik yang bisa menghapus
        if ($bookmark->account_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $bookmark->delete();

        return back()->with('success', 'Bookmark berhasil dihapus.');
    }
}