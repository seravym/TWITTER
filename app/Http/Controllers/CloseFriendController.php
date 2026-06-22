<?php

namespace App\Http\Controllers;

use App\Models\CloseFriend;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CloseFriendController extends Controller
{
    /**
     * Tampilkan halaman kelola close friend.
     * Hanya menampilkan akun yang sudah difollow (status accepted).
     * Route: GET /close-friends
     */
    public function index()
    {
        $myId = Auth::id();

        // Ambil semua akun yang sedang difollow (accepted) oleh user ini
        $followingIds = Auth::user()
            ->following()
            ->where('status', 'accepted')
            ->pluck('following_id')
            ->toArray();

        // Load data akun yang difollow beserta info apakah sudah ada di close friend
        $followingAccounts = \App\Models\Account::whereIn('id', $followingIds)->get();

        // ID close friend yang sudah disimpan
        $closeFriendIds = CloseFriend::where('account_id', $myId)
            ->pluck('friend_id')
            ->toArray();

        return view('close_friends.index', compact('followingAccounts', 'closeFriendIds'));
    }

    /**
     * Tambahkan akun ke daftar close friend.
     * Route: POST /close-friends/{friend_id}
     */
    public function store(int $friendId)
    {
        $myId = Auth::id();

        if ($friendId === $myId) {
            return back()->withErrors(['error' => 'Tidak bisa menambah diri sendiri ke close friend.']);
        }

        // Pastikan user memang follow orang ini
        $isFollowing = Auth::user()
            ->following()
            ->where('following_id', $friendId)
            ->where('status', 'accepted')
            ->exists();

        if (!$isFollowing) {
            return back()->withErrors(['error' => 'Kamu hanya bisa menambahkan akun yang kamu follow.']);
        }

        CloseFriend::firstOrCreate([
            'account_id' => $myId,
            'friend_id'  => $friendId,
        ]);

        return back()->with('success', 'Akun berhasil ditambahkan ke Close Friend! 🌟');
    }

    /**
     * Hapus akun dari daftar close friend.
     * Route: DELETE /close-friends/{friend_id}
     */
    public function destroy(int $friendId)
    {
        CloseFriend::where('account_id', Auth::id())
            ->where('friend_id', $friendId)
            ->delete();

        return back()->with('success', 'Akun dihapus dari Close Friend.');
    }
}