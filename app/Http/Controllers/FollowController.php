<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Account;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\CloseFriend; 
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
            
        $closeFriendIds = CloseFriend::where('account_id', Auth::id())
            ->pluck('friend_id')
            ->toArray();
            
        return view('follows.index', compact('follows', 'closeFriendIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'following_id' => 'required|exists:accounts,id'
        ]);

        if (Auth::id() == $request->following_id) {
            return back()->withErrors(['error' => 'Anda tidak bisa mem-follow diri sendiri.']);
        }

        $existing = Follow::where('follower_id', Auth::id())
            ->where('following_id', $request->following_id)
            ->first();

        if ($existing) {
            return back()->withErrors(['error' => 'Anda sudah mem-follow atau sudah mengirim permintaan follow.']);
        }

        $targetSetting = Setting::where('account_id', $request->following_id)->first();
        $status = ($targetSetting && $targetSetting->isPrivateAccount) ? 'pending' : 'accepted';

        $follow = Follow::create([
            'follower_id' => Auth::id(),
            'following_id' => $request->following_id,
            'status' => $status,
        ]);

        if ($status === 'pending') {
            Notification::create([
                'account_id' => $request->following_id,
                'sender_id'  => Auth::id(),
                'type'       => 'follow_request',
                'message'    => 'ingin mengikuti akunmu.',
                'reference_id' => $follow->id,
            ]);

            return back()->with('success', 'Permintaan ikuti (Follow Request) telah dikirim!');
        }

        Notification::create([
            'account_id' => $request->following_id,
            'sender_id'  => Auth::id(),
            'type'       => 'follow_accepted',
            'message'    => 'mulai mengikutimu.',
            'reference_id' => $follow->id,
        ]);

        return back()->with('success', 'Berhasil mem-follow akun!');
    }

    public function destroy($id)
    {
        Follow::where('follower_id', Auth::id())
            ->where('following_id', $id)
            ->delete();

        CloseFriend::where('account_id', Auth::id())
            ->where('friend_id', $id)
            ->delete();

        return back()->with('success', 'Berhasil berhenti mengikuti!');
    }

    public function accept($followerId)
    {
        $follow = Follow::where('follower_id', $followerId)
            ->where('following_id', Auth::id())
            ->first();

        if ($follow) {
            $follow->update(['status' => 'accepted']);

            Notification::create([
                'account_id' => $followerId,
                'sender_id'  => Auth::id(),
                'type'       => 'follow_accepted',
                'message'    => 'menerima permintaan follow-mu.',
                'reference_id' => $follow->id,
            ]);
        }

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