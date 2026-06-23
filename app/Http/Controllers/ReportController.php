<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function storePost(Request $request, Post $post)
    {
        if ($post->account_id === Auth::id()) {
            return back()->withErrors('Kamu tidak bisa report post sendiri.');
        }

        $this->storeReport($request, Post::class, $post->id);

        return back()->with('success', 'Report post berhasil dikirim.');
    }

    public function storeComment(Request $request, Comment $comment)
    {
        if ($comment->account_id === Auth::id()) {
            return back()->withErrors('Kamu tidak bisa report komentar sendiri.');
        }

        $this->storeReport($request, Comment::class, $comment->id);

        return back()->with('success', 'Report komentar berhasil dikirim.');
    }

    public function storeAccount(Request $request, Account $account)
    {
        if ($account->id === Auth::id()) {
            return back()->withErrors('Kamu tidak bisa report akun sendiri.');
        }

        $this->storeReport($request, Account::class, $account->id);

        return back()->with('success', 'Report akun berhasil dikirim.');
    }

    private function storeReport(Request $request, string $type, int $id): void
    {
        $data = $request->validate([
            'reason' => 'required|string|max:100',
            'details' => 'nullable|string|max:500',
        ]);

        Report::updateOrCreate(
            [
                'reporter_id' => Auth::id(),
                'reportable_type' => $type,
                'reportable_id' => $id,
            ],
            [
                'reason' => $data['reason'],
                'details' => $data['details'] ?? null,
                'status' => 'pending',
            ]
        );
    }
}
