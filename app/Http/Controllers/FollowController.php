<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Account;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function index()
    {
        $follows = Follow::where('follower_id', Auth::id())
            ->where('status', 'accepted')
            ->with('following')
            ->get();
            
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

        $targetSetting = Setting::where('account_id', $request->following_id)->first();
        $status = ($targetSetting && $targetSetting->isPrivateAccount) ? 'pending' : 'accepted';

        $follow = Follow::updateOrCreate(
            [
                'follower_id' => Auth::id(),
                'following_id' => $request->following_id
            ],
            [
                'status' => $status
            ]
        );

        if ($status === 'pending') {
            return back()->with('success', 'Permintaan ikuti (Follow Request) telah dikirim!');
        }

        return back()->with('success', 'Berhasil mem-follow akun!');
    }

    public function destroy($id)
    {
        Follow::where('follower_id', Auth::id())
            ->where('following_id', $id)
            ->delete();

        return back()->with('success', 'Berhasil berhenti mengikuti!');
    }

    public function accept($followerId)
    {
        Follow::where('follower_id', $followerId)
            ->where('following_id', Auth::id())
            ->update(['status' => 'accepted']);

        return back()->with('success', 'Permintaan mem-follow berhasil disetujui!');
    }

    public function reject($followerId)
    {
        Follow::where('follower_id', $followerId)
            ->where('following_id', Auth::id())
            ->delete();

        return back()->with('success', 'Permintaan mem-follow ditolak.');
    }
}