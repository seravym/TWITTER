<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi milik user yang sedang login.
     */
    public function index()
    {
        $notifications = Notification::where('account_id', Auth::id())
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil follow requests pending untuk akun ini (untuk ditampilkan secara khusus)
        $followRequests = Follow::where('following_id', Auth::id())
            ->where('status', 'pending')
            ->with('follower')
            ->get();

        // Tandai semua notif sebagai dibaca
        Notification::where('account_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('notifications.index', compact('notifications', 'followRequests'));
    }

    /**
     * Hitung notifikasi yang belum dibaca (untuk badge).
     */
    public static function unreadCount(): int
    {
        if (!Auth::check()) return 0;

        return Notification::where('account_id', Auth::id())
            ->where('is_read', false)
            ->count();
    }

    /**
     * Hapus satu notifikasi.
     */
    public function destroy(Notification $notification)
    {
        if ($notification->account_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notifikasi dihapus.');
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllRead()
    {
        Notification::where('account_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}