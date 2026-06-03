<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the user's comments.
     * Fetches all comments created by the authenticated user along with the associated post.
     */
    public function index()
    {
        $comments = Comment::where('account_id', Auth::id())->with('post')->latest()->get();
        return view('comments.index', compact('comments'));
    }

    /**
     * Store a newly created comment in storage.
     * Validates the request and associates the comment with the current user and specified post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ]);

        $toxicWords = ['bodoh', 'jelek', 'kasar', 'anjing']; 
        
        $commentText = strtolower($request->content);
        
        foreach ($toxicWords as $word) {
            if (str_contains($commentText, $word)) {
                return back()->withErrors(['error' => 'Komentar ditolak! Mengandung kata yang tidak pantas.']);
            }
        }

        Comment::create([
            'account_id' => Auth::id(),
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Remove the specified comment from storage.
     * Ensures only the comment owner can delete their specific comment.
     */
    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->account_id) {
            abort(403, 'Anda tidak berhak menghapus komentar ini.');
        }

        $comment->delete();
        return back()->with('success', 'Komentar berhasil dihapus!');
    }
}