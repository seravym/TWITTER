<?php

namespace App\Http\Controllers;

use App\Models\PollVote;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    public function vote(Request $request, Post $post)
    {
        $poll = $post->poll;

        if (!$poll || $poll->isClosed()) {
            abort(403, 'Polling ini tidak tersedia lagi.');
        }

        $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id',
        ]);

        $option = $poll->options()->findOrFail($request->poll_option_id);

        PollVote::updateOrCreate(
            [
                'poll_id' => $poll->id,
                'account_id' => Auth::id(),
            ],
            [
                'poll_option_id' => $option->id,
            ]
        );

        return back();
    }
}
