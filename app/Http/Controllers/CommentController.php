<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255', 
            'post_id' => 'required'
        ]);
        
        Comment::create([
            'account_id' => Auth::id(),
            'post_id' => $request->post_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim!');
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate(['content' => 'required|string|max:255']);
        
        if (Auth::id() === $comment->account_id) {
            $comment->update(['content' => $request->content]);
            return back()->with('success', 'Komentar berhasil diperbarui!');
        }
        return back()->with('error', 'Tidak bisa mengedit komentar ini.');
    }

    public function destroy(Comment $comment)
    {
        if (Auth::id() === $comment->account_id) {
            $comment->delete();
            return back()->with('success', 'Komentar dihapus.');
        }
        return back()->with('error', 'Gagal menghapus.');
    }
}