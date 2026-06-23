<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function vote(Request $request, Poll $poll)
    {
        if ($poll->isExpired()) {
            return back();
        }

        $alreadyVoted = PollVote::where('account_id', auth()->id())
            ->whereIn(
                'poll_option_id',
                $poll->options->pluck('id')
            )
            ->exists();

        if ($alreadyVoted) {
            return back();
        }

        PollVote::create([
            'poll_option_id' => $request->option_id,
            'account_id' => auth()->id(),
        ]);

        return back();
    }
}