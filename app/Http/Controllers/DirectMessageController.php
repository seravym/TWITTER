<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\DirectMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectMessageController extends Controller
{
    /**
     * Inbox: tampilkan daftar semua percakapan unik.
     */
    public function index()
    {
        $myId = Auth::id();

        // Ambil semua pesan yang melibatkan akun ini
        $messages = DirectMessage::where('sender_id', $myId)
            ->orWhere('receiver_id', $myId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Buat daftar percakapan unik per "lawan bicara"
        $conversations = collect();
        $seen = [];

        foreach ($messages as $msg) {
            $otherId = ($msg->sender_id === $myId) ? $msg->receiver_id : $msg->sender_id;

            if (!in_array($otherId, $seen)) {
                $seen[] = $otherId;
                $other = ($msg->sender_id === $myId) ? $msg->receiver : $msg->sender;

                $unreadCount = DirectMessage::where('sender_id', $otherId)
                    ->where('receiver_id', $myId)
                    ->whereNull('read_at')
                    ->count();

                $conversations->push([
                    'account'      => $other,
                    'last_message' => $msg,
                    'unread_count' => $unreadCount,
                ]);
            }
        }

        return view('messages.index', compact('conversations'));
    }

    /**
     * Chat: tampilkan percakapan dengan user tertentu (berdasarkan username).
     */
    public function show($username)
    {
        $other = Account::where('username', $username)->firstOrFail();
        $myId  = Auth::id();

        // Tandai semua pesan dari lawan bicara ini sebagai sudah dibaca
        DirectMessage::where('sender_id', $other->id)
            ->where('receiver_id', $myId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Ambil semua pesan dalam percakapan ini
        $messages = DirectMessage::conversation($myId, $other->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('messages.show', compact('other', 'messages'));
    }

    /**
     * Kirim pesan baru ke user tertentu.
     */
    public function store(Request $request, $username)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $other = Account::where('username', $username)->firstOrFail();

        // Tidak boleh DM diri sendiri
        if ($other->id === Auth::id()) {
            return back()->withErrors(['body' => 'Kamu tidak bisa mengirim DM ke diri sendiri.']);
        }

        DirectMessage::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $other->id,
            'body'        => $request->body,
        ]);

        return redirect()->route('messages.show', $username)
                         ->with('success', 'Pesan terkirim!');
    }

    /**
     * Hapus pesan — hanya pengirim yang boleh menghapus.
     */
    public function destroy($id)
    {
        $message = DirectMessage::findOrFail($id);

        if ($message->sender_id !== Auth::id()) {
            abort(403, 'Kamu tidak berhak menghapus pesan ini.');
        }

        $otherUsername = $message->receiver->username;
        $message->delete();

        return redirect()->route('messages.show', $otherUsername)
                         ->with('success', 'Pesan dihapus.');
    }
}