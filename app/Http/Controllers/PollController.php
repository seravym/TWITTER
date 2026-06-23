<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    public function vote(Request $request, Poll $poll)
    {
        $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id',
        ]);

        if ($poll->isExpired()) {
            return back()->withErrors(['poll' => 'Polling sudah ditutup.']);
        }

        $option = PollOption::where('id', $request->poll_option_id)
            ->where('poll_id', $poll->id)
            ->firstOrFail();

        $alreadyVoted = PollVote::where('account_id', Auth::id())
            ->whereIn('poll_option_id', $poll->options()->pluck('id'))
            ->exists();

        if ($alreadyVoted) {
            return back()->withErrors(['poll' => 'Kamu sudah vote di polling ini.']);
        }

        PollVote::create([
            'poll_option_id' => $option->id,
            'account_id'    => Auth::id(),
        ]);

        return back()->with('success', 'Vote berhasil disimpan!');
    }
}
