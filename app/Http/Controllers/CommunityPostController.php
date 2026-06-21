<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityPostController extends Controller
{
    public function store(Request $request, Community $community)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $isMember = $community->members()->where('account_id', Auth::id())->exists();
        $isCreator = $community->creator_id == Auth::id();

        if (!$isMember && !$isCreator) {
            return back()->withErrors(['error' => 'Anda harus bergabung dengan komunitas ini untuk memposting sesuatu.']);
        }
        
        Post::create([
            'account_id' => Auth::id(),
            'community_id' => $community->id,
            'content' => $request->content
        ]);

        return back()->with('success', 'Post berhasil ditambahkan ke komunitas!');
    }
}