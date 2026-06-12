<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'media' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('media')) {
            $path = $request->file('media')->store('stories', 'public');

            $account = Auth::user();
            $account->stories()->create([
                'media_path' => $path,
                'media_type' => 'image',
                'expires_at' => now()->addHours(24),
            ]);

            return back()->with('success', 'Story berhasil diunggah!');
        }

        return back()->with('error', 'Gagal mengunggah story.');
    }

    public function show($accountId)
    {
        $account = \App\Models\Account::findOrFail($accountId);
        $stories = $account->stories()->where('expires_at', '>', now())->get();
        
        if ($stories->isEmpty()) {
            return redirect('/accounts/' . $accountId)->with('error', 'Tidak ada story aktif.');
        }

        return view('stories.show', compact('account', 'stories'));
    }
}