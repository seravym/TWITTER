<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menfess;
use App\Models\Post;
use App\Models\Account;

class MenfessController extends Controller
{
    public function create()
    {
        $bases = Account::where('username', 'like', '%base%')->get();

        return view('menfesses.create', compact('bases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'base_id' => 'required',
            'message' => 'required|string|max:280',
        ]);

        Menfess::create([
            'sender_id' => Auth::id(),
            'base_id'   => $request->base_id,
            'message'   => $request->message,
            'status'    => 'pending',
        ]);

        return back()->with(
            'success',
            'Menfess berhasil dikirim dan menunggu persetujuan admin Base!'
        );
    }

    public function index()
    {
        $menfesses = Menfess::where('base_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        return view('menfesses.index', compact('menfesses'));
    }

    public function approve(int $id)
    {
        $menfess = Menfess::where('id', $id)
            ->where('base_id', Auth::id())
            ->firstOrFail();

        Post::create([
            'account_id' => Auth::id(),
            'content' => "guys!\n\n" . $menfess->message,
        ]);

        $menfess->update([
            'status' => 'approved'
        ]);

        return back()->with(
            'success',
            'Menfess berhasil dipublish ke timeline!'
        );
    }
}
