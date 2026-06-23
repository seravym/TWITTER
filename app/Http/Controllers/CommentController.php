<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Account;
use App\Models\Notification;
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
        
        $comment = Comment::create([
            'account_id' => Auth::id(),
            'post_id' => $request->post_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        $this->notifyMentions($comment, $request->content);

        return back()->with('success', 'Komentar berhasil dikirim!');
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate(['content' => 'required|string|max:255']);
        
        if (Auth::id() === $comment->account_id) {
            $comment->update(['content' => $request->content]);
            $this->notifyMentions($comment, $request->content);
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

    private function notifyMentions(Comment $comment, string $content): void
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
                'message' => '@' . Auth::user()->username . ' mentioned you in a comment.',
                'reference_id' => $comment->post_id,
                'is_read' => false,
            ]);
        }
    }
}
