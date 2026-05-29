<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function index()
    {
        $follows = Follow::where('follower_id', Auth::id())->with('following')->get();
        return view('follows.index', compact('follows'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'following_id' => 'required|exists:accounts,id'
        ]);

        if (Auth::id() == $request->following_id) {
            return back()->withErrors(['error' => 'Anda tidak bisa mem-follow diri sendiri.']);
        }

        Follow::firstOrCreate([
            'follower_id' => Auth::id(),
            'following_id' => $request->following_id
        ]);

        return back()->with('success', 'Berhasil mem-follow akun!');
    }

    public function destroy($id)
    {
        $follow = Follow::where('follower_id', Auth::id())
                        ->where('following_id', $id)
                        ->first();

        if ($follow) {
            $follow->delete();
        }

        return back()->with('success', 'Berhasil berhenti mengikuti!');
    }
}